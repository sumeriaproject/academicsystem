<?php
if (!isset($GLOBALS["autorizado"])) {
    include("index.php");
    exit;
} else {
    $token = strrev(($this->context->getVariable("enlace")));
    if (isset($_REQUEST[$token . "usuario"]) && isset($_REQUEST[$token . "clave"])) {
        $variable["usuario"] = $_REQUEST[$token . "usuario"];
        $variable["clave"]   = md5($_REQUEST[$token . "clave"]);
        $conexion            = "aplicativo";
        $esteRecursoDB       = $this->context->fabricaConexiones->getRecursoDB($conexion);
        if (!$esteRecursoDB) {
            exit;
        }
        $cadena_sql = $this->sql->get("buscarUsuarioAplicativo", $variable);
        $registro   = $esteRecursoDB->execute($cadena_sql, "busqueda");
        if ($registro) {
            if ($registro[0]["CLAVE"] == $variable["clave"]) {
                $this->session->tema   = $registro[0]["TEMA"];
                $this->session->rol    = $registro[0]["ROL"];
                $this->session->idioma = $registro[0]["IDIOMA"];
                $registro[0]["SESION"]  = $this->session->crearSesion($registro[0]["USUARIOID"]);
                $registro[0]["OPCION"]  = "account";
                $this->funcion->redireccionar("indexUsuario", $registro[0]);
                return true;
            }
        }
        $this->funcion->redireccionar("index", "Datos de acceso incorrectos!");
    }
}