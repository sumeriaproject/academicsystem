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
include_once("plugin/mail/class.phpmailer.php");
include_once("plugin/mail/class.smtp.php");
include_once("plugin/html2pdf/html2pdf.class.php");
include_once("plugin/fpdf/fpdf.php");
include_once("class/Array.class.php");

class FuncionHistorico{

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

	function procesarNota($variable){

		$respuesta= new StdClass();
    $variable['nota']=str_replace(",",".",$variable['nota']);
    $variable['nota']=filter_var($variable['nota'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);

		//1. Validar nota menor a 5
		if($variable['nota']>5){
			echo "La nota máxima es 5";
			return false;
		}

		//2. Consultar si la nota existe
		$cadenaSql=$this->sql->cadenaSql("notaPorCriterio",$variable);
		$notaPorCriterio=$this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");

		//3. Si ya existe actualizarNota
		if(is_array($notaPorCriterio)){
			$cadenaSql=$this->sql->cadenaSql("actualizarNota",$variable);
			$notaPorCriterio=$this->miRecursoDB->ejecutarAcceso($cadenaSql,"");
		}
		//4. Si no existe insertar
		else{
			$cadenaSql=$this->sql->cadenaSql("insertarNota",$variable);
			$notaPorCriterio=$this->miRecursoDB->ejecutarAcceso($cadenaSql,"");
		}
	}

	/**
	*	Este método retorna un JSON con los resultados de la operación
	*/
	function calcularNotasFinales($estudiante,$competencia){

			$respuesta=new stdClass();
      //5. Recalcular definitivas

			//5.1 Para poder calcular la sumatoria, todos los criterios deben tener la correspondiente nota

			$cadenaSql=$this->sql->cadenaSql("criteriosPorCompetencia",$competencia);
			$criteriosPorCompetencia=$this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");

			$variable['competencia']=$competencia;
			$variable['estudiante']=$estudiante;

			$cadenaSql=$this->sql->cadenaSql("notasPorEstudianteyCompetencia",$variable);
			$notasPorEstudianteyCompetencia=$this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
			$notasPorEstudianteyCompetencia=$this->organizador->orderKeyBy($notasPorEstudianteyCompetencia,"CRITERIO");

			$criteriosPendientes=0;
			$i=0;
			$notaFinal=0;
			while(isset($criteriosPorCompetencia[$i][0])){
				if(!isset($notasPorEstudianteyCompetencia[$criteriosPorCompetencia[$i]['ID']]) || $notasPorEstudianteyCompetencia[$criteriosPorCompetencia[$i]['ID']]['NOTA']==""){
					$criteriosPendientes++;
				}else{
					$notaFinal=$notaFinal+($notasPorEstudianteyCompetencia[$criteriosPorCompetencia[$i]['ID']]['NOTA'])*($criteriosPorCompetencia[$i]['PORCENTAJE'])/100;
				}
			$i++;
			}
			if($criteriosPendientes==0){

				$respuesta->notaFinal=round($notaFinal,2);
				$respuesta->notaFinalPorcentaje=$notaFinal*100/5;
				$respuesta->notaFinalPorcentaje=round($respuesta->notaFinalPorcentaje,2);

				//no esta dinamico no esta arrojando correctamente el desempeño

				if(($respuesta->notaFinalPorcentaje)*1>=100){
					$respuesta->desempenio="Superior";
				}
				elseif(($respuesta->notaFinalPorcentaje)*1>=80 && ($respuesta->notaFinalPorcentaje)*1<100){
					$respuesta->desempenio="Alto";
				}
				elseif(($respuesta->notaFinalPorcentaje)*1<80){
					$respuesta->desempenio="Basico";
				}

			}else{
        $respuesta->desempenio="";
        $respuesta->notaFinal=$notaFinal;
				$respuesta->notaFinalPorcentaje="";
				$respuesta->error="Criterios pendientes por evaluar";
			}


      //almacenar en bd
      $variable['desempenio']=$respuesta->desempenio;
      $variable['nota_final']=$respuesta->notaFinal;
      $variable['nota_porcentual']=$respuesta->notaFinalPorcentaje;

      $cadenaSql=$this->sql->cadenaSql("notaFinal",$variable);
      $notaFinal=$this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");

      if(is_array($notaFinal)){
        $variable['id_nota_final']=$notaFinal[0]['ID'];
        $cadenaSql=$this->sql->cadenaSql("actualizarNotaFinal",$variable);
        $notaFinal=$this->miRecursoDB->ejecutarAcceso($cadenaSql,"");
      }else{
        $cadenaSql=$this->sql->cadenaSql("insertarNotaFinal",$variable);
        $notaFinal=$this->miRecursoDB->ejecutarAcceso($cadenaSql,"");
      }

		echo json_encode($respuesta);
		return true;

	}

	function imprimirBoletin($variable){


		$this->formulario->setSql($this->sql);
		$this->formulario->setRuta($this->ruta);

		//1.Rescato el listado de competencias para el grado actual
		$cadenaSql=$this->sql->cadenaSql("competencias",$variable['grado']);
		$competencias=$this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
		$competenciasPorArea=$this->organizador->orderMultiKeyBy($competencias,"ID_AREA");


		//2.Consulto el listado de areas para el grado actual
		$cadenaSql=$this->sql->cadenaSql("areas",$variable['grado']);
		$areas=$this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");


		//2.Consultar nombres Sede, Curso, Area
		$cadenaSql=$this->sql->cadenaSql("sedeByID",$variable['sede']);
		$sedeByID=$this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
		$sedeByID=$sedeByID[0];

		$cadenaSql=$this->sql->cadenaSql("cursoByID",$variable['curso']);
		$cursoByID=$this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
		$cursoByID=$cursoByID[0];


    if( isset($variable['estudiante']) && !empty($variable['estudiante']) ){

      $cadenaSql=$this->sql->cadenaSql("estudianteByID",$variable['estudiante']);
      $listadoEstudiantes=$this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");

    }else{

      $cadenaSql=$this->sql->cadenaSql("estudiantesPorCurso",$variable['curso']);
      $listadoEstudiantes=$this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");

    }

    $this->loadPDFBoletin($listadoEstudiantes,$cursoByID,$sedeByID,$areas,$competenciasPorArea);

	}

  function loadPDFBoletin($listadoEstudiantes,$cursoByID,$sedeByID,$areas,$competenciasPorArea){

		$pdf = new FPDF('P','mm','Letter'); //215.9 mm x 279.4 mm
		$pdf->SetMargins(10,10,10); //izq,arr,der

    $e=0;

    while(isset($listadoEstudiantes[$e][0])){


      //6. Consulto las notas finales de los estudiante
      $variable['estudiante']=$listadoEstudiantes[$e]['ID'];
      $cadenaSql=$this->sql->cadenaSql("notasFinalesPorEstudiante",$variable);
      $notasFinalesPorEstudiante=$this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");

      //6.1 Organizar por competencias
      $notaEstudiante=$this->organizador->orderKeyBy($notasFinalesPorEstudiante,"COMPETENCIA");


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
      $pdf->Ln(8);
      $pdf->SetFont('Arial','B',13);
      $pdf->Cell(190,5,utf8_decode("Boletín Académico Institucional 2016 Sede ".$sedeByID['NOMBRE']),0,0,'C');
      $pdf->Ln(10);
      $pdf->SetFont('Arial','B',13);
      $estudiante = $listadoEstudiantes[$e]['APELLIDO'].' '.$listadoEstudiantes[$e]['APELLIDO2'].' '.$listadoEstudiantes[$e]['NOMBRE'].' '.$estudianteByID[$e]['NOMBRE2'];
      $pdf->Cell(40,10,"Estudiante ".utf8_decode($estudiante)."    Grado ".$cursoByID['NOMBRE']);

      $pdf->Ln(10);
      $pdf->SetFont('Arial','B',9);
      $a = 0;
      while(isset($areas[$a][0])){

        if($pdf->GetY()>240){
          $pdf->AddPage();
        }

        $pdf->SetFont('Arial','B',8);
        $pdf->Cell(165,6,"AREA:".$areas[$a]['AREA'],1);
        $pdf->Cell(15,6,"(D)",1,0,'C');
        $pdf->Cell(15,6,"(V)",1,0,'C');
        $pdf->Ln();

        $c=0;
        while(isset($competenciasPorArea[$areas[$a]['ID']][$c][0])){

          $competencia = utf8_decode($competenciasPorArea[$areas[$a]['ID']][$c]['COMPETENCIA']);

          $x = $pdf->GetX(); //x inicial
          $y = $pdf->GetY(); //y inicial

          $pdf->SetFont('Arial','',8);
          $pdf->MultiCell(165,4,"Competencia ".$competenciasPorArea[$areas[$a]['ID']][$c]['IDENTIFICADOR'].": ".$competencia,1);
          $alto = ($pdf->GetY())-$y;

          $pdf->SetXY($x+165,$y);
          $pdf->Cell(15,$alto,$notaEstudiante[$competenciasPorArea[$areas[$a]['ID']][$c]['ID']]["DESEMPENIO"],1,0,'C');

          $nota_final = $notaEstudiante[$competenciasPorArea[$areas[$a]['ID']][$c]['ID']]["NOTA_FINAL"];

          if(($nota_final*1) < 3 ){
            $nota_final = "";
          }

          $pdf->Cell(15,$alto,$nota_final,1,0,'C');
          $pdf->Ln();
        $c++;
        }
        $pdf->Ln(5);
        $a++;
      }
      $e++;

      $pdf->Ln(2);
      $pdf->Cell(40,3,"OBSERVACIONES:",0,0,'L');


      $pdf->Ln(35);

      $pdf->Cell(195,4,utf8_decode("_____________________________"),0,0,'C');
      $pdf->Ln(5);
      $pdf->Cell(195,3,"FIRMA DOCENTE",0,0,'C');
    }
    $pdf->Output();
  }


	function processDelete($id){
		include_once($this->ruta."/funcion/processDelete.php");
		return $status;
	}

	function enviarCorreo($email,$clave){
		include_once($this->ruta."/funcion/enviarCorreo.php");
	}

	function action(){

		//Evitar que se ingrese codigo HTML y PHP en los campos de texto
		//Campos que se quieren excluir de la limpieza de código. Formato: nombreCampo1|nombreCampo2|nombreCampo3
		$excluir="";
		$_REQUEST=$this->miInspectorHTML->limpiarPHPHTML($_REQUEST);

		$option=isset($_REQUEST['option'])?$_REQUEST['option']:"list";


		switch($option){
			case "processList":
				$variable["option"]="list";
				$variable["grado"]=$_REQUEST['grado'];
				$variable["sede"]=$_REQUEST['sede'];
				$variable["anio"]=$_REQUEST['anio'];
 
				$this->miConfigurador->render("Historico",$variable);

			break;
			case "actualizarNota":
				$result=$this->procesarNota($_REQUEST);
				$result=$this->calcularNotasFinales($_REQUEST['estudiante'],$_REQUEST['competencia']);
			break;
			case "imprimirBoletin":
				$this->imprimirBoletin($_REQUEST);
			break;
			case "imprimirBoletinLote":
				$this->imprimirBoletin($_REQUEST);
			break;
			case "processDelete":

				$result=$this->processDelete($_REQUEST['optionValue']);

				if($result===TRUE){
					$variable["mensaje"]="El usuario se elimino correctamente";
				}else{
					$variable["mensaje"]="El usuario no se elimino";
				}
				$variable["option"]="list";

				$this->miConfigurador->render("Historico",$variable);

			break;
		}
	}


	function __construct()
	{

		$this->miConfigurador=Configurador::singleton();
		$this->miSesion=Sesion::singleton();
		$this->idSesion=$this->miSesion->getValorSesion('idUsuario');

		$this->miInspectorHTML=InspectorHTML::singleton();

		$this->ruta=$this->miConfigurador->getVariableConfiguracion("rutaBloque");

		$this->miMensaje=Mensaje::singleton();
		$this->mail=new phpmailer();
		$this->enlace=$this->miConfigurador->getVariableConfiguracion("host").$this->miConfigurador->getVariableConfiguracion("site")."?".$this->miConfigurador->getVariableConfiguracion("enlace");
		$conexion="aplicativo";
		$this->miRecursoDB=$this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		$this->organizador=orderArray::singleton();


	}

	public function setRuta($unaRuta){
		$this->ruta=$unaRuta;
		//Incluir las funciones
	}

	function setSql($a){
		$this->sql=$a;
	}

	function setFuncion($funcion){
		$this->funcion=$funcion;
	}

	public function setLenguaje($lenguaje)
	{
		$this->lenguaje=$lenguaje;
	}

	public function setFormulario($formulario){
		$this->formulario=$formulario;
	}

}
?>