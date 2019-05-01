<?php
if (!isset($GLOBALS["autorizado"])) {
    include("../index.php");
    exit;
}
include_once("core/manager/Context.class.php");
include_once("plugin/filter/Sql.class.php");
class generadorFiltros
{
    var $nombreBloque;
    var $miFuncion;
    var $miSql;
    var $context;
    public function __construct()
    {
        $this->context  = Context::singleton();
        $this->sql      = new SqlgeneradorFiltros();
        $ruta           = $this->context->getVariable("raizDocumento");
        $rutaURL        = $this->context->getVariable("host") . $this->context->getVariable("site");
        $conexion       = "aplicativo";
        $this->resource = $this->context->fabricaConexiones->getRecursoDB($conexion);
    }
    public function rescatarFiltros($identificador)
    {
        $cadena_sql = $this->sql->cadena_sql("buscarComponente", 'GTR_RESTAURANTES');
        $registro   = $this->resource->execute($cadena_sql, "busqueda");
        foreach ($registro as $clave => $valor) {
            $componentes[$valor['ID_COMPONENTE']]['OPCIONES'][]        = array(
                'VALUE' => $valor['ID_OPCION'],
                'NAME' => $valor['NOMBRE_OPCION']
            );
            $componentes[$valor['ID_COMPONENTE']]['NOMBRE_COMPONENTE'] = $valor['NOMBRE_COMPONENTE'];
            $componentes[$valor['ID_COMPONENTE']]['ID_COMPONENTE']     = $valor['ID_COMPONENTE'];
        }
        $html = "";
        foreach ($componentes as $clave => $valor) {
            $html .= "<label>";
            $html .= "<select name='filter-" . $valor['ID_COMPONENTE'] . "'>";
            $html .= "<option value='' selected>" . $valor['NOMBRE_COMPONENTE'] . "</option>";
            foreach ($valor['OPCIONES'] as $opcion => $valorOpcion) {
                $html .= "<option value='" . $valorOpcion['VALUE'] . "' >";
                $html .= $valorOpcion['NAME'];
                $html .= "</option>";
            }
            $html .= "</select>";
            $html .= "</label><br>";
        }
        return $html;
    }
    public function filterComponentList($component)
    {
        $cadena_sql = $this->sql->cadena_sql("buscarComponente", $component);
        $result     = $this->resource->execute($cadena_sql, "busqueda");
        return $result;
    }
    public function valuesListByID($id, $component, $defaul = "")
    {
        $cadena_sql = $this->sql->cadena_sql("valuesListByID", $component);
        $result     = $this->resource->execute($cadena_sql, "busqueda");
        return $result;
    }
}