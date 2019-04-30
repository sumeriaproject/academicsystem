<?php
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

include_once("core/auth/Sesion.class.php");
include_once("core/manager/Configurador.class.php");
include_once("core/builder/InspectorHTML.class.php");
include_once("core/builder/Mensaje.class.php");
include_once("core/crypto/Encriptador.class.php");
include_once("plugin/mail/class.phpmailer.php");
include_once("plugin/mail/class.smtp.php");

//Esta clase contiene la logica de negocio del bloque y extiende a la clase funcion general la cual encapsula los
//metodos mas utilizados en la aplicacion

//Para evitar redefiniciones de clases el nombre de la clase del archivo funcion debe corresponder al nombre del bloque
//en camel case precedido por la palabra Funcion

class FuncionadministrarCompetencias
{

	var $sql;
	var $funcion;
	var $lenguaje;
	var $ruta;
	var $miConfigurador;
	var $miInspectorHTML;
	var $error;
	var $miRecursoDB;
	var $crypto;
	var $mensaje;
	var $status;
	

	function processNew($variable)
	{
		include_once($this->ruta."/funcion/processNew.php");
	}

	function processEdit($variable)
	{
		include_once($this->ruta."/funcion/processEdit.php");
	}

	function processDelete($variable)
	{
		include_once($this->ruta."/funcion/processDelete.php");
	}
	
	function enviarCorreo($email,$clave)
	{
		include_once($this->ruta."/funcion/enviarCorreo.php");
	}
	
	function action()
	{
		$excluir  = "";
		$_REQUEST = $this->miInspectorHTML->limpiarPHPHTML($_REQUEST);
		$option   = isset($_REQUEST['option'])?$_REQUEST['option']:"list";
		
		switch($option){
			case "processNew":
				$this->processNew($_REQUEST);
				if(!$this->status)
				{
					$mensaje = implode("<br/>",$this->mensaje['error']);	
					$variable["mensaje"] = $mensaje;
				}else
				{
					$variable["mensaje"] = "La competencia se creo correctamente";
				}
				$variable["option"] = "list"; 
				$variable["grado"]  = $_REQUEST['grado']; 
				
				$this->miConfigurador->render("administrarCompetencias",$variable);
			break;
			case "processEdit":
				$this->processEdit($_REQUEST);
				if(!$this->status)
				{
					$mensaje = implode("<br>",$this->mensaje['error']);	
					$variable["mensaje"] = $mensaje;
				}else
				{
					$variable["mensaje"] = "La competencia se actualizo correctamente";
				}
				$variable["option"] = "list"; 
				$variable["grado"]  = $_REQUEST['grado']; 
				$this->miConfigurador->render("administrarCompetencias",$variable);
				
			break;
			case "processDelete": 
				$this->processDelete($_REQUEST['optionValue']);
				if(!$this->status)
				{
					$mensaje = implode("<br>",$this->mensaje['error']);	
					$variable["mensaje"] = $mensaje;
				}else
				{
					$variable["mensaje"] = "La competencia se actualizo correctamente";
				}
				$variable["option"]="list"; 
				$this->miConfigurador->render("administrarCompetencias",$variable);
			break;
		}
	}


	function __construct()
	{
		
		$this->miConfigurador = Configurador::singleton();
		$this->miSesion       = Sesion::singleton();
		$this->idSesion       = $this->miSesion->getValorSesion('idUsuario');
		$this->miInspectorHTML = InspectorHTML::singleton();
		$this->ruta   = $this->miConfigurador->getVariableConfiguracion("rutaBloque");		
		$this->enlace = $this->miConfigurador->getVariableConfiguracion("host").$this->miConfigurador->getVariableConfiguracion("site")."?".$this->miConfigurador->getVariableConfiguracion("enlace");
		$conexion = "aplicativo";
		$this->miRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		$this->pagina = new Pagina();
		$this->mensaje['error'] = [];

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