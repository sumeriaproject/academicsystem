<?php

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}

include_once("core/manager/Configurador.class.php");
include_once("core/connection/Sql.class.php");

//Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
//en camel case precedida por la palabra sql

class SqluserManagement extends sql {


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
			case "estudiantebyID":
				$cadena_sql="SELECT ";
				$cadena_sql.="u.id_usuario ID, ";
				$cadena_sql.="u.identificacion DOCUMENTO, ";
				$cadena_sql.="u.nombre NOMBRE, ";
				$cadena_sql.="u.nombre2 NOMBRE2, ";
				$cadena_sql.="u.usuario CODIGO, ";
				$cadena_sql.="u.apellido APELLIDO, ";
				$cadena_sql.="u.apellido2 APELLIDO2, ";
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
				$cadena_sql.="u.id_usuario ='".$variable."' ";
				$cadena_sql.="AND ";
				$cadena_sql.="us.id_subsistema = 3 ";
				break;
        
			case "estudianteEnrollbyID":
				$cadena_sql="SELECT ";
				$cadena_sql.="u.id_usuario ID ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."matricula u ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="u.id_usuario ='".$variable."' ";

				break;

			case "userList":
				$cadena_sql="SELECT ";
				$cadena_sql.="u.id_usuario ID, ";
				$cadena_sql.="u.identificacion IDENT, ";
				$cadena_sql.="u.usuario USUARIO,";
				$cadena_sql.="CONCAT(u.apellido,' ',u.apellido2,' ',u.nombre,' ',u.nombre2) NOMBRE,";
				$cadena_sql.="u.id_sede  IDSEDE, ";
				$cadena_sql.="correo CORREO, ";
				$cadena_sql.="GROUP_CONCAT( DISTINCT (s.nombre) ) ROL, ";
				$cadena_sql.="u.estado ESTADO ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."usuario u ";
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.=$prefijo."usuario_subsistema us ";
				$cadena_sql.="ON ";
				$cadena_sql.="u.id_usuario = us.id_usuario ";
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.=$prefijo."subsistema s ";
				$cadena_sql.="ON ";
				$cadena_sql.="us.id_subsistema = s.id_subsistema ";
				$cadena_sql.="WHERE 1=1 ";
				if($variable['sede']<>""){
					$cadena_sql.="AND ";
					$cadena_sql.="id_sede='".$variable['sede']."' ";
				}
				if($variable['rol']<>""){
					$cadena_sql.="AND ";
					$cadena_sql.="s.id_subsistema='".$variable['rol']."' ";
				}
				$cadena_sql.="GROUP BY u.id_usuario ";
				break;

			case "userListByID":
			case "userByID":
				$cadena_sql="SELECT ";
				$cadena_sql.="u.id_usuario ID, ";
				$cadena_sql.="u.nombre NOMBRE,";
				$cadena_sql.="u.nombre2 NOMBRE2,";
				$cadena_sql.="u.apellido APELLIDO,";
				$cadena_sql.="u.apellido2 APELLIDO2,";
				$cadena_sql.="u.identificacion IDENT,";
				$cadena_sql.="u.tipo_identificacion TIPO_IDENT,";
				$cadena_sql.="correo CORREO, ";
				$cadena_sql.="u.usuario USUARIO, ";
				$cadena_sql.="GROUP_CONCAT( DISTINCT (s.nombre) ) ROL, ";
				$cadena_sql.="u.id_sede IDSEDE, ";
				$cadena_sql.="u.estado ESTADO ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."usuario u ";
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.=$prefijo."usuario_subsistema us ";
				$cadena_sql.="ON ";
				$cadena_sql.="u.id_usuario = us.id_usuario ";
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.=$prefijo."subsistema s ";
				$cadena_sql.="ON ";
				$cadena_sql.="us.id_subsistema = s.id_subsistema ";
				$cadena_sql.="WHERE u.id_usuario=".$variable;
				$cadena_sql.=" GROUP BY u.id_usuario ";
				break;

      case "basicUserByID":
				$cadena_sql="SELECT ";
				$cadena_sql.="u.id_usuario ID ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."usuario u ";
				$cadena_sql.="WHERE u.id_usuario=".$variable;
				break;

			case "roleList":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_subsistema ID, ";
				$cadena_sql.="nombre ROL ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."subsistema ";
				break;

			case "courseByUser":
				$cadena_sql="SELECT ";
				$cadena_sql.="GROUP_CONCAT(uc.id_curso) COURSES ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."usuario_curso uc ";
				$cadena_sql.="WHERE uc.id_usuario=".$variable;
				break;


			case "teacherList":
				$cadena_sql=" SELECT ";
				$cadena_sql.="u.id_usuario ID, ";
				$cadena_sql.="u.nombre NOMBRE, ";
				$cadena_sql.="u.nombre2 NOMBRE2, ";
				$cadena_sql.="u.apellido APELLIDO, ";
				$cadena_sql.="u.apellido2 APELLIDO2 ";
				$cadena_sql.="FROM  ";
				$cadena_sql.=$prefijo."usuario u ";
				$cadena_sql.=" INNER JOIN notas_usuario_subsistema us ON u.id_usuario=us.id_usuario  ";
				$cadena_sql.=" WHERE id_subsistema = 2 ";
				$cadena_sql.=" ORDER BY u.id_usuario DESC  ";
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

			case "notasByID":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_estudiante ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."nota_parcial ";
				$cadena_sql.="WHERE id_estudiante=".$variable;
				$cadena_sql.=" UNION ";
				$cadena_sql.="SELECT ";
				$cadena_sql.="id_estudiante ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."nota_final ";
				$cadena_sql.="WHERE id_estudiante=".$variable;
				break;

			case "espacioList":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_espacio ID, ";
				$cadena_sql.="nombre NOMBRE ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."espacio ";
				break;

			case "sedeList":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_sede ID, ";
				$cadena_sql.="nombre NOMBRE ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."sede ";
				break;

			case "UserListbyCourse":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_usuario IDUSER, ";
				$cadena_sql.="id_curso IDCOURSE ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."usuario_curso ";
				break;

			case "UserListWithAcces":
				$cadena_sql="SELECT ";
				$cadena_sql.="u.id_usuario USUARIO, ";
				$cadena_sql.="u.identificacion IDENT, ";
				$cadena_sql.="u.nombre NOMBRE, ";
				$cadena_sql.="u.nombre2 NOMBRE2, ";
				$cadena_sql.="u.apellido APELLIDO, ";
				$cadena_sql.="u.apellido2 APELLIDO2, ";
				$cadena_sql.="s.id_sede SEDE_ID, ";
				$cadena_sql.="s.nombre SEDE_NOMBRE, ";
				$cadena_sql.="c.id_curso CURSO_ID ";
				$cadena_sql.="FROM notas_usuario u ";
				$cadena_sql.="INNER JOIN notas_usuario_curso uc ON u.id_usuario = uc.id_usuario ";
				$cadena_sql.="INNER JOIN notas_curso c ON uc.id_curso = c.id_curso ";
				$cadena_sql.="INNER JOIN notas_sede s ON c.id_sede = s.id_sede ";
				$cadena_sql.="INNER JOIN notas_grado g ON c.id_grado = g.id_grado ";
				$cadena_sql.="INNER JOIN notas_usuario_subsistema us ON u.id_usuario = us.id_usuario ";
				$cadena_sql.="WHERE us.id_subsistema = 3 ";
				$cadena_sql.="AND u.estado = 1 ";
				$cadena_sql.="ORDER BY u.apellido,u.apellido2 ASC ";
				break;

			case "UserListbyRole":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_usuario IDUSER, ";
				$cadena_sql.="id_subsistema IDROL ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."usuario_subsistema ";
				break;

			case "courseList":
				$cadena_sql="SELECT ";
				$cadena_sql.="c.id_curso IDCOURSE, ";
				$cadena_sql.="c.nombre NAMECOURSE, ";
				$cadena_sql.="s.nombre NAMESEDE ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."curso c ";
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.=$prefijo."sede s ";
				$cadena_sql.="ON c.id_sede = s.id_sede ";
				break;

			case "getCursoById":
				$cadena_sql="SELECT ";
				$cadena_sql.="c.id_sede IDSEDE ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."curso c ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="`id_curso` ='".$variable."' ";

				break;

			case "buscarUsuario":
				$cadena_sql="SELECT ";
				$cadena_sql.="FECHA_CREACION, ";
				$cadena_sql.="PRIMER_NOMBRE ";
				$cadena_sql.="FROM ";
				$cadena_sql.="USUARIOS ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="`PRIMER_NOMBRE` ='".$variable."' ";
				break;


			case "searchEmail":
				$cadena_sql="SELECT ";
				$cadena_sql.="correo ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."usuario ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="`correo` ='".$variable."' ";
				break;

			case "searchUserByIdent":
				$cadena_sql="SELECT ";
				$cadena_sql.="identificacion, ";
				$cadena_sql.="id_usuario ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."usuario ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="`identificacion` ='".$variable."' ";
				break;

			case "searchUserByIdandUser":
				$cadena_sql="SELECT ";
				$cadena_sql.="identificacion ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."usuario ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="`identificacion` ='".$variable['identificacion']."' ";
        $cadena_sql.="AND ";
				$cadena_sql.="`id_usuario` <> '".$variable['userid']."' ";
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
				$cadena_sql.="'".$variable['id']."', ";
				$cadena_sql.="'".$variable['subsistema']."', ";
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
				$cadena_sql.="'".$variable['id']."', ";
				$cadena_sql.="'".$variable['curso']."' ";
				$cadena_sql.=")";
				break;

			case "EliminarCursos":
				$cadena_sql="DELETE FROM ";
				$cadena_sql.=$prefijo."usuario_curso ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_usuario='".$variable."' ";
				break;

			case "EliminarSubsistema":
				$cadena_sql="DELETE FROM ";
				$cadena_sql.=$prefijo."usuario_subsistema ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_usuario='".$variable."' ";
				break;

			case "DeleteUser":
				$cadena_sql="DELETE FROM ";
				$cadena_sql.=$prefijo."usuario ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_usuario='".$variable."' ";
				break;

			case "insertarEstablecimiento":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.=$prefijo."usuario_establecimiento ";
				$cadena_sql.="( ";
				$cadena_sql.="`id_usuario`, ";
				$cadena_sql.="`id_establecimiento` ";
				$cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="( ";
				$cadena_sql.="'".$variable['id']."', ";
				$cadena_sql.="'".$variable['company']."' ";
				$cadena_sql.=")";
				break;

			case "EliminarEstablecimiento":
				$cadena_sql="DELETE FROM ";
				$cadena_sql.=$prefijo."usuario_establecimiento ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="id_usuario='".$variable."' ";
				break;


			case "actualizarRegistro":
				$cadena_sql="UPDATE ";
				$cadena_sql.=$prefijo."usuario ";
				$cadena_sql.="SET ";
				$cadena_sql.="`nombre` = '".$variable["nombre"]."', ";
				$cadena_sql.="`nombre2` = '".$variable["nombre2"]."', ";
				$cadena_sql.="`apellido` = '".$variable["apellido"]."', ";
				$cadena_sql.="`apellido2` = '".$variable["apellido2"]."', ";
				$cadena_sql.="`identificacion` = '".$variable["identificacion"]."', ";
				$cadena_sql.="`estado` = '".$variable["estado"]."', ";
				$cadena_sql.="`id_sede` = '".$variable["usersede"]."', ";
				//$cadena_sql.="`usuario` = '".$variable["usuario"]."', ";
				if($variable["password"]<>""){
				$cadena_sql.="`clave` = MD5('".$variable["password"]."'), ";

				}
				$cadena_sql.="`correo` = '".$variable["email"]."' ";

				$cadena_sql.="WHERE ";
				$cadena_sql.="`id_usuario` ='".$_REQUEST["id_usuario"]."' ";
				break;

			case "insertUser":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.=$prefijo."usuario ";
				$cadena_sql.="( ";
				//$cadena_sql.="`nui`, "; 
				$cadena_sql.="`identificacion`, ";
				$cadena_sql.="`nombre`, ";
				$cadena_sql.="`nombre2`, ";
				$cadena_sql.="`apellido`, ";
				$cadena_sql.="`apellido2`, ";
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
				//$cadena_sql.="'".$variable['nui']."', ";
				$cadena_sql.="'".$variable['identificacion']."', ";
				$cadena_sql.="'".$variable['nombre']."', ";
				$cadena_sql.="'".$variable['nombre2']."', ";
				$cadena_sql.="'".$variable['apellido']."', ";
				$cadena_sql.="'".$variable['apellido2']."', ";
				$cadena_sql.="'".$variable['usersede']."', ";
				$cadena_sql.="'".$variable['email']."', ";
				$cadena_sql.="'".$variable['usuario']."', ";
				$cadena_sql.="MD5('".$variable['password']."'), ";
				$cadena_sql.="'admin', ";
				$cadena_sql.="'es_es', ";
				$cadena_sql.="'".$variable['estado']."' ";
				$cadena_sql.=")";
				break;
      
      case "updateEnrollbyID":
				$cadena_sql="UPDATE ";
				$cadena_sql.=$prefijo."matricula ";
				$cadena_sql.="SET ";
        $cadena_sql.="`tipo_estudiante` = '".$variable["tipo_estudiante"]."', "; 
        $cadena_sql.="`tipo_documento` = '".$variable["tipo_documento"]."', "; 
        $cadena_sql.="`fecha_nacimiento` = '".$variable["fecha_nacimiento"]."', "; 
        $cadena_sql.="`dept_exp_doc` = '".$variable["dept_exp_doc"]."', ";
        $cadena_sql.="`munc_exp_doc` = '".$variable["munc_exp_doc"]."', ";	 
        $cadena_sql.="`genero` = '".$variable["genero"]."', ";	 
        $cadena_sql.="`dept_nacimiento` = '".$variable["dept_nacimiento"]."', ";	 
        $cadena_sql.="`munc_nacimiento` = '".$variable["munc_nacimiento"]."', ";	 
        $cadena_sql.="`dir_residencia` = '".$variable["dir_residencia"]."', ";	 
        $cadena_sql.="`barr_residencia` = '".$variable["barr_residencia"]."', ";	 
        $cadena_sql.="`dept_residencia` = '".$variable["dept_residencia"]."', ";	 
        $cadena_sql.="`munc_residencia` = '".$variable["munc_residencia"]."', ";	 
        $cadena_sql.="`zona_residencia` = '".$variable["zona_residencia"]."', ";	 
        $cadena_sql.="`ult_anio` = '".$variable["ult_anio"]."', ";	 
        $cadena_sql.="`ult_grado` = '".$variable["ult_grado"]."', ";	 
        $cadena_sql.="`ult_plantel` = '".$variable["ult_plantel"]."', ";	 
        $cadena_sql.="`ult_estado` = '".$variable["ult_estado"]."', ";	 
        $cadena_sql.="`ult_interno` = '".$variable["ult_interno"]."', ";
        $cadena_sql.="`grado_ingresa` = '".$variable["grado_ingresa"]."', ";	 
        $cadena_sql.="`nivel_ingresa` = '".$variable["nivel_ingresa"]."', ";	 
        $cadena_sql.="`sede_ingresa` = '".$variable["sede_ingresa"]."', ";	 
        $cadena_sql.="`docente_ingresa` = '".$variable["docente_ingresa"]."', ";	 
        $cadena_sql.="`EPS` = '".$variable["EPS"]."', ";	 
        $cadena_sql.="`IPS` = '".$variable["IPS"]."', ";	 
        $cadena_sql.="`tipo_sangre` = '".$variable["tipo_sangre"]."', ";	 
        $cadena_sql.="`rh_sangre` = '".$variable["rh_sangre"]."', ";	 
        $cadena_sql.="`tipo_victima` = '".$variable["tipo_victima"]."', ";	 
        $cadena_sql.="`dept_expulsion` = '".$variable["dept_expulsion"]."', ";	 
        $cadena_sql.="`munc_expulsion` = '".$variable["munc_expulsion"]."', ";	 
        $cadena_sql.="`fecha_expulsion` = '".$variable["fecha_expulsion"]."', ";	 
        $cadena_sql.="`certif_expulsion` = '".$variable["certif_expulsion"]."', ";
        $cadena_sql.="`discapacidad` = '".$variable["discapacidad"]."', ";	 
        $cadena_sql.="`acud_nombre` = '".$variable["acud_nombre"]."', ";
        $cadena_sql.="`acud_nombre2` = '".$variable["acud_nombre2"]."', ";	 
        $cadena_sql.="`acud_apellido` = '".$variable["acud_apellido"]."', ";
        $cadena_sql.="`acud_apellido2` = '".$variable["acud_apellido2"]."', ";	 
        $cadena_sql.="`acud_tipo_documento` = '".$variable["acud_tipo_documento"]."', ";	 
        $cadena_sql.="`acud_documento` = '".$variable["acud_documento"]."', ";	 
        $cadena_sql.="`acud_dept_exp_doc` = '".$variable["acud_dept_exp_doc"]."', ";	 
        $cadena_sql.="`acud_munc_exp_doc` = '".$variable["acud_munc_exp_doc"]."', ";	 
        $cadena_sql.="`acud_parentezco` = '".$variable["acud_parentezco"]."', ";	 
        $cadena_sql.="`acud_tipo` = '".$variable["acud_tipo"]."', ";	 
        $cadena_sql.="`acud_dir_residencia` = '".$variable["acud_dir_residencia"]."', ";	 
        $cadena_sql.="`acud_telefono1` = '".$variable["acud_telefono1"]."', ";	 
        $cadena_sql.="`acud_telefono2` = '".$variable["acud_telefono2"]."' ";
				$cadena_sql.="WHERE "; 
				$cadena_sql.="`id_usuario` ='".$variable["optionValue"]."' ";
				break;

      case "userEnrollByID":
				$cadena_sql="SELECT ";
        $cadena_sql.="m.id_usuario id_usuario, ";
        $cadena_sql.="m.tipo_estudiante tipo_estudiante, "; 
        $cadena_sql.="m.tipo_documento tipo_documento, "; 
        $cadena_sql.="m.fecha_nacimiento fecha_nacimiento, "; 
        $cadena_sql.="m.dept_exp_doc dept_exp_doc, ";
        $cadena_sql.="m.munc_exp_doc munc_exp_doc, ";	 
        $cadena_sql.="m.genero genero, ";	 
        $cadena_sql.="m.dept_nacimiento dept_nacimiento, ";	 
        $cadena_sql.="m.munc_nacimiento munc_nacimiento, ";	 
        $cadena_sql.="m.dir_residencia dir_residencia, ";	 
        $cadena_sql.="m.barr_residencia barr_residencia, ";	 
        $cadena_sql.="m.dept_residencia dept_residencia, ";	 
        $cadena_sql.="m.munc_residencia munc_residencia, ";	 
        $cadena_sql.="m.zona_residencia zona_residencia, ";	 
        $cadena_sql.="m.ult_anio ult_anio, ";	 
        $cadena_sql.="m.ult_grado ult_grado, ";	 
        $cadena_sql.="m.ult_plantel ult_plantel, ";	 
        $cadena_sql.="m.ult_estado ult_estado, ";	 
        $cadena_sql.="m.ult_interno ult_interno, ";
        $cadena_sql.="m.grado_ingresa grado_ingresa, ";	 
        $cadena_sql.="m.nivel_ingresa nivel_ingresa, ";	 
        $cadena_sql.="m.sede_ingresa sede_ingresa, ";	 
        $cadena_sql.="m.docente_ingresa docente_ingresa, ";	 
        $cadena_sql.="m.EPS EPS, ";	 
        $cadena_sql.="m.IPS IPS, ";	 
        $cadena_sql.="m.tipo_sangre tipo_sangre, ";	 
        $cadena_sql.="m.rh_sangre rh_sangre, ";	 
        $cadena_sql.="m.tipo_victima tipo_victima, ";	 
        $cadena_sql.="m.dept_expulsion dept_expulsion, ";	 
        $cadena_sql.="m.munc_expulsion munc_expulsion, ";	 
        $cadena_sql.="m.fecha_expulsion fecha_expulsion, ";	 
        $cadena_sql.="m.certif_expulsion certif_expulsion, ";
        $cadena_sql.="m.discapacidad discapacidad, ";	 
        $cadena_sql.="m.acud_nombre acud_nombre, ";
        $cadena_sql.="m.acud_nombre2 acud_nombre2, ";	 
        $cadena_sql.="m.acud_apellido acud_apellido, ";
        $cadena_sql.="m.acud_apellido2 acud_apellido2, ";	 
        $cadena_sql.="m.acud_tipo_documento acud_tipo_documento, ";	 
        $cadena_sql.="m.acud_documento acud_documento, ";	 
        $cadena_sql.="m.acud_dept_exp_doc acud_dept_exp_doc, ";	 
        $cadena_sql.="m.acud_munc_exp_doc acud_munc_exp_doc, ";	 
        $cadena_sql.="m.acud_parentezco acud_parentezco, ";	 
        $cadena_sql.="m.acud_tipo acud_tipo, ";	 
        $cadena_sql.="m.acud_dir_residencia acud_dir_residencia, ";	 
        $cadena_sql.="m.acud_telefono1 acud_telefono1, ";	 
        $cadena_sql.="m.acud_telefono2 acud_telefono2 ";	 
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."matricula m ";
				$cadena_sql.="WHERE m.id_usuario = '".$variable."'";
				break;
        
			case "insertEnrollbyID":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.=$prefijo."matricula ";
				$cadena_sql.="( ";
        $cadena_sql.="`id_usuario`, ";
        $cadena_sql.="`tipo_estudiante`, "; 
        $cadena_sql.="`tipo_documento`, "; 
        $cadena_sql.="`fecha_nacimiento`, "; 
        $cadena_sql.="`dept_exp_doc`, ";
        $cadena_sql.="`munc_exp_doc`, ";	 
        $cadena_sql.="`genero`, ";	 
        $cadena_sql.="`dept_nacimiento`, ";	 
        $cadena_sql.="`munc_nacimiento`, ";	 
        $cadena_sql.="`dir_residencia`, ";	 
        $cadena_sql.="`barr_residencia`, ";	 
        $cadena_sql.="`dept_residencia`, ";	 
        $cadena_sql.="`munc_residencia`, ";	 
        $cadena_sql.="`zona_residencia`, ";	 
        $cadena_sql.="`ult_anio`, ";	 
        $cadena_sql.="`ult_grado`, ";	 
        $cadena_sql.="`ult_plantel`, ";	 
        $cadena_sql.="`ult_estado`, ";	 
        $cadena_sql.="`ult_interno`, ";
        $cadena_sql.="`grado_ingresa`, ";	 
        $cadena_sql.="`nivel_ingresa`, ";	 
        $cadena_sql.="`sede_ingresa`, ";	 
        $cadena_sql.="`docente_ingresa`, ";	 
        $cadena_sql.="`EPS`, ";	 
        $cadena_sql.="`IPS`, ";	 
        $cadena_sql.="`tipo_sangre`, ";	 
        $cadena_sql.="`rh_sangre`, ";	 
        $cadena_sql.="`tipo_victima`, ";	 
        $cadena_sql.="`dept_expulsion`, ";	 
        $cadena_sql.="`munc_expulsion`, ";	 
        $cadena_sql.="`fecha_expulsion`, ";	 
        $cadena_sql.="`certif_expulsion`, ";
        $cadena_sql.="`discapacidad`, ";	 
        $cadena_sql.="`acud_nombre`, ";
        $cadena_sql.="`acud_nombre2`, ";	 
        $cadena_sql.="`acud_apellido`, ";
        $cadena_sql.="`acud_apellido2`, ";	 
        $cadena_sql.="`acud_tipo_documento`, ";	 
        $cadena_sql.="`acud_documento`, ";	 
        $cadena_sql.="`acud_dept_exp_doc`, ";	 
        $cadena_sql.="`acud_munc_exp_doc`, ";	 
        $cadena_sql.="`acud_parentezco`, ";	 
        $cadena_sql.="`acud_tipo`, ";	 
        $cadena_sql.="`acud_dir_residencia`, ";	 
        $cadena_sql.="`acud_telefono1`, ";	 
        $cadena_sql.="`acud_telefono2`, ";	 
        $cadena_sql.="`estado` ";      
				$cadena_sql.=") ";
				$cadena_sql.="VALUES ";
				$cadena_sql.="( ";
        $cadena_sql.="'".$variable['optionValue']."', ";
        $cadena_sql.="'".$variable['tipo_estudiante']."', ";	 
        $cadena_sql.="'".$variable['tipo_documento']."', ";	 
        $cadena_sql.="'".$variable['fecha_nacimiento']."', ";	 
        $cadena_sql.="'".$variable['dept_exp_doc']."', ";	 
        $cadena_sql.="'".$variable['munc_exp_doc']."', ";	 
        $cadena_sql.="'".$variable['genero']."', ";	 
        $cadena_sql.="'".$variable['dept_nacimiento']."', ";	 
        $cadena_sql.="'".$variable['munc_nacimiento']."', ";	 
        $cadena_sql.="'".$variable['dir_residencia']."', ";	 
        $cadena_sql.="'".$variable['barr_residencia']."', ";	 
        $cadena_sql.="'".$variable['dept_residencia']."', ";	 
        $cadena_sql.="'".$variable['munc_residencia']."', ";	 
        $cadena_sql.="'".$variable['zona_residencia']."', ";	 
        $cadena_sql.="'".$variable['ult_anio']."', ";	 
        $cadena_sql.="'".$variable['ult_grado']."', ";	 
        $cadena_sql.="'".$variable['ult_plantel']."', ";	 
        $cadena_sql.="'".$variable['ult_estado']."', ";	 
        $cadena_sql.="'".$variable['ult_interno']."', ";
        $cadena_sql.="'".$variable['grado_ingresa']."', ";	 
        $cadena_sql.="'".$variable['nivel_ingresa']."', ";	 
        $cadena_sql.="'".$variable['sede_ingresa']."', ";	 
        $cadena_sql.="'".$variable['docente_ingresa']."', ";	 
        $cadena_sql.="'".$variable['EPS']."', ";	 
        $cadena_sql.="'".$variable['IPS']."', ";	 
        $cadena_sql.="'".$variable['tipo_sangre']."', ";	 
        $cadena_sql.="'".$variable['rh_sangre']."', ";	 
        $cadena_sql.="'".$variable['tipo_victima']."', ";	 
        $cadena_sql.="'".$variable['dept_expulsion']."', ";	 
        $cadena_sql.="'".$variable['munc_expulsion']."', ";	 
        $cadena_sql.="'".$variable['fecha_expulsion']."', ";	 
        $cadena_sql.="'".$variable['certif_expulsion']."', ";
        $cadena_sql.="'".$variable['discapacidad']."', ";	 
        $cadena_sql.="'".$variable['acud_nombre']."', ";
        $cadena_sql.="'".$variable['acud_nombre2']."', ";	 
        $cadena_sql.="'".$variable['acud_apellido']."', ";
        $cadena_sql.="'".$variable['acud_apellido2']."', ";	 
        $cadena_sql.="'".$variable['acud_tipo_documento']."', ";	 
        $cadena_sql.="'".$variable['acud_documento']."', ";	 
        $cadena_sql.="'".$variable['acud_dept_exp_doc']."', ";	 
        $cadena_sql.="'".$variable['acud_munc_exp_doc']."', ";	 
        $cadena_sql.="'".$variable['acud_parentezco']."', ";	 
        $cadena_sql.="'".$variable['acud_tipo']."', ";	 
        $cadena_sql.="'".$variable['acud_dir_residencia']."', ";	 
        $cadena_sql.="'".$variable['acud_telefono1']."', ";	 
        $cadena_sql.="'".$variable['acud_telefono2']."', ";	 
        $cadena_sql.="'1' "; 
		$cadena_sql.=")";
		break;

		case "logger":
			$cadena_sql="INSERT INTO ";
			$cadena_sql.=$prefijo."log ";
			$cadena_sql.="( ";
			$cadena_sql.="`id_usuario`, ";
			$cadena_sql.="`evento`, ";
			$cadena_sql.="`fecha` ";
			$cadena_sql.=") ";
			$cadena_sql.="VALUES ";
			$cadena_sql.="( ";
			$cadena_sql.="'".$variable['usuario']."', ";
			$cadena_sql.="'".$variable['evento']."', ";
			$cadena_sql.="'".time()."' ";
			$cadena_sql.=")";
			break;
		default:
		echo $tipo;
		}
		//echo "<br/><br/>".$cadena_sql;
		return $cadena_sql;

	}
}
?>
