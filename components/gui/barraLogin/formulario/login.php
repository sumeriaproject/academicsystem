<?php

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}


	$esteBloque=$this->miConfigurador->getVariableConfiguracion("esteBloque");

	$nombreFormulario="loginForm";

	$token=strrev(($this->miConfigurador->getVariableConfiguracion("enlace")));

	$valorCodificado="action=".$esteBloque["nombre"];
	$valorCodificado.="&bloque=".$esteBloque["nombre"];
	$valorCodificado.="&opcionLogin=login";
	$valorCodificado.="&bloqueGrupo=".$esteBloque["grupo"];
	$valorCodificado=$this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);
	
	$directorio=$this->miConfigurador->getVariableConfiguracion("rutaUrlBloque")."/imagen/";
	$rutaTema=$this->miConfigurador->getVariableConfiguracion("host").$this->miConfigurador->getVariableConfiguracion("site")."/theme/".$this->miConfigurador->getVariableConfiguracion("tema");
	$tab=1;

	//--------------------  Enlace (<a>)(Login)-------------------------------------
	$esteCampo="loginButton";
	$atributos["id"]=$esteCampo;
	$atributos["enlace"]="";
	$atributos["enlaceTexto"]=$this->lenguaje->getCadena($esteCampo);
	$loginButton=$this->miFormulario->enlace($atributos);

	//--------------------  Enlace (Registro) -------------------------------------

	$miPaginaActual=$this->miConfigurador->getVariableConfiguracion("pagina");
	$enlace=$this->miConfigurador->getVariableConfiguracion("host").$this->miConfigurador->getVariableConfiguracion("site")."?".$this->miConfigurador->getVariableConfiguracion("enlace");

	$variable="pagina=registro";
	$registerEnlace=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable,$enlace);

	$variable="pagina=recuperarClave";
	$variable.="&jxajax=main";		
	$variable.="&bloque=recuperarClave";
	$variable.="&bloqueGrupo=gui";
	$forgotPass=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable,$enlace);


?><center>
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

						<input type="hidden" value="<?=$valorCodificado?>" id="formSaraData" name="formSaraData">
						
						</fieldset>
				
						<a style="cursor:pointer" onclick="showFormPass('<?=$forgotPass?>')">
							<span>¿Olvidó su clave?</span>
						</a>
					</form>
				</div>
			</div>
		</div>
	</center>




