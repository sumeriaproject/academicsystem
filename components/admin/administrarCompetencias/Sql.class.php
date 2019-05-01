<?php

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}

include_once("core/manager/Context.class.php");
include_once("core/connection/Sql.class.php");

//Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
//en camel case precedida por la palabra sql

class SqladministrarCompetencias extends sql {
	
	
	var $context;
	
	
	function __construct(){
		$this->context=Context::singleton();
	}
	

	function cadena_sql($tipo,$variable="") {
		 
		/**
		 * 1. Revisar las variables para evitar SQL Injection
		 *
		 */
		
		$prefijo=$this->context->getVariable("prefijo");
		$idSesion=$this->context->getVariable("id_sesion");
		 
		switch($tipo) {
			 

			case "areas":
				$cadena_sql="SELECT ";
				$cadena_sql.="a.id_area ID, ";
				$cadena_sql.="a.nombre AREA ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."area a ";
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.=$prefijo."area_grado ag ";
				$cadena_sql.="ON a.id_area = ag.id_area ";	
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.=$prefijo."grado g ";
				$cadena_sql.="ON g.id_grado = ag.id_grado ";	
				$cadena_sql.="WHERE "; 
				$cadena_sql.="g.id_grado ='".$variable."' ";				
				break;
				
			case "competencias":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_competencia ID, ";
				$cadena_sql.="identificador IDENTIFICADOR, ";
				$cadena_sql.="competencia COMPETENCIA, ";
				$cadena_sql.="desempenio_basico BASICO, ";
				$cadena_sql.="desempenio_alto ALTO, ";
				$cadena_sql.="desempenio_superior SUPERIOR, ";
				$cadena_sql.="periodo PERIODO, ";
				$cadena_sql.="id_area ID_AREA, "; 
				$cadena_sql.="estado ESTADO "; 
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."competencia ";
				$cadena_sql.="WHERE "; 
				$cadena_sql.="id_grado ='".$variable."' ";	
				$cadena_sql.="ORDER BY periodo ASC "; 				
				break;		
				
			case "competenciaByID":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_competencia ID, ";
				$cadena_sql.="identificador IDENTIFICADOR, ";
				$cadena_sql.="competencia COMPETENCIA, ";
				$cadena_sql.="desempenio_basico BASICO, ";
				$cadena_sql.="desempenio_alto ALTO, ";
				$cadena_sql.="desempenio_superior SUPERIOR, ";
				$cadena_sql.="periodo PERIODO, ";
				$cadena_sql.="id_grado GRADO, ";
				$cadena_sql.="id_area ID_AREA, "; 
				$cadena_sql.="estado ESTADO ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."competencia ";
				$cadena_sql.="WHERE "; 
				$cadena_sql.="id_competencia ='".$variable."' ";	
				break;		
				
			case "grados":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_grado ID, ";
				$cadena_sql.="nombre_letra NOMBRELETRA, ";
				$cadena_sql.="nombre_numero NOMBRENUMERO ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."grado ";
				$cadena_sql.="WHERE "; 
				$cadena_sql.="estado = '1' ";				
				break;
				
	
			case "actualizarCompetencia":
				$cadena_sql="UPDATE ";
				$cadena_sql.=$prefijo."competencia ";
				$cadena_sql.="SET ";
				$cadena_sql.="`competencia`='".$variable['competencia']."', ";
				$cadena_sql.="`desempenio_basico`='".$variable['dbasico']."', ";
				$cadena_sql.="`desempenio_alto`='".$variable['dalto']."', ";
				$cadena_sql.="`desempenio_superior`='".$variable['dsuperior']."', ";
				$cadena_sql.="`id_grado`='".$variable['grado']."', ";
				$cadena_sql.="`id_area`='".$variable['area']."', ";
				$cadena_sql.="`periodo`='".$variable['periodo']."', "; 
				$cadena_sql.="`identificador`='".$variable['identificador']."', "; 
				$cadena_sql.="`estado`='".$variable['estado']."' "; 
				$cadena_sql.="WHERE "; 
				$cadena_sql.="id_competencia ='".$variable['optionValue']."' ";	 
				break;
				
			case "insertarCompetencia":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.=$prefijo."competencia ";
				$cadena_sql.="( ";
				$cadena_sql.="`competencia`, ";
				$cadena_sql.="`desempenio_basico`, ";
				$cadena_sql.="`desempenio_alto`, ";
				$cadena_sql.="`desempenio_superior`, ";
				$cadena_sql.="`id_grado`, ";
				$cadena_sql.="`id_area`, ";
				$cadena_sql.="`periodo`, ";
				$cadena_sql.="`identificador`, ";
				$cadena_sql.="`estado` ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="( ";
				$cadena_sql.="'".$variable['competencia']."', ";
				$cadena_sql.="'".$variable['dbasico']."', ";
				$cadena_sql.="'".$variable['dalto']."', ";
				$cadena_sql.="'".$variable['dsuperior']."', ";
				$cadena_sql.="'".$variable['grado']."', ";
				$cadena_sql.="'".$variable['area']."', ";
				$cadena_sql.="'".$variable['periodo']."', "; 
				$cadena_sql.="'".$variable['identificador']."', "; 
				$cadena_sql.="'1' ";
				$cadena_sql.=")";
				break;

			


		}
		//echo "<br/><br/>".$cadena_sql;
		return $cadena_sql;

	}
}
?>
