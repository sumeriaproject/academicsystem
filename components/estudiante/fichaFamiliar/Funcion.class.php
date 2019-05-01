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

class FuncionfichaFamiliar
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
	

	function processNew(){
		include_once($this->ruta."/funcion/processNew.php");
	}

	function processEdit($option){
		include_once($this->ruta."/funcion/processEdit.php");
	}

	function processDelete($id){
		include_once($this->ruta."/funcion/processDelete.php");
	}

	function redireccionar($option, $valor=""){
		include_once($this->ruta."/funcion/redireccionar.php");
	}

	function action()
	{
			
		//Evitar que se ingrese codigo HTML y PHP en los campos de texto
		//Campos que se quieren excluir de la limpieza de código. Formato: nombreCampo1|nombreCampo2|nombreCampo3
		$excluir="";
		$_REQUEST=$this->miInspectorHTML->limpiarPHPHTML($_REQUEST);

		$option=isset($_REQUEST['optionProcess'])?$_REQUEST['optionProcess']:"";

		
		switch($option){
			case "processNew":

			break;
			case "processEditCommerce":
			case "processEditCompany":
				$this->processEdit($option);
				echo $this->mensaje;
			break;
			case "processDelete":
				$this->processDelete($option);
				echo $this->mensaje;
			break;
		}




	}


	function __construct()
	{
		
		$this->context=Context::singleton();

		$this->miInspectorHTML=InspectorHTML::singleton();
			
		$this->ruta=$this->context->getVariable("rutaBloque");		
		
		$this->miMensaje=Mensaje::singleton();
		
		$conexion="aplicativo";
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