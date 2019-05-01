<?php
if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}

include_once("core/manager/Context.class.php");
include_once("core/connection/Sql.class.php");

//Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
//en camel case precedida por la palabra sql

class SqlcierreFinal extends sql {


	var $context;


	function __construct(){
		$this->context=Context::singleton();
	}


	function get($tipo,$variable="") {

    $anioActivo = $this->context->getVariable("anio");
    
		if($anioActivo == $this->activeYear) {
			$sufijo = "";
		}else{
			$sufijo = "_".$this->activeYear;
		}
    
		/**
		 * 1. Revisar las variables para evitar SQL Injection
		 *
		 */

		$prefijo=$this->context->getVariable("prefijo");
		$sessionId=$this->context->getVariable("id_sesion");

		switch($tipo) {

      case "cursosCerrados":
				$cadena_sql  = " SELECT ";
				$cadena_sql .= "id_curso IDCURSO ";
				$cadena_sql .= "FROM ";
				$cadena_sql .= $prefijo."curso ";
                $cadena_sql .= "WHERE ";
				$cadena_sql .= "id_curso IN (";
				$cadena_sql .= " SELECT ";
				$cadena_sql .= " id_curso ";
				$cadena_sql .= " FROM ";
				$cadena_sql .= $prefijo."nota_area ";
				$cadena_sql .= " WHERE ";
				$cadena_sql .= " anio ='".$variable."' ";
				$cadena_sql .= " ) ";
				break;
        
      case "borrarNotasCerradas":
				$cadena_sql .= " DELETE ";
				$cadena_sql .= " FROM ";
				$cadena_sql .= $prefijo."nota_area ";
				$cadena_sql .= " WHERE ";
				$cadena_sql .= " anio ='".$variable[0]."' ";
				$cadena_sql .= " AND ";
				$cadena_sql .= " id_curso ='".$variable[1]."' ";
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
				$cadena_sql.="id_area IDAREA, ";
				$cadena_sql.="id_grado IDGRADO ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."competencia ";
        $cadena_sql.="WHERE ";
				$cadena_sql.="estado ='1' ";
				break;

      case "competenciasPorGrado":
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

      case "areasPorGrado":
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
				if($anioActivo == $this->activeYear) {
          $cadena_sql.="AND ";
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
				$cadena_sql.=$prefijo."usuario_curso uc ";
				$cadena_sql.="ON uc.id_usuario=u.id_usuario ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_curso ='".$variable."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="us.id_subsistema = 3 ";
				$cadena_sql.="AND ";
				$cadena_sql.="u.estado = 1 ";
				break;

      case "competenciasPorArea":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_competencia ID, ";
				$cadena_sql.="identificador IDENTIFICADOR, ";
				$cadena_sql.="competencia COMPETENCIA, ";
				$cadena_sql.="desempenio_basico BASICO, ";
				$cadena_sql.="desempenio_alto ALTO, ";
				$cadena_sql.="desempenio_superior SUPERIOR, ";
				$cadena_sql.="periodo PERIODO, ";
				$cadena_sql.="id_area IDAREA, ";
				$cadena_sql.="id_grado IDGRADO ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."competencia ";
        $cadena_sql.="WHERE ";
				$cadena_sql.="id_grado ='".$variable."' ";
        $cadena_sql.="AND ";
				$cadena_sql.="estado ='1' ";
				break;

			case "estudiantes":
				$cadena_sql="SELECT ";
				$cadena_sql.="u.id_usuario ID, ";
				$cadena_sql.="u.nombre NOMBRE, ";
				$cadena_sql.="u.nombre2 NOMBRE2, ";
				$cadena_sql.="u.usuario CODIGO, ";
				$cadena_sql.="u.apellido APELLIDO, ";
				$cadena_sql.="u.apellido2 APELLIDO2, ";
				$cadena_sql.="c.id_curso IDCURSO, ";
				$cadena_sql.="c.id_grado IDGRADO ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."usuario u ";
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.=$prefijo."usuario_subsistema us ";
				$cadena_sql.="ON us.id_usuario=u.id_usuario ";
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.=$prefijo."usuario_curso uc ";
				$cadena_sql.="ON uc.id_usuario=u.id_usuario ";
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.=$prefijo."curso c ";
				$cadena_sql.="ON c.id_curso=uc.id_curso ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="us.id_subsistema = 3 ";
				$cadena_sql.="AND ";
				$cadena_sql.="u.estado = 1 ";
				break;

			case "notasFinalesFueradelGrado":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_nota_competencia IDNOTA ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."nota_competencia".$sufijo;
				$cadena_sql.=" WHERE ";
				$cadena_sql.="id_estudiante ='".$variable['ID']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="estado = '1'  ";
				$cadena_sql.="AND ";
				$cadena_sql.="id_competencia NOT IN  ";
				$cadena_sql.="(SELECT id_competencia FROM notas_competencia WHERE id_grado ='".$variable['IDGRADO']."' ) ";
				break;

			case "inactivarNotaFinal":
				$cadena_sql="UPDATE ";
				$cadena_sql.=$prefijo."nota_competencia ";
				$cadena_sql.="SET ";
				$cadena_sql.="estado ='0' ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_nota_competencia ='".$variable."' ";
				break;

			case "notasParcialesFueradelGrado":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_nota_criterio IDNOTA ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."nota_criterio".$sufijo;
				$cadena_sql.=" WHERE ";
				$cadena_sql.="id_estudiante ='".$variable['ID']."' ";
        $cadena_sql.="AND ";
				$cadena_sql.="estado = '1'  ";
				$cadena_sql.="AND ";
				$cadena_sql.="id_competencia NOT IN  ";
				$cadena_sql.="(SELECT id_competencia FROM notas_competencia".$sufijo." WHERE id_grado ='".$variable['IDGRADO']."' ) ";
				break;


			case "inactivarNotaParcial":
				$cadena_sql="UPDATE ";
				$cadena_sql.=$prefijo."nota_criterio ";
				$cadena_sql.="SET ";
				$cadena_sql.="estado ='0' ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_nota_criterio ='".$variable."' ";
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
				$cadena_sql.=$prefijo."nota_competencia ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_competencia ='".$variable['competencia']."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="estado <> 0 ";
				break;

			case "notasFinalesPorEstudiante":
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

       case "notasDefinitivasPorEstudiante":
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
				$cadena_sql.="id_estudiante ='".$variable['estudiante']."' ";
				break;

			case "notasFinalesPorCurso":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_nota_competencia ID, ";
				$cadena_sql.="id_curso IDCURSO, ";
				$cadena_sql.="id_estudiante ESTUDIANTE, ";
				$cadena_sql.="id_competencia COMPETENCIA, ";
				$cadena_sql.="nota_final NOTA_FINAL, ";
				$cadena_sql.="nota_porcentual NOTA_PORCENTUAL, ";
				$cadena_sql.="desempenio DESEMPENIO ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."nota_competencia nf ";
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.=$prefijo."usuario_curso nuc ";
				$cadena_sql.="ON nuc.id_usuario = nf.id_estudiante ";
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.=$prefijo."usuario_subsistema us ";
				$cadena_sql.="ON us.id_usuario = nuc.id_usuario ";
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.=$prefijo."usuario u ";
				$cadena_sql.="ON u.id_usuario = us.id_usuario ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="nuc.id_curso ='".$variable."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="nf.estado ='1' ";
				$cadena_sql.="AND ";
				$cadena_sql.="u.estado ='1' ";
				break;

      case "insertarNotaCerrada":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.=$prefijo."nota_area( ";
				$cadena_sql.="anio, ";
				$cadena_sql.="id_estudiante, ";
				$cadena_sql.="id_curso, ";
				$cadena_sql.="id_area, ";
				$cadena_sql.="nota, ";
				$cadena_sql.="desempenio, ";
				$cadena_sql.="estado ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES( ";
				$cadena_sql.="'".$variable['anio']."', ";
				$cadena_sql.="'".$variable['id_estudiante']."', ";
				$cadena_sql.="'".$variable['id_curso']."', ";
				$cadena_sql.="'".$variable['id_area']."', ";
				$cadena_sql.="'".$variable['nota']."', ";
				$cadena_sql.="'".$variable['desempenio']."', ";
				$cadena_sql.="'".$variable['estado']."' ";
				$cadena_sql.=") ";
				break;

		}
		//echo "<br/><br/>".$cadena_sql;
		return $cadena_sql;

	}
}
?>
