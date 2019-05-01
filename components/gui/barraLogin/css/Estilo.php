<?php
$indice=0;
$estilo[$indice++]="login.css";
$estilo[$indice++]="jquery.ketchup.css";

$rutaBloque=$this->context->getVariable("host");
$rutaBloque.=$this->context->getVariable("site");

if($unBloque["grupo"]==""){
	$rutaBloque.="/components/".$unBloque["nombre"];
}else{
	$rutaBloque.="/components/".$unBloque["grupo"]."/".$unBloque["nombre"];
}

foreach ($estilo as $nombre){
	echo "<link rel='stylesheet' type='text/css' href='".$rutaBloque."/css/".$nombre."'>\n";

}
?>