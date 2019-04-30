<?php
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

include_once("core/manager/Configurador.class.php");
include_once("View.class.php");
include_once("Controller.class.php");
include_once("Sql.class.php");
include_once("Language.class.php");

if(class_exists('BloquecierreFinal') === false) {
	class BloquecierreFinal  {

		var $nombreBloque;

		var $miFuncion;

		var $miSql;

		var $miConfigurador;

		public function __construct($esteBloque,$lenguaje="") {

			//El objeto de la clase Configurador debe ser único en toda la aplicación
			$this->miConfigurador=Configurador::singleton();

			$ruta=$this->miConfigurador->getVariableConfiguracion("raizDocumento");
			$rutaURL=$this->miConfigurador->getVariableConfiguracion("host").$this->miConfigurador->getVariableConfiguracion("site");

      if(!isset($_REQUEST['anio']))	{
				$this->year = $this->miConfigurador->getVariableConfiguracion("anio");
			}else {
				$this->year = $_REQUEST['anio'];
			}
      
			if ($esteBloque["grupo"]=="") {
				$ruta.="/components/".$esteBloque["nombre"]."/";
				$rutaURL.="/components/".$esteBloque["nombre"]."/";
			}else {
				$ruta.="/components/".$esteBloque["grupo"]."/".$esteBloque["nombre"]."/";
				$rutaURL.="/components/".$esteBloque["grupo"]."/".$esteBloque["nombre"]."/";
			}

			$this->miConfigurador->setVariableConfiguracion("rutaBloque",$ruta);
			$this->miConfigurador->setVariableConfiguracion("rutaUrlBloque",$rutaURL);

			$nombreClaseFuncion="Funcion".$esteBloque["nombre"];
			$this->miFuncion=new $nombreClaseFuncion();

			$nombreClaseSQL="Sql".$esteBloque["nombre"];
			$this->miSql=new $nombreClaseSQL();
      $this->miSql->activeYear = $this->year;

			$nombreClaseView="View".$esteBloque["nombre"];
			$this->miView=new $nombreClaseView();

			$nombreClaseLenguaje="Lenguaje".$esteBloque["nombre"];
			$this->miLenguaje=new $nombreClaseLenguaje();


		}

		public function bloque(){

			if(!isset($_REQUEST['action'])){
				$this->miView->setSql($this->miSql);
				$this->miView->setFuncion($this->miFuncion);
				$this->miView->setLenguaje($this->miLenguaje);
        $this->miView->activeYear = $this->year;
				$this->miView->html();
			}else{

				$this->miFuncion->setSql($this->miSql);
				$this->miFuncion->setFuncion($this->miFuncion);
				$this->miFuncion->setLenguaje($this->miLenguaje);
				$this->miFuncion->setFormulario($this->miView);
        $this->miFuncion->activeYear = $this->year;
				$this->miFuncion->action();
			}

		}

	}
}



$unBloque["nombre"]="cierreFinal";
$unBloque["grupo"]="instrumentos";
$estaClase="Bloque".$unBloque["nombre"];

$this->miConfigurador->setVariableConfiguracion("esteBloque",$unBloque);

if(isset($lenguaje)){

	$esteBloque=new $estaClase($unBloque,$lenguaje);
}else{

	$esteBloque=new $estaClase($unBloque);

}

$esteBloque->bloque();



?>
