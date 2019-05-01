<?php

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}
  
  $esteBloque=$this->context->getVariable("esteBloque");
  $enlace=$this->context->getVariable("host").$this->context->getVariable("site")."?".$this->context->getVariable("enlace");
  
  if($this->miSesion->getValorSesion('idUsuario')<>""){
	
	$usuario_registro=$this->miSesion->getValorSesion('idUsuario');
	$linkAccount="pagina=myAccount";
	$linkAccount.="&user=".$usuario_registro;
	$linkAccount=$this->context->fabricaConexiones->crypto->codificar_url($linkAccount,$enlace);
		  
	  
  }else{
  
	  $usuario_registro=0; //0 ES POR DEFECTO EL USUARIO ANONIMO
  }
	
	$cadena_sql=$this->sql->cadena_sql("dataUserByID",$usuario_registro);
	$user=$this->resource->execute($cadena_sql,"busqueda"); 

	$formSaraData="action=barraLogin";
	$formSaraData.="&bloque=barraLogin";
	$formSaraData.="&bloqueGrupo=gui";
	$formSaraData.="&opcionLogin=logout";
	$formSaraData=$this->context->fabricaConexiones->crypto->codificar_url($formSaraData,$enlace);
	
	$rutaTema=$this->context->getVariable("host").$this->context->getVariable("site")."/theme/".$this->context->getVariable("tema");


?>

	<div class="menu1">
	<ul>
		<li>BIENVENIDO <?=$user[0]['NOMBRE']?></li>
		<li><a href="<?=$formSaraData?>" >TERMINAR SESION</a></li>
		<li><a href="<?=$linkAccount?>" >MI CUENTA</a></li>

	</ul>
	<img src="<?=$rutaTema?>/image/face.png" style="width: 35px; float:left; position:relative;top: -35px;left: 974px;" />
	<img src="<?=$rutaTema?>/image/tw.png" style="width: 35px; float:left;position:relative;top:-35px;left: 891px;" />
	</div>
		
		