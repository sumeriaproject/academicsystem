<?php
$indice=0;
$estilo[$indice++]="";
$rutaBloque  = $this->miConfigurador->getVariableConfiguracion("host");
$rutaBloque .= $this->miConfigurador->getVariableConfiguracion("site");

if($unBloque["grupo"]=="")
{
	$rutaBloque.="/components/".$unBloque["nombre"];
}
else
{
	$rutaBloque.="/components/".$unBloque["grupo"]."/".$unBloque["nombre"];
}

foreach ($estilo as $nombre){
	echo "<link rel='stylesheet' type='text/css' href='".$rutaBloque."/css/".$nombre."'>\n";
}
