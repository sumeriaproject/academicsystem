<?php
if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}
include_once("core/manager/Configurador.class.php");
include_once("core/connection/Sql.class.php");
class SqlbarraLogin extends sql
{
    var $miConfigurador;
    function __construct()
    {
        $this->miConfigurador = Configurador::singleton();
    }
    function cadena_sql($tipo, $variable = "")
    {
        $prefijo  = $this->miConfigurador->getVariableConfiguracion("prefijo");
        $idSesion = $this->miConfigurador->getVariableConfiguracion("id_sesion");
        switch ($tipo) {
            case "dataUserByID":
                $cadena_sql = "SELECT ";
                $cadena_sql .= "id_usuario USUARIOID, ";
                $cadena_sql .= "nombre NOMBRE ";
                $cadena_sql .= "FROM ";
                $cadena_sql .= $prefijo . "usuario ";
                $cadena_sql .= "WHERE ";
                $cadena_sql .= "id_usuario ='" . $variable . "'";
                break;
            case "buscarUsuarioAplicativo":
                $cadena_sql = "SELECT ";
                $cadena_sql .= $prefijo . "usuario.id_usuario USUARIOID, ";
                $cadena_sql .= $prefijo . "usuario.clave CLAVE, ";
                $cadena_sql .= $prefijo . "usuario.usuario, ";
                $cadena_sql .= $prefijo . "usuario_subsistema.id_subsistema ROL, ";
                $cadena_sql .= $prefijo . "usuario_subsistema.estado, ";
                $cadena_sql .= $prefijo . "usuario.estilo TEMA, ";
                $cadena_sql .= $prefijo . "usuario.idioma IDIOMA, ";
                $cadena_sql .= $prefijo . "pagina.nombre PAGINA, ";
                $cadena_sql .= $prefijo . "usuario.tipo ";
                $cadena_sql .= "FROM ";
                $cadena_sql .= $prefijo . "usuario, ";
                $cadena_sql .= $prefijo . "pagina, ";
                $cadena_sql .= $prefijo . "subsistema, ";
                $cadena_sql .= $prefijo . "usuario_subsistema ";
                $cadena_sql .= "WHERE ";
                $cadena_sql .= $prefijo . "usuario.usuario='" . $variable["usuario"] . "' ";
                $cadena_sql .= "AND ";
                $cadena_sql .= $prefijo . "usuario.clave='" . $variable['clave'] . "'  ";
                $cadena_sql .= "AND ";
                $cadena_sql .= $prefijo . "usuario_subsistema.id_subsistema=" . $prefijo . "subsistema.id_subsistema ";
                $cadena_sql .= "AND ";
                $cadena_sql .= $prefijo . "pagina.id_pagina=" . $prefijo . "subsistema.id_pagina ";
                $cadena_sql .= "AND ";
                $cadena_sql .= $prefijo . "usuario_subsistema.estado=1 ";
                $cadena_sql .= "AND ";
                $cadena_sql .= $prefijo . "usuario.id_usuario=" . $prefijo . "usuario_subsistema.id_usuario ";
                break;
            case "buscarIndexUsuario":
                $cadena_sql = "SELECT ";
                $cadena_sql .= $prefijo . "usuario.id_usuario USUARIOID, ";
                $cadena_sql .= $prefijo . "usuario.usuario, ";
                $cadena_sql .= $prefijo . "usuario_subsistema.id_subsistema ROL, ";
                $cadena_sql .= $prefijo . "usuario_subsistema.estado, ";
                $cadena_sql .= $prefijo . "usuario.estilo TEMA, ";
                $cadena_sql .= $prefijo . "usuario.idioma IDIOMA, ";
                $cadena_sql .= $prefijo . "pagina.nombre PAGINA, ";
                $cadena_sql .= $prefijo . "usuario.tipo ";
                $cadena_sql .= "FROM ";
                $cadena_sql .= $prefijo . "usuario, ";
                $cadena_sql .= $prefijo . "subsistema, ";
                $cadena_sql .= $prefijo . "pagina, ";
                $cadena_sql .= $prefijo . "usuario_subsistema ";
                $cadena_sql .= "WHERE ";
                $cadena_sql .= $prefijo . "usuario.id_usuario='" . $variable["usuario"] . "' ";
                $cadena_sql .= "AND ";
                $cadena_sql .= $prefijo . "usuario_subsistema.id_subsistema=" . $prefijo . "subsistema.id_subsistema ";
                $cadena_sql .= "AND ";
                $cadena_sql .= $prefijo . "pagina.id_pagina=" . $prefijo . "subsistema.id_pagina ";
                $cadena_sql .= "AND ";
                $cadena_sql .= $prefijo . "usuario_subsistema.estado=1 ";
                $cadena_sql .= "AND ";
                $cadena_sql .= $prefijo . "usuario.id_usuario=" . $prefijo . "usuario_subsistema.id_usuario ";
                break;
            case "iniciarTransaccion":
                $cadena_sql = "START TRANSACTION";
                break;
            case "finalizarTransaccion":
                $cadena_sql = "COMMIT";
                break;
            case "cancelarTransaccion":
                $cadena_sql = "ROLLBACK";
                break;
            case "eliminarTemp":
                $cadena_sql = "DELETE ";
                $cadena_sql .= "FROM ";
                $cadena_sql .= $prefijo . "tempFormulario ";
                $cadena_sql .= "WHERE ";
                $cadena_sql .= "id_sesion = '" . $variable . "' ";
                break;
        }
        return $cadena_sql;
    }
}