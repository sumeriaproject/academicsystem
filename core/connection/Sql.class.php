<?php
include_once("core/manager/Context.class.php");
class Sql
{
    var $context;
    function __construct()
    {
        $this->context = Context::singleton();
    }
    function sql()
    {
        $this->propiedades_dbms = array(
            'mysql' => array(
                'etiqueta' => 'MySQL 3.x',
                'arquitectura' => 'mysql',
                'delimitador' => ';;;',
                'delimitador_basico' => ';;;',
                'comentario' => '#'
            )
        );
    }
    function limpiarVariables($variable, $conexion)
    {
        $recursoDB = $this->context->fabricaConexiones->getRecursoDB($conexion);
        return $recursoDB->limpiarVariable($variable);
    }
    function remover_marcas(&$archivo_sql, $dbms)
    {
        $lineas        = explode("\n", $archivo_sql);
        $archivo_sql   = "";
        $simbolo       = $this->propiedades_dbms[$dbms]['comentario'];
        $contar_lineas = count($lineas);
        for ($contador = 0; $contador < $contar_lineas; $contador++) {
            $this->cadena = trim($lineas[$contador]);
            if ($this->cadena) {
                $comparacion = strstr($this->cadena, $simbolo);
            } else {
                $comparacion = TRUE;
            }
            if (!$comparacion) {
                $archivo_sql .= $this->cadena . "\n";
            }
        }
        unset($lineas);
        return $archivo_sql;
    }
    function rescatar_get($sql, $dbms)
    {
        $delimitador   = $this->propiedades_dbms[$dbms]['delimitador'];
        $instruccion   = explode($delimitador, $sql);
        $sql           = "";
        $contar_lineas = count($instruccion);
        $comentario    = FALSE;
        $contador_2    = 0;
        for ($contador = 0; $contador < $contar_lineas; $contador++) {
            if (strlen($instruccion[$contador]) > 5) {
                $sql[$contador_2] = trim($instruccion[$contador]) . "\n";
                $contador_2++;
            }
        }
        return $sql;
    }
}