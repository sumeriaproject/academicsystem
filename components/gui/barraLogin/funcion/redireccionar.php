<?php
if(!isset($GLOBALS["autorizado"]))
{
	include("index.php");
	exit;
}else{

	$miPaginaActual=$this->miConfigurador->getVariableConfiguracion("pagina");

	

	switch($opcion)
	{
		

		case "indexUsuario":

			if($miPaginaActual=="index"){
				$variable="pagina=".$valor['PAGINA'];
			}else{
				$variable="pagina=".$miPaginaActual;
			}

			$variable.="&opcionLogin=".$valor['OPCION'];
		break;

		case "index":
			$variable="pagina=index";
			$variable.="&mensaje=".$valor;

		break;
	
		case "paginaPrincipal":
			$variable="pagina=index";
			$variable.="&usuario=".$valor;
			$variable.="&mensaje=fallo Registro";
		break;
		
		


	}

	foreach($_REQUEST as $clave=>$valor)
	{
		unset($_REQUEST[$clave]);

	}

	$rutaURL=$this->miConfigurador->getVariableConfiguracion("host").$this->miConfigurador->getVariableConfiguracion("site");
	$enlace=$rutaURL."?".$this->miConfigurador->getVariableConfiguracion("enlace");
	$variable=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable,$enlace);

	echo "<script>location.replace('".$variable."')</script>";  


}

?>
