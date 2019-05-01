<?php
if(!isset($GLOBALS["autorizado"])){
	include("../index.php");
	exit;
}

include_once("core/manager/Context.class.php");
include_once("core/auth/Sesion.class.php");
include_once("class/controlAcceso.class.php");
include_once("class/Array.class.php");



class ViewboletinFinal{

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
		$this->resource=$this->context->fabricaConexiones->getRecursoDB("aplicativo");
		$this->enlace=$this->context->getVariable("host").$this->context->getVariable("site")."?".$this->context->getVariable("enlace");
		$this->sessionId=$this->session->getValue('idUsuario');
		$this->controlAcceso=new controlAcceso();
		$this->controlAcceso->usuario=$this->sessionId;
		$this->controlAcceso->rol=$this->session->getValue('rol');
		$this->sorter = orderArray::singleton();
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
		$option = isset($_REQUEST['option'])?$_REQUEST['option']:"list";

		switch($option){
			case "list":
				$this->showList();
				break;
			case "new":
				$this->showNew();
				break;
			case "edit":
				$this->showEdit($_REQUEST['optionValue']);
				break;
			case "view":
				$this->showView($_REQUEST['optionValue']);
				break;
			case "verBoletin":
				$this->showBoletin($_REQUEST);
				break;
		}
	}

	function showBoletin($variable){

	   	//1.Rescato el listado de competencias para el grado actual
	    $cadenaSql = $this->sql->get("competencias",$variable['grado']);
	    $competencias = $this->resource->execute($cadenaSql,"busqueda");
	    $competenciasPorArea = $this->sorter->orderMultiKeyBy($competencias,"ID_AREA");


	    //Consulto el listado de areas para el grado actual
	    $cadenaSql    = $this->sql->get("areas",$variable['grado']);
	    $areas        = $this->resource->execute($cadenaSql,"busqueda");
	    $areasPorTipo = $this->sorter->orderMultiKeyBy($areas,"TIPO");


		//Consultar nombres Sede, Curso, Area
		$cadenaSql = $this->sql->get("sedeByID",$variable['sede']);
		$sedeByID = $this->resource->execute($cadenaSql,"busqueda");
		$sedeByID = $sedeByID[0];

		$cadenaSql = $this->sql->get("cursoByID",$variable['curso']);
		$cursoByID = $this->resource->execute($cadenaSql,"busqueda");
		$cursoByID = $cursoByID[0];

		$cadenaSql=$this->sql->get("estudianteByID",$variable['estudiante']);
		$estudianteByID=$this->resource->execute($cadenaSql,"busqueda");


		//Consultar las notas finales de los estudiante
		$cadenaSql = $this->sql->get("notasFinalesPorEstudianteCriterios",$variable);
		$notasFinalesPorEstudianteCriterios = $this->resource->execute($cadenaSql,"busqueda");
		$notasFinalesPorEstudianteCriterios = $this->sorter->orderKeyBy($notasFinalesPorEstudianteCriterios,"COMPETENCIA");

		$cadenaSql = $this->sql->get("notasFinalesPorEstudianteCualitativa",$variable);
		$notasFinalesPorEstudianteCualitativa = $this->resource->execute($cadenaSql,"busqueda");
		$notasFinalesPorEstudianteCualitativa = $this->sorter->orderKeyBy($notasFinalesPorEstudianteCualitativa,"COMPETENCIA");
   
   		//Organizar por competencias
		$notaEstudiante = $notasFinalesPorEstudianteCriterios + $notasFinalesPorEstudianteCualitativa;


		$formSaraDataBoletin="jxajax=boletinFinal";
		$formSaraDataBoletin.="&bloque=boletinFinal";
		$formSaraDataBoletin.="&bloqueGrupo=instrumentos";
		$formSaraDataBoletin.="&action=boletinFinal";
		$formSaraDataBoletin.="&option=actualizarNota";
		//$formSaraDataBoletin.="&competencia=".$_REQUEST['competencia'];
		$formSaraDataBoletin = $this->context->fabricaConexiones->crypto->codificar_url($formSaraDataBoletin,$this->enlace);

    	include_once($this->ruta."/html/competenciasPorCurso.php");
	}


	function showList()
	{

		//1.Consulto el listado de sedes y grados permitidos para el usuario
		$grados = $this->controlAcceso->getGrados();
		$sedes  = $this->controlAcceso->getSedes();

		//2. Valores actuales
		$keysGrados = array_keys($grados);
		$keysSedes  = array_keys($sedes);

		$id_grado = isset($_REQUEST['grado'])?$_REQUEST['grado']:array_shift($keysGrados);
		$id_sede  = isset($_REQUEST['sede'])?$_REQUEST['sede']:array_shift($keysSedes);


		//3.Consulto el curso asociado a la sede y grado, inicialmente solo existe un curso para cada grado y sede
		//  por consiguiente la consulta solo debe arrojar un registro.

		$cadenaSql = $this->sql->get("cursos",array("GRADO"=>$id_grado,"SEDE"=>$id_sede));
		$cursos    = $this->resource->execute($cadenaSql,"busqueda");
		$id_curso  = $cursos[0]['ID'];

		//4.Consulto el listado de estudiantes para el curso actual
		$cadenaSql = $this->sql->get("estudiantesPorCurso",$id_curso);
		$estudiantesPorCurso=$this->resource->execute($cadenaSql,"busqueda");

		//5.Consultar nombres Sede, Curso, Area
		$cadenaSql = $this->sql->get("sedeByID",$id_sede);
		$sedeByID  = $this->resource->execute($cadenaSql,"busqueda");
		$sedeByID  = $sedeByID[0];

		$cadenaSql = $this->sql->get("cursoByID",$id_curso);
		$cursoByID = $this->resource->execute($cadenaSql,"busqueda");
		$cursoByID = $cursoByID[0];

  		$formSaraDataActionList="bloque=boletinFinal";
		$formSaraDataActionList.="&bloqueGrupo=instrumentos";
		$formSaraDataActionList.="&action=boletinFinal";
		$formSaraDataActionList.="&option=processList";
		$formSaraDataActionList=$this->context->fabricaConexiones->crypto->codificar($formSaraDataActionList);

		$formSaraDataBoletin="pagina=boletinFinal";
		$formSaraDataBoletin.="&option=verBoletin";
		$formSaraDataBoletin.="&kipu-development=1";
		$formSaraDataBoletin.="&curso=".$id_curso;
		$formSaraDataBoletin.="&sede=".$id_sede;
		$formSaraDataBoletin.="&grado=".$id_grado;
		$formSaraDataBoletin=$this->context->fabricaConexiones->crypto->codificar_url($formSaraDataBoletin,$this->enlace);

 		$formSaraDataPrint="bloque=boletinFinal";
		$formSaraDataPrint.="&bloqueGrupo=instrumentos";
		$formSaraDataPrint.="&action=boletinFinal";
		$formSaraDataPrint.="&option=imprimirBoletin";
		$formSaraDataPrint.="&curso=".$id_curso;
		$formSaraDataPrint.="&sede=".$id_sede;
		$formSaraDataPrint.="&grado=".$id_grado;
		$formSaraDataPrint=$this->context->fabricaConexiones->crypto->codificar_url($formSaraDataPrint,$this->enlace);
  
  		$formSaraDataPrintLote="bloque=boletinFinal";
		$formSaraDataPrintLote.="&bloqueGrupo=instrumentos";
		$formSaraDataPrintLote.="&action=boletinFinal";
		$formSaraDataPrintLote.="&option=imprimirBoletinLote";
		$formSaraDataPrintLote.="&curso=".$id_curso;
		$formSaraDataPrintLote.="&sede=".$id_sede;
		$formSaraDataPrintLote.="&grado=".$id_grado;
		$formSaraDataPrintLote=$this->context->fabricaConexiones->crypto->codificar_url($formSaraDataPrintLote,$this->enlace);

		include_once($this->ruta."/html/estudiantesPorCurso.php");
	}

}

