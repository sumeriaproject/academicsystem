<?
if(!isset($GLOBALS["autorizado"]))
{
	include("index.php");
	exit;
}else{

	if(!empty($_REQUEST)){
	
    //consultar si existen notas asociadas, si existen no se puede eliminar el usuario
    
    $this->mensaje['error'][] = $cadena_sql=$this->sql->cadena_sql("notasByID",$id);
		$result=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");	

    if(is_array($result)){
      $this->mensaje['error'][] = "No puede eliminar un usuario con notas asociadas";
      return FALSE;
    }

		$cadena_sql=$this->sql->cadena_sql("userByID",$id);
		$result=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");	
		$variable['usuario']=$this->idSesion;
		$variable['evento']=json_encode(array("evento"=>"delete","datos"=>$result));
		 
		$cadena_sql=$this->sql->cadena_sql("DeleteUser",$id);
		$result=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"");
		if($result===TRUE){
			$cadena_sql=$this->sql->cadena_sql("logger",$variable);
			$result=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"");
			$status=TRUE;
		}else{
			$status=FALSE;
		}
	}
	
}
?>
