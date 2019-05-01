<?php
if (!isset($GLOBALS["autorizado"])) {
    include("index.php");
    exit;
} else {
    
    if (!empty($_POST)) {
        
        $cadena_sql = $this->sql->cadena_sql("estudianteEnrollbyID", $id);
        $user       = $this->miRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        
        if (is_array($user)) {
            //update
            $cadena_sql = $this->sql->cadena_sql("updateEnrollbyID", $_REQUEST);
            $update     = $this->miRecursoDB->ejecutarAcceso($cadena_sql, "");
        } else {
            $cadena_sql = $this->sql->cadena_sql("insertEnrollbyID", $_REQUEST);
            $insert     = $this->miRecursoDB->ejecutarAcceso($cadena_sql, "");
            //insert  
        }
        
    } else {
        return $this->status = FALSE;
    }
    $this->mensaje['exito'][] = "La Matricula se actualizo correctamente";
    return $this->status = TRUE;
}