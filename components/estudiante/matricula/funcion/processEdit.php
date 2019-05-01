<?
if(!isset($GLOBALS["autorizado"]))
{
	include("index.php");
	exit;
}else{
	if(!empty($_POST)){
		if(count($this->mensaje['error']) == 0){	
			$cadena_sql=$this->sql->cadena_sql("actualizarCompetencia",$variable);
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
