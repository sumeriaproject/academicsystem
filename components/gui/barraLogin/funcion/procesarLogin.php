<?php
if (!isset($GLOBALS["autorizado"])) {
    include("index.php");
    exit;
} else {
    $token = strrev(($this->miConfigurador->getVariableConfiguracion("enlace")));
    if (isset($_REQUEST[$token . "usuario"]) && isset($_REQUEST[$token . "clave"])) {
        $variable["usuario"] = $_REQUEST[$token . "usuario"];
        $variable["clave"]   = md5($_REQUEST[$token . "clave"]);
        $conexion            = "aplicativo";
        $esteRecursoDB       = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
        if (!$esteRecursoDB) {
            exit;
        }
        $cadena_sql = $this->sql->cadena_sql("buscarUsuarioAplicativo", $variable);
        $registro   = $esteRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        if ($registro) {
            if ($registro[0]["CLAVE"] == $variable["clave"]) {
                $this->miSesion->tema   = $registro[0]["TEMA"];
                $this->miSesion->rol    = $registro[0]["ROL"];
                $this->miSesion->idioma = $registro[0]["IDIOMA"];
                $registro[0]["SESION"]  = $this->miSesion->crearSesion($registro[0]["USUARIOID"]);
                $registro[0]["OPCION"]  = "account";
                $this->funcion->redireccionar("indexUsuario", $registro[0]);
                return true;
            }
        }
        $this->funcion->redireccionar("index", "Datos de acceso incorrectos!");
    }
}