<?php
if(!isset($GLOBALS["autorizado"]))
{
	include("index.php");
	exit;
}else{

	if(!empty($_POST)){

 		if(!isset($variable["periodo"])){
			$this->mensaje['error'][] = "- El periodo es obligatorio";
		}

		if(!isset($variable["competencia"])){
			$this->mensaje['error'][] = "- Por favor escribe un texto para esta competencia";
		}

		if(!isset($variable["identificador"])){
			$this->mensaje['error'][] = "- Se requiere un identificador numerico para esta competencia";
		}
		
		//End data validation
		if(count($this->mensaje['error']) == 0){	

			$cadena_sql=$this->sql->get("insertarCompetencia",$variable);
			$result=$this->resource->execute($cadena_sql,"");

			return $this->status = TRUE;
			
		}else{

			return $this->status = FALSE;
		}
	}else{
	
		return $this->status = FALSE;

	}
	
	return $this->status = TRUE;


}
?>
