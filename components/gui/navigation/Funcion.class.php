<?php
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

include_once("core/manager/Context.class.php");
include_once("core/builder/InspectorHTML.class.php");
include_once("core/builder/Mensaje.class.php");
include_once("core/crypto/Encriptador.class.php");

class FuncionNavigation
{

	var $sql;
	var $funcion;
	var $lenguaje;
	var $ruta;
	var $context;
	var $miInspectorHTML;
	var $error;
	var $resource;
	var $crypto;
	var $mensaje;
	var $status;
	
	function action()
	{

	}


	function __construct()
	{
		
		$this->context=Context::singleton();

		$this->miInspectorHTML=InspectorHTML::singleton();
			
		$this->ruta=$this->context->getVariable("rutaBloque");		
		
		$this->miMensaje=Mensaje::singleton();
		
		$conexion = "aplicativo";
		$this->resource=$this->context->fabricaConexiones->getRecursoDB($conexion);
		
		if(!$this->resource){
		
			$this->context->fabricaConexiones->setRecursoDB($conexion,"tabla");
			$this->resource=$this->context->fabricaConexiones->getRecursoDB($conexion);			
		}
		
		
	}

	public function setRuta($unaRuta){
		$this->ruta=$unaRuta;
		//Incluir las funciones
	}

	function setSql($a)
	{
		$this->sql=$a;
	}

	function setFuncion($funcion)
	{
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
