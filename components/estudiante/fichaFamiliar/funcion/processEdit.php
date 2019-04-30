<?
if(!isset($GLOBALS["autorizado"]))
{
	include("index.php");
	exit;
}else{

	switch($option){
		case "processEditCompany":	
			$cadena_sql=$this->sql->cadena_sql("updateDataCompany",$_REQUEST);
			$result=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"");
			$this->mensaje="Datos Actualizados Correctamente";
			return $this->status=FALSE;
		break;
		case "processEditCommerce":	

			switch($_REQUEST['optionTab']){
				case "basic":
					$cadena_sql=$this->sql->cadena_sql("updateDataCommerceBasic",$_REQUEST);
					$result=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"");
					$this->mensaje="Datos Basicos Actualizados Correctamente";
				break;
				case "features":

					$cadena_sql=$this->sql->cadena_sql("deleteDataCommerceFeatures",$_REQUEST);
					$result=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"");

					foreach($_REQUEST['optionFeature'] as $value){
						$_REQUEST['optionValFeature']=$value;
						$cadena_sql=$this->sql->cadena_sql("insertDataCommerceFeatures",$_REQUEST);
						$result=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"");

					}

					$this->mensaje="Datos de descripcion Actualizados Correctamente";


				break;
				case "time":
					$cadena_sql=$this->sql->cadena_sql("updateDataCommerceTime",$_REQUEST);
					$result=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"");
					$this->mensaje="Datos de Calendario Actualizados Correctamente";
				break;
			}


			return $this->status=FALSE;
		break;


		default:
			$this->mensaje="Datos incorrectos";
			return $this->status=FALSE;
		break;
	}
	return $this->status=FALSE;


}
?>
