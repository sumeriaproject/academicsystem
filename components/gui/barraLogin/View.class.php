<?php
include_once("core/manager/Context.class.php");

class ViewbarraLogin{

	var $ruta;
	var $sql;
	var $funcion;
	var $lenguaje;
	var $formulario;
	
	var $context;
	
	function __construct()
	{
		$conexion="aplicativo";
		$this->session=Sesion::singleton();
		$this->context=Context::singleton();
		$this->resource=$this->context->fabricaConexiones->getRecursoDB($conexion);		
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

	function frontera()
	{
		$this->html();
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

		if($this->session->getValue('idUsuario')<>"" && $this->session->getValue('rol')<>"0"){
		  
      include_once($this->ruta."formulario/account.php");
      $variable['usuario']=$this->session->getValue('idUsuario');

			$cadena_sql=$this->sql->get("buscarIndexUsuario",$variable);
			$registro=$this->resource->execute($cadena_sql,"busqueda");
      
      if(!is_array($registro)){
        include_once($this->ruta."html/login.php");
        exit;
		  }
		  $registro[0]["OPCION"]="account";
		  $this->funcion->redireccionar("indexUsuario",$registro[0]);       
		}
		else{
		   include_once($this->ruta."formulario/login.php");
		}
		
		return true;
		


	}


}
?>