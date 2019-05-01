<?php
if(!isset($GLOBALS["autorizado"])){
	include("../index.php");
	exit;
}

include_once("core/manager/Context.class.php");
include_once("core/auth/Sesion.class.php");
include_once("class/controlAcceso.class.php");
include_once("class/Array.class.php");

class Viewmatricula{

	var $ruta;
	var $sql;
	var $funcion;
	var $lenguaje;
	var $formulario;
	var $enlace;
	var $context;

	function __construct(){
		$this->context=Context::singleton();
		$this->session=Sesion::singleton();
		$this->resource = $this->context->fabricaConexiones->getRecursoDB("aplicativo");
		$this->enlace = $this->context->getVariable("host").$this->context->getVariable("site")."?".$this->context->getVariable("enlace");
		$this->sessionId = $this->session->getValue('idUsuario');
		$this->controlAcceso=new controlAcceso();
		$this->controlAcceso->usuario = $this->sessionId;
		$this->controlAcceso->rol = $this->session->getValue('rol');
		$this->sorter=orderArray::singleton();
	}

	public function setRuta($unaRuta){
		$this->ruta = $unaRuta;
	}

	public function setLenguaje($lenguaje){
		$this->lenguaje = $lenguaje;
	}

	public function setFormulario($formulario){
		$this->formulario = $formulario;
	}

	function setSql($a){
		$this->sql = $a;
	}

	function setFuncion($funcion){
		$this->funcion = $funcion;
	}


	function html(){
		$this->ruta = $this->context->getVariable("rutaBloque");
		$option=isset($_REQUEST['option'])?$_REQUEST['option']:"new";

		switch($option){
			case "new":
				$this->showNew();
				break;
		}
	}

  function showNew() {
    include_once($this->ruta."/html/new.php");
  }

	function showList(){

		//1.Consulto el listado de sedes y grados permitidos para el usuario
			$grados = $this->controlAcceso->getGrados();
			$sedes = $this->controlAcceso->getSedes();

		//2. Valores actuales

			$id_grado=isset($_REQUEST['grado'])?$_REQUEST['grado']:array_shift(array_keys($grados));
			$id_sede=isset($_REQUEST['sede'])?$_REQUEST['sede']:array_shift(array_keys($sedes));
			$id_periodo=isset($_REQUEST['periodo'])?$_REQUEST['periodo']:1;


		//3.Consulto el curso asociado a la sede y grado, inicialmente solo existe un curso para cada grado y sede
		//  por consiguiente la consulta solo debe arrojar un registro.

			$cadenaSql = $this->sql->get("cursos",array("GRADO"=>$id_grado,"SEDE"=>$id_sede));
			$cursos = $this->resource->execute($cadenaSql,"busqueda");
			$id_curso = $cursos[0]['ID'];

		//4.Consulto el listado de estudiantes para el curso actual
			$cadenaSql = $this->sql->get("estudiantesPorCurso",$id_curso);
      $estudiantesPorCurso = $this->resource->execute($cadenaSql,"busqueda");

		//4.1.Consulto el listado de estudiantes para el curso actual
   		$cadenaSql   = $this->sql->get("notasEstudiantes",$id_curso);
    	$notasEstudiantes = $this->resource->execute($cadenaSql,"busqueda");
    	$compEstudiante = array();
    	foreach($notasEstudiantes as $key=>$value){
    		$compEstudiante[$value["ID"]] = $value;
    	}//echo "<pre>"; var_dump($compEstudiante); echo "</pre>";

    //5.Consultar nombres Sede, Curso, Area
      $cadenaSql = $this->sql->get("sedeByID",$id_sede);
      $sedeByID = $this->resource->execute($cadenaSql,"busqueda");
      $sedeByID = $sedeByID[0];

      $cadenaSql = $this->sql->get("cursoByID",$id_curso);
      $cursoByID = $this->resource->execute($cadenaSql,"busqueda");
      $cursoByID = $cursoByID[0];


      $formSaraDataActionList="bloque=matricula";
			$formSaraDataActionList.="&bloqueGrupo=instrumentos";
			$formSaraDataActionList.="&action=matricula";
			$formSaraDataActionList.="&option=processList";
			$formSaraDataActionList = $this->context->fabricaConexiones->crypto->codificar($formSaraDataActionList);


			$formSaraDataNota="jxajax=matricula";
			$formSaraDataNota.="&bloque=matricula";
			$formSaraDataNota.="&bloqueGrupo=instrumentos";
			$formSaraDataNota.="&action=matricula";
			$formSaraDataNota.="&option=actualizarNota";
			$formSaraDataNota.="&periodo=".$id_periodo;
			$formSaraDataNota.="&grado=".$id_grado;
			$formSaraDataNota.="&curso=".$id_curso;

			$formSaraDataNota = $this->context->fabricaConexiones->crypto->codificar_url($formSaraDataNota,$this->enlace);


			include_once($this->ruta."/html/estudiantesPorCurso.php");
	}


}

