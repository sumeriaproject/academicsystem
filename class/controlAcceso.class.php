<?php
include_once("core/manager/Context.class.php");
include_once("class/Array.class.php");

class controlAcceso{

	public $usuario;
	public $rol;

	public function __construct(){
		$this->context=Context::singleton();
		$this->organizador=orderArray::singleton();
		$this->resource=$this->context->fabricaConexiones->getRecursoDB("aplicativo");
		$this->prefijo=$this->context->getVariable("prefijo");
	}
	
	public function getGrados(){
		
		$cursos=$this->getCursosUsuario();
		$cursos=implode(",",array_keys($cursos));

		$cadena_sql="SELECT DISTINCT(id_grado) ID FROM {$this->prefijo}curso WHERE id_curso IN ({$cursos}) ";
		$result=$this->resource->execute($cadena_sql,"busqueda");
		$result=$this->organizador->orderKeyBy($result,"ID");
		
		$grados=implode(",",array_keys($result));
		
		$cadena_sql="SELECT id_grado ID,nombre_numero NOMBRE FROM {$this->prefijo}grado WHERE  id_grado IN ({$grados}) AND estado<>0";
		$result=$this->resource->execute($cadena_sql,"busqueda");
		$result=$this->organizador->orderKeyBy($result,"ID");
		return $result;
	}
	
	public function getCursosUsuario(){
		$cadena_sql="SELECT id_curso ID FROM {$this->prefijo}usuario_curso WHERE id_usuario={$this->usuario} ";
		$result=$this->resource->execute($cadena_sql,"busqueda");
		$result=$this->organizador->orderKeyBy($result,"ID");
		return $result;
	}	
  
	public function getAccesoCompleto(){
		$cadena_sql="SELECT 
	    uc.id_usuario USUARIO, 
	    c.id_curso CURSO_ID,
	    c.nombre CURSO_NOMBRE,
	    g.id_grado GRADO_ID,
	    g.nombre_numero GRADO_NOMBRE_NUMERO,
	    g.nombre_letra GRADO_NOMBRE_LETRA,
	    s.id_sede SEDE_ID,
	    s.nombre SEDE_NOMBRE
	    FROM notas_usuario_curso uc 
	    INNER JOIN notas_curso c ON uc.id_curso = c.id_curso 
	    INNER JOIN notas_sede s ON c.id_sede = s.id_sede
	    INNER JOIN notas_grado g ON c.id_grado = g.id_grado
	    AND uc.id_usuario = {$this->usuario} ";
	    
			$result=$this->resource->execute($cadena_sql,"busqueda");
		$result=$this->organizador->orderTwoKeyBy($result,"SEDE_ID","CURSO_ID");
		return $result;
	}
	
	public function getSedes(){
	
		$cursos=$this->getCursosUsuario();
		$cursos=implode(",",array_keys($cursos));
		
		$cadena_sql="SELECT DISTINCT(id_sede) ID FROM {$this->prefijo}curso WHERE id_curso IN ({$cursos}) ";
		$result=$this->resource->execute($cadena_sql,"busqueda");
		$result=$this->organizador->orderKeyBy($result,"ID");
		
		$sedes=implode(",",array_keys($result));
		
		$cadena_sql="SELECT id_sede ID,nombre NOMBRE FROM {$this->prefijo}sede WHERE  id_sede IN ({$sedes}) AND estado<>0 ";
		$result=$this->resource->execute($cadena_sql,"busqueda");
		$result=$this->organizador->orderKeyBy($result,"ID");
		return $result;	
	}
}
?>