<?
if(!isset($GLOBALS["autorizado"]))
{
	include("index.php");
	exit;
}else{

	$miPaginaActual=$this->context->getVariable("pagina");
	switch($option)
	{

		case "confirmarNuevo":
			$variable="pagina=".$miPaginaActual;
			$variable.="&opcion=confirmar";
			if($valor!=""){
				$variable.="&id_sesion=".$valor;
			}
			break;

		case "confirmacionEditar":
			$variable="pagina=userManagement";
			$variable.="&opcion=confirmarEditar";
			if($valor!=""){
				$variable.="&registro=".$valor;
			}
			break;

		case "exitoRegistro":
			$variable="pagina=userManagement";
			$variable.="&tema=admin";
			$variable.="&opcion=nuevo";
			$variable.="&mensaje=".$valor[0];
			break;

		case "falloRegistro":
			$variable="pagina=userManagement";
			$variable.="&tema=admin";
			$variable.="&option=new";
			$variable.="&mensaje=".$valor[0];
			foreach($valor[1] as $clave=>$contenido){
				$variable.="&".$clave."=".$contenido;
			}
			break;

		case "exitoEdicion":
			$variable="pagina=userManagement";
			$variable.="&opcion=mostrar";
			$variable.="&mensaje=exitoEdicion";
			break;

		case "falloEdicion":
			$variable="pagina=userManagement";
			$variable.="&opcion=mostrar";
			$variable.="&mensaje=falloRegistro";
			break;

		case "paginaPrincipal":
			$variable="pagina=index";
			break;


	}

	foreach($_REQUEST as $clave=>$valor)
	{
		unset($_REQUEST[$clave]);

	}

	$enlace=$this->context->getVariable("enlace");
	$variable=$this->context->fabricaConexiones->crypto->codificar($variable);

	$_REQUEST[$enlace]=$variable;
	$_REQUEST["recargar"]=true;

}

?>
