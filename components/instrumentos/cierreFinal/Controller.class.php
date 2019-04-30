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
include_once("plugin/html2pdf/html2pdf.class.php");
include_once("plugin/fpdf/fpdf.php");
include_once("class/Array.class.php");

class FuncioncierreFinal{

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


	function action(){

		//Evitar que se ingrese codigo HTML y PHP en los campos de texto
		//Campos que se quieren excluir de la limpieza de código. Formato: nombreCampo1|nombreCampo2|nombreCampo3
		$excluir="";
		$_REQUEST=$this->miInspectorHTML->limpiarPHPHTML($_REQUEST);

		$option=isset($_REQUEST['option'])?$_REQUEST['option']:"list";

		switch($option){
			case "cierreNotas":

        echo "Cerrando";
        $this->procesarCierre($_REQUEST);
				/*$variable["option"]="list";
				$variable["grado"]=$_REQUEST['grado'];
				$variable["sede"]=$_REQUEST['sede'];

				$this->miConfigurador->render("cierreFinal",$variable);*/

			break;
      case "imprimirBoletines":
        $this->imprimirBoletines($_REQUEST);
      break;

		}
	}

	function imprimirBoletines($variable){

		//1.Rescato el listado de competencias para el grado actual
		$cadenaSql = $this->sql->cadenaSql("competenciasPorGrado",$variable['grado']);
		$competencias = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
		$competenciasPorArea = $this->organizador->orderMultiKeyBy($competencias,"ID_AREA");

		//2.Consulto el listado de areas para el grado actual
		$cadenaSql = $this->sql->cadenaSql("areasPorGrado",$variable['grado']);
		$areas = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");

		//2.Consultar nombres Sede, Curso, Area
		$cadenaSql = $this->sql->cadenaSql("sedeByID",$variable['sede']);
		$sedeByID  = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
		$sedeByID  = $sedeByID[0];

		$cadenaSql = $this->sql->cadenaSql("cursoByID",$variable['curso']);
		$cursoByID = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
		$cursoByID = $cursoByID[0];

    if( isset($variable['estudiante']) && !empty($variable['estudiante']) ) {
      $cadenaSql = $this->sql->cadenaSql("estudianteByID",$variable['estudiante']);
      $listadoEstudiantes = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
    }else  {

      $cadenaSql = $this->sql->cadenaSql("estudiantesPorCurso",$variable['curso']);
      $listadoEstudiantes = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
    }
    $this->loadPDFBoletin($listadoEstudiantes,$cursoByID,$sedeByID,$areas,$competenciasPorArea);
	}

  function loadPDFBoletin($listadoEstudiantes,$cursoByID,$sedeByID,$areas,$competenciasPorArea){

		$pdf = new FPDF('P','mm','Letter'); //215.9 mm x 279.4 mm
		$pdf->SetMargins(10,10,10); //izq,arr,der

    $e = 0;

    while(isset($listadoEstudiantes[$e][0])) {

      // Consulto las notas finales de los estudiantes
      $variable['estudiante'] = $listadoEstudiantes[$e]['ID'];
      $cadenaSql = $this->sql->cadenaSql("notasDefinitivasPorEstudiante",$variable);
      $notasDefEstudiante = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
      $notasDefEstudiante = $this->organizador->orderKeyBy($notasDefEstudiante,"AREA");

      // Consulto las notas finales de las competencias
      $cadenaSql = $this->sql->cadenaSql("notasFinalesPorEstudiante",$variable);
      $notasCompetencias = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
      $notasCompetencias = $this->organizador->orderKeyBy($notasCompetencias,"COMPETENCIA");

      //traer

      $pdf->AddPage();

      $y = $pdf->GetY();
      $pdf->Image('http://www.academia.ceruralrestrepo.com/cerural.jpg',10,10,25,25);
      $pdf->SetY($y);

      $pdf->SetFont('Arial','',10);
      $pdf->Cell(190,5,utf8_decode("DEPARTAMENTO DEL META - RESTREPO - SECRETARÍA DE EDUCACIÓN"),0,0,'C');
      $pdf->Ln();
      $pdf->SetFont('Arial','B',13);
      $pdf->Cell(190,5,"CENTRO EDUCATIVO RURAL DE RESTREPO",0,0,'C');
      $pdf->Ln();
      $pdf->SetFont('Arial','I',10);
      $pdf->Cell(190,5,"DANE 150606000393",0,0,'C');
      $pdf->Ln(8);
      $pdf->SetFont('Arial','B',13);
      $pdf->Cell(190,5,utf8_decode("BOLETÍN FINAL ".$this->activeYear." - Sede ".$sedeByID['NOMBRE']),0,0,'C');
      $pdf->Ln(8);
      $pdf->SetFont('Arial','B',13);
      $estudiante = $listadoEstudiantes[$e]['APELLIDO'].' '.$listadoEstudiantes[$e]['APELLIDO2'].' '.$listadoEstudiantes[$e]['NOMBRE'].' '.@$estudianteByID[$e]['NOMBRE2'];
      $pdf->Cell(40,10,"Estudiante: ".utf8_decode($estudiante)."    Grado ".$cursoByID['NOMBRE']);

      $pdf->Ln(12);

      $yini = $pdf->GetY(); //y inicial

      $a = 0;
      $ypendtotal = 0;
      
      while(isset($areas[$a][0])) {
        
        if($ypendtotal>0) {
          $pdf->SetY($ypendtotal+4);
          $ypendtotal = 0;
        }
        
        if($pdf->GetY()>220) {
          $pdf->AddPage();
        }

        $pdf->SetFont('Arial','B',9);
        $pdf->Cell(195,4,$areas[$a]['AREA'],0,0,'L');
        $pdf->SetFont('Arial','',8);
        $pdf->Ln();

        $x = $pdf->GetX(); //x inicial
        $y = $pdf->GetY(); //y inicial

        $pdf->Cell(20,8,'Competencias:',0,0,'R');
        $pdf->SetXY($x+21,$y);

        $c=0;
        $competenciasPendientes = "";
        
        
        while(isset($competenciasPorArea[$areas[$a]['ID']][$c][0])){

          $competencia = trim(utf8_decode($competenciasPorArea[$areas[$a]['ID']][$c]['COMPETENCIA']));
          $nota_final = @$notasCompetencias[$competenciasPorArea[$areas[$a]['ID']][$c]['ID']]["NOTA_FINAL"];

          $xc = $pdf->GetX(); //x area inicial
          $yc = $pdf->GetY(); //y area inicial
       

          if($c == 0 ){ //si es la primer competencia obtener su posición en x
            $xfirstc = $pdf->GetX();
            $yfirstc = $pdf->GetY();
          }
          
          if($c == 5 ){
            $xc = $x;
            $yc = $y+12;
            $pdf->SetXY($xfirstc,$y+12);
            $xc = $xfirstc;
          }
          if($c == 10 ){
            $xc = $x;
            $yc = $y+24;
            $pdf->SetXY($xfirstc,$y+24);
            $xc = $xfirstc;
          }

          $pdf->Cell(10,8,$competenciasPorArea[$areas[$a]['ID']][$c]['IDENTIFICADOR'],1,0,'C');
          $pdf->SetXY($xc+10,$yc);
          $pdf->Cell(5,4,'D',1,0,'C');
          $pdf->SetXY($xc+10,$yc+4);
          $pdf->Cell(5,4,'V',1,0,'C');
          $pdf->SetXY($xc+15,$yc);
          $pdf->Cell(15,4,@$notasCompetencias[$competenciasPorArea[$areas[$a]['ID']][$c]['ID']]["DESEMPENIO"],1,0,'C');
          $pdf->SetXY($xc+15,$yc+4);

          if(($nota_final*1) < 3 || empty($nota_final) ){
            $nota_final = "";
            $competenciasPendientes .= "[ ".$competenciasPorArea[$areas[$a]['ID']][$c]['IDENTIFICADOR']." ] "
                                      .$competencia."\n";
          }
          $pdf->Cell(15,4,$nota_final,1,0,'C');
          $pdf->SetXY(($xc+36),$yc);

          $c++;
        }

        $xdef = $pdf->GetX(); //x inicial
        $ydef = $pdf->GetY(); //y inicial

        $pdf->Cell(10,8,"DEF",1,0,'C');
        $pdf->SetXY($xdef+10,$ydef);
        $pdf->Cell(5,4,'D',1,0,'C');
        $pdf->SetXY($xdef+10,$ydef+4);
        $pdf->Cell(5,4,'V',1,0,'C');
        $pdf->SetXY($xdef+15,$ydef);

        if(isset($notasDefEstudiante[$areas[$a]['ID']]['DESEMPENIO'])){
          $desempDef = $notasDefEstudiante[$areas[$a]['ID']]['DESEMPENIO'];
        }else{
          $desempDef = 'P';
        }

        $pdf->Cell(15,4,$desempDef,1,0,'C');
        $pdf->SetXY($xdef+15,$ydef+4);

        if(isset($notasDefEstudiante[$areas[$a]['ID']]['NOTA'])) {
          $notaDef = $notasDefEstudiante[$areas[$a]['ID']]['NOTA'];
        }else {
          $notaDef = 'P';
        }

        $pdf->Cell(15,4,$notaDef,1,0,'C');

        if(!empty($competenciasPendientes)) {
          $pdf->SetXY($x,$ydef+9);
          $pdf->Cell(20,4,"Pendientes:",0,0,'R');
          $ypend1 = $pdf->GetY();
          $pdf->SetXY($x+21,$ydef+9);
          $pdf->MultiCell(174,4,$competenciasPendientes,0,'L');
          $ypend2 = $pdf->GetY();
          $pdf->SetXY($x,$ydef+8+($ypend2-$ypend1));
          $ypendtotal = $pdf->GetY();
        }else {
          $pdf->SetXY($x,$y+7); //no tocar
        }

        if($c > 4 ){
          $pdf->SetXY($x,$y+12);
        }
        if($c > 7 ){
          $pdf->SetXY($x,$y+20);
        }
        
        if($c > 9 ){
          $pdf->SetXY($x,$y+32);
        }
        
        $yareafin = $pdf->GetY();
        $pdf->Ln();
        $a++;
      }
      
      if($pdf->GetY()>220) {
        $pdf->AddPage();
      }

      $xcomp = $pdf->GetX(); //x inicial
      $ycomp = $pdf->GetY(); //y inicial
      
      $pdf->SetFont('Arial','B',9);

      $pdf->Cell(35,4,"COMPORTAMIENTO",0,0,'L');
      $pdf->Ln();

      $pdf->SetXY($xcomp,$ycomp+4);

      $pdf->Cell(10,8,"DEF",1,0,'C');
      $pdf->SetXY($xcomp+10,$ycomp+4);
      $pdf->Cell(5,4,'D',1,0,'C');
      $pdf->SetXY($xcomp+10,$ycomp+8);
      $pdf->Cell(5,4,'V',1,0,'C');
      $pdf->SetXY($xcomp+15,$ycomp+4);

      $pdf->Cell(15,4," ",1,0,'C');
      $pdf->SetXY($xcomp+15,$ycomp+8);
      $pdf->Cell(15,4," ",1,0,'C');

      $pdf->SetXY($xcomp+36,$ycomp);
      $pdf->Cell(159,4,"OBSERVACION:",0,0,'L');
      $pdf->SetXY($xcomp+36,$ycomp+4);
      $pdf->Cell(159,24," ",0,0,'L');

      $pdf->SetXY($xcomp+30,$ycomp+50);
      $pdf->Cell(40,4,utf8_decode("____________________"),0,0,'C');
      $pdf->SetXY($xcomp+30,$ycomp+54);
      $pdf->Cell(40,3,"Docente",0,0,'C');

      $pdf->Image('http://www.academia.ceruralrestrepo.com/director.jpg',$xcomp+110,$ycomp+25,40,23);
      $pdf->SetXY($xcomp+110,$ycomp+50);
      $pdf->Cell(40,4,utf8_decode("JAVIER MUÑOZ MORALES"),0,0,'C');
      $pdf->SetXY($xcomp+110,$ycomp+54);
      $pdf->Cell(40,3,"Director Rural",0,0,'C');


      $e++;
    }

    $pdf->Output();
  }



  function procesarCierre($variable){

    //Traer listado de cursos sin cerrar
    $cadenaSql = $this->sql->cadenaSql("cursosCerrados",$this->activeYear);
    $cursosCerrados = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
    $cursosCerrados = $this->organizador->orderMultiKeyBy($cursosCerrados,"IDCURSO");


    if(isset($cursosCerrados[$variable["curso"]]) && !empty($cursosCerrados[$variable["curso"]])) {
      $cadenaSql = $this->sql->cadenaSql("borrarNotasCerradas",array($this->activeYear,$variable["curso"]));
      $cursosCerrados = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"");
      echo "<br/>El curso ya fue cerrado";
    }

    //listado de competencias por grado organizadas por area
    $cadenaSql    = $this->sql->cadenaSql("competenciasPorArea",$variable["grado"]);
    $competencias = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
    $competencias = $this->organizador->orderTwoKeyBy($competencias,"IDAREA","ID");

   /*  echo ":: <pre>";
      echo count($competencias);
      var_dump($competencias);
    echo "</pre>";*/

    //listado de notas de estudiantes por curso organizados por estudiante y competencia
    $cadenaSql    = $this->sql->cadenaSql("notasFinalesPorCurso",$variable["curso"]);
    $notasFinales = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
    $notasFinales = $this->organizador->orderTwoKeyBy($notasFinales,"ESTUDIANTE","COMPETENCIA");

    //recorro las areas con sus respecitivas competencias
    //e inserto la nota del area por cada estudiante

    foreach ($competencias  as $idarea => $competencia) {

      $numCompetenArea = count($competencias[$idarea]);

      foreach($notasFinales as $idestudiante => $idcompetencia) {

        echo "<br>ESTUDIANTE:".$idestudiante;
        $sumatoriaArea = 0;
        $numeroDeNotas = 0;

        foreach ($competencia  as $idcompetencia => $value) {
          if(!empty($notasFinales[$idestudiante][$idcompetencia]["DESEMPENIO"])) { 
            $nota = $notasFinales[$idestudiante][$idcompetencia]["NOTA_FINAL"];
            if (!empty($nota) && ($nota*1)>=3) {
              $sumatoriaArea =  $sumatoriaArea + ($nota*1);
              $numeroDeNotas++;
            }
          }
        }
        $variable['anio']          = $this->activeYear;
        $variable['id_estudiante'] = $idestudiante;
        $variable['id_area']       = $idarea;
        $variable['id_curso']      = $variable["curso"];

        if($numeroDeNotas == $numCompetenArea) {

          $promedio = round($sumatoriaArea/$numCompetenArea,2);
          $variable['nota'] = $promedio;

          $notaFinal           = round($promedio,2);
          $notaFinalPorcentaje = $notaFinal*100/5;
          $notaFinalPorcentaje = round($notaFinalPorcentaje,2);

          //no esta dinamico esta repetido en el control de notas

          if(($notaFinalPorcentaje)*1>=100) {
            $variable['desempenio'] = "Superior";
          }
          elseif(($notaFinalPorcentaje)*1>=80 && ($notaFinalPorcentaje)*1<100) {
            $variable['desempenio'] = "Alto";
          }
          elseif(($notaFinalPorcentaje)*1<80) {
            $variable['desempenio'] = "Basico";
          }
          $variable['estado']       = '1';

          $cadenaSql = $this->sql->cadenaSql("insertarNotaCerrada",$variable);
          $insert    = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"");

        }
      }
    }

   /* echo ":: <pre>";
      echo count($notasFinales);
      var_dump($notasFinales);
    echo "</pre>";*/
  }


	function __construct() {

		$this->miConfigurador = Configurador::singleton();
		$this->miSesion = Sesion::singleton();
		$this->idSesion = $this->miSesion->getValorSesion('idUsuario');
        $this->miInspectorHTML = InspectorHTML::singleton();
		$this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");
		$this->miMensaje = Mensaje::singleton();
		$this->enlace = $this->miConfigurador->getVariableConfiguracion("host").$this->miConfigurador->getVariableConfiguracion("site")."?".$this->miConfigurador->getVariableConfiguracion("enlace");
		$conexion = "aplicativo";
		$this->miRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		$this->organizador = orderArray::singleton();

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
