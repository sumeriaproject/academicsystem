<?php

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}

include_once("core/manager/Configurador.class.php");
include_once("core/connection/Sql.class.php");

//Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
//en camel case precedida por la palabra sql

class SqlboletinFinal extends sql {


	var $miConfigurador;


	function __construct(){
		$this->miConfigurador=Configurador::singleton();
	}


	function cadenaSql($tipo,$variable="") {

		/**
		 * 1. Revisar las variables para evitar SQL Injection
		 *
		 */

		$prefijo    = $this->miConfigurador->getVariableConfiguracion("prefijo");
		$idSesion   = $this->miConfigurador->getVariableConfiguracion("id_sesion");
		$anioActivo = $this->miConfigurador->getVariableConfiguracion("anio");

		if($anioActivo == $this->activeYear) {
			$sufijo = "";
		}else{
			$sufijo = "_".$this->activeYear;
		}

		switch($tipo) {

      case "estudianteByID":
				$cadena_sql="SELECT ";
				$cadena_sql.="u.id_usuario ID, ";
				$cadena_sql.="u.nombre NOMBRE, ";
				$cadena_sql.="u.nombre2 NOMBRE2, ";
				$cadena_sql.="u.usuario CODIGO, ";
				$cadena_sql.="u.apellido APELLIDO, ";
				$cadena_sql.="u.apellido2 APELLIDO2 ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."usuario u ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_usuario ='".$variable."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="u.estado = 1 ";
				break;

			case "estudiantesPorCurso":
				$cadena_sql="SELECT ";
				$cadena_sql.="u.id_usuario ID, ";
				$cadena_sql.="u.nombre NOMBRE, ";
				$cadena_sql.="u.nombre2 NOMBRE2, ";
				$cadena_sql.="u.usuario CODIGO, ";
				$cadena_sql.="u.apellido APELLIDO, ";
				$cadena_sql.="u.apellido2 APELLIDO2 ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."usuario u ";
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.=$prefijo."usuario_subsistema us ";
				$cadena_sql.="ON us.id_usuario=u.id_usuario ";
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.=$prefijo."usuario_curso uc ";
				$cadena_sql.="ON uc.id_usuario=u.id_usuario ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_curso ='".$variable."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="us.id_subsistema = 3 ";
				$cadena_sql.="AND ";
				$cadena_sql.="u.estado = 1 ";
				break;


			case "criteriosPorCompetencia":
				$cadena_sql="SELECT ";
				$cadena_sql.="ce.id_criterio ID, ";
				$cadena_sql.="ce.nombre NOMBRE, ";
				$cadena_sql.="ce.grupo GRUPO, ";
				$cadena_sql.="ce.porcentaje PORCENTAJE ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."criterio_evaluacion ce ";
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.=$prefijo."competencia c ";
				$cadena_sql.="ON c.id_area=ce.id_area ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="c.id_competencia ='".$variable."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="ce.estado <> 0 ";
				break;

			case "notasPorCompetencia":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_nota_criterio ID, ";
				$cadena_sql.="id_estudiante ESTUDIANTE, ";
				$cadena_sql.="id_criterio CRITERIO, ";
				$cadena_sql.="nota NOTA ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."nota_criterio ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_competencia ='".$variable['competencia']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="estado <> 0 ";
				break;

			case "notasFinalesPorCompetencia":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_nota_competencia ID, ";
				$cadena_sql.="id_estudiante ESTUDIANTE, ";
				$cadena_sql.="nota_final NOTA_FINAL, ";
				$cadena_sql.="nota_porcentual NOTA_PORCENTUAL, ";
				$cadena_sql.="desempenio DESEMPENIO ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."nota_competencia".$sufijo;
				$cadena_sql.=" WHERE ";
				$cadena_sql.="id_competencia ='".$variable['competencia']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="estado <> 0 ";
				break;



			case "notaFinal":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_nota_competencia ID ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."nota_competencia".$sufijo;
				$cadena_sql.=" WHERE ";
				$cadena_sql.="id_competencia ='".$variable['competencia']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="id_estudiante ='".$variable['estudiante']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="estado <> 0 ";
				break;

			case "notasFinalesPorEstudianteCriterios":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_nota_competencia ID, ";
				$cadena_sql.="id_estudiante ESTUDIANTE, ";
				$cadena_sql.="id_competencia COMPETENCIA, ";
				$cadena_sql.="nota_final NOTA_FINAL, ";
				$cadena_sql.="nota_porcentual NOTA_PORCENTUAL, ";
				$cadena_sql.="desempenio DESEMPENIO ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."nota_competencia".$sufijo;
				$cadena_sql.=" WHERE ";
				$cadena_sql.="id_estudiante ='".$variable['estudiante']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="estado <> 0 ";
				break;


			case "notasFinalesPorEstudianteCualitativa":
				$cadena_sql="SELECT ";
				$cadena_sql.="nc.id_estudiante ESTUDIANTE, ";
				$cadena_sql.="nc.id_competencia COMPETENCIA, ";
				$cadena_sql.="ce.abreviacion NOTA_FINAL ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."nota_cualitativa".$sufijo." nc";
				$cadena_sql.=" INNER JOIN ";
				$cadena_sql.=$prefijo."cualitativa_evaluacion ce ";
				$cadena_sql.=" USING(id_cualitativa) ";
				$cadena_sql.=" WHERE ";
				$cadena_sql.="id_estudiante ='".$variable['estudiante']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="nc.estado <> 0 ";
				break;


			case "notasPorEstudianteyCompetencia":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_nota_criterio ID, ";
				$cadena_sql.="id_estudiante ESTUDIANTE, ";
				$cadena_sql.="id_criterio CRITERIO, ";
				$cadena_sql.="nota NOTA ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."nota_criterio ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_competencia ='".$variable['competencia']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="id_estudiante ='".$variable['estudiante']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="estado <> 0 ";
				break;

			case "notaPorCriterio":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_nota_criterio ID ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."nota_criterio ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_estudiante ='".$variable['estudiante']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="id_competencia ='".$variable['competencia']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="id_criterio ='".$variable['criterio']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="estado <> 0 ";
				break;

			case "cursos":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_curso ID ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."curso ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_sede ='".$variable['SEDE']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="id_grado ='".$variable['GRADO']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="estado <> 0 ";
				break;

			case "areas":
				$cadena_sql="SELECT ";
				$cadena_sql.="a.id_area ID, ";
				$cadena_sql.="a.nombre AREA, ";
				$cadena_sql.="a.tipo TIPO ";
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
				$cadena_sql.="id_area ID_AREA ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."competencia ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_grado ='".$variable."' ";
				$cadena_sql.="ORDER BY periodo ASC ";
				break;

			case "competenciaByID":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_competencia ID, ";
				$cadena_sql.="competencia COMPETENCIA, ";
				$cadena_sql.="desempenio_basico BASICO, ";
				$cadena_sql.="desempenio_alto ALTO, ";
				$cadena_sql.="desempenio_superior SUPERIOR, ";
				$cadena_sql.="periodo PERIODO, ";
				$cadena_sql.="id_grado GRADO, ";
				$cadena_sql.="id_area ID_AREA ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."competencia ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_competencia ='".$variable."' ";
				break;

			case "cursoByID":
				$cadena_sql="SELECT ";
				$cadena_sql.="nombre NOMBRE ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."curso ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_curso ='".$variable."' ";
				break;

			case "sedeByID":
				$cadena_sql="SELECT ";
				$cadena_sql.="nombre NOMBRE ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."sede ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_sede ='".$variable."' ";
				break;

			case "areaByID":
				$cadena_sql="SELECT ";
				$cadena_sql.="nombre NOMBRE ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."area ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_area ='".$variable."' ";
				break;

		}
		//echo "<br/><br/>$tipo = ".$cadena_sql;
		return $cadena_sql;

	}
}
?>
