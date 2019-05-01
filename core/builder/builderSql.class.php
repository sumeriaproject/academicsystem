<?php
include_once($this->context->getVariable("raizDocumento") . "/core/connection/Sql.class.php");
class BuilderSql extends Sql
{
    var $cadena_sql;
    var $context;
    private static $instance;
    function __construct()
    {
        $this->context = Context::singleton();
        return 0;
    }
    public static function singleton()
    {
        if (!isset(self::$instance)) {
            $className      = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
    function get($indice, $parametro = "")
    {
        $this->clausula($indice, $parametro);
        if (isset($this->cadena_sql[$indice])) {
            return $this->cadena_sql[$indice];
        }
        return false;
    }
    private function clausula($indice, $parametro)
    {
        $prefijo = $this->context->getVariable("prefijo");
        switch ($indice) {
            case "usuario":
                $cadena_sql = "SELECT  ";
                $cadena_sql .= "usuario, ";
                $cadena_sql .= "nombre NOMBRE, ";
                $cadena_sql .= "apellido APELLIDO, ";
                $cadena_sql .= "estilo ";
                $cadena_sql .= "FROM ";
                $cadena_sql .= $prefijo . "usuario ";
                $cadena_sql .= "WHERE ";
                $cadena_sql .= "id_usuario='" . $parametro . "'";
                break;
            case "pagina":
                $cadena_sql = "SELECT  ";
                $cadena_sql .= $prefijo . "bloque_pagina.*,";
                $cadena_sql .= $prefijo . "bloque.nombre, ";
                $cadena_sql .= $prefijo . "pagina.parametro ";
                $cadena_sql .= "FROM ";
                $cadena_sql .= $prefijo . "pagina, ";
                $cadena_sql .= $prefijo . "bloque_pagina, ";
                $cadena_sql .= $prefijo . "bloque ";
                $cadena_sql .= "WHERE ";
                $cadena_sql .= $prefijo . "pagina.nombre='" . $parametro . "' ";
                $cadena_sql .= "AND ";
                $cadena_sql .= $prefijo . "bloque_pagina.id_bloque=" . $prefijo . "bloque.id_bloque ";
                $cadena_sql .= "AND ";
                $cadena_sql .= $prefijo . "bloque_pagina.id_pagina=" . $prefijo . "pagina.id_pagina";
                break;
            case "bloquesPagina":
                $cadena_sql = "SELECT  ";
                $cadena_sql .= $prefijo . "bloque_pagina.*,";
                $cadena_sql .= $prefijo . "bloque.nombre ,";
                $cadena_sql .= $prefijo . "pagina.parametro, ";
                $cadena_sql .= $prefijo . "bloque.grupo ";
                $cadena_sql .= "FROM ";
                $cadena_sql .= $prefijo . "pagina, ";
                $cadena_sql .= $prefijo . "bloque_pagina, ";
                $cadena_sql .= $prefijo . "bloque ";
                $cadena_sql .= "WHERE ";
                $cadena_sql .= $prefijo . "pagina.nombre='" . $parametro . "' ";
                $cadena_sql .= "AND ";
                $cadena_sql .= $prefijo . "bloque_pagina.id_bloque=" . $prefijo . "bloque.id_bloque ";
                $cadena_sql .= "AND ";
                $cadena_sql .= $prefijo . "bloque_pagina.id_pagina=" . $prefijo . "pagina.id_pagina ";
                $cadena_sql .= "ORDER BY " . $prefijo . "bloque_pagina.seccion," . $prefijo . "bloque_pagina.seccion ";
                break;
        }
        if (isset($cadena_sql)) {
            $this->cadena_sql[$indice] = $cadena_sql;
        }
    }
}
