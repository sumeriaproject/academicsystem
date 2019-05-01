<?php

$host=$this->context->getVariable("host");
$sitio=$this->context->getVariable("site");
$indice=0;
$funcion[$indice++]="funciones.js";

if(isset($_REQUEST["jquery"])) {
	$funcion[$indice++]="jquery.js";
}

if(isset($_REQUEST["jquery-ui"])) {
	$funcion[$indice++]="jquery-ui/jquery-ui.js";
	$funcion[$indice++]="jquery-ui/jquery-ui-timepicker-addon.js";
}

foreach ($funcion as $nombre){
	echo "<script type='text/javascript' src='".$host.$sitio."/plugin/scripts/javascript/".$nombre."'></script>";
}

?>