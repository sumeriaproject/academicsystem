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
        $nui                    = $variable['nui'] = strtoupper(trim($variable['nui']));

        if ($email <> "") {
            if (!$this->miInspectorHTML->isValidEmail($email)) {
                $this->mensaje['error'][] = "- Por favor ingresa un email valido";
            } else {
                $cadena_sql = $this->sql->cadena_sql("searchEmail", $email);
                $registro   = $this->miRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
                
                if (is_array($registro)) {
                    $this->mensaje['error'][] = "- Ya tenemos un registro con este correo.";
                }
            }
        }
        
        if ($password <> "") {
            if ($this->miInspectorHTML->minMaxRange(5, 25, $password)) {
                $this->mensaje['error'][] = "- La clave debe tener entre 5 y 25 caracteres";
            }
            if ($password != $confirm_pass) {
                $this->mensaje['error'][] = "- La confirmacion de la clave no coincide";
            }
        }
        
        if (!is_array($_REQUEST["role"])) {
            $this->mensaje['error'][] = "- Se debe seleccionar un rol para el usuario";
        }
        
        if ($_REQUEST["identificacion"] == "") {
            $this->mensaje['error'][] = "- El numero de identificacion es obligatorio";
        }
        
        $cadena_sql = $this->sql->cadena_sql("searchUserByIdent", $_REQUEST["identificacion"]);
        $registro   = $this->miRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
        
        if (is_array($registro)) {
            $this->mensaje['error'][] = "- Ya tenemos un registro con este numero de identificacion.";
        }
        
        if (count($this->mensaje['error']) == 0) {
            
            $_REQUEST['estado'] = isset($_REQUEST['estado']) ? $_REQUEST['estado'] : "1";
            
            $cadena_sql = $this->sql->cadena_sql("insertUser", $_REQUEST);
            $result     = $this->miRecursoDB->ejecutarAcceso($cadena_sql, "");
            
            $cadena_sql = $this->sql->cadena_sql("searchUserByIdent", $_REQUEST["identificacion"]);
            $id         = $this->miRecursoDB->ejecutarAcceso($cadena_sql, "busqueda");
            $id         = $id[0]['id_usuario'];
            
            $this->new_id = $id;
            
            $cadena_sql = $this->sql->cadena_sql("EliminarSubsistema", $id);
            $result     = $this->miRecursoDB->ejecutarAcceso($cadena_sql, "");
            
            if (is_array($_POST["role"])) {
                foreach ($_POST["role"] as $name => $value) {
                    $cadena_sql = $this->sql->cadena_sql("insertarSubsistema", array(
                        "id" => $id,
                        "subsistema" => $value
                    ));
                    $registro   = $this->miRecursoDB->ejecutarAcceso($cadena_sql, "");
                }
            }
            
            $cadena_sql = $this->sql->cadena_sql("EliminarCursos", $id);
            $result     = $this->miRecursoDB->ejecutarAcceso($cadena_sql, "");
            
            if (is_array($_POST["course"])) {
                foreach ($_POST["course"] as $name => $value) {
                    $cadena_sql = $this->sql->cadena_sql("insertarCurso", array(
                        "id" => $id,
                        "curso" => $value
                    ));
                    $registro   = $this->miRecursoDB->ejecutarAcceso($cadena_sql, "");
                }
            }
            $this->mensaje['exito'][] = "El registro fue exitoso";
            return $this->status = TRUE;
            
        } else {
            return $this->status = FALSE;
        }
    } else {
        return $this->status = FALSE;
    }
    return $this->status = TRUE;
}