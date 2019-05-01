<?php
if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}
include_once("core/manager/Context.class.php");
include_once("core/auth/Sesion.class.php");
include_once("core/builder/InspectorHTML.class.php");
include_once("core/builder/Mensaje.class.php");
class FuncionbarraLogin
{
    var $sql;
    var $funcion;
    var $lenguaje;
    var $ruta;
    var $context;
    var $miInspectorHTML;
    var $miSesion;
    var $error;
    var $resource;
    var $crypto;
    function verificarCampos()
    {
        include_once($this->ruta . "/funcion/verificarCampos.php");
        if ($this->error == true) {
            return false;
        } else {
            return true;
        }
    }
    function login()
    {
        include_once($this->ruta . "/funcion/procesarLogin.php");
    }
    function redireccionar($opcion, $valor = "")
    {
        include_once($this->ruta . "/funcion/redireccionar.php");
    }
    function action()
    {
        $this->borrarSesionesExpiradas();
        $excluir  = "";
        $_REQUEST = $this->miInspectorHTML->limpiarPHPHTML($_REQUEST);
        if (!isset($_REQUEST["opcionLogin"]) || (isset($_REQUEST["opcionLogin"]) && ($_REQUEST["opcionLogin"] == "login"))) {
            $validacion = $this->verificarCampos();
            if ($validacion == false) {
                echo "Datos Incorrectos";
            } else {
                $_REQUEST = $this->miInspectorHTML->limpiarSQL($_REQUEST);
                if (!isset($_REQUEST['opcionLogin']) || $_REQUEST["opcionLogin"] == "login") {
                    $this->login();
                }
            }
        } else if (isset($_REQUEST["opcionLogin"]) && ($_REQUEST["opcionLogin"] == "logout")) {
            $this->miSesion->terminarSesion($_SESSION['aplicativo']);
            $valor['OPCION'] = "login";
            $_REQUEST        = array();
            echo "<script>location.replace('http://www.ceruralrestrepo.com/')</script>";
        }
        return false;
    }
    function __construct()
    {
        $this->context  = Context::singleton();
        $this->miInspectorHTML = InspectorHTML::singleton();
        $this->ruta            = $this->context->getVariable("rutaBloque");
        $this->miMensaje       = Mensaje::singleton();
        $this->miSesion        = Sesion::singleton();
        $this->enlace          = $this->context->getVariable("host") . $this->context->getVariable("site") . "?" . $this->context->getVariable("enlace");
        $conexion              = "aplicativo";
        $this->resource     = $this->context->fabricaConexiones->getRecursoDB($conexion);
        if (!$this->resource) {
            $this->context->fabricaConexiones->setRecursoDB($conexion, "tabla");
            $this->resource = $this->context->fabricaConexiones->getRecursoDB($conexion);
        }
    }
    public function setRuta($unaRuta)
    {
        $this->ruta = $unaRuta;
    }
    function setSql($a)
    {
        $this->sql = $a;
    }
    function setFuncion($funcion)
    {
        $this->funcion = $funcion;
    }
    public function setLenguaje($lenguaje)
    {
        $this->lenguaje = $lenguaje;
    }
    public function setFormulario($formulario)
    {
        $this->formulario = $formulario;
    }
    public function borrarSesionesExpiradas()
    {
        if (!$this->context->getVariable("estaSesion")) {
            $miSesion["sesionId"] = time();
            $this->context->setVariable("estaSesion", $miSesion);
        }
        $estaSesion    = $this->context->getVariable("estaSesion");
        $cadena_sql    = $this->sql->cadena_sql("eliminarTemp", $estaSesion["sesionId"]);
        $conexion      = "configuracion";
        $esteRecursoDB = $this->context->fabricaConexiones->getRecursoDB($conexion);
        $resultado     = $esteRecursoDB->execute($cadena_sql, "acceso");
    }
}