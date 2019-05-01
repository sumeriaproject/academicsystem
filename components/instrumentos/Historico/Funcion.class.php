<?php
if(!isset($GLOBALS["autorizado"])){
	include("../index.php");
	exit;
}

include_once("core/auth/Sesion.class.php");
include_once("core/manager/Context.class.php");
include_once("core/builder/Inspector.class.php");
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
	var $context;
	var $inspector;
	var $error;
	var $resource;
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
		$cadenaSql=$this->sql->get("notaPorCriterio",$variable);
		$notaPorCriterio=$this->resource->execute($cadenaSql,"busqueda");

		//3. Si ya existe actualizarNota
		if(is_array($notaPorCriterio)){
			$cadenaSql=$this->sql->get("actualizarNota",$variable);
			$notaPorCriterio=$this->resource->execute($cadenaSql,"");
		}
		//4. Si no existe insertar
		else{
			$cadenaSql=$this->sql->get("insertarNota",$variable);
			$notaPorCriterio=$this->resource->execute($cadenaSql,"");
		}
	}

	/**
	*	Este método retorna un JSON con los resultados de la operación
	*/
	function calcularNotasFinales($estudiante,$competencia){

			$respuesta=new stdClass();
      //5. Recalcular definitivas

			//5.1 Para poder calcular la sumatoria, todos los criterios deben tener la correspondiente nota

			$cadenaSql=$this->sql->get("criteriosPorCompetencia",$competencia);
			$criteriosPorCompetencia=$this->resource->execute($cadenaSql,"busqueda");

			$variable['competencia']=$competencia;
			$variable['estudiante']=$estudiante;

			$cadenaSql=$this->sql->get("notasPorEstudianteyCompetencia",$variable);
			$notasPorEstudianteyCompetencia=$this->resource->execute($cadenaSql,"busqueda");
			$notasPorEstudianteyCompetencia=$this->sorter->orderKeyBy($notasPorEstudianteyCompetencia,"CRITERIO");

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

      $cadenaSql=$this->sql->get("notaFinal",$variable);
      $notaFinal=$this->resource->execute($cadenaSql,"busqueda");

      if(is_array($notaFinal)){
        $variable['id_nota_final']=$notaFinal[0]['ID'];
        $cadenaSql=$this->sql->get("actualizarNotaFinal",$variable);
        $notaFinal=$this->resource->execute($cadenaSql,"");
      }else{
        $cadenaSql=$this->sql->get("insertarNotaFinal",$variable);
        $notaFinal=$this->resource->execute($cadenaSql,"");
      }

		echo json_encode($respuesta);
		return true;

	}

	function imprimirBoletin($variable){


		$this->formulario->setSql($this->sql);
		$this->formulario->setRuta($this->ruta);

		//1.Rescato el listado de competencias para el grado actual
		$cadenaSql=$this->sql->get("competencias",$variable['grado']);
		$competencias=$this->resource->execute($cadenaSql,"busqueda");
		$competenciasPorArea=$this->sorter->orderMultiKeyBy($competencias,"ID_AREA");


		//2.Consulto el listado de areas para el grado actual
		$cadenaSql=$this->sql->get("areas",$variable['grado']);
		$areas=$this->resource->execute($cadenaSql,"busqueda");


		//2.Consultar nombres Sede, Curso, Area
		$cadenaSql=$this->sql->get("sedeByID",$variable['sede']);
		$sedeByID=$this->resource->execute($cadenaSql,"busqueda");
		$sedeByID=$sedeByID[0];

		$cadenaSql=$this->sql->get("cursoByID",$variable['curso']);
		$cursoByID=$this->resource->execute($cadenaSql,"busqueda");
		$cursoByID=$cursoByID[0];


    if( isset($variable['estudiante']) && !empty($variable['estudiante']) ){

      $cadenaSql=$this->sql->get("estudianteByID",$variable['estudiante']);
      $listadoEstudiantes=$this->resource->execute($cadenaSql,"busqueda");

    }else{

      $cadenaSql=$this->sql->get("estudiantesPorCurso",$variable['curso']);
      $listadoEstudiantes=$this->resource->execute($cadenaSql,"busqueda");

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
      $cadenaSql=$this->sql->get("notasFinalesPorEstudiante",$variable);
      $notasFinalesPorEstudiante=$this->resource->execute($cadenaSql,"busqueda");

      //6.1 Organizar por competencias
      $notaEstudiante=$this->sorter->orderKeyBy($notasFinalesPorEstudiante,"COMPETENCIA");


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
		$_REQUEST=$this->inspector->cleanPHPHTML($_REQUEST);

		$option=isset($_REQUEST['option'])?$_REQUEST['option']:"list";


		switch($option){
			case "processList":
				$variable["option"]="list";
				$variable["grado"]=$_REQUEST['grado'];
				$variable["sede"]=$_REQUEST['sede'];
				$variable["anio"]=$_REQUEST['anio'];
 
				$this->context->render("Historico",$variable);

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

				$this->context->render("Historico",$variable);

			break;
		}
	}


	function __construct()
	{

		$this->context=Context::singleton();
		$this->session=Sesion::singleton();
		$this->sessionId=$this->session->getValue('idUsuario');

		$this->inspector=Inspector::singleton();

		$this->ruta=$this->context->getVariable("rutaBloque");

		$this->miMensaje=Mensaje::singleton();
		$this->mail=new phpmailer();
		$this->enlace=$this->context->getVariable("host").$this->context->getVariable("site")."?".$this->context->getVariable("enlace");
		$conexion="aplicativo";
		$this->resource=$this->context->fabricaConexiones->getRecursoDB($conexion);
		$this->sorter=orderArray::singleton();


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
