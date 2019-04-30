<?php

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}
  
  $esteBloque=$this->miConfigurador->getVariableConfiguracion("esteBloque");
  $enlace=$this->miConfigurador->getVariableConfiguracion("host").$this->miConfigurador->getVariableConfiguracion("site")."?".$this->miConfigurador->getVariableConfiguracion("enlace");
  
  if($this->miSesion->getValorSesion('idUsuario')<>""){
	
	$usuario_registro=$this->miSesion->getValorSesion('idUsuario');
	$linkAccount="pagina=myAccount";
	$linkAccount.="&user=".$usuario_registro;
	$linkAccount=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($linkAccount,$enlace);
		  
	  
  }else{
  
	  $usuario_registro=0; //0 ES POR DEFECTO EL USUARIO ANONIMO
  }
	
	$cadena_sql=$this->sql->cadena_sql("dataUserByID",$usuario_registro);
	$user=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda"); 

	$formSaraData="action=barraLogin";
	$formSaraData.="&bloque=barraLogin";
	$formSaraData.="&bloqueGrupo=gui";
	$formSaraData.="&opcionLogin=logout";
	$formSaraData=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($formSaraData,$enlace);
	
	$rutaTema=$this->miConfigurador->getVariableConfiguracion("host").$this->miConfigurador->getVariableConfiguracion("site")."/theme/".$this->miConfigurador->getVariableConfiguracion("tema");


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
		
		