<?php

require_once("config/config.class.php");


class Context{

	private static $instance;

	public $configuracion;

	public $fabricaConexiones;
		
	public $conexionDB;
	

	private function __construct() {
		$this->configuracion["inicio"] = true;
		$this->conexion=array();
	
	}

	public static function singleton(){
		if (!isset(self::$instance)) {
			$className = __CLASS__;
			self::$instance = new $className;
		}
		return self::$instance;
	}

	public function setConectorDB($objeto){
		$this->fabricaConexiones=$objeto;
	}


	function variable() {
			
		$this->setMainConfig();
		$this->fabricaConexiones->setConfiguracion($this->configuracion);
		
		$resultado=$this->fabricaConexiones->setRecursoDB("","configuracion");
		
		if($resultado){
			$this->conexionDB=$this->fabricaConexiones->getRecursoDB("configuracion");
		}
		
		if($this->conexionDB){

			$resultado=$this->rescatarVariablesDB();

			if($resultado){
				$this->rescatarVariablesSesion();
			};
			
			return true;
		};
		
		return false;
	}

	private function setMainConfig(){

		$configuracionBasica= ArchivoConfiguracion::singleton();

		$conf=$configuracionBasica->getConf();

		foreach($conf as $clave=>$valor){
			$this->configuracion[$clave] = $valor;
		}

		return true;
	}

	private function rescatarVariablesDB(){
		
		if ($this->conexionDB->getEnlace()) {
			
			$cadena_sql = "SELECT ";
			$cadena_sql.=" parametro,  ";
			$cadena_sql.=" valor  ";
			$cadena_sql.="FROM ";
			$cadena_sql.=$this->configuracion["dbprefijo"] . "configuracion ";
			
			
			$this->total = $this->conexionDB->registro_db($cadena_sql, 0);

			if ($this->total > 0) {
				$this->registro = $this->conexionDB->getRegistroDb();
				for ($j = 0; $j < $this->total; $j++) {
					$this->configuracion[trim($this->registro[$j]["parametro"])] = trim($this->registro[$j]["valor"]);
					//print_r($this->configuracion);
				}
				
				return true;
			
				
			} else {
				
				error_log("No se puede iniciar la aplicacion. Imposible rescatar las variables de configuracion!", 0);
				return 0;
			}
		} else {
			error_log("No se puede iniciar la aplicacion. Imposible determinar un recurso de base de datos.!", 0);
			return 0;
		}
		

	}


	private function rescatarVariablesSesion(){

		
		/**
		 * @todo implementar esta función
		 */
		/*
		if (isset($_SERVER["HTTPS"])) {

			$this->configuracion["host"] = $this->configuracion["host_seguro"];
		}

		//Rescatar las variable basicas de sesion de un usuario
		$this->configuracion["id_sesion"] = $this->funcion->rescatarNumeroSesion($this->configuracion);

		$this->configuracion["sesionAcceso"] = $this->funcion->rescatarValorSesion($this->configuracion, "acceso");
		$this->configuracion["sesionIdUsuario"] = $this->funcion->rescatarValorSesion($this->configuracion, "id_usuario");
		$this->configuracion["sesionUsuario"] = $this->funcion->rescatarValorSesion($this->configuracion, "usuario");

		$lenguaje = $this->funcion->rescatarValorSesion($this->configuracion, "lenguaje");
		if ($lenguaje) {
			$this->configuracion["lenguaje"] = $this->funcion->rescatarValorSesion($this->configuracion, "lenguaje");
		}
		*/
	}



	/**
	 * Método de acceso que retorna el arreglo con todos los datos de configuración
	 * @return string[]
	 */

	function getConfiguracion() {
		return $this->configuracion;
	}

	/**
	 * Método de acceso, retorna una variable específica desde el arreglo de
	 * configuración.
	 * @param string $cadena
	 * @return string|boolean
	 */

	function getVariable($cadena="") {
		
		if(isset($this->configuracion[$cadena])){
			
			return $this->configuracion[$cadena];
		}

		return false;

	}


	function setVariable($variable="", $cadena="") {
		
		

		if($variable!="" && $cadena!=""){
			$this->configuracion[$variable]=$cadena;
		}else{
			if (isset($this->configuracion[$variable]) && $cadena==null){
				unset($this->configuracion[$variable]);
			}
		}
		return true;

	}	
	
	
	function render($pagina,$parametros=array()){
		foreach($_REQUEST as $clave=>$valor){
			unset($_REQUEST[$clave]);
		}
		$variable="pagina=".$pagina;
		foreach($parametros as $key=>$value){
			$variable.="&".$key."=".$value;
		}
		
		$enlace=$this->getVariable("enlace");
		$variable=$this->fabricaConexiones->crypto->codificar($variable);
		$_REQUEST[$enlace]=$variable;
		$_REQUEST["recargar"]=true;
	}
	

};

?>