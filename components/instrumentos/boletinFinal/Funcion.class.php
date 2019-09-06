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

class FuncionboletinFinal{

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


	function imprimirBoletin($variable)
    {
		$this->formulario->setSql($this->sql);
		$this->formulario->setRuta($this->ruta);

		//Rescato el listado de competencias para el grado actual
		$cadenaSql    = $this->sql->get("competencias",$variable['grado']);
		$competencias = $this->resource->execute($cadenaSql,"busqueda");
		$competenciasPorArea = $this->sorter->orderMultiKeyBy($competencias,"ID_AREA");

		//Consulto el listado de areas para el grado actual
		$cadenaSql = $this->sql->get("areas",$variable['grado']);
		$areas = $this->resource->execute($cadenaSql,"busqueda");

		//Consultar nombres Sede, Curso, Area
		$cadenaSql = $this->sql->get("sedeByID",$variable['sede']);
		$sedeByID  = $this->resource->execute($cadenaSql,"busqueda");
		$sedeByID  = $sedeByID[0];

		$cadenaSql = $this->sql->get("cursoByID",$variable['curso']);
		$cursoByID = $this->resource->execute($cadenaSql,"busqueda");
		$cursoByID = $cursoByID[0];

        if( isset($variable['estudiante']) && !empty($variable['estudiante']) ) {
          $cadenaSql = $this->sql->get("estudianteByID",$variable['estudiante']);
          $listadoEstudiantes = $this->resource->execute($cadenaSql,"busqueda");
        } else {
          $cadenaSql = $this->sql->get("estudiantesPorCurso",$variable['curso']);
          $listadoEstudiantes = $this->resource->execute($cadenaSql,"busqueda");
        }
        
        $this->loadPDFBoletin($listadoEstudiantes,$cursoByID,$sedeByID,$areas,$competenciasPorArea,$variable['grado']);

    }

    function loadPDFBoletin($listadoEstudiantes,$cursoByID,$sedeByID,$areas,$competenciasPorArea,$idGrado)
    {
		$pdf = new FPDF('P','mm','Letter'); //215.9 mm x 279.4 mm
		$pdf->SetMargins(10,10,10); //izq,arr,der
        $imageFolder = $this->context->getVariable("raizDocumento").'/images/';

        $e=0;

        while(isset($listadoEstudiantes[$e][0])) {

            //Consulto las notas finales de los estudiante
            $variable['estudiante'] = $listadoEstudiantes[$e]['ID'];
            //Consultar las notas finales de los estudiante
            $cadenaSql = $this->sql->get("notasFinalesPorEstudianteCriterios",$variable);
            $notasFinalesPorEstudianteCriterios = $this->resource->execute($cadenaSql,"busqueda");
            $notasFinalesPorEstudianteCriterios = $this->sorter->orderKeyBy($notasFinalesPorEstudianteCriterios,"COMPETENCIA");

            $cadenaSql = $this->sql->get("notasFinalesPorEstudianteCualitativa",$variable);
            $notasFinalesPorEstudianteCualitativa = $this->resource->execute($cadenaSql,"busqueda");
            $notasFinalesPorEstudianteCualitativa = $this->sorter->orderKeyBy($notasFinalesPorEstudianteCualitativa,"COMPETENCIA");

            //Organizar por competencias
            $notaEstudiante = $notasFinalesPorEstudianteCriterios + $notasFinalesPorEstudianteCualitativa;

            //Tipo de Boletin de Acuerdo al Grado del Estudiante
            switch($idGrado) {
                case 1: //Grado 0
                    $check = $imageFolder.'/star.png';
                    $pdf->AddPage();
                    $pdf->Image($imageFolder.'/bg-boletin-1.jpg', 0, 0, 215.9, 279.4);
                    $y = $pdf->GetY();
                    $pdf->Image($imageFolder.'/cerural.png',10,10,30,30);
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
                    $pdf->Cell(190,5,utf8_decode("Boletín Académico Institucional ".$this->activeYear." Sede ".$sedeByID['NOMBRE']),0,0,'C');
                    $pdf->Ln(8);
                    $pdf->SetFont('Arial','B',13);
                    $estudiante = $listadoEstudiantes[$e]['APELLIDO'].' '.$listadoEstudiantes[$e]['APELLIDO2'].' '.$listadoEstudiantes[$e]['NOMBRE'].' '.@$estudianteByID[$e]['NOMBRE2'];
                    $pdf->Cell(40,8,"Estudiante ".utf8_decode($estudiante)."    Grado ".$cursoByID['NOMBRE']);
                    $pdf->Ln(8);
                    $pdf->SetFont('Arial','B',9);
                    $colorCell = [];
                    $colorCell[] = [255,145,53];
                    $colorCell[] = [31,193,195];
                    $colorCell[] = [255,73,156];
                    $colorCell[] = [61,161,63];
                    $colorCell[] = [255,189,46];
                    $colorCell[] = [0,150,136];
                    $colorCell[] = [156,39,176];
                    $a = 0;
                    while(isset($areas[$a][0])){
                    if($pdf->GetY()>230){
                    $pdf->AddPage();
                    }
                    $pdf->SetFont('Arial','B',10);
                    $pdf->SetFillColor($colorCell[$a][0],$colorCell[$a][1],$colorCell[$a][2]);

                    $pdf->Cell(150,6,"AREA: ".$areas[$a]['AREA'],1,0,'L',1);
                    $pdf->Cell(15,6,"S",1,0,'C',1);
                    $pdf->Cell(15,6,"CS",1,0,'C',1);
                    $pdf->Cell(15,6,"AV",1,0,'C',1);
                    $pdf->Ln();

                    $c=0;
                    while(isset($competenciasPorArea[$areas[$a]['ID']][$c][0]))
                    {
                    $competencia = utf8_decode($competenciasPorArea[$areas[$a]['ID']][$c]['COMPETENCIA']);

                    $x = $pdf->GetX(); //x inicial
                    $y = $pdf->GetY(); //y inicial

                    $pdf->SetFillColor(255,255,255);


                    $pdf->SetFont('Arial','B',9);
                    $pdf->MultiCell(150,5,"Competencia ".$competenciasPorArea[$areas[$a]['ID']][$c]['IDENTIFICADOR'].": ".$competencia,1,'J',0);
                    $alto = ($pdf->GetY()) - $y;

                    $pdf->SetXY($x + 150,$y);

                    if(isset($notaEstudiante[$competenciasPorArea[$areas[$a]['ID']][$c]['ID']]["NOTA_FINAL"])) {
                    $nota_final = $notaEstudiante[$competenciasPorArea[$areas[$a]['ID']][$c]['ID']]["NOTA_FINAL"];
                    }else {
                    $nota_final = '';
                    }

                    //$pdf->Image($check,15,$alto);

                    $pdf->Cell(15,$alto,($nota_final=='S')?$pdf->Image($check, $pdf->GetX()+5, $pdf->GetY(),4.5):'',1,0,'C');
                    $pdf->Cell(15,$alto,($nota_final=='CS')?$pdf->Image($check, $pdf->GetX()+5, $pdf->GetY(),4.5):'',1,0,'C');
                    $pdf->Cell(15,$alto,($nota_final=='AV')?$pdf->Image($check, $pdf->GetX()+5, $pdf->GetY(),4.5):'',1,0,'C');
                    $pdf->Ln();
                    $c++;
                    }
                    $pdf->Ln(3);
                    $a++;
                    }
                    $pdf->Ln(0);
                    $pdf->Cell(40,3,"OBSERVACIONES:",0,0,'L');
                    $pdf->Ln(5);
                    $pdf->Cell(295,2,utf8_decode("_____________________________"),0,0,'C');
                    $pdf->Ln(4);
                    $pdf->Cell(295,3,"FIRMA DOCENTE",0,0,'C');
                break;
                default:
                    $pdf->AddPage();
                    $y = $pdf->GetY();
                    $pdf->Image($imageFolder.'/cerural.jpg',10,10,25,25);
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
                    $pdf->Cell(190,5,utf8_decode("Boletín Académico Institucional ".$this->activeYear." Sede ".$sedeByID['NOMBRE']),0,0,'C');
                    $pdf->Ln(8);
                    $pdf->SetFont('Arial','B',13);
                    $estudiante = $listadoEstudiantes[$e]['APELLIDO'].' '.$listadoEstudiantes[$e]['APELLIDO2'].' '.$listadoEstudiantes[$e]['NOMBRE'].' '.@$estudianteByID[$e]['NOMBRE2'];
                    $pdf->Cell(40,8,"Estudiante ".utf8_decode($estudiante)."    Grado ".$cursoByID['NOMBRE']);
                    $pdf->Ln(8);
                    $pdf->SetFont('Arial','B',9);
                    $a = 0;
                    while(isset($areas[$a][0])){
                    if($pdf->GetY()>230){
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
                    $pdf->Cell(15,$alto,@$notaEstudiante[$competenciasPorArea[$areas[$a]['ID']][$c]['ID']]["DESEMPENIO"],1,0,'C');
                    $nota_final = isset($notaEstudiante[$competenciasPorArea[$areas[$a]['ID']][$c]['ID']]["NOTA_FINAL"]) ? $notaEstudiante[$competenciasPorArea[$areas[$a]['ID']][$c]['ID']]["NOTA_FINAL"] : 0;
                    if(($nota_final*1) < 3 ) {
                    $nota_final = "";
                    }
                    $pdf->Cell(15,$alto,$nota_final,1,0,'C');
                    $pdf->Ln();
                    $c++;
                    }
                    $pdf->Ln(3);
                    $a++;
                    }
                    $pdf->Ln(0);
                    $pdf->Cell(40,3,"OBSERVACIONES:",0,0,'L');
                    $pdf->Ln(5);
                    $pdf->Cell(295,2,utf8_decode("_____________________________"),0,0,'C');
                    $pdf->Ln(4);
                    $pdf->Cell(295,3,"FIRMA DOCENTE",0,0,'C');
                break;
            }

            $e++;
        }

        $pdf->Output();
    }

	function action()
    {

		//Evitar que se ingrese codigo HTML y PHP en los campos de texto
		//Campos que se quieren excluir de la limpieza de código. Formato: nombreCampo1|nombreCampo2|nombreCampo3
		$excluir  = "";
		$_REQUEST = $this->inspector->cleanPHPHTML($_REQUEST);
		$option = isset($_REQUEST['option'])?$_REQUEST['option']:"list";
		switch($option)
        {
			case "processList":
				$variable["option"]="list";
				$variable["grado"]=$_REQUEST['grado'];
				$variable["sede"]=$_REQUEST['sede'];

				$this->context->render("boletinFinal",$variable);

			break;
			case "imprimirBoletin":
				$this->imprimirBoletin($_REQUEST);
			break;
			case "imprimirBoletinLote":
				$this->imprimirBoletin($_REQUEST);
			break;
		}
	}


	function __construct()
	{
		$this->context = Context::singleton();
		$this->session = Sesion::singleton();
		$this->sessionId = $this->session->getValue('idUsuario');
		$this->inspector = Inspector::singleton();
		$this->ruta = $this->context->getVariable("rutaBloque");
		$this->miMensaje = Mensaje::singleton();
		$this->enlace = $this->context->getVariable("host").$this->context->getVariable("site")."?".$this->context->getVariable("enlace");
		$conexion = "aplicativo";
		$this->resource = $this->context->fabricaConexiones->getRecursoDB($conexion);
		$this->sorter = orderArray::singleton();


	}

	public function setRuta($unaRuta)
    {
		$this->ruta = $unaRuta;
		//Incluir las funciones
	}

	function setSql($a)
    {
		$this->sql=$a;
	}

	function setFuncion($funcion){
		$this->funcion=$funcion;
	}

	public function setLenguaje($lenguaje)
	{
		$this->lenguaje=$lenguaje;
	}

	public function setFormulario($formulario)
    {
		$this->formulario=$formulario;
	}
}
