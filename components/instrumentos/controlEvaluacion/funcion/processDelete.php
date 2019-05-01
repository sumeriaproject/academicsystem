<?
if(!isset($GLOBALS["autorizado"]))
{
	include("index.php");
	exit;
}else{

	if(!empty($_REQUEST)){
	
	
		
		$cadena_sql=$this->sql->cadena_sql("userByID",$id);
		$result=$this->resource->execute($cadena_sql,"busqueda");	
		$variable['usuario']=$this->idSesion;
		$variable['evento']=json_encode(array("evento"=>"delete","datos"=>$result));
		 
		$cadena_sql=$this->sql->cadena_sql("DeleteUser",$id);
		$result=$this->resource->execute($cadena_sql,"");
		if($result===TRUE){
			$cadena_sql=$this->sql->cadena_sql("logger",$variable);
			$result=$this->resource->execute($cadena_sql,"");
			$status=TRUE;
		}else{
			$status=FALSE;
		}
	}
	
}
?>
