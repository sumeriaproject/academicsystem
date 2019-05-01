<?php
include_once("core/manager/Context.class.php");
include_once("class/Array.class.php");

class Calendario{

	public $usuario;
	public $rol;

	public function __construct(){
		$this->context=Context::singleton();
		$this->organizador=orderArray::singleton();
		$this->resource=$this->context->fabricaConexiones->getRecursoDB("aplicativo");
		$this->prefijo=$this->context->getVariable("prefijo");
	}
	
	public function getEvento($idcurso,$evento){
		
		$cadena_sql  = " SELECT id_calendario ID FROM ";
    $cadena_sql .= " {$this->prefijo}calendario ";
    $cadena_sql .= " WHERE id_curso = '{$idcurso}' ";
    $cadena_sql .= " AND evento = '{$evento}' ";
    $cadena_sql .= " AND NOW() BETWEEN fecha_inicio AND fecha_fin ";
    
		$result = $this->resource->execute($cadena_sql,"busqueda");
    
    if(is_array($result)){
      return TRUE;
    }else{
      return FALSE;
    }
		
  }

  public function getMensaje($mensaje){
  
    switch($mensaje){
    
      case 'notas':
       
        $m = "Ya cerramos el Calendario Acad&eacute;mico para digitaci&oacute;n de Notas
              El Plazo m&aacute;ximo fue hasta el 21 de Noviembre de 2015
              ";
      
      break;
    
    }
    return $m;
  }
}
?>