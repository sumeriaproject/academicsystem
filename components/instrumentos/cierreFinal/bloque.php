<?php
if(!isset($GLOBALS["autorizado"]))
{
	include("../index.php");
	exit;
}

include_once("core/manager/Context.class.php");
include_once("View.class.php");
include_once("Controller.class.php");
include_once("Sql.class.php");
include_once("Language.class.php");

if(class_exists('BloquecierreFinal') === false) {
	class BloquecierreFinal  {

		var $nombreBloque;

		var $miFuncion;

		var $miSql;

		var $context;

		public function __construct($esteBloque,$lenguaje="") {

			//El objeto de la clase Context debe ser único en toda la aplicación
			$this->context=Context::singleton();

			$ruta=$this->context->getVariable("raizDocumento");
			$rutaURL=$this->context->getVariable("host").$this->context->getVariable("site");

      if(!isset($_REQUEST['anio']))	{
				$this->year = $this->context->getVariable("anio");
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

			$this->context->setVariable("rutaBloque",$ruta);
			$this->context->setVariable("rutaUrlBloque",$rutaURL);

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

$this->context->setVariable("esteBloque",$unBloque);

if(isset($lenguaje)){

	$esteBloque=new $estaClase($unBloque,$lenguaje);
}else{

	$esteBloque=new $estaClase($unBloque);

}

$esteBloque->bloque();



?>
