<?php
class Mensaje{

	private static $instance;
	
	/**
	 * @deprecated
	 * Arreglo que contiene las variables de configuración
	 * @var String
	 */
	
	private $miConfigurador;
	
	
	function __construct(){
		
		$this->miConfigurador=Configurador::singleton();

	}


	public static function singleton()
	{
		if (!isset(self::$instance)) {
			$className = __CLASS__;
			self::$instance = new $className;
		}
		return self::$instance;
	}

	function mostrarMensaje($mensaje, $tipoMensaje="warning"){
		
		include_once("Mensaje.page.php");

	}


}


?>