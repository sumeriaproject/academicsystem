<?php
if (!isset($GLOBALS["autorizado"])) {
    include("index.php");
    exit;
} else {
    if (!empty($_POST)) {
        $errors                 = array();
        $email                  = isset($_REQUEST["email"]) ? trim($_REQUEST["email"]) : "";
        $this->data['nombre']   = $nombre = trim($_POST["nombre"]);
        $this->data['apellido'] = $apellido = trim($_POST["apellido"]);
        $password               = trim($_POST["password"]);
        $confirm_pass           = trim($_POST["passwordc"]);
        $birthday               = "";
        $_REQUEST['id_usuario'] = $id;
        
        if ($_REQUEST["identificacion"] == "") {
            $this->mensaje['error'][] = "- El numero de identificacion es obligatorio";
        }
        
        $cadena_sql = $this->sql->cadena_sql("searchUserByIdandUser", $_REQUEST);
        $registro   = $this->resource->execute($cadena_sql, "busqueda");
        
        if (is_array($registro)) {
            $this->mensaje['error'][] = "- Ya tenemos un registro con este numero de identificacion.";
        }
        
        if ($password <> "") {
            if ($this->miInspectorHTML->minMaxRange(5, 25, $password)) {
                $this->mensaje['error'][] = "- La clave debe tener entre 5 y 25 caracteres";
            }
            if ($password != $confirm_pass) {
                $this->mensaje['error'][] = "- La confirmacion de la clave no coincide";
            }
        }
        //End data validation
        if (count($this->mensaje['error']) == 0) {
            
            $cadena_sql = $this->sql->cadena_sql("getCursoById", $_POST["course"]);
            $result     = $this->resource->execute($cadena_sql, "");
            
            $variable['usersede'] = $result[0]['IDSEDE'];
            
            $cadena_sql = $this->sql->cadena_sql("estudiantebyID", $_REQUEST['id_usuario']);
            $datalog    = $this->resource->execute($cadena_sql, "busqueda");
            
            $logger            = array();
            $logger['usuario'] = $this->idSesion;
            $logger['evento']  = json_encode(array(
                "evento" => "modify",
                "datos" => $datalog
            ));
            
            $cadena_sql = $this->sql->cadena_sql("logger", $logger);
            $datalog    = $this->resource->execute($cadena_sql, "");
            
            $cadena_sql = $this->sql->cadena_sql("actualizarRegistro", $_REQUEST);
            $result     = $this->resource->execute($cadena_sql, "");
            
            $cadena_sql = $this->sql->cadena_sql("EliminarSubsistema", $id);
            $result     = $this->resource->execute($cadena_sql, "");
            
            if (is_array($_POST["role"])) {
                foreach ($_POST["role"] as $name => $value) {
                    $cadena_sql = $this->sql->cadena_sql("insertarSubsistema", array(
                        "id" => $id,
                        "subsistema" => $value
                    ));
                    $registro   = $this->resource->execute($cadena_sql, "");
                }
            }
           
            $cadena_sql = $this->sql->cadena_sql("EliminarCursos", $id);
            $result     = $this->resource->execute($cadena_sql, "");
            
            if ($id <> "") {
                $cadena_sql = $this->sql->cadena_sql("insertarCurso", array(
                    "id" => $id,
                    "curso" => $_POST["course"]
                ));
                $registro   = $this->resource->execute($cadena_sql, "");
            }
            
            $this->mensaje['exito'][] = "El estudiante se actualizo correctamente";
            return $this->status = TRUE;
            
        } else {
            return $this->status = FALSE;
        }
    } else {
        return $this->status = FALSE;
    }
    return $this->status = TRUE;
}