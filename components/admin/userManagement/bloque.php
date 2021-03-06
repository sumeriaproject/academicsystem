<?php
if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}
include_once("core/manager/Context.class.php");
include_once("View.class.php");
include_once("Funcion.class.php");
include_once("Sql.class.php");
include_once("Lenguaje.class.php");
if (class_exists('BloqueuserManagement') === false) {
    class BloqueuserManagement
    {
        var $nombreBloque;
        var $miFuncion;
        var $miSql;
        var $context;
        public function __construct($esteBloque, $lenguaje = "")
        {
            $this->context = Context::singleton();
            $ruta                 = $this->context->getVariable("raizDocumento");
            $rutaURL              = $this->context->getVariable("host") . $this->context->getVariable("site");
            if ($esteBloque["grupo"] == "") {
                $ruta .= "/components/" . $esteBloque["nombre"] . "/";
                $rutaURL .= "/components/" . $esteBloque["nombre"] . "/";
            } else {
                $ruta .= "/components/" . $esteBloque["grupo"] . "/" . $esteBloque["nombre"] . "/";
                $rutaURL .= "/components/" . $esteBloque["grupo"] . "/" . $esteBloque["nombre"] . "/";
            }
            $this->context->setVariable("rutaBloque", $ruta);
            $this->context->setVariable("rutaUrlBloque", $rutaURL);
            $nombreClaseFuncion  = "Funcion" . $esteBloque["nombre"];
            $this->miFuncion     = new $nombreClaseFuncion();
            $nombreClaseSQL      = "Sql" . $esteBloque["nombre"];
            $this->miSql         = new $nombreClaseSQL();
            $nombreClaseView     = "View" . $esteBloque["nombre"];
            $this->miView        = new $nombreClaseView();
            $nombreClaseLenguaje = "Lenguaje" . $esteBloque["nombre"];
            $this->miLenguaje    = new $nombreClaseLenguaje();
        }
        public function bloque()
        {
            if (!isset($_REQUEST['action'])) {
                $this->miView->setSql($this->miSql);
                $this->miView->setFuncion($this->miFuncion);
                $this->miView->setLenguaje($this->miLenguaje);
                $this->miView->html();
            } else {
                $this->miFuncion->setSql($this->miSql);
                $this->miFuncion->setFuncion($this->miFuncion);
                $this->miFuncion->setLenguaje($this->miLenguaje);
                $this->miFuncion->action();
            }
        }
    }
}
$unBloque["nombre"] = "userManagement";
$unBloque["grupo"]  = "admin";
$estaClase          = "Bloque" . $unBloque["nombre"];
$this->context->setVariable("esteBloque", $unBloque);
if (isset($lenguaje)) {
    $esteBloque = new $estaClase($unBloque, $lenguaje);
} else {
    $esteBloque = new $estaClase($unBloque);
}
$esteBloque->bloque();