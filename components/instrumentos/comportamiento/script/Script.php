<?php
$indice=0;

$embebido[$indice]=true;
$funcion[$indice++]="function.js";

$rutaBloque=$this->context->getVariable("host");
$rutaBloque.=$this->context->getVariable("site");

if($unBloque["grupo"]==""){
	$rutaBloque.="/components/".$unBloque["nombre"];
}else{
	$rutaBloque.="/components/".$unBloque["grupo"]."/".$unBloque["nombre"];
}	

foreach ($funcion as $clave=>$nombre){
	if(!isset($embebido[$clave])){
		echo "\n<script type='text/javascript' src='".$rutaBloque."/script/".$nombre."'>\n</script>\n";
	}else{
		echo "\n<script type='text/javascript'>";
		include($nombre);
		echo "\n</script>\n";
	}
}
