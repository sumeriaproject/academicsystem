<?php
if(!isset($GLOBALS["autorizado"])){
	include("../index.php");
	exit;
}

include_once("core/manager/Context.class.php");
include_once("core/auth/Sesion.class.php");
include_once("class/controlAcceso.class.php");
include_once("class/Array.class.php");

class Viewcomportamiento{

	var $ruta;
	var $sql;
	var $funcion;
	var $lenguaje;
	var $formulario;
	var $enlace;
	var $context;

	function __construct(){
		$this->context=Context::singleton();
		$this->miSesion=Sesion::singleton();
		$this->resource = $this->context->fabricaConexiones->getRecursoDB("aplicativo");
		$this->enlace = $this->context->getVariable("host").$this->context->getVariable("site")."?".$this->context->getVariable("enlace");
		$this->idSesion = $this->miSesion->getValorSesion('idUsuario');
		$this->controlAcceso=new controlAcceso();
		$this->controlAcceso->usuario = $this->idSesion;
		$this->controlAcceso->rol = $this->miSesion->getValorSesion('rol');
		$this->organizador=orderArray::singleton();
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
		$option=isset($_REQUEST['option'])?$_REQUEST['option']:"list";

		switch($option){
			case "list":
				$this->showList();
				break;
		}
	}



	function showList(){

		//1.Consulto el listado de sedes y grados permitidos para el usuario
		$grados = $this->controlAcceso->getGrados();
		$sedes = $this->controlAcceso->getSedes();

		//2. Valores actuales

		$keysGrados = array_keys($grados);
		$keysSedes  = array_keys($sedes);

		$id_grado   = isset($_REQUEST['grado'])?$_REQUEST['grado']:array_shift($keysGrados);
		$id_sede    = isset($_REQUEST['sede'])?$_REQUEST['sede']:array_shift($keysSedes);
		$id_periodo = isset($_REQUEST['periodo'])?$_REQUEST['periodo']:1;


		//3.Consulto el curso asociado a la sede y grado, inicialmente solo existe un curso para cada grado y sede
		//  por consiguiente la consulta solo debe arrojar un registro.

		$cadenaSql = $this->sql->cadenaSql("cursos",array("GRADO"=>$id_grado,"SEDE"=>$id_sede));
		$cursos    = $this->resource->execute($cadenaSql,"busqueda");
		$id_curso  = $cursos[0]['ID'];

		//4.Consulto el listado de estudiantes para el curso actual
		$cadenaSql = $this->sql->cadenaSql("estudiantesPorCurso",$id_curso);
     	$estudiantesPorCurso = $this->resource->execute($cadenaSql,"busqueda");

		//4.1.Consulto el listado de estudiantes para el curso actual
   		$cadenaSql        = $this->sql->cadenaSql("notasEstudiantes",$id_curso);
    	$notasEstudiantes = $this->resource->execute($cadenaSql,"busqueda");
    	$compEstudiante   = array();

    	if(is_array($notasEstudiantes)) {
	    	foreach($notasEstudiantes as $key=>$value){
	    		$compEstudiante[$value["ID"]] = $value;
	    	}
    	}

    	//5.Consultar nombres Sede, Curso, Area
		$cadenaSql = $this->sql->cadenaSql("sedeByID",$id_sede);
		$sedeByID  = $this->resource->execute($cadenaSql,"busqueda");
		$sedeByID  = $sedeByID[0];

		$cadenaSql = $this->sql->cadenaSql("cursoByID",$id_curso);
		$cursoByID = $this->resource->execute($cadenaSql,"busqueda");
		$cursoByID = $cursoByID[0];


 		$formSaraDataActionList="bloque=comportamiento";
		$formSaraDataActionList.="&bloqueGrupo=instrumentos";
		$formSaraDataActionList.="&action=comportamiento";
		$formSaraDataActionList.="&option=processList";
		$formSaraDataActionList = $this->context->fabricaConexiones->crypto->codificar($formSaraDataActionList);


		$formSaraDataNota="jxajax=comportamiento";
		$formSaraDataNota.="&bloque=comportamiento";
		$formSaraDataNota.="&bloqueGrupo=instrumentos";
		$formSaraDataNota.="&action=comportamiento";
		$formSaraDataNota.="&option=actualizarNota";
		$formSaraDataNota.="&periodo=".$id_periodo;
		$formSaraDataNota.="&grado=".$id_grado;
		$formSaraDataNota.="&curso=".$id_curso;

		$formSaraDataNota = $this->context->fabricaConexiones->crypto->codificar_url($formSaraDataNota,$this->enlace);


		include_once($this->ruta."/html/estudiantesPorCurso.php");
	}


}

