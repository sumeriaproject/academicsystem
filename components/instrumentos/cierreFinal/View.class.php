<?php
if(!isset($GLOBALS["autorizado"])){
	include("../index.php");
	exit;
}

include_once("core/manager/Configurador.class.php");
include_once("core/auth/Sesion.class.php");
include_once("class/controlAcceso.class.php");
include_once("class/Array.class.php");

class ViewcierreFinal{

	var $ruta;
	var $sql;
	var $funcion;
	var $lenguaje;
	var $formulario;
	var $enlace;
	var $miConfigurador;

	function __construct() {
		$this->miConfigurador = Configurador::singleton();
		$this->miSesion = Sesion::singleton();
		$this->miRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB("aplicativo");
		$this->enlace = $this->miConfigurador->getVariableConfiguracion("host").$this->miConfigurador->getVariableConfiguracion("site")."?".$this->miConfigurador->getVariableConfiguracion("enlace");
		$this->idSesion = $this->miSesion->getValorSesion('idUsuario');
		$this->controlAcceso = new controlAcceso();
		$this->controlAcceso->usuario = $this->idSesion;
		$this->controlAcceso->rol = $this->miSesion->getValorSesion('rol');
		$this->organizador = orderArray::singleton();
	}

	public function setRuta($unaRuta){
		$this->ruta=$unaRuta;
	}

	public function setLenguaje($lenguaje){
		$this->lenguaje=$lenguaje;
	}

	public function setFormulario($formulario){
		$this->formulario=$formulario;
	}

	function setSql($a){
		$this->sql=$a;
	}

	function setFuncion($funcion){
		$this->funcion=$funcion;
	}


	function html() {

		$this->ruta=$this->miConfigurador->getVariableConfiguracion("rutaBloque");
		$option=isset($_REQUEST['option'])?$_REQUEST['option']:"panel";

		switch($option){
			case "panel":
				$this->showMainReport();
				//$this->conflictStudents();
				break;
		}

	}

  /**
  * Estudiantes con notas de competencias diferentes al grado actual
  */
  function conflictStudents(){

      //traer listado de todos los estudiantes con el respectivo grado
      $cadenaSql = $this->sql->cadenaSql("estudiantes","");
      $estudiantes = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
      $e=0;
      while(isset($estudiantes[$e][0])){

        $cadenaSql = $this->sql->cadenaSql("notasFinalesFueradelGrado",$estudiantes[$e]);
        $notasFinales = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");

        if(is_array($notasFinales)){

          $n=0;
          while(isset($notasFinales[$n][0])) {
            $cadenaSql = $this->sql->cadenaSql("inactivarNotaFinal",$notasFinales[$n]['IDNOTA']);
            $result = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"");
            $n++;
          }
          echo "<br>".$e.":".$estudiantes[$e]['ID']." idgrado:".$estudiantes[$e]['IDGRADO'];
          echo " conflictos:".count($notasFinales);

        }
        $e++;
      }
      //var_dump($estudiantes);


  }

	function showMainReport(){

		//Consulto el listado de sedes y grados permitidos para el usuario
			$courses = $this->controlAcceso->getAccesoCompleto();
			$sedes = $this->controlAcceso->getSedes();

		//Consulto el listado de estudiantes organizados por curso
			$cadenaSql = $this->sql->cadenaSql("estudiantes","");
      $estudiantes = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
      $estudiantes = $this->organizador->orderMultiKeyBy($estudiantes,"IDCURSO");

    //Consulto el listado de competencias organizados por grado
			$cadenaSql = $this->sql->cadenaSql("competencias","");
      $competencias = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
      $competencias = $this->organizador->orderMultiKeyBy($competencias,"IDGRADO");

    //Traer listado de cursos sin cerrar
      $cadenaSql = $this->sql->cadenaSql("cursosCerrados",$this->activeYear);
      $cursosCerrados = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
      $cursosCerrados = $this->organizador->orderMultiKeyBy($cursosCerrados,"IDCURSO");

    //Se asume que un curso esta asociado a un unico grado y una unica sede
    // Listado de notas finales por curso y estudiante

      foreach($courses as $idsede => $course ) {

        foreach($course as $idcourse => $value ) {

          $courses[$idsede][$idcourse]["ESTUDIANTES_ALDIA"]      = array();
          $courses[$idsede][$idcourse]["ESTUDIANTES_PENDIENTES"] = array();

          $numeroDeCompetencias = count($competencias[$value["GRADO_ID"]]);

          $estudiantesPendientes = 0;
          $estudiantesAlDia      = 0;

        	$cadenaSql = $this->sql->cadenaSql("notasFinalesPorCurso",$idcourse);
          $notas = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");

          if(is_array($notas)) {

           $notas = $this->organizador->orderMultiTwoKeyBy($notas,"IDCURSO","ESTUDIANTE");

            foreach($notas[$idcourse] as $idestudiante => $notasestudiante) {

              $numeroDeNotas = count($notasestudiante);

              if($numeroDeNotas < $numeroDeCompetencias) {
                $courses[$idsede][$idcourse]["ESTUDIANTES_PENDIENTES"][] = $idestudiante;
              }else {
                $courses[$idsede][$idcourse]["ESTUDIANTES_ALDIA"][]      = $idestudiante;
              }
            }

          }

          $formSaraData  = "action=cierreFinal";
          $formSaraData .= "&bloque=cierreFinal";
          $formSaraData .= "&bloqueGrupo=instrumentos";
          $formSaraData .= "&option=imprimirBoletines";
          $formSaraData .= "&curso=".$idcourse;
          $formSaraData .= "&sede=".$idsede;
          $formSaraData .= "&grado=".$courses[$idsede][$idcourse]["GRADO_ID"];
          $courses[$idsede][$idcourse]["LINK_BOLETIN"] = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($formSaraData,$this->enlace);

          $formSaraData  = "action=controlEvaluacion";
          $formSaraData .= "&bloque=controlEvaluacion";
          $formSaraData .= "&bloqueGrupo=instrumentos";
          $formSaraData .= "&option=showPDFCompetencias";
          $formSaraData .= "&curso=".$idcourse;
          $formSaraData .= "&sede=".$idsede;
          $formSaraData .= "&grado=".$courses[$idsede][$idcourse]["GRADO_ID"];
          $courses[$idsede][$idcourse]["LINK_NOTAS"] = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($formSaraData,$this->enlace);

          if(!isset($cursosCerrados[$idcourse])) {
            $formSaraData  = "action=cierreFinal";
            $formSaraData .= "&bloque=cierreFinal";
            $formSaraData .= "&bloqueGrupo=instrumentos";
            $formSaraData .= "&option=cierreNotas";
            $formSaraData .= "&curso=".$idcourse;
            $formSaraData .= "&sede=".$idsede;
            $formSaraData .= "&grado=".$courses[$idsede][$idcourse]["GRADO_ID"];
            $courses[$idsede][$idcourse]["LINK_CIERRE"] = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($formSaraData,$this->enlace);
            $courses[$idsede][$idcourse]["REPEAT_CIERRE"] = FALSE;
          }else {
            $formSaraData  = "action=cierreFinal";
            $formSaraData .= "&bloque=cierreFinal";
            $formSaraData .= "&bloqueGrupo=instrumentos";
            $formSaraData .= "&option=cierreNotas";
            $formSaraData .= "&curso=".$idcourse;
            $formSaraData .= "&sede=".$idsede;
            $formSaraData .= "&grado=".$courses[$idsede][$idcourse]["GRADO_ID"];
            $courses[$idsede][$idcourse]["LINK_CIERRE"] = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($formSaraData,$this->enlace);
            $courses[$idsede][$idcourse]["REPEAT_CIERRE"] = TRUE;
          }
        }
      }


			include_once($this->ruta."/html/mainReport.php");
	}



}
?>