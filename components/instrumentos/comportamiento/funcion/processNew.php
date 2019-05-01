<?
if(!isset($GLOBALS["autorizado"]))
{
	include("index.php");
	exit;
}else{

	if(!empty($_POST)){
	
		/*if(!is_array($_REQUEST["role"])){
			$this->mensaje['error'][] = "- Se debe seleccionar un rol para el usuario";
		}*/
		
		//End data validation
		if(count($this->mensaje['error']) == 0){	

			$cadena_sql=$this->sql->cadena_sql("insertarCompetencia",$_REQUEST);
			$result=$this->resource->execute($cadena_sql,"");

			return $this->status=TRUE;
			
		}else{

			return $this->status=FALSE;
		}
	}else{
	
		return $this->status=FALSE;

	}
	
	return $this->status=TRUE;


}
?>
