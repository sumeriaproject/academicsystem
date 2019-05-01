<?php
if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}

include_once("core/manager/Context.class.php");
include_once("core/connection/Sql.class.php");

//Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
//en camel case precedida por la palabra sql

class Sqlpromocion extends sql {


	var $context;


	function __construct(){
		$this->context=Context::singleton();
	}


	function cadenaSql($tipo,$variable="") {

		/**
		 * 1. Revisar las variables para evitar SQL Injection
		 *
		 */

		$prefijo=$this->context->getVariable("prefijo");
		$idSesion=$this->context->getVariable("id_sesion");

		switch($tipo) {

      case "loadSIMAT":
				$cadena_sql  = "SELECT ";
				$cadena_sql .= "SEDE, ";
				$cadena_sql .= "GRADO, ";
				$cadena_sql .= "DOC, ";
				$cadena_sql .= "APELLIDO1, ";
				$cadena_sql .= "NOMBRE1 ";
				$cadena_sql .= "FROM ";
				$cadena_sql .= $prefijo."csv_simat_tmp ";
				break;

      case "countSIMAT":
				$cadena_sql  = "SELECT ";
				$cadena_sql .= "count(ANO) ";
				$cadena_sql .= "FROM ";
				$cadena_sql .= $prefijo."csv_simat_tmp ";
				break;

      case "estudiantesSinSistema":
				$cadena_sql  = "SELECT ";
				$cadena_sql .= "cst.SEDE SEDE, ";
				$cadena_sql .= "c.id_curso CURSO, ";
				$cadena_sql .= "cst.NUI NUI, ";
				$cadena_sql .= "cst.DOC DOC,";
				$cadena_sql .= "cst.TIPODOC TIPODOC, ";
				$cadena_sql .= "cst.APELLIDO1 APELLIDO1,";
				$cadena_sql .= "cst.APELLIDO2 APELLIDO2,";
				$cadena_sql .= "cst.NOMBRE1 NOMBRE1,";
				$cadena_sql .= "cst.NOMBRE2 NOMBRE2 ";
        $cadena_sql .= "FROM ";
				$cadena_sql .= $prefijo."csv_simat_tmp cst ";
				$cadena_sql .= "INNER JOIN notas_curso c ON c.id_grado = cst.grado AND c.id_sede = cst.sede ";
				$cadena_sql .= "WHERE cst.NUI NOT IN ";
				$cadena_sql .= "(SELECT nui
      										FROM  `notas_usuario` INNER JOIN notas_usuario_subsistema
      										USING ( id_usuario ) WHERE id_subsistema =3 )  ";
				break;

      case "insertarEstudiante":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.=$prefijo."usuario ";
				$cadena_sql.="( ";
				$cadena_sql.="`id_usuario`, ";
				$cadena_sql.="`identificacion`, ";
				$cadena_sql.="`nombre`, ";
				$cadena_sql.="`nombre2`, ";
				$cadena_sql.="`apellido`, ";
				$cadena_sql.="`apellido2`, ";
				$cadena_sql.="`nui`, ";
				$cadena_sql.="`id_sede`, ";
				$cadena_sql.="`correo`, ";
				$cadena_sql.="`usuario`, ";
				$cadena_sql.="`clave`, ";
				$cadena_sql.="`estilo`, ";
				$cadena_sql.="`idioma`, ";
				$cadena_sql.="`estado` ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="( ";
				$cadena_sql.="'".$variable['LASTID']."', ";
				$cadena_sql.="'".$variable['DOC']."', ";
				$cadena_sql.="'".$variable['NOMBRE1']."', ";
				$cadena_sql.="'".$variable['NOMBRE2']."', ";
				$cadena_sql.="'".$variable['APELLIDO1']."', ";
				$cadena_sql.="'".$variable['APELLIDO2']."', ";
				$cadena_sql.="'".$variable['NUI']."', ";
				$cadena_sql.="'".$variable['SEDE']."', ";
				$cadena_sql.="'', ";
				$cadena_sql.="'".$variable['LASTID']."', ";
				$cadena_sql.="MD5('".$variable['LASTID']."'), ";
				$cadena_sql.="'admin', ";
				$cadena_sql.="'es_es', ";
				$cadena_sql.="'1' ";
				$cadena_sql.=")";
				break;

      case "insertarCurso":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.=$prefijo."usuario_curso ";
				$cadena_sql.="( ";
				$cadena_sql.="`id_usuario`, ";
				$cadena_sql.="`id_curso` ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="( ";
				$cadena_sql.="'".$variable['LASTID']."', ";
				$cadena_sql.="'".$variable['CURSO']."' ";
				$cadena_sql.=")";
				break;

      case "insertarSubsistema":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.=$prefijo."usuario_subsistema ";
				$cadena_sql.="( ";
				$cadena_sql.="`id_usuario`, ";
				$cadena_sql.="`id_subsistema`, ";
				$cadena_sql.="`estado` ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="( ";
				$cadena_sql.="'".$variable['LASTID']."', ";
				$cadena_sql.="'3', ";
				$cadena_sql.="'1' ";
				$cadena_sql.=")";
				break;

      case "lastIdByCourse":
				$cadena_sql=" SELECT ";
				$cadena_sql.="(u.id_usuario+1) ";
				$cadena_sql.="FROM  ";
				$cadena_sql.=$prefijo."usuario u ";
				$cadena_sql.="INNER JOIN notas_usuario_subsistema us ON u.id_usuario=us.id_usuario   ";
				$cadena_sql.=" WHERE id_subsistema = 3 AND u.id_sede = ".$variable;
				$cadena_sql.=" ORDER BY u.id_usuario DESC LIMIT 0,1   ";
				break;

      case "basicUserByID":
				$cadena_sql="SELECT ";
				$cadena_sql.="u.id_usuario ID ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."usuario u ";
				$cadena_sql.="WHERE u.id_usuario=".$variable;
				break;
      case "conteoEstudiantesSinSistema":
				$cadena_sql  = "SELECT count(*) ";
				$cadena_sql .= "FROM ";
				$cadena_sql .= $prefijo."csv_simat_tmp ";
				$cadena_sql .= "WHERE NUI NOT IN ";
				$cadena_sql .= "(SELECT nui
      										FROM  `notas_usuario` INNER JOIN notas_usuario_subsistema
      										USING ( id_usuario ) WHERE id_subsistema =3 )  ";
				break;

      case "conteoEstudiantesSinSIMAT":
				$cadena_sql  = "SELECT count(*) ";
				$cadena_sql .= "FROM ";
				$cadena_sql .= $prefijo."usuario ";
				$cadena_sql .= "INNER JOIN ";
				$cadena_sql .= $prefijo."usuario_subsistema ";
      	$cadena_sql .= "USING ( id_usuario ) WHERE id_subsistema =3 ";
				$cadena_sql .= "AND nui NOT IN ";
				$cadena_sql .= "(SELECT NUI
      										FROM  ".$prefijo."csv_simat_tmp  )  ";
        break;


      case "estudiantesSinSIMATPorNUI":
				$cadena_sql  = "SELECT ";
        $cadena_sql .= "u.id_usuario ID, ";
				$cadena_sql .= "uc.id_curso CURSO, ";
				$cadena_sql .= "u.estado ESTADO ";
				$cadena_sql .= "FROM ";
				$cadena_sql .= $prefijo."usuario u ";
				$cadena_sql .= "INNER JOIN ";
				$cadena_sql .= $prefijo."usuario_subsistema ";
      	$cadena_sql .= "USING ( id_usuario ) ";
        $cadena_sql .= "INNER JOIN ";
				$cadena_sql .= $prefijo."usuario_curso uc ";
				$cadena_sql .= "ON uc.id_usuario=u.id_usuario ";
				$cadena_sql .= "WHERE id_subsistema =3  ";
				$cadena_sql .= "AND nui NOT IN ";
				$cadena_sql .= "(SELECT NUI
      										FROM  ".$prefijo."csv_simat_tmp  )  ";
        $cadena_sql .= "AND u.id_usuario NOT IN ";
				$cadena_sql .= "(SELECT id_estudiante
      										FROM  ".$prefijo."estudiante_cierre  )  ";
        break; //@todo validar año

      case "estudiantesGradoIgual":
				$cadena_sql  = "SELECT ";
        $cadena_sql .= "u.id_usuario ID, ";
				$cadena_sql .= "uc.id_curso CURSO, ";
				$cadena_sql .= "c.nombre GRADO, ";
				$cadena_sql .= "cst.GRADO_COD, ";
				$cadena_sql .= "u.estado ESTADO ";
				$cadena_sql .= "FROM ";
				$cadena_sql .= $prefijo."usuario u ";
				$cadena_sql .= "INNER JOIN ";
				$cadena_sql .= $prefijo."usuario_subsistema ";
      	$cadena_sql .= "USING ( id_usuario ) ";
        $cadena_sql .= "INNER JOIN ";
				$cadena_sql .= $prefijo."usuario_curso uc ";
				$cadena_sql .= "ON uc.id_usuario = u.id_usuario ";
        $cadena_sql .= "INNER JOIN ";
				$cadena_sql .= $prefijo."curso c ";
				$cadena_sql .= "ON c.id_curso = uc.id_curso ";
        $cadena_sql .= "INNER JOIN ";
				$cadena_sql .= $prefijo."csv_simat_tmp cst ";
				$cadena_sql .= "ON cst.NUI = u.nui ";
 				$cadena_sql .= "WHERE id_subsistema =3  ";
				$cadena_sql .= "AND c.id_grado = cst.GRADO ";
        $cadena_sql .= "AND u.id_usuario NOT IN ";
				$cadena_sql .= "(SELECT id_estudiante
      										FROM  ".$prefijo."estudiante_cierre  )  ";
        break; //@todo validar año
      case "resultadoPendientes":
        $cadena_sql = "SELECT
                        u.id_usuario,
                        s.nombre,
                        u.nombre,
                        nombre2,
                        apellido,
                        apellido2,
                        observacion,
                        estado_cierre
                        FROM `notas_estudiante_cierre` ec
                        INNER JOIN notas_usuario u ON u.id_usuario = ec.id_estudiante
                        INNER JOIN notas_sede s ON s.id_sede = u.id_sede
                        WHERE estado_cierre = 'PENDIENTE'";

      break;
      case "estudiantesNotasPendientes":
				$cadena_sql = "SELECT
                      u.id_usuario ID, uc.id_curso CURSO,
                      c.nombre GRADO,
                      u.estado ESTADO,
                      (SELECT count(*)
                            FROM notas_area a
                            INNER JOIN notas_area_grado ag ON a.id_area = ag.id_area
                            INNER JOIN notas_grado g ON g.id_grado = ag.id_grado
                            WHERE g.id_grado = c.id_grado ) NUM_AREAS,
                      (SELECT count(*)
                            FROM notas_nota_area nc
                            INNER JOIN notas_usuario nun ON nun.id_usuario = nc.id_estudiante
                            WHERE nun.id_usuario = u.id_usuario) NUM_NOTAS
                      FROM notas_usuario u
                      INNER JOIN notas_usuario_subsistema USING ( id_usuario )
                      INNER JOIN notas_usuario_curso uc ON uc.id_usuario = u.id_usuario
                      INNER JOIN notas_curso c ON c.id_curso = uc.id_curso
                      WHERE id_subsistema =3
                      AND u.id_usuario
                      NOT IN (SELECT id_estudiante FROM notas_estudiante_cierre )
                      AND ( SELECT count(*)
                            FROM notas_area a
                            INNER JOIN notas_area_grado ag ON a.id_area = ag.id_area
                            INNER JOIN notas_grado g ON g.id_grado = ag.id_grado
                            WHERE g.id_grado = c.id_grado )
                             >
                            ( SELECT count(*)
                            FROM notas_nota_area nc
                            INNER JOIN notas_usuario nun ON nun.id_usuario = nc.id_estudiante
                            WHERE nun.id_usuario = u.id_usuario ) ";
				break;

      case "estudiantesNotasCompletas":
				$cadena_sql = "SELECT
                      u.id_usuario ID, uc.id_curso CURSO,
                      c.nombre GRADO,
                      u.estado ESTADO,
                      (SELECT count(*)
                            FROM notas_area a
                            INNER JOIN notas_area_grado ag ON a.id_area = ag.id_area
                            INNER JOIN notas_grado g ON g.id_grado = ag.id_grado
                            WHERE g.id_grado = c.id_grado ) NUM_AREAS,
                      (SELECT count(*)
                            FROM notas_nota_area nc
                            INNER JOIN notas_usuario nun ON nun.id_usuario = nc.id_estudiante
                            WHERE nun.id_usuario = u.id_usuario) NUM_NOTAS
                      FROM notas_usuario u
                      INNER JOIN notas_usuario_subsistema USING ( id_usuario )
                      INNER JOIN notas_usuario_curso uc ON uc.id_usuario = u.id_usuario
                      INNER JOIN notas_curso c ON c.id_curso = uc.id_curso
                      WHERE id_subsistema =3
                      AND u.id_usuario
                      NOT IN (SELECT id_estudiante FROM notas_estudiante_cierre )
                      AND ( SELECT count(*)
                            FROM notas_area a
                            INNER JOIN notas_area_grado ag ON a.id_area = ag.id_area
                            INNER JOIN notas_grado g ON g.id_grado = ag.id_grado
                            WHERE g.id_grado = c.id_grado )
                             =
                            ( SELECT count(*)
                            FROM notas_nota_area nc
                            INNER JOIN notas_usuario nun ON nun.id_usuario = nc.id_estudiante
                            WHERE nun.id_usuario = u.id_usuario ) ";
				break;

      case "estudiantesInactivos":
				$cadena_sql="SELECT ";
				$cadena_sql.="u.id_usuario ID, ";
				$cadena_sql.="uc.id_curso CURSO, ";
				$cadena_sql.="u.estado ESTADO ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."usuario u ";
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.=$prefijo."usuario_subsistema us ";
				$cadena_sql.="ON us.id_usuario=u.id_usuario ";
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.=$prefijo."usuario_curso uc ";
				$cadena_sql.="ON uc.id_usuario=u.id_usuario ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="us.id_subsistema = 3 ";
				$cadena_sql.="AND ";
				$cadena_sql.="u.estado <> 1 ";
				break;

      case "estudiantesParaHistoricos":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_estudiante ID ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."estudiante_cierre u ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="estado_cierre IN ('PROMOVIDO','INACTIVO') ";
				break;

      case "estudiantesPromovidos":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_estudiante ID ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."estudiante_cierre u ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="estado_cierre IN ('PROMOVIDO') ";
				break;

      case "countEstudiantes":
				$cadena_sql="SELECT ";
				$cadena_sql.="count(u.id_usuario) ";
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

				break;

			case "insertarHistoricoParcial":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.=$prefijo."nota_parcial_historico ";
				$cadena_sql.="SELECT ";
				$cadena_sql.="'".$variable['anio']."', ";
				$cadena_sql.="id_nota_criterio, ";
				$cadena_sql.="id_estudiante, ";
				$cadena_sql.="id_competencia, ";
				$cadena_sql.="id_criterio, ";
				$cadena_sql.="nota, ";
				$cadena_sql.="usuario, ";
				$cadena_sql.="fecha, ";
				$cadena_sql.="estado ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."nota_criterio ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_estudiante ='".$variable['estudiante']."' ";
				break;

			case "eliminarActualParcial":
				$cadena_sql="DELETE FROM ";
				$cadena_sql.=$prefijo."nota_criterio ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_estudiante ='".$variable['estudiante']."' ";
				break;

			case "insertarHistoricoFinal":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.=$prefijo."nota_final_historico ";
				$cadena_sql.="SELECT ";
				$cadena_sql.="'".$variable['anio']."', ";
				$cadena_sql.="id_nota_final, ";
				$cadena_sql.="id_estudiante, ";
				$cadena_sql.="id_competencia, ";
				$cadena_sql.="nota_final, ";
				$cadena_sql.="nota_porcentual, ";
				$cadena_sql.="desempenio, ";
				$cadena_sql.="estado ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."nota_final ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_estudiante ='".$variable['estudiante']."' ";
				break;

			case "eliminarActualFinal":
				$cadena_sql="DELETE FROM ";
				$cadena_sql.=$prefijo."nota_final ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_estudiante ='".$variable['estudiante']."' ";
				break;

			case "insertarHistoricoCerrada":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.=$prefijo."nota_cerrada_historico ";
				$cadena_sql.="SELECT ";
				$cadena_sql.="'".$variable['anio']."', ";
				$cadena_sql.="id_nota_cerrada, ";
				$cadena_sql.="id_estudiante, ";
				$cadena_sql.="id_curso, ";
				$cadena_sql.="id_area, ";
				$cadena_sql.="nota, ";
				$cadena_sql.="desempenio, ";
				$cadena_sql.="estado ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."nota_area ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_estudiante ='".$variable['estudiante']."' ";
				break;

			case "eliminarActualCerrada":
				$cadena_sql="DELETE FROM ";
				$cadena_sql.=$prefijo."nota_area ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_estudiante ='".$variable['estudiante']."' ";
				break;

			case "registrarHistoricoEstudiante":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.=$prefijo."estudiante_cierre( ";
				$cadena_sql.="anio, ";
				$cadena_sql.="id_estudiante, ";
				$cadena_sql.="id_curso, ";
				$cadena_sql.="estado_academico, ";
				$cadena_sql.="observacion, ";
				$cadena_sql.="adjunto, ";
				$cadena_sql.="estado_cierre ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES( ";
				$cadena_sql.="'".$variable['anio']."', ";
				$cadena_sql.="'".$variable['id_estudiante']."', ";
				$cadena_sql.="'".$variable['id_curso']."', ";
				$cadena_sql.="'".$variable['estado_academico']."', ";
				$cadena_sql.="'".$variable['observacion']."', ";
				$cadena_sql.="'".$variable['desempenio']."', ";
				$cadena_sql.="'".$variable['adjunto']."' ";
				$cadena_sql.="'".$variable['estado_cierre']."' ";
				$cadena_sql.=") ";
				break;

		}
		//echo "<br/><br/>".$cadena_sql;
		return $cadena_sql;

	}
}
?>
