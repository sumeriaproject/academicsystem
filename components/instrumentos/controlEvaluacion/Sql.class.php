<?php
if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}

include_once("core/manager/Context.class.php");
include_once("core/connection/Sql.class.php");

class SqlcontrolEvaluacion extends sql {

	var $context;

	function __construct(){
		$this->context=Context::singleton();
	}

	function get($tipo,$variable="") {

		$prefijo=$this->context->getVariable("prefijo");
		$sessionId=$this->context->getVariable("id_sesion");
		$anioActivo = $this->context->getVariable("anio");

		if($anioActivo == $this->activeYear) {
			$sufijo = "";
		}else{
			$sufijo = "_".$this->activeYear;
		}

		switch($tipo) {

			case "notasDefinitivasPorCurso":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_nota_area ID, ";
				$cadena_sql.="id_estudiante ESTUDIANTE, ";
				$cadena_sql.="id_area AREA, ";
				$cadena_sql.="nota NOTA, ";
				$cadena_sql.="desempenio DESEMPENIO, ";
				$cadena_sql.="estado ESTADO ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."nota_area".$sufijo;
				$cadena_sql.=" WHERE ";
				$cadena_sql.="id_curso ='".$variable."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="estado = 1 ";
				break;

			case "notasCriteroPorCurso":
				$cadena_sql="SELECT ";
				$cadena_sql.="nf.id_nota_competencia ID, ";
				$cadena_sql.="nf.id_estudiante ESTUDIANTE, ";
				$cadena_sql.="nf.id_competencia COMPETENCIA, ";
				$cadena_sql.="nf.nota_final NOTA_FINAL, ";
				$cadena_sql.="nf.nota_porcentual NOTA_PORCENTUAL, ";
				$cadena_sql.="nf.desempenio DESEMPENIO ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."nota_competencia".$sufijo." nf ";
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.=$prefijo."usuario u ";
				$cadena_sql.="ON nf.id_estudiante=u.id_usuario ";
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.=$prefijo."usuario_curso".$sufijo." uc ";
				$cadena_sql.="ON uc.id_usuario=u.id_usuario ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_curso ='".$variable."' ";
		        if($sufijo == "") {
		          $cadena_sql.="AND ";
		          $cadena_sql.="u.estado = 1 ";
		        }
				break;

			case "notasCualitativaPorCurso":
				$cadena_sql="SELECT ";
				$cadena_sql.="nf.id_estudiante ESTUDIANTE, ";
				$cadena_sql.="nf.id_competencia COMPETENCIA, ";
				$cadena_sql.="nf.id_cualitativa NOTA_FINAL ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."nota_cualitativa".$sufijo." nf ";
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.=$prefijo."usuario u ";
				$cadena_sql.="ON nf.id_estudiante = u.id_usuario ";
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.=$prefijo."usuario_curso".$sufijo." uc ";
				$cadena_sql.="ON uc.id_usuario = u.id_usuario ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_curso ='".$variable."' ";
		        if($sufijo == "") {
		          $cadena_sql.="AND ";
		          $cadena_sql.="u.estado = 1 ";
		        }
				break;
	

			case "notas":
				$cadena_sql="SELECT ";
				$cadena_sql.="nf.id_nota_competencia ID, ";
				$cadena_sql.="nf.id_estudiante ESTUDIANTE, ";
				$cadena_sql.="nf.id_competencia COMPETENCIA, ";
				$cadena_sql.="nf.nota_final NOTA_FINAL, ";
				$cadena_sql.="nf.nota_porcentual NOTA_PORCENTUAL, ";
				$cadena_sql.="nf.desempenio DESEMPENIO ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."nota_competencia".$sufijo." nf ";
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.=$prefijo."usuario u ";
				$cadena_sql.="ON nf.id_estudiante=u.id_usuario ";
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.=$prefijo."usuario_curso".$sufijo." uc ";
				$cadena_sql.="ON uc.id_usuario=u.id_usuario ";
        if($sufijo==""){
          $cadena_sql.="WHERE ";
          $cadena_sql.="u.estado = 1 ";
        }
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
        if($sufijo==""){
          $cadena_sql.="AND ";
          $cadena_sql.="u.estado = 1 ";
        }
				break;

			case "estudiantes":
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

			case "cualitativasPorCompetencia":
				$cadena_sql="SELECT ";
				$cadena_sql.="ca.id_cualitativa ID, ";
				$cadena_sql.="ca.nombre NOMBRE, ";
				$cadena_sql.="ca.abreviacion ABREVIACION ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."cualitativa_evaluacion ca ";
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.=$prefijo."competencia c ";
				$cadena_sql.="ON c.id_area=ca.id_area ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="c.id_competencia ='".$variable."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="ca.estado <> 0 ";
				break;				

			case "notasPorCompetencia":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_estudiante ESTUDIANTE, ";
				$cadena_sql.="id_criterio CRITERIO, ";
				$cadena_sql.="nota NOTA ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."nota_criterio".$sufijo." ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_competencia ='".$variable['competencia']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="estado <> 0 ";
				break;

			case "notasFinalesCriteriosPorCompetencia":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_nota_competencia ID, ";
				$cadena_sql.="id_estudiante ESTUDIANTE, ";
				$cadena_sql.="nota_final NOTA_FINAL, ";
				$cadena_sql.="nota_porcentual NOTA_PORCENTUAL, ";
				$cadena_sql.="desempenio DESEMPENIO ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."nota_competencia".$sufijo." ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_competencia ='".$variable['competencia']."' ";
				$cadena_sql.="AND "; 
				$cadena_sql.="estado <> 0 ";
				break;

			case "notasFinalesCualitativasPorCompetencia":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_estudiante ESTUDIANTE, ";
				$cadena_sql.="id_cualitativa NOTA ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."nota_cualitativa".$sufijo." ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_competencia ='".$variable['competencia']."' ";
				$cadena_sql.="AND "; 
				$cadena_sql.="estado <> 0 ";
				break;

			case "actualizarNotaFinal":
				$cadena_sql="UPDATE ";
				$cadena_sql.=$prefijo."nota_competencia ";
				$cadena_sql.="SET nota_final='".$variable['nota_final']."', ";
				$cadena_sql.="nota_porcentual='".$variable['nota_porcentual']."', ";
				$cadena_sql.="desempenio='".$variable['desempenio']."' ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_competencia ='".$variable['competencia']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="id_estudiante ='".$variable['estudiante']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="id_nota_competencia ='".$variable['id_nota_final']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="estado <> 0 ";
				break;

			case "notaFinal":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_nota_competencia ID, ";
				$cadena_sql.="desempenio DESEMPENIO ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."nota_competencia".$sufijo." ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_competencia ='".$variable['competencia']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="id_estudiante ='".$variable['estudiante']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="estado <> 0 ";
				break;


			case "notasPorEstudianteyCompetencia":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_estudiante ESTUDIANTE, ";
				$cadena_sql.="id_criterio CRITERIO, ";
				$cadena_sql.="nota NOTA ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."nota_criterio".$sufijo." ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_competencia ='".$variable['competencia']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="id_estudiante ='".$variable['estudiante']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="estado <> 0 ";
				break;

			case "notaPorCriterio":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_criterio ID ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."nota_criterio".$sufijo." ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_estudiante ='".$variable['estudiante']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="id_competencia ='".$variable['competencia']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="id_criterio ='".$variable['criterio']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="estado <> 0 ";
				break;

			case "notaPorCualitativa":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_cualitativa ID ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."nota_cualitativa".$sufijo." ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_estudiante ='".$variable['estudiante']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="id_competencia ='".$variable['competencia']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="estado <> 0 ";
				break;

			case "insertarNotaCualitativa":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.=$prefijo."nota_cualitativa( ";
				$cadena_sql.="id_estudiante, ";
				$cadena_sql.="id_competencia, ";
				$cadena_sql.="id_cualitativa, ";
				$cadena_sql.="usuario ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES( ";
				$cadena_sql.="'".$variable['estudiante']."', ";
				$cadena_sql.="'".$variable['competencia']."', ";
				$cadena_sql.="'".$variable['nota']."', ";
				$cadena_sql.="'".$variable['usuario']."' ";
				$cadena_sql.=") ";
				break;

			case "actualizarNotaCualitativa":
				$cadena_sql="UPDATE ";
				$cadena_sql.=$prefijo."nota_cualitativa ";
				$cadena_sql.="SET ";
				$cadena_sql.="id_cualitativa = '".$variable['nota']."' ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_estudiante ='".$variable['estudiante']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="id_competencia ='".$variable['competencia']."' ";
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

			case "insertarNotaCriterio":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.=$prefijo."nota_criterio( ";
				$cadena_sql.="id_estudiante, ";
				$cadena_sql.="id_competencia, ";
				$cadena_sql.="id_criterio, ";
				$cadena_sql.="nota, ";
				$cadena_sql.="usuario ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES( ";
				$cadena_sql.="'".$variable['estudiante']."', ";
				$cadena_sql.="'".$variable['competencia']."', ";
				$cadena_sql.="'".$variable['criterio']."', ";
				$cadena_sql.="'".$variable['nota']."', ";
				$cadena_sql.="'".$variable['usuario']."' ";
				$cadena_sql.=") ";
				break;

			case "actualizarNotaCriterio":
				$cadena_sql="UPDATE ";
				$cadena_sql.=$prefijo."nota_criterio ";
				$cadena_sql.="SET ";
				$cadena_sql.="nota='".$variable['nota']."' ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_estudiante ='".$variable['estudiante']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="id_competencia ='".$variable['competencia']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="id_criterio ='".$variable['criterio']."' ";
				break;

			case "cursos":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_curso ID, ";
				$cadena_sql.="nombre NAME ";
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

			case "areasAll":
				$cadena_sql="SELECT ";
				$cadena_sql.="a.id_area ID, ";
				$cadena_sql.="a.nombre AREA ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."area a ";
				break;

			case "competencias":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_competencia ID, ";
				$cadena_sql.="competencia COMPETENCIA, ";
				$cadena_sql.="identificador IDENTIFICADOR, ";
				$cadena_sql.="desempenio_basico BASICO, ";
				$cadena_sql.="desempenio_alto ALTO, ";
				$cadena_sql.="desempenio_superior SUPERIOR, ";
				$cadena_sql.="id_area ID_AREA ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."competencia ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_grado ='".$variable."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="estado = '1' ";
				$cadena_sql.="ORDER BY periodo ASC ";
				break;

			case "competenciasAll":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_competencia ID, ";
				$cadena_sql.="competencia COMPETENCIA, ";
				$cadena_sql.="identificador IDENTIFICADOR, ";
				$cadena_sql.="desempenio_basico BASICO, ";
				$cadena_sql.="desempenio_alto ALTO, ";
				$cadena_sql.="desempenio_superior SUPERIOR, ";
				$cadena_sql.="id_area ID_AREA ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."competencia ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="estado = '1' ";
				$cadena_sql.="ORDER BY periodo ASC ";
				break;

			case "competenciaByID":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_competencia ID, ";
				$cadena_sql.="competencia COMPETENCIA, ";
				$cadena_sql.="identificador IDENTIFICADOR, ";
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

			case "competenciasAnteriores":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_competencia ID ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."competencia c ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="c.identificador < ".$variable['identificador']." ";
				$cadena_sql.="AND ";
				$cadena_sql.="c.id_area = '".$variable['area']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="c.id_grado = '".$variable['grado']."' ";
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
				$cadena_sql.="nombre NOMBRE, ";
				$cadena_sql.="tipo TIPO ";
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
