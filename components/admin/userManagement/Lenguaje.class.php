<?php
class LenguajeuserManagement
{
    private $idioma;
    private $context;
    private $nombreBloque;
    function __construct()
    {
        $this->context = Context::singleton();
        $esteBloque           = $this->context->getVariable("esteBloque");
        $this->nombreBloque   = $esteBloque["nombre"];
        $this->ruta           = $this->context->getVariable("rutaBloque");
        if ($this->context->getVariable("idioma")) {
            $idioma = $this->context->getVariable("idioma");
        } else {
            $idioma = "es_es";
        }
        include($this->ruta . "/locale/" . $idioma . "/Mensaje.php");
    }
    public function getCadena($opcion = "")
    {
        $opcion = trim($opcion);
        if (isset($this->idioma[$opcion])) {
            return $this->idioma[$opcion];
        } else {
            return $this->idioma["noDefinido"];
        }
    }
}