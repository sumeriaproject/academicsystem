<?php

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}
	
	$component = $this->context->getVariable("component");

	$dataLoginForm  = "action=".$component["nombre"];
	$dataLoginForm .= "&bloque=".$component["nombre"];
	$dataLoginForm .= "&opcionLogin=login";
	$dataLoginForm .= "&bloqueGrupo=".$component["grupo"];
	$dataLoginForm  = $this->context->fabricaConexiones->crypto->codificar($dataLoginForm);

?>
<center>
	<div id="loginContainer">
		<div id="loginBox2" style="display: block;">
			<div id="containerLoginBox">
				<form name="loginForm" title="Formulario" method="post" enctype="multipart/form-data" id="loginForm">
					<fieldset id="bodyForm">
			
					Usuario:<input type="text" tabindex="1" data-validate="validate(required, minlength(1))" maxlength="100" size="" id="atadusuario" name="atadusuario" class="cuadroTexto ">
					<br/>
					
					Clave:<input type="password" tabindex="2" data-validate="validate(required)" maxlength="100" size="" value="" id="atadclave" name="atadclave" class="cuadroTexto ">
				
					</br>
					<input type="submit" tabindex="3" class="buscar" name="botonAceptar" value="Aceptar">
					<input type="hidden" value="<?=$dataLoginForm?>" id="formSaraData" name="formSaraData">
					
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</center>




