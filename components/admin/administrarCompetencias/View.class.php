<?php
include_once("core/manager/Context.class.php");
include_once("core/auth/Sesion.class.php");

class ViewadministrarCompetencias{

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
		$this->resource=$this->context->fabricaConexiones->getRecursoDB("aplicativo");
		$this->enlace=$this->context->getVariable("host").$this->context->getVariable("site")."?".$this->context->getVariable("enlace");
		$this->idSesion=$this->miSesion->getValorSesion('idUsuario');
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

	function setSql($a)
	{
		$this->sql=$a;

	}

	function setFuncion($funcion){
		$this->funcion=$funcion;

	}


	function html(){
		
		$this->ruta=$this->context->getVariable("rutaBloque");
		$option=isset($_REQUEST['option'])?$_REQUEST['option']:"list";
		
		switch($option){
			case "list":
				$grado=isset($_REQUEST['grado'])?$_REQUEST['grado']:"2";
				$this->showList($grado);
				break;
			case "new":
				$this->showNew();
				break;
			case "edit":
				$this->showEdit($_REQUEST['optionValue']);
				break;
		}
	}
	
	function showEdit($id) 
	{ 
		$cadena_sql  = $this->sql->cadena_sql("competenciaByID",$id);
		$competencia = $this->resource->execute($cadena_sql,"busqueda");
		$competencia = $competencia[0];
		 
		$cadena_sql = $this->sql->cadena_sql("grados");
		$grados = $this->resource->execute($cadena_sql,"busqueda");
		
		$id_grado = "1"; //corregir esto para q las areas correspondan al grado  
		$cadena_sql = $this->sql->cadena_sql("areas",$id_grado);
		$areas = $this->resource->execute($cadena_sql,"busqueda");

		$id_grado = "2"; //corregir esto para q las areas correspondan al grado  
		$cadena_sql = $this->sql->cadena_sql("areas",$id_grado);
		$areas2 = $this->resource->execute($cadena_sql,"busqueda");

		$areas = array_merge($areas,$areas2);

		$formSaraDataAction  = "bloque=administrarCompetencias";
		$formSaraDataAction .= "&bloqueGrupo=admin";
		$formSaraDataAction .= "&action=administrarCompetencias";
		$formSaraDataAction .= "&option=processEdit"; 
		$formSaraDataAction .= "&optionValue=".$id;
		$formSaraDataAction = $this->context->fabricaConexiones->crypto->codificar($formSaraDataAction);

		$formSaraDataUrl  = "pagina=administrarCompetencias";
		$formSaraDataUrl .= "&grado=".$competencia['GRADO'];
		$formSaraDataUrl  = $this->context->fabricaConexiones->crypto->codificar_url($formSaraDataUrl,$this->enlace);

		$formSaraDeleteAction  = "bloque=administrarCompetencias";
		$formSaraDeleteAction .= "&bloqueGrupo=admin";
		$formSaraDeleteAction .= "&action=administrarCompetencias";
		$formSaraDeleteAction .= "&option=processDelete"; 
		$formSaraDeleteAction .= "&optionValue=".$id;
		$formSaraDeleteAction  = $this->context->fabricaConexiones->crypto->codificar($formSaraDeleteAction);

		include_once($this->ruta."/html/edit.php");
	}
	
	function showList($id_grado){
		
		$cadena_sql=$this->sql->cadena_sql("grados");
		$grados=$this->resource->execute($cadena_sql,"busqueda");		
		$grados=$this->orderArrayKeyBy($grados,"ID");		
		
		//rescato el listado de areas para el grado actual
		$cadena_sql=$this->sql->cadena_sql("areas",$id_grado);
		$areas=$this->resource->execute($cadena_sql,"busqueda");
		
		//rescato el listado de competencias para el grado actual
		$cadena_sql = $this->sql->cadena_sql("competencias",$id_grado);
		$competencias = $this->resource->execute($cadena_sql,"busqueda");
		$competenciasPorArea = $this->orderArrayMultiKeyBy($competencias,"ID_AREA");

		
		$formSaraDataUrl  = "pagina=administrarCompetencias";

		$formSaraDataNew  = "&option=new";
		$formSaraDataNew  = $this->context->fabricaConexiones->crypto->codificar_url($formSaraDataUrl.$formSaraDataNew,$this->enlace);

		$formSaraDataEdit = "&option=edit";
		$formSaraDataEdit = $this->context->fabricaConexiones->crypto->codificar_url($formSaraDataUrl.$formSaraDataEdit,$this->enlace);

		$formSaraDataView = "&option=view"; 
		$formSaraDataView = $this->context->fabricaConexiones->crypto->codificar_url($formSaraDataUrl.$formSaraDataView,$this->enlace);

		$formSaraDataList = "&option=list";
		$formSaraDataList = $this->context->fabricaConexiones->crypto->codificar_url($formSaraDataUrl.$formSaraDataList,$this->enlace);


		$formSaraDataDelete="bloque=blockName";
		$formSaraDataDelete.="&bloqueGrupo=admin";
		$formSaraDataDelete.="&action=blockName";
		$formSaraDataDelete.="&option=processDelete";
		$formSaraDataDelete=$this->context->fabricaConexiones->crypto->codificar_url($formSaraDataDelete,$this->enlace);

		include_once($this->ruta."/html/list.php");
	}

	function showNew(){
		
		$cadena_sql=$this->sql->cadena_sql("grados");
		$grados=$this->resource->execute($cadena_sql,"busqueda");
		
		$id_grado = "1"; //corregir esto para q las areas correspondan al grado  
		$cadena_sql = $this->sql->cadena_sql("areas",$id_grado);
		$areas = $this->resource->execute($cadena_sql,"busqueda");

		$id_grado = "2"; //corregir esto para q las areas correspondan al grado  
		$cadena_sql = $this->sql->cadena_sql("areas",$id_grado);
		$areas2 = $this->resource->execute($cadena_sql,"busqueda");

		$areas = array_merge($areas,$areas2);
		
		$formSaraDataAction="bloque=administrarCompetencias";
		$formSaraDataAction.="&bloqueGrupo=admin";
		$formSaraDataAction.="&action=administrarCompetencias";
		$formSaraDataAction.="&option=processNew"; 
		$formSaraDataAction=$this->context->fabricaConexiones->crypto->codificar($formSaraDataAction);

		$formSaraDataUrl  = "pagina=administrarCompetencias";
		$formSaraDataUrl  = $this->context->fabricaConexiones->crypto->codificar_url($formSaraDataUrl,$this->enlace);


		include_once($this->ruta."/html/new.php");
	}


	function orderArrayKeyBy($array,$key){

		$newArray=array();

		foreach($array as $name=>$value){
			$newArray[$value[$key]]=$array[$name];
		}
		/*echo "<pre>";
		var_dump($newArray);
		echo "</pre>";*/
		return $newArray;
	}
	
	function orderArrayMultiKeyBy($array,$key){

		$newArray=array();

		foreach($array as $name=>$value){
			$newArray[$value[$key]][]=$array[$name];
		}
		/*echo "<pre>";
		var_dump($newArray);
		echo "</pre>";*/
		return $newArray;
	}






}
?>
