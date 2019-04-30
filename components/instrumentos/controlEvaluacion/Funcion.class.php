<?php
if(!isset($GLOBALS["autorizado"])){
	include("../index.php");
	exit;
}

include_once("core/auth/Sesion.class.php");
include_once("core/manager/Configurador.class.php");
include_once("core/builder/InspectorHTML.class.php");
include_once("core/builder/Mensaje.class.php");
include_once("core/crypto/Encriptador.class.php");
include_once("class/Array.class.php");
include_once("plugin/html2pdf/html2pdf.class.php");
include_once("plugin/fpdf/fpdf.php");

class FuncioncontrolEvaluacion
{

	var $sql;
	var $funcion;
	var $lenguaje;
	var $ruta;
	var $miConfigurador;
	var $miInspectorHTML;
	var $error;
	var $miRecursoDB;
	var $crypto;
	var $mensaje;
	var $status;

    function __construct()
    {
        $this->miConfigurador = Configurador::singleton();
        $this->miSesion = Sesion::singleton();
        $this->idSesion = $this->miSesion->getValorSesion('idUsuario');
        $this->miInspectorHTML=InspectorHTML::singleton();
        $this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");
        $this->miMensaje = Mensaje::singleton();
        $this->enlace = $this->miConfigurador->getVariableConfiguracion("host").$this->miConfigurador->getVariableConfiguracion("site")."?".$this->miConfigurador->getVariableConfiguracion("enlace");
        $conexion = "aplicativo";
        $this->miRecursoDB= $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
        $this->sorter=orderArray::singleton();
    }

	function procesarNotaCriterio($variable)
    {
    	$respuesta = new StdClass();
        $variable['nota'] = str_replace(",",".",$variable['nota']);
        $variable['nota'] = filter_var($variable['nota'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
        $variable['usuario'] = $this->idSesion;

        //0. Consultar desempenio competencia anterior
        //a. Seleccionar todas las competencias con un identificador menor al actual
        $cadenaSql = $this->sql->cadenaSql("competenciasAnteriores",$variable);
    	$competenciasAnteriores = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");

        if(is_array($competenciasAnteriores)){
          $ca = 0;
          while( isset($competenciasAnteriores[$ca][0]) ){
            $variable_tmp['competencia'] = $competenciasAnteriores[$ca]['ID'];
            $variable_tmp['estudiante'] = $variable['estudiante'];

            $cadenaSql = $this->sql->cadenaSql("notaFinal",$variable_tmp);
            $nota = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");

            if(!is_array($nota) || $nota[0]['DESEMPENIO']=="" ){
              $respuesta->error = "La nota no sera almacenada!!. Complete las notas correspondientes a competencias anteriores";
              $respuesta->status = false;
              return $respuesta;
            }
            $ca++;
          }
        }

		//1. Validar nota menor a 5
		if($variable['nota']>5){
			$respuesta->error = "La nota máxima es 5";
			$respuesta->status = false;
		}

		//2. Consultar si la nota existe
		$cadenaSql = $this->sql->cadenaSql("notaPorCriterio",$variable);
		$notaPorCriterio = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");

		//3. Si ya existe, actualizarNota
		if(is_array($notaPorCriterio)){
			$cadenaSql = $this->sql->cadenaSql("actualizarNotaCriterio",$variable);
			$notaPorCriterio = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"");
            $respuesta->status = true;
		}
		//4. Si no existe insertar
		else{
			$cadenaSql = $this->sql->cadenaSql("insertarNotaCriterio",$variable);
			$notaPorCriterio = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"");
            $respuesta->status = true;
		}
    return $respuesta;
	}

    function procesarNotaCualitativa($variable)
    {
        $respuesta = new StdClass();

        $variable['usuario'] = $this->idSesion;

        //Consultar si la nota existe
        $cadenaSql = $this->sql->cadenaSql("notaPorCualitativa",$variable);
        $notaPorCualitativa = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");

        //Si ya existe, actualizarNota
        if(is_array($notaPorCualitativa)){
            $cadenaSql = $this->sql->cadenaSql("actualizarNotaCualitativa",$variable);
            $notaPorCualitativa = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"");
            $respuesta->status = true;
        }
        //Si no existe insertar
        else{
            $cadenaSql = $this->sql->cadenaSql("insertarNotaCualitativa",$variable);
            $notaPorCualitativa = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"");
            $respuesta->status = true;
        }

        $respuesta->status = true;
        return $respuesta;

    }

    function showNotasXML()
    {   
        $unBloque = [
                        'nombre' => 'controlEvaluacion',
                        'grupo' => 'instrumentos'
                    ];
        
        ob_start();
            include_once($this->ruta."/css/Estilo.php");
            $this->view->showNotas();
            $content = ob_get_contents();
        ob_end_clean();
        $html2pdf = new HTML2PDF('L','Legal','en',true, 'UTF-8', array(10, 10, 10, 10));
        $html2pdf->WriteHTML($content);
        $html2pdf->Output('notas.pdf');
	}

	/**
	*	Este método retorna un JSON con los resultados de la operación
	*/
	function calcularNotasFinales($estudiante,$competencia)
    {

			$respuesta=new stdClass();
        //5. Recalcular definitivas

			//5.1 Para poder calcular la sumatoria, todos los criterios deben tener la correspondiente nota

			$cadenaSql= $this->sql->cadenaSql("criteriosPorCompetencia",$competencia);
			$criteriosPorCompetencia= $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");

			$variable['competencia']= $competencia;
			$variable['estudiante']= $estudiante;

			$cadenaSql= $this->sql->cadenaSql("notasPorEstudianteyCompetencia",$variable);
			$notasPorEstudianteyCompetencia= $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
			$notasPorEstudianteyCompetencia= $this->sorter->orderKeyBy($notasPorEstudianteyCompetencia,"CRITERIO");

			$criteriosPendientes=0;
			$i=0;
			$notaFinal=0;
			while(isset($criteriosPorCompetencia[$i][0])){
				if(!isset($notasPorEstudianteyCompetencia[$criteriosPorCompetencia[$i]['ID']]) || $notasPorEstudianteyCompetencia[$criteriosPorCompetencia[$i]['ID']]['NOTA']==""){
					$criteriosPendientes++;
				}else{
					$notaFinal= $notaFinal+($notasPorEstudianteyCompetencia[$criteriosPorCompetencia[$i]['ID']]['NOTA'])*($criteriosPorCompetencia[$i]['PORCENTAJE'])/100;
				}
			$i++;
			}
			if($criteriosPendientes==0){

				$respuesta->notaFinal=round($notaFinal,2);
				$respuesta->notaFinalPorcentaje= $notaFinal*100/5;
				$respuesta->notaFinalPorcentaje=round($respuesta->notaFinalPorcentaje,2);

				//no esta dinamico

				if(($respuesta->notaFinalPorcentaje)*1>=100){
					$respuesta->desempenio = "Superior";
				}
				elseif(($respuesta->notaFinalPorcentaje)*1>=80 && ($respuesta->notaFinalPorcentaje)*1<100){
					$respuesta->desempenio = "Alto";
				}
				elseif(($respuesta->notaFinalPorcentaje)*1<80){
					$respuesta->desempenio = "Basico";
				}

			}else{
                $respuesta->desempenio = "";
                $respuesta->notaFinal = $notaFinal;
				$respuesta->notaFinalPorcentaje = "";
			}

        //almacenar en bd
        $variable['desempenio'] = $respuesta->desempenio;
        $variable['nota_final'] = $respuesta->notaFinal;
        $variable['nota_porcentual'] = $respuesta->notaFinalPorcentaje;

        $cadenaSql = $this->sql->cadenaSql("notaFinal",$variable);
        $notaFinal = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");

        if(is_array($notaFinal)){
            $variable['id_nota_final'] = $notaFinal[0]['ID'];
            $cadenaSql = $this->sql->cadenaSql("actualizarNotaFinal",$variable);
            $notaFinal = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"");
        }else{
            $cadenaSql = $this->sql->cadenaSql("insertarNotaFinal",$variable);
            $notaFinal = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"");
        }

        $respuesta->status=true;
		return $respuesta;
	}

	function action(){

		//Evitar que se ingrese codigo HTML y PHP en los campos de texto
		//Campos que se quieren excluir de la limpieza de código. Formato: nombreCampo1|nombreCampo2|nombreCampo3
		$excluir="";
		$_REQUEST= $this->miInspectorHTML->limpiarPHPHTML($_REQUEST);

		$option=isset($_REQUEST['option'])?$_REQUEST['option']:"list";


		switch($option){
			case "processList":

				$variable["option"] = "list";
				$variable["grado"] = $_REQUEST['grado'];
				$variable["sede"] = $_REQUEST['sede'];

				$this->miConfigurador->render("controlEvaluacion",$variable);

			break;
			case "showPDFCompetencias":
				$result = $this->showPDFCompetencias($_REQUEST);
			break;
			case "showNotasCompleto":
				$result = $this->showNotasCompleto($_REQUEST);
			break;

			case "actualizarNotaCriterio":
				$result = $this->procesarNotaCriterio($_REQUEST);
                if(!$result->status){
                  	echo json_encode($result);
                    return false;
                }
				$result = $this->calcularNotasFinales($_REQUEST['estudiante'],$_REQUEST['competencia']);
                echo json_encode($result);
			break;

            case "actualizarNotaCualitativa":
                $result = $this->procesarNotaCualitativa($_REQUEST);
                if(!$result->status){
                    echo json_encode($result);
                    return false;
                }
                $result = $this->calcularNotasFinales($_REQUEST['estudiante'],$_REQUEST['competencia']);
                echo json_encode($result);
            break;

			case "showNotasXML":
				$result = $this->showNotasXML();
			break;
		}
	}


	public function setRuta($unaRuta){
		$this->ruta= $unaRuta;
		//Incluir las funciones
	}

	function setSql($a)
	{
		$this->sql = $a;
	}

	function setFuncion($funcion)
	{
		$this->funcion = $funcion;
	}

	public function setLenguaje($lenguaje)
	{
		$this->lenguaje = $lenguaje;
	}

	public function setView($view)
    {
		$this->view = $view;
	}

	public function showPDFCompetencias($variable)
    {
        $cadenaSql = $this->sql->cadenaSql("sedeByID",$variable['sede']);
    	$sedeByID  = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
    	$sedeByID  = $sedeByID[0];

        $cadenaSql = $this->sql->cadenaSql("cursoByID",$_REQUEST['curso']);
    	$cursoByID = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
    	$cursoByID = $cursoByID[0];

        $cadenaSql = $this->sql->cadenaSql("estudiantesPorCurso",$variable['curso']);
        $estudiantesPorCurso = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");

        $cadenaSql = $this->sql->cadenaSql("notasCriteroPorCurso",$variable['curso']);
        $notasPorCurso = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
        $notasPorCurso = $this->sorter->orderTwoKeyBy($notasPorCurso,"ESTUDIANTE","COMPETENCIA");       
        
        $cadenaSql = $this->sql->cadenaSql("notasDefinitivasPorCurso",$variable['curso']);
        $notasDefinitivas = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
        $notasDefinitivas = $this->sorter->orderTwoKeyBy($notasDefinitivas,"ESTUDIANTE","AREA");

        $cadenaSql = $this->sql->cadenaSql("competencias",$variable['grado']);
    	$competencias = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
    	$competenciasPorArea = $this->sorter->orderMultiKeyBy($competencias,"ID_AREA");

        //Consulto el listado de areas para el grado actual
        $cadenaSql = $this->sql->cadenaSql("areas",$variable['grado']);
        $areas = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
        $areas = $this->sorter->orderKeyBy($areas,"ID");

        /*switch($areaByID['TIPO']) 
        {
            case 'CRITERIOS':



            break;
            case 'CUALITATIVA':

                $cadenaSql = $this->sql->cadenaSql("notasCualitativaPorCurso",$variable['curso']);
                $notasPorCurso = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
                $notasPorCurso = $this->sorter->orderTwoKeyBy($notasPorCurso,"ESTUDIANTE","COMPETENCIA");
               
            break;
        }*/


        $grid_w  = 4.7; //ancho de la cuadricula de notas
        $grid_fz = 5.6; //Tamaño letra de la cuadricula de notas

    	$pdf = new FPDF('L','mm','Legal'); //215.9 mm x 279.4 mm
    	$pdf->SetMargins(10,10,10); //izq,arr,der
        $pdf->AddPage();

        $y = $pdf->GetY();
        $pdf->Image('http://www.academia.ceruralrestrepo.com/cerural.jpg',10,10,25,25);
        $pdf->SetY($y);

        $pdf->SetFont('Arial','',10);
        $pdf->Cell(190,5,"DEPARTAMENTO DEL META - RESTREPO",0,0,'C');
        $pdf->Ln();
        $pdf->Cell(190,5,utf8_decode("SECRETARÍA DE EDUCACIÓN"),0,0,'C');
        $pdf->Ln();
        $pdf->SetFont('Arial','B',13);
        $pdf->Cell(190,5,"CENTRO EDUCATIVO RURAL DE RESTREPO - ".$this->activeYear,0,0,'C');
        $pdf->Ln();
        $pdf->SetFont('Arial','I',10);
        $pdf->Cell(190,5,"DANE 150606000393",0,0,'C');
        $pdf->Cell(60,5,utf8_decode("Sede: ".$sedeByID['NOMBRE']),0,0,'C');
        $pdf->Cell(20,5,utf8_decode("Grado: ".$cursoByID['NOMBRE']),0,0,'C');

        $pdf->Ln(8);

        //Encabezado

        $pdf->SetFont('Arial','',5);
        $pdf->SetX(65);

        foreach($competenciasPorArea as $areaKey => $competencias){

            $numCompArea = count($competenciasPorArea[$areaKey]);
            $areaType    = $areas[$areaKey]['TIPO'];

            if($areaType == 'CRITERIOS') 
            {
                $areaCellWidth = ($grid_w * ($numCompArea + 1)) ; //mas 1 de la nota definitiva
            }
            else if ($areaType == 'CUALITATIVA')
            {
                $areaCellWidth = ($grid_w * $numCompArea);

                if($numCompArea < 4 ) {

                    $areaCellWidth = ($grid_w * 4);

                }
            }
            if(isset($areas[$areaKey]['AREA'])) {
                $pdf->Cell($areaCellWidth,6, substr($areas[$areaKey]['AREA'],0,19),1);
            }
        }

        $pdf->Ln();

        $pdf->SetFont('Arial','',6);

        //Codigos competencias
        $pdf->SetX(65);

        foreach($competenciasPorArea as $areaKey => $competencias){
            foreach($competencias as $competencia){
                $pdf->Cell($grid_w,6,$competencia["IDENTIFICADOR"],1,0,'C');
            }

            if($areaType == 'CRITERIOS') 
            {
                $pdf->Cell($grid_w,6,'D',1,0,'C');
            }    
        }

        $pdf->Ln();

        //Notas Estudiantes

        $a=0;
        while(isset($estudiantesPorCurso[$a][0])){
          $pdf->SetFont('Arial','',7);
          $nombreEstudiante = $estudiantesPorCurso[$a]['APELLIDO']." ".$estudiantesPorCurso[$a]['APELLIDO2']." ";
          $nombreEstudiante .= $estudiantesPorCurso[$a]['NOMBRE']." ".$estudiantesPorCurso[$a]['NOMBRE2']." ";
          $pdf->Cell(55,5,utf8_decode($nombreEstudiante),1);

          $pdf->SetFont('Arial','',$grid_fz);
          
          foreach($competenciasPorArea as $key => $value){
            foreach($value as $competencia){
              $desempenio = @$notasPorCurso[$estudiantesPorCurso[$a]['ID']][$competencia["ID"]]['DESEMPENIO'];
              if( $desempenio == "Alto"){
                $pdf->SetFillColor(255,255,0);
              }
              elseif( $desempenio == "Basico" ){
                $pdf->SetFillColor(221, 228, 255);
              }
              elseif( $desempenio == "Superior" ){
                $pdf->SetFillColor(0,255,255);
              }
              else{
                $pdf->SetFillColor(255,255,255);
              }
              @$pdf->Cell($grid_w,5,$notasPorCurso[$estudiantesPorCurso[$a]['ID']][$competencia["ID"]]['NOTA_FINAL'],1,0,'C',true);
            }
            if($areaType == 'CRITERIOS') 
            {
                @$pdf->Cell($grid_w,5,$notasDefinitivas[$estudiantesPorCurso[$a]['ID']][$key]['NOTA'],1,0,'C',true);
            }    
          }

          $pdf->Ln();
         $a++;
        }
        $pdf->Output();
	}


	/*public function showNotasCompleto($variable){

        $cadenaSql = $this->sql->cadenaSql("sedeByID",$variable['sede']);
        $sedeByID = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
        $sedeByID = $sedeByID[0];

        $cadenaSql = $this->sql->cadenaSql("cursoByID",$_REQUEST['curso']);
        $cursoByID = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
        $cursoByID = $cursoByID[0];

        $cadenaSql = $this->sql->cadenaSql("estudiantes",$variable['curso']);
        $estudiantes = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");

        $cadenaSql = $this->sql->cadenaSql("notas",$variable['curso']);
        $notasPorCurso = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
        $notasPorCurso = $this->sorter->orderTwoKeyBy($notasPorCurso,"ESTUDIANTE","COMPETENCIA");


        $cadenaSql = $this->sql->cadenaSql("competenciasAll");
        $competencias = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
        $competenciasPorArea = $this->sorter->orderMultiKeyBy($competencias,"ID_AREA");

        //Consulto el listado de areas para el grado actual
        $cadenaSql = $this->sql->cadenaSql("areasAll");
        $areas = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
        $areas = $this->sorter->orderKeyBy($areas,"ID");


		$pdf = new FPDF('L','mm','Legal'); //215.9 mm x 279.4 mm
		$pdf->SetMargins(10,10,10); //izq,arr,der
        $pdf->AddPage();

    $y = $pdf->GetY();
    $pdf->Image('http://www.academia.ceruralrestrepo.com/cerural.jpg',10,10,25,25);
    $pdf->SetY($y);

    $pdf->SetFont('Arial','',10);
    $pdf->Cell(190,5,"DEPARTAMENTO DEL META - RESTREPO",0,0,'C');
    $pdf->Ln();
    $pdf->Cell(190,5,utf8_decode("SECRETARÍA DE EDUCACIÓN"),0,0,'C');
    $pdf->Ln();
    $pdf->SetFont('Arial','B',13);
    $pdf->Cell(190,5,"CENTRO EDUCATIVO RURAL DE RESTREPO",0,0,'C');
    $pdf->Ln();
    $pdf->SetFont('Arial','I',10);
    $pdf->Cell(190,5,"DANE 150606000393",0,0,'C');
    $pdf->Cell(60,5,utf8_decode("Sede: ".$sedeByID['NOMBRE']),0,0,'C');
    $pdf->Cell(20,5,utf8_decode("Grado: ".$cursoByID['NOMBRE']),0,0,'C');

    $pdf->Ln(8);

    //Encabezado

    $pdf->SetFont('Arial','',5);
    $pdf->SetX(65);

    foreach($competenciasPorArea as $areaKey => $competencias){

        $numCompArea = count($competenciasPorArea[$areaKey]);
        $anchoArea = (5.5*($numCompArea+1)) ; //mas 1 de la nota definitiva
        $pdf->Cell($anchoArea,6, substr($areas[$areaKey]['AREA'],0,23),1);

    }

    $pdf->Ln();

    $pdf->SetFont('Arial','',6);

    //Codigos competencias
    $pdf->SetX(65);

    foreach($competenciasPorArea as $areaKey => $competencias){
      foreach($competencias as $competencia){
        $pdf->Cell(5.5,6,$competencia["IDENTIFICADOR"],1,0,'C');
      }
      $pdf->Cell(5.5,6,'D',1,0,'C');
    }
    $pdf->Ln();

    //Notas Estudiantes

    $a=0;
    while(isset($estudiantes[$a][0])){
      $pdf->SetFont('Arial','',7);
      $nombreEstudiante = $estudiantes[$a]['APELLIDO']." ".$estudiantes[$a]['APELLIDO2']." ";
      $nombreEstudiante .= $estudiantes[$a]['NOMBRE']." ".$estudiantes[$a]['NOMBRE2']." ";
      $pdf->Cell(55,5,utf8_decode($nombreEstudiante),1);

      foreach($competenciasPorArea as $key => $value){
        foreach($value as $competencia){
          $desempenio = $notasPorCurso[$estudiantes[$a]['ID']][$competencia["ID"]]['DESEMPENIO'];
          if( $desempenio == "Alto"){
            $pdf->SetFillColor(255,255,0);
          }
          elseif( $desempenio == "Basico" ){
            $pdf->SetFillColor(221, 228, 255);
          }
          elseif( $desempenio == "Superior" ){
            $pdf->SetFillColor(0,255,255);
          }
          else{
            $pdf->SetFillColor(255,255,255);
          }
          $pdf->Cell(5.5,5,$notasPorCurso[$estudiantes[$a]['ID']][$competencia["ID"]]['NOTA_FINAL'],1,0,'C',true);
        }
        $pdf->Cell(5.5,5,' ',1,0,'C',true);
      }

      $pdf->Ln();
     $a++;
    }
    $pdf->Output();
	}*/
}