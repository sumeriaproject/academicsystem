<?php
include_once("core/manager/Context.class.php");
include_once("core/auth/Sesion.class.php");

class ViewNavigation{

	var $ruta;
	var $sql;
	var $funcion;
	var $lenguaje;
	var $formulario;
	var $enlace;
	var $context;
	
	function __construct()
	{
		$this->session=Sesion::singleton();
		$this->context=Context::singleton();
		$this->resource=$this->context->fabricaConexiones->getRecursoDB("aplicativo");
		$this->enlace=$this->context->getVariable("host").$this->context->getVariable("site")."?".$this->context->getVariable("enlace");
		$this->id_usuario=$this->session->getValue('idUsuario');
	}

	public function setRuta($unaRuta)
	{
		$this->ruta=$unaRuta;
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
		$this->ruta=$this->context->getVariable("rutaBloque");
		$this->showMenu();
	}
	
	
	function showMenu(){

		$cadena_sql=$this->sql->get("menuList",$this->session->getValue('rol'));
		$menuList=$this->resource->execute($cadena_sql,"busqueda");

		$menuList=$this->orderArrayKeyBy($menuList,"PADRE");

		$cadena_sql=$this->sql->get("roleList");
		$roleList=$this->resource->execute($cadena_sql,"busqueda");



		include_once($this->ruta."/html/menu.php");
	}


	function makeURL($param,$page){

		$formSaraData="pagina=".$page;
		$formSaraData.="&";
		$formSaraData.=$param;
		$formSaraData=$this->context->fabricaConexiones->crypto->codificar_url($formSaraData,$this->enlace);

		return $formSaraData;

	}


	function orderArrayKeyBy($array,$key){

		$newArray=array();

		foreach($array as $name=>$value){
			$newArray[$value[$key]][]=$array[$name];
		}

		return $newArray;
	}

}