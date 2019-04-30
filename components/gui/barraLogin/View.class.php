<?php
include_once("core/manager/Configurador.class.php");

class ViewbarraLogin{

	var $ruta;
	var $sql;
	var $funcion;
	var $lenguaje;
	var $formulario;
	
	var $miConfigurador;
	
	function __construct()
	{
		$conexion="aplicativo";
		$this->miSesion=Sesion::singleton();
		$this->miConfigurador=Configurador::singleton();
		$this->miRecursoDB=$this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);		
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
		$this->ruta=$this->miConfigurador->getVariableConfiguracion("rutaBloque");

		if($this->miSesion->getValorSesion('idUsuario')<>"" && $this->miSesion->getValorSesion('rol')<>"0"){
		  
      include_once($this->ruta."formulario/account.php");
      $variable['usuario']=$this->miSesion->getValorSesion('idUsuario');

			$cadena_sql=$this->sql->cadena_sql("buscarIndexUsuario",$variable);
			$registro=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
      
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