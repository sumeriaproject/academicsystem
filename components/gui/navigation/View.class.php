<?php
include_once("core/manager/Configurador.class.php");
include_once("core/auth/Sesion.class.php");

class ViewNavigation{

	var $ruta;
	var $sql;
	var $funcion;
	var $lenguaje;
	var $formulario;
	var $enlace;
	var $miConfigurador;
	
	function __construct()
	{
		$this->miSesion=Sesion::singleton();
		$this->miConfigurador=Configurador::singleton();
		$this->miRecursoDB=$this->miConfigurador->fabricaConexiones->getRecursoDB("aplicativo");
		$this->enlace=$this->miConfigurador->getVariableConfiguracion("host").$this->miConfigurador->getVariableConfiguracion("site")."?".$this->miConfigurador->getVariableConfiguracion("enlace");
		$this->id_usuario=$this->miSesion->getValorSesion('idUsuario');
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
		$this->ruta=$this->miConfigurador->getVariableConfiguracion("rutaBloque");
		$this->showMenu();
	}
	
	
	function showMenu(){

		$cadena_sql=$this->sql->cadena_sql("menuList",$this->miSesion->getValorSesion('rol'));
		$menuList=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");

		$menuList=$this->orderArrayKeyBy($menuList,"PADRE");

		$cadena_sql=$this->sql->cadena_sql("roleList");
		$roleList=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");



		include_once($this->ruta."/html/menu.php");
	}


	function makeURL($param,$page){

		$formSaraData="pagina=".$page;
		$formSaraData.="&";
		$formSaraData.=$param;
		$formSaraData=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($formSaraData,$this->enlace);

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