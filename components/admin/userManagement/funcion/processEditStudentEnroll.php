<?php
if (!isset($GLOBALS["autorizado"])) {
    include("index.php");
    exit;
} else {
    
    if (!empty($_POST)) {
        
        $cadena_sql = $this->sql->get("estudianteEnrollbyID", $id);
        $user       = $this->resource->execute($cadena_sql, "busqueda");
        
        if (is_array($user)) {
            //update
            $cadena_sql = $this->sql->get("updateEnrollbyID", $_REQUEST);
            $update     = $this->resource->execute($cadena_sql, "");
        } else {
            $cadena_sql = $this->sql->get("insertEnrollbyID", $_REQUEST);
            $insert     = $this->resource->execute($cadena_sql, "");
            //insert  
        }
        
    } else {
        return $this->status = FALSE;
    }
    $this->mensaje['exito'][] = "La Matricula se actualizo correctamente";
    return $this->status = TRUE;
}