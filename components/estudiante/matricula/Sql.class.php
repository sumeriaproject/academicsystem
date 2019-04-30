<?php
if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}

include_once("core/manager/Configurador.class.php");
include_once("core/connection/Sql.class.php");


class Sqlmatricula extends sql {


	var $miConfigurador;


	function __construct(){
		$this->miConfigurador=Configurador::singleton();
	}


	function cadenaSql($tipo,$variable="") {

		$prefijo=$this->miConfigurador->getVariableConfiguracion("prefijo");
		$idSesion=$this->miConfigurador->getVariableConfiguracion("id_sesion");

		if(isset($_REQUEST['sufijo']) && !empty($_REQUEST['sufijo'])) {
    	$sufijo = $_REQUEST['sufijo'];
  	}else {
  		$sufijo = "";
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
				$cadena_sql.=$prefijo."usuario_curso".$sufijo." uc ";
				$cadena_sql.="ON uc.id_usuario=u.id_usuario ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_curso ='".$variable."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="us.id_subsistema = 3 ";
				$cadena_sql.="AND ";
				$cadena_sql.="u.estado = 1 ";
				break;

			case "notaPorEstudiante":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_estudiante ID ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."nota_matricula ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_estudiante ='".$variable['estudiante']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="estado <> 0 ";
				break;

			case "notasEstudiantes":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_estudiante ID, ";
				$cadena_sql.="nota_periodo_1 NOT1, ";
				$cadena_sql.="nota_periodo_2 NOT2, ";
				$cadena_sql.="nota_periodo_3 NOT3, ";
				$cadena_sql.="nota_periodo_4 NOT4, ";
				$cadena_sql.="nota_periodo_f NOTF, ";
				$cadena_sql.="obs_periodo_1 OBS1, ";
				$cadena_sql.="obs_periodo_2 OBS2, ";
				$cadena_sql.="obs_periodo_3 OBS3, ";
				$cadena_sql.="obs_periodo_4 OBS4, ";
				$cadena_sql.="obs_periodo_f OBSF ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."nota_matricula nc ";
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.=$prefijo."usuario_curso".$sufijo." uc ";
				$cadena_sql.="ON nc.id_estudiante = uc.id_usuario ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="uc.id_curso ='".$variable."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="nc.estado = 1 ";
				break;

			case "insertarNotaFinal":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.=$prefijo."nota_competencia( ";
				$cadena_sql.="id_estudiante, ";
				$cadena_sql.="id_competencia, ";
				$cadena_sql.="nota_final, ";
				$cadena_sql.="nota_porcentual, ";
				$cadena_sql.="desempenio ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES( ";
				$cadena_sql.="'".$variable['estudiante']."', ";
				$cadena_sql.="'".$variable['competencia']."', ";
				$cadena_sql.="'".$variable['nota_final']."', ";
				$cadena_sql.="'".$variable['nota_porcentual']."', ";
				$cadena_sql.="'".$variable['desempenio']."' ";
				$cadena_sql.=") ";
				break;

			case "insertarNotaPerido":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.=$prefijo."nota_matricula( ";
				$cadena_sql.="id_estudiante, ";
				$cadena_sql.="nota_periodo_".$variable['periodo'].", ";
				$cadena_sql.="estado ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES( ";
				$cadena_sql.="'".$variable['estudiante']."', ";
				$cadena_sql.="'".$variable['nota']."', ";
				$cadena_sql.="'1' ";
				$cadena_sql.=") ";
				break;

			case "actualizarNotaPerido":
				$cadena_sql="UPDATE ";
				$cadena_sql.=$prefijo."nota_matricula ";
				$cadena_sql.="SET ";
				$cadena_sql.="nota_periodo_".$variable['periodo']." ='".$variable['nota']."' ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_estudiante ='".$variable['estudiante']."' ";
				break;

			case "insertarObsPerido":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.=$prefijo."nota_matricula( ";
				$cadena_sql.="id_estudiante, ";
				$cadena_sql.="obs_periodo_".$variable['periodo'].", ";
				$cadena_sql.="estado ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES( ";
				$cadena_sql.="'".$variable['estudiante']."', ";
				$cadena_sql.="'".$variable['obs']."', ";
				$cadena_sql.="'1' ";
				$cadena_sql.=") ";
				break;

			case "actualizarObsPerido":
				$cadena_sql="UPDATE ";
				$cadena_sql.=$prefijo."nota_matricula ";
				$cadena_sql.="SET ";
				$cadena_sql.="obs_periodo_".$variable['periodo']." ='".$variable['obs']."' ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_estudiante ='".$variable['estudiante']."' ";
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
		//echo "<br/><br/>".$cadena_sql;
		return $cadena_sql;

	}
}
?>
