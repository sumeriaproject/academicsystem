<?php
if(!$this->context->getVariable("estilo")){
	
	$this->context->setVariable("estilo","basico");
}

if(!$this->context->getVariable("idioma")){
	$this->context->setVariable("idioma", "es_es");
}

/**
 * I18n
 */

$miIdioma=$this->context->getVariable("raizDocumento")."/core/locale/";
$miIdioma.=$this->context->getVariable("idioma")."/LC_MESSAGES/Mensaje.page.php";
include $miIdioma;

$indice=strpos($_SERVER["REQUEST_URI"], "/index.php");

if($indice===false){
	$indice=strpos($_SERVER["REQUEST_URI"], "/",1);

}
$sitio=substr($_SERVER["REQUEST_URI"],0,$indice);

$_REQUEST["jquery"]=true;
?>
<html>
<head>
<title></title>
<?php include_once $this->context->getVariable("raizDocumento")."/plugin/scripts/Script.php"?>
<script>
$(window).load(function() {
	$("#mensaje").fadeIn(1000);
	
});

	  
	
</script>
<?php include_once $this->context->getVariable("raizDocumento")."/theme/".$this->context->getVariable("estilo")."/Estilo.php"?>

<meta content="text/html;" http-equiv="content-type" charset="utf-8">
</head>
<body>
	<div id="mensaje" class="<?php echo $tipoMensaje ?> shadow ocultar" ><?php
		echo $this->idioma[$mensaje];
		
	?></div>
</body>
</html>
