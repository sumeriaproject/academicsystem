<?php
if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}
include_once("core/manager/Configurador.class.php");
include_once("core/auth/Sesion.class.php");
include_once("core/builder/InspectorHTML.class.php");
include_once("core/builder/Mensaje.class.php");
class FuncionbarraLogin
{
    var $sql;
    var $funcion;
    var $lenguaje;
    var $ruta;
    var $miConfigurador;
    var $miInspectorHTML;
    var $miSesion;
    var $error;
    var $miRecursoDB;
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
        $this->miConfigurador  = Configurador::singleton();
        $this->miInspectorHTML = InspectorHTML::singleton();
        $this->ruta            = $this->miConfigurador->getVariableConfiguracion("rutaBloque");
        $this->miMensaje       = Mensaje::singleton();
        $this->miSesion        = Sesion::singleton();
        $this->enlace          = $this->miConfigurador->getVariableConfiguracion("host") . $this->miConfigurador->getVariableConfiguracion("site") . "?" . $this->miConfigurador->getVariableConfiguracion("enlace");
        $conexion              = "aplicativo";
        $this->miRecursoDB     = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
        if (!$this->miRecursoDB) {
            $this->miConfigurador->fabricaConexiones->setRecursoDB($conexion, "tabla");
            $this->miRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
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
        if (!$this->miConfigurador->getVariableConfiguracion("estaSesion")) {
            $miSesion["sesionId"] = time();
            $this->miConfigurador->setVariableConfiguracion("estaSesion", $miSesion);
        }
        $estaSesion    = $this->miConfigurador->getVariableConfiguracion("estaSesion");
        $cadena_sql    = $this->sql->cadena_sql("eliminarTemp", $estaSesion["sesionId"]);
        $conexion      = "configuracion";
        $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
        $resultado     = $esteRecursoDB->ejecutarAcceso($cadena_sql, "acceso");
    }
}