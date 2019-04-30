<?php

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}

include_once("core/manager/Configurador.class.php");
include_once("core/connection/Sql.class.php");

//Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
//en camel case precedida por la palabra sql

class SqlgeneradorFiltros extends sql {
	
	
	var $miConfigurador;
	
	
	function __construct(){
		$this->miConfigurador=Configurador::singleton();
	}
	

	function cadena_sql($tipo,$variable="") {
		 
		/**
		 * 1. Revisar las variables para evitar SQL Injection
		 *
		 */
		
		$prefijo=$this->miConfigurador->getVariableConfiguracion("prefijo");
		$idSesion=$this->miConfigurador->getVariableConfiguracion("id_sesion");
		 
		switch($tipo) {
			 
			/**
			 * Clausulas específicas
			 */
			 
			case "buscarComponente":
				$cadena_sql="SELECT  ";
				$cadena_sql.=$prefijo."filtro_componente.id_filtroComponente ID_COMPONENTE, ";
				$cadena_sql.=$prefijo."filtro_componente.nombre NOMBRE_COMPONENTE, ";
				$cadena_sql.=$prefijo."filtro_componente.name NAME_COMPONENTE, ";
				$cadena_sql.=$prefijo."filtro_opcion.id_filtroOpcion ID_OPCION, ";
				$cadena_sql.=$prefijo."filtro_opcion.nombre NOMBRE_OPCION ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."filtro_componente ";
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.=$prefijo."filtro_opcion ";
				$cadena_sql.="ON (".$prefijo."filtro_componente.id_filtroComponente = ".$prefijo."filtro_opcion.id_filtroComponente) ";
				$cadena_sql.="WHERE ";
				$cadena_sql.=$prefijo."filtro_componente.identificador='".$variable."' ";
				$cadena_sql.="AND ";
				$cadena_sql.=$prefijo."filtro_componente.estado=1 ";
				$cadena_sql.="AND ";
				$cadena_sql.=$prefijo."filtro_opcion.estado=1 ";
				break;
				
		}
		//echo "<br/>".$tipo."=".$cadena_sql;
		return $cadena_sql;

	}
}



?>
