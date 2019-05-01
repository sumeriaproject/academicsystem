<?php
  ini_set ('display_errors','0');
if(isset($_REQUEST['kipu-development'])){
  ini_set ('display_errors','1');
}


require_once("core/manager/Context.class.php");
require_once("core/builder/builderSql.class.php");
include_once("core/auth/Sesion.class.php");


class ArmadorPagina{

	var $context;
	var $generadorClausulas;
	var $host;
	var $sitio;
	var $raizDocumentos;
	var $bloques;
	var $seccionesDeclaradas;

	function __construct(){

		$this->context=Context::singleton();
		$this->generadorClausulas=BuilderSql::singleton();
		$this->host=$this->context->getVariable("host");
		$this->sitio=$this->context->getVariable("site");
		$this->raizDocumentos=$this->context->getVariable("raizDocumento");
		$this->miSesion=Sesion::singleton();
		$this->enlace=$this->context->getVariable("host").$this->context->getVariable("site")."?".$this->context->getVariable("enlace");
		$conexion="aplicativo";
		$this->resource=$this->context->fabricaConexiones->getRecursoDB($conexion);

	}

	function armarHTML($registroBloques){

		$this->bloques=$registroBloques;

		if($this->context->getVariable("cache")) {

			//De forma predeterminada las paginas del aplicativo no tienen cache
			header("Cache-Control: cache");
			// header("Expires: Sat, 20 Jun 1974 10:00:00 GMT");
		}

		$this->raizDocumento=$this->context->getVariable("raizDocumento");

		echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"> ';
		echo "\n<html lang='es'>\n";
		$this->encabezadoPagina();
		$this->cuerpoPagina();
		echo "</html>\n";
	}

	private function encabezadoPagina(){
		$htmlPagina="<head>\n";
		$htmlPagina.="<title>".$this->context->getVariable("nombreAplicativo")."</title>\n";
		$htmlPagina.="<meta http-equiv='Content-Type' content='text/html; charset=utf-8' >\n";
		$htmlPagina.="<link rel='shortcut icon' href='".$this->host.$this->sitio."/"."favicon.ico' >\n";
		echo $htmlPagina;


		// Enlazar los estilos definidos en cada bloque
		foreach($this->bloques as $unBloque){
			$this->incluirEstilosBloque($unBloque);
		}

		//Funciones javascript globales del aplicativo
		include_once("plugin/scripts/Script.php");

		// Insertar las funciones js definidas en cada bloque
		foreach($this->bloques as $unBloque){
			$this->incluirFuncionesBloque($unBloque);
		}

		// Para las páginas que requieren jquery
		if(isset($_REQUEST["jquery"])){

			$this->incluirFuncionReady($unBloque);
		}

		echo "</head>\n";
	}



	private function cuerpoPagina() {


		echo "<body>\n";

		if(isset($_REQUEST["tema"]) && $_REQUEST["tema"]<>""){
			$this->context->setVariable("tema",$_REQUEST["tema"]);

		}else{
			$tema=$this->miSesion->getValorSesion("tema");

			if($tema<>""){
				$this->context->setVariable("tema",$tema);
			}

		}


		$valor=$this->variablesTema();
		$mensaje=isset($_REQUEST["mensaje"])?$_REQUEST["mensaje"]:"";
		$nombreUsuario=$userName=$valor["nombreUsuario"];
		$linkFin=$linkEnd=$valor["linkFinSesion"];
		$this->context->setVariable("linkEnd",$linkEnd);

		$rutaTema=$this->context->getVariable("host").$this->context->getVariable("site")."/theme/".$this->context->getVariable("tema");




		foreach($this->bloques as $unBloque){
			$salida[$unBloque["seccion"]]=$this->incluirBloque($unBloque);
		}

		include($this->raizDocumentos."/theme/".$this->context->getVariable("tema")."/template.php");


		echo "</body>\n";

	}

	private function variablesTema(){

		//mensaje
		if(isset($_REQUEST["mensaje"]) && $_REQUEST["mensaje"]<>""){
			$valor['mensaje']=$_REQUEST['mensaje'];
		}else{
			$valor['mensaje']="";
		}
		//Link Terminar Sesion ***************************************************************************

		$formSaraData="action=barraLogin";
		$formSaraData.="&bloque=barraLogin";
		$formSaraData.="&bloqueGrupo=gui";
		$formSaraData.="&opcionLogin=logout";
		$formSaraData=$this->context->fabricaConexiones->crypto->codificar_url($formSaraData,$this->enlace);

		$valor['linkFinSesion']=$formSaraData;

		//Usuario Actual *********************************************************************************

		  if($this->miSesion->getValorSesion('idUsuario')<>""){

			  $usuario_registro=$this->miSesion->getValorSesion('idUsuario');

		  }else{

			  $usuario_registro=0; //0 ES POR DEFECTO EL USUARIO ANONIMO
		  }

		$cadena=$this->generadorClausulas->cadenaSql("usuario",$usuario_registro);
		$usuario=$this->resource->execute($cadena,"busqueda");

		if(is_array($usuario)){
			$valor['nombreUsuario']=$usuario[0]['NOMBRE'];
		}else{
			$valor['nombreUsuario']='';
		}


		//Link Mi cuenta **********************************************************************************

		return $valor;
	}




	private function incluirBloque($unBloque){

		foreach($unBloque as $clave=>$valor){
			$unBloque[$clave]=trim($valor);
		}

		if($unBloque["grupo"]==""){
			$archivo=$this->raizDocumentos."/components/".$unBloque["nombre"]."/bloque.php";
		}else{
			$archivo=$this->raizDocumentos."/components/".$unBloque["grupo"]."/".$unBloque["nombre"]."/bloque.php";
		}
		return $archivo;
	}




	private function incluirEstilosBloque($unBloque){

		foreach($unBloque as $clave=>$valor){
			$unBloque[$clave]=trim($valor);
		}

		if($unBloque["grupo"]==""){
			$archivo=$this->raizDocumentos."/components/".$unBloque["nombre"]."/css/Estilo.php";
		}else{
			$archivo=$this->raizDocumentos."/components/".$unBloque["grupo"]."/".$unBloque["nombre"]."/css/Estilo.php";
		}

		if(file_exists($archivo)){
			include_once($archivo);
		}
	}


	private function incluirFuncionesBloque($unBloque){

		foreach($unBloque as $clave=>$valor){
			$unBloque[$clave]=trim($valor);
		}

		if($unBloque["grupo"]==""){
			$archivo=$this->raizDocumentos."/components/".$unBloque["nombre"]."/script/Script.php";
		}else{
			$archivo=$this->raizDocumentos."/components/".$unBloque["grupo"]."/".$unBloque["nombre"]."/script/Script.php";
		}

		if(file_exists($archivo)){

			include_once($archivo);
		}
	}

	function incluirFuncionReady($unBloque){

		echo "<script type='text/javascript'>\n";
		echo "$(document).ready(function(){\n";

		foreach($this->bloques as $unBloque){

			foreach($unBloque as $clave=>$valor){
				$unBloque[$clave]=trim($valor);
			}

			if($unBloque["grupo"]==""){
				$archivo=$this->raizDocumentos."/components/".$unBloque["nombre"]."/script/ready.js";
			}else{
				$archivo=$this->raizDocumentos."/components/".$unBloque["grupo"]."/".$unBloque["nombre"]."/script/ready.js";
			}

			if(file_exists($archivo)){

				include($archivo);
				echo "\n";

			}
		}
		echo "});\n";
		echo "</script>\n";

	}





	private function armar_no_pagina($seccion,$cadena) {
		$this->la_cadena=$cadena.' AND '.$this->context->configuracion["prefijo"].'bloque_pagina.seccion="'.$seccion.'" ORDER BY '.$this->context->configuracion["prefijo"].'bloque_pagina.posicion ASC';
		$this->base->registro_db($this->la_cadena,0);
		$this->armar_registro=$this->base->getRegistroDb();
		$this->total=$this->base->obtener_conteo_db();
		if($this->total>0) {


			for($this->contador=0;$this->contador<$this->total;$this->contador++) {

				$this->id_bloque=$this->armar_registro[$this->contador][0];
				$this->incluir=$this->armar_registro[$this->contador][4];
				include($this->context->configuracion["raiz_documento"].$this->context->configuracion["bloques"]."/".$this->incluir."/bloque.php");


			}


		}
		return TRUE;

	}
}
