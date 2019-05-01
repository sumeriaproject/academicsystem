<?php
class Inspector{
	
	private static $instance;
	
	
	//Constructor
	function __construct(){
	
	}
	
	
	public static function singleton()
	{
		if (!isset(self::$instance)) {
			$className = __CLASS__;
			self::$instance = new $className;
		}
		return self::$instance;
	}
	
	function cleanPHPHTML($arreglo, $excluir=""){
		
		if($excluir!=""){
			$variables=explode("|",$excluir);
		}else{
			$variables[0]="";
		}
		
		foreach ($arreglo as $clave => $valor){

			if(!in_array($clave,$variables)){
				if(!is_array($valor)){
					$arreglo[$clave]= strip_tags($valor);
				}else{
					foreach ($valor as $clavevalor => $valorvalor){
						$arreglo[$clave][$clavevalor]= strip_tags($valorvalor);
					}
				}
			}
		}		
		
		return $arreglo;
		
	}
	
	
	function cleanSQL($arreglo, $excluir=""){
	
		if($excluir!=""){
			$variables=explode("|",$excluir);
		}else{
			$variables[0]="";
		}
	
		foreach ($arreglo as $clave => $valor)
		{
			if(!is_array($valor)){
				$arreglo[$clave]= strip_tags($valor);
			}else{
				foreach ($valor as $clavevalor => $valorvalor){
					$arreglo[$clave][$clavevalor]= strip_tags($valorvalor);
				}
			}
		}
	
		return $arreglo;
	
	}

	function isValidEmail($email)
	{
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return true;
		}
		else {
			return false;
		}
	}

	function minMaxRange($min, $max, $what)
	{
		if(strlen(trim($what)) < $min)
			return true;
		else if(strlen(trim($what)) > $max)
			return true;
		else
		return false;
	}

	
	
}
