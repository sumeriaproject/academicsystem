<?
if(!isset($GLOBALS["autorizado"]))
{
	include("index.php");
	exit;
}else{

	$cadena_sql=$this->sql->cadena_sql("deleteDataCompany",$_REQUEST);
	$result=$this->resource->execute($cadena_sql,"");

	$cadena_sql=$this->sql->cadena_sql("deleteDataCommmerce",$_REQUEST);
	$result=$this->resource->execute($cadena_sql,"");
	
	//pendiente log 
	$this->mensaje="true";
	return $this->status=FALSE;
	

}
?>
