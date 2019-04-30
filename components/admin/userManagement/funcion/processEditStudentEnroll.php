<?php
if(!isset($GLOBALS["autorizado"])) {
	include("index.php");
	exit;
}else {
	
  if(!empty($_POST)) {

			$cadena_sql = $this->sql->cadena_sql("estudianteEnrollbyID",$id);
			$user       = $this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
      
      if(is_array($user)){
          //update
          $cadena_sql = $this->sql->cadena_sql("updateEnrollbyID",$_REQUEST);
          $update     = $this->miRecursoDB->ejecutarAcceso($cadena_sql,""); 
      } else {
          $cadena_sql = $this->sql->cadena_sql("insertEnrollbyID",$_REQUEST);
          $insert     = $this->miRecursoDB->ejecutarAcceso($cadena_sql,"");
          //insert  
      }

			/*id_usuario
      tipo_estudiante	 
      tipo_documento	 
      dept_exp_doc	 
      munc_exp_doc	 
      genero	 
      dept_nacimiento	 
      munc_nacimiento	 
      dir_residencia	 
      barr_residencia	 
      dept_residencia	 
      munc_residencia	 
      zona_residencia	 
      ult_anio	 
      ult_grado	 
      ult_plantel	 
      ult_estado	 
      ult_interno
      grado_ingresa	 
      nivel_ingresa	 
      sede_ingresa	 
      docente_ingresa	 
      EPS	 
      IPS	 
      tipo_sangre	 
      rh_sangre	 
      tipo_victima	 
      dept_expulsion	 
      munc_expulsion	 
      fecha_expulsion	 
      certif_expulsion
      discapacidad	 
      acud_nombre
      acud_nombre2	 
      acud_apellido
      acud_apellido2	 
      acud_tipo_documento	 
      acud_documento	 
      acud_dept_exp_doc	 
      acud_munc_exp_doc	 
      acud_parentezco	 
      acud_tipo	 
      acud_dir_residencia	 
      acud_telefono1	 
      acud_telefono2	 
      estado*/
     // exit;

	}else {
			return $this->status=FALSE;
	}
  $this->mensaje['exito'][] = "La Matricula se actualizo correctamente";
	return $this->status=TRUE;
}
?>
