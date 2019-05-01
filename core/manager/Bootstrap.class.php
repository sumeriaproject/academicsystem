<?php
require_once("core/manager/Context.class.php");
require_once("core/auth/Sesion.class.php");
require_once("core/connection/dbConnect.class.php");
require_once("core/crypto/Encriptador.class.php");
require_once("core/builder/Mensaje.class.php");

class Bootstrap{

		/**
	 * Objeto. 
	 * Con los atributos y métodos para gestionar la sesión de usuario
	 * @var Sesion
	 */
	
	var $sesionUsuario;
	
	
	/**
	 *
	 * Objeto.
	 * Encargado de inicializar las variables globales. Su atributo $configuracion contiene los valores necesarios
	 * para gestionar la aplicacion.
	 * @var Context
	 */
	var $context;


	/**
	 *
	 * Objeto, con funciones miembro generales que encapsulan funcionalidades
	 * básicas.
	 * @var FuncionGeneral
	 */
	private $miFuncion;

	/**
	 *
	 * Objeto. Gestiona conexiones a bases de datos.
	 * @var FabricaDBConexion
	 */
	private $manejadorDB;

	/**
	 * Objeto de la clase Encriptador se encarga de codificar/decodificar cadenas de texto.
	 * @var Encriptador
	 */
	private $cripto;


	/**
	 *
	 * Objeto. Actua como controlador del modulo de instalación del framework/aplicativo
	 * @var Instalador
	 */
	var $miInstalador;

	/**
	 *
	 * Objeto. Instancia de la pagina que se está visitando
	 * @var Pagina
	 */
	var $miPagina;

	/**
	 *
	 * Arreglo.Ruta de acceso a los archivos, se utilizan porque aún no se ha rescatado las
	 * variables de configuración.
	 *
	 * @var string
	 */
	var $misVariables;

	/**
	 * Objeto que se encarga de mostrar los mensajes de error fatales.
	 * @var Mensaje
	 */
	var $cuadroMensaje;




	/**
	 * Contructor
	 * @param none
	 * @return integer
	 * */

	function __construct(){

		$this->cuadroMensaje=Mensaje::singleton();
		$this->conectorDB = dbConnect::singleton();
		$this->cripto = Encriptador::singleton();
		
		/**
		 * Importante conservar el orden de creación de los siguientes objetos porque tienen
		 * referencias cruzadas.
		 */
		$this->context=Context::singleton();
		$this->context->setConectorDB($this->conectorDB);
		
		/**
		 * El objeto del a clase Sesion es el último que se debe crear.
		 */
		$this->sesionUsuario=Sesion::singleton();

	}

	/**
	 *
	 * Iniciar la aplicación.
	 */

	public function iniciar(){

		// Poblar el atributo context->configuracion

		$this->context->variable();

		if(!$this->context->getVariable("instalado"))
		{
			$this->instalarAplicativo();

		}else{
			$this->ingresar();
		}
	}

	/**
	 *
	 * Asigna los valores a las variables que indican las rutas predeterminadas.
	 * @param strting array $variables
	 */

	function setMisVariables($variables){
		$this->misVariables=$variables;
		$this->context->setRutas($variables);
	}

	/**
	 *
	 * Ingresar al aplicativo.
	 * @param Ninguno
	 * @return int
	 */
	private function ingresar() {

		/**
		 * @global boolean $GLOBALS['autorizado']
		 * @name $autorizado
		 */
		$GLOBALS["autorizado"]=TRUE;

		$pagina=$this->determinarPagina();
	
		
		$this->context->setVariable("pagina",$pagina);
		
		
		/**
		 * Verificar que se tenga una sesión válida
		*/

		require_once($this->context->getVariable("raizDocumento")."/core/auth/Autenticador.class.php");
		$this->autenticador=Autenticador::singleton();
		$this->autenticador->especificarPagina($pagina);

		if($this->autenticador->iniciarAutenticacion()){

			/**
			 * Procesa la página solicitada por el usuario
			 */
			require_once($this->context->getVariable("raizDocumento")."/core/builder/Pagina.class.php");
			
					
			$this->miPagina=new Pagina();
				
			if($this->miPagina->inicializarPagina($pagina)){
				
				return true;
			}else{
				$this->mostrarMensajeError($this->miPagina->getError());
				return false;
			}			
			
		}else{
			$this->mostrarMensajeError($this->autenticador->getError());
			return false;
		}		
		
	}
	
	private function mostrarMensajeError($mensaje){
		$this->context->setVariable("error", true);
		$this->cuadroMensaje->mostrarMensaje($mensaje, "error");		
	}


	private function determinarPagina(){
		/**
		 * Determinar la página que se desea cargar
		 */

		if(isset($_REQUEST[$this->context->getVariable("enlace")])) {
			$this->context->fabricaConexiones->crypto->decodificar_url($_REQUEST[$this->context->getVariable("enlace")]);
			
			if(isset($_REQUEST["redireccionar"])) {
				$this->redireccionar();
				return false;
			}
			if(isset($_REQUEST["pagina"])) {
				return $_REQUEST["pagina"];
			}else {
				if(!isset($_REQUEST["action"])){
					return "";
				}else {
					return "index";
				}
			}

		}else {

			return "index";
		}
	}

	/**
	 *
	 * Instalar el aplicativo.
	 */

	private function instalarAplicativo() {


		require_once("install/Instalador.class.php");
		$this->miInstalador=new Instalador();
		if(isset($_REQUEST["instalador"])){
			$this->miInstalador->procesarInstalacion();
		}else{
			$this->miInstalador->mostrarFormularioDatosConexion();
		}
		return 0;
	}
	/**
	 * Redireccionar a otra página
	 * @return number
	 */

	function redireccionar(){
		$variable="";

		foreach($_REQUEST as $clave=> $val) {
			if($clave !="redireccion") {
				$variable.="&".$clave."=".$val;
			}
		}

		$this->context->cripto->decodificar_url($_REQUEST["redireccion"]);

		foreach($_REQUEST as $clave=> $val) {
			$variable.="&".$clave."=".$val;
		}

		$variable=$this->context->cripto->codificar_url($variable,$this->context->configuracion);
		$indice=$this->context->configuracion["host"].$this->context->configuracion["site"]."/index.php?";
		echo "<script>location.replace('".$indice.$variable."')</script>";
		return 0;


	}

};

?>
