<?php
if(!isset($GLOBALS["autorizado"])){
	include("../index.php");
	exit;
}

include_once("core/manager/Context.class.php");
include_once("core/auth/Sesion.class.php");
include_once("class/controlAcceso.class.php");
include_once("class/Calendario.class.php");
include_once("class/Array.class.php");

class ViewcontrolEvaluacion{

	var $ruta;
	var $sql;
	var $funcion;
	var $lenguaje;
	var $formulario;
	var $enlace;
	var $context;

	function __construct(){
		$this->context  = Context::singleton();
		$this->session        = Sesion::singleton();
		$this->resource     = $this->context->fabricaConexiones->getRecursoDB("aplicativo");
		$this->enlace          = $this->context->getVariable("host").$this->context->getVariable("site")."?".$this->context->getVariable("enlace");
		$this->sessionId        = $this->session->getValue('idUsuario');
		$this->controlAcceso   = new controlAcceso();
		$this->calendario      = new Calendario();
		$this->sorter     = orderArray::singleton();
		$this->controlAcceso->usuario = $this->sessionId;
		$this->controlAcceso->rol     = $this->session->getValue('rol');
	}

	public function setRuta($unaRuta)
	{
		$this->ruta = $unaRuta;
	}

	public function setLenguaje($lenguaje)
	{
		$this->lenguaje=$lenguaje;
	}

	public function setFormulario($formulario)
	{
		$this->formulario=$formulario;
	}

	function setSql($a)
	{
		$this->sql=$a;
	}

	function setFuncion($funcion)
	{
		$this->funcion=$funcion;
	}


	function html()
	{
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
			case "verNotas":
				$this->showNotas();
				break;
		}
	}

	function showNotas()
	{

		//2. Consulto la competencia
		$cadenaSql   = $this->sql->get("competenciaByID",$_REQUEST['competencia']);
		$competencia = $this->resource->execute($cadenaSql,"busqueda");
		$competencia = $competencia[0];

		//1.Consultar nombres Sede, Curso, Area
		$cadenaSql = $this->sql->get("sedeByID",$_REQUEST['sede']);
		$sedeByID  = $this->resource->execute($cadenaSql,"busqueda");
		$sedeByID  = $sedeByID[0];

		$cadenaSql = $this->sql->get("cursoByID",$_REQUEST['curso']);
		$cursoByID = $this->resource->execute($cadenaSql,"busqueda");
		$cursoByID = $cursoByID[0];

		$cadenaSql = $this->sql->get("areaByID",$competencia['ID_AREA']);
		$areaByID  = $this->resource->execute($cadenaSql,"busqueda");
		$areaByID  = $areaByID[0];

		//3. Consulto los estudiantes que pertenecen al curso
		$cadenaSql = $this->sql->get("estudiantesPorCurso",$_REQUEST['curso']);
		$estudiantesPorCurso = $this->resource->execute($cadenaSql,"busqueda");

	    $formSaraDataXML  = "action=controlEvaluacion";
	    $formSaraDataXML .= "&bloque=controlEvaluacion";
	    $formSaraDataXML .= "&bloqueGrupo=instrumentos";
	    $formSaraDataXML .= "&option=showNotasXML";
	    $formSaraDataXML .= "&print=true";
	    $formSaraDataXML .= "&curso=".$_REQUEST['curso'];
	    $formSaraDataXML .= "&grado=".$_REQUEST['grado'];
	    $formSaraDataXML .= "&competencia=".$_REQUEST['competencia'];
	    $formSaraDataXML .= "&sede=".$_REQUEST['sede'];
	    $formSaraDataXML =  $this->context->fabricaConexiones->crypto->codificar_url($formSaraDataXML,$this->enlace);

	    $formSaraDataUrl  = "pagina=controlEvaluacion";
		$formSaraDataUrl .= "&grado=".$_REQUEST['grado'];
		$formSaraDataUrl .= "&sede=".$_REQUEST['sede'];
		$formSaraDataUrl  = $this->context->fabricaConexiones->crypto->codificar_url($formSaraDataUrl,$this->enlace);

		switch($areaByID['TIPO']) 
		{
			case 'CRITERIOS':
				//4. Consulto los criterios de evaluacion para el area a la que pertenece la competencia
				$cadenaSql = $this->sql->get("criteriosPorCompetencia",$_REQUEST['competencia']);
				$criteriosPorCompetencia = $this->resource->execute($cadenaSql,"busqueda");

				//4.1 Agrupo los criterios por grupo
				$criteriosPorCompetencia = $this->sorter->orderMultiKeyBy($criteriosPorCompetencia,"GRUPO");

				//5. Consulto las notas parciales de los estudiantes correspondientes a los criterios
				$cadenaSql  = $this->sql->get("notasPorCompetencia",$_REQUEST);
				$notasPorCompetencia = $this->resource->execute($cadenaSql,"busqueda");
				$notasPorCompetencia = $this->sorter->orderTwoKeyBy($notasPorCompetencia,"ESTUDIANTE","CRITERIO");

				//6. Consulto las notas finales de los estudiantes correspondientes a los criterios
				$cadenaSql = $this->sql->get("notasFinalesCriteriosPorCompetencia",$_REQUEST);
				$notasFinalesPorCompetencia = $this->resource->execute($cadenaSql,"busqueda");
				$notasFinalesPorCompetencia = $this->sorter->orderKeyBy($notasFinalesPorCompetencia,"ESTUDIANTE");

				$formSaraDataNota  = "jxajax=controlEvaluacion";
				$formSaraDataNota .= "&bloque=controlEvaluacion";
				$formSaraDataNota .= "&bloqueGrupo=instrumentos";
				$formSaraDataNota .= "&action=controlEvaluacion";
				$formSaraDataNota .= "&option=actualizarNotaCriterio";
				$formSaraDataNota .= "&competencia=".$_REQUEST['competencia'];
				$formSaraDataNota .= "&identificador=".$competencia['IDENTIFICADOR'];
				$formSaraDataNota .= "&area=".$competencia['ID_AREA'];
				$formSaraDataNota .= "&grado=".$competencia['GRADO'];

				$formSaraDataNota=$this->context->fabricaConexiones->crypto->codificar_url($formSaraDataNota,$this->enlace);

   				include_once($this->ruta."/html/notasPorCompetenciaCriterios.php");
			break;
			case 'CUALITATIVA':

				$cadenaSql = $this->sql->get("cualitativasPorCompetencia",$_REQUEST['competencia']);
				$cualitativasPorCompetencia = $this->resource->execute($cadenaSql,"busqueda");
				$cualitativasPorCompetencia = $this->sorter->orderKeyBy($cualitativasPorCompetencia,"ID");

				//Consulto las notas finales de los estudiantes correspondientes a las cualitativas
				$cadenaSql  = $this->sql->get("notasFinalesCualitativasPorCompetencia",$_REQUEST);
				$notasPorCompetencia = $this->resource->execute($cadenaSql,"busqueda");
				$notasPorCompetencia = $this->sorter->orderKeyBy($notasPorCompetencia,"ESTUDIANTE");

				$formSaraDataNota  = "jxajax=controlEvaluacion";
				$formSaraDataNota .= "&bloque=controlEvaluacion";
				$formSaraDataNota .= "&bloqueGrupo=instrumentos";
				$formSaraDataNota .= "&action=controlEvaluacion";
				$formSaraDataNota .= "&option=actualizarNotaCualitativa";
				$formSaraDataNota .= "&competencia=".$_REQUEST['competencia'];
				$formSaraDataNota .= "&identificador=".$competencia['IDENTIFICADOR'];
				$formSaraDataNota .= "&area=".$competencia['ID_AREA'];
				$formSaraDataNota .= "&grado=".$competencia['GRADO'];

				$formSaraDataNota = $this->context->fabricaConexiones->crypto->codificar_url($formSaraDataNota,$this->enlace);

				include_once($this->ruta."/html/notasPorCompetenciaCualitativa.php");
			break;
		}
	}



	function showList(){

		//1.Consulto el listado de sedes y grados permitidos para el usuario
		$grados = $this->controlAcceso->getGrados();
		$sedes  = $this->controlAcceso->getSedes();

		//2. Valores actuales

		$keysGrados = array_keys($grados);
		$keysSedes  = array_keys($sedes);

		$id_grado   = isset($_REQUEST['grado'])?$_REQUEST['grado']:array_shift($keysGrados);
		$id_sede    = isset($_REQUEST['sede'])?$_REQUEST['sede']:array_shift($keysSedes);


		//3.Consulto el curso asociado a la sede y grado, inicialmente solo existe un curso para cada grado y sede
		//  por consiguiente la consulta solo debe arrojar un registro.

		$cadenaSql = $this->sql->get("cursos",array("GRADO"=>$id_grado,"SEDE"=>$id_sede));
		$cursos    =$this->resource->execute($cadenaSql,"busqueda");
		$id_curso  = $cursos[0]['ID'];


		//Validación calendario
		/* $abierto = $this->calendario->getEvento($id_curso,"notas");
		if(!$abierto){
		$mensajeCierre = $this->calendario->getMensaje("notas");
		}*/

		//4.Consulto el listado de areas para el grado actual
		$cadenaSql = $this->sql->get("areas",$id_grado);
		$areas     = $this->resource->execute($cadenaSql,"busqueda");

		//5.Rescato el listado de competencias para el grado actual
		$cadenaSql    = $this->sql->get("competencias",$id_grado);
		$competencias = $this->resource->execute($cadenaSql,"busqueda");
		$competenciasPorArea = $this->orderArrayMultiKeyBy($competencias,"ID_AREA");

		$formSaraDataActionList="bloque=controlEvaluacion";
		$formSaraDataActionList.="&bloqueGrupo=instrumentos";
		$formSaraDataActionList.="&action=controlEvaluacion";
		$formSaraDataActionList.="&option=processList";
		$formSaraDataActionList=$this->context->fabricaConexiones->crypto->codificar($formSaraDataActionList);

		$formSaraDataCurso="pagina=controlEvaluacion";
		$formSaraDataCurso.="&option=verNotas";
		$formSaraDataCurso.="&curso=".$id_curso;
		$formSaraDataCurso.="&sede=".$id_sede;
		$formSaraDataCurso.="&grado=".$id_grado;
		$formSaraDataCurso=$this->context->fabricaConexiones->crypto->codificar_url($formSaraDataCurso,$this->enlace);

		$formSaraDataPrint="action=controlEvaluacion";
		$formSaraDataPrint.="&bloque=controlEvaluacion";
		$formSaraDataPrint.="&bloqueGrupo=instrumentos";
		$formSaraDataPrint.="&option=showPDFCompetencias";
		$formSaraDataPrint.="&curso=".$id_curso;
		$formSaraDataPrint.="&sede=".$id_sede;
		$formSaraDataPrint.="&grado=".$id_grado;
		$formSaraDataPrint=$this->context->fabricaConexiones->crypto->codificar_url($formSaraDataPrint,$this->enlace);

		include_once($this->ruta."/html/competenciasPorCurso.php");
	}

	function showNew(){

		$cadenaSql=$this->sql->get("grados");
		$grados=$this->resource->execute($cadenaSql,"busqueda");

		$id_grado="2"; //corregir esto para q las areas correspondan al grado
		$cadenaSql=$this->sql->get("areas",$id_grado);
		$areas=$this->resource->execute($cadenaSql,"busqueda");

		$formSaraDataAction="bloque=controlEvaluacion";
		$formSaraDataAction.="&bloqueGrupo=instrumentos";
		$formSaraDataAction.="&action=controlEvaluacion";
		$formSaraDataAction.="&option=processNew";
		$formSaraDataAction=$this->context->fabricaConexiones->crypto->codificar($formSaraDataAction);

		include_once($this->ruta."/html/new.php");
	}

	function orderArrayKeyBy($array,$key){
		$newArray=array();
		foreach($array as $name=>$value){
			$newArray[$value[$key]]=$array[$name];
		}
		return $newArray;
	}

	function orderArrayMultiKeyBy($array,$key){
		$newArray=array();
		foreach($array as $name=>$value){
			$newArray[$value[$key]][]=$array[$name];
		}
		return $newArray;
	}
}