<?php
require_once("core/manager/Bootstrap.class.php");

class Aplicacion{

	/**
	 * Arreglo. Contiene las rutas donde se encuentran los archivos del aplicativo.
	 * @var string
	 */

	/**
	 * Objeto. Se encarga de las tareas preliminares que se requieren para lanzar la aplicación.
	 *
	 * @var Inicializador
	 */
	var $miLanzador;

	function __construct() {

		$GLOBALS["configuracion"] = TRUE;
		$this->miLanzador = new Bootstrap();
		
		do{
			if(isset($_REQUEST["recargar"])){
				unset($_REQUEST["recargar"]);
			}
			$this->miLanzador->iniciar();			
		}while(isset($_REQUEST["recargar"]));
	}

};

/**
 * Iniciar la aplicacion.
 */
$miAplicacion = new Aplicacion();

?>