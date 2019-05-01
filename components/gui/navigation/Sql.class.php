<?php

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}

include_once("core/manager/Context.class.php");
include_once("core/connection/Sql.class.php");

//Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
//en camel case precedida por la palabra sql

class SqlNavigation extends sql {
	
	
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
			 
			/**
			 * Clausulas específicas
			 */
			 
			case "userList":
				$cadena_sql="SELECT ";
				$cadena_sql.="u.id_usuario ID, ";
				$cadena_sql.="CONCAT(u.nombre,' ',apellido) NOMBRE,";
				$cadena_sql.="GROUP_CONCAT( DISTINCT (eu.id_establecimiento))  EMPRESA, ";				 
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
				$cadena_sql.="LEFT JOIN ";
				$cadena_sql.=$prefijo."usuario_establecimiento eu ";
				$cadena_sql.="ON ";
				$cadena_sql.="u.id_usuario = eu.id_usuario ";
				$cadena_sql.="GROUP BY u.id_usuario ";
				break;

			case "userListByID":
				$cadena_sql="SELECT ";
				$cadena_sql.="u.id_usuario ID, ";
				$cadena_sql.="u.nombre NOMBRE,";
				$cadena_sql.="u.apellido APELLIDO,";
				$cadena_sql.="GROUP_CONCAT( DISTINCT (eu.id_establecimiento))  EMPRESA, ";				 
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
				$cadena_sql.="LEFT JOIN ";
				$cadena_sql.=$prefijo."usuario_establecimiento eu ";
				$cadena_sql.="ON ";
				$cadena_sql.="u.id_usuario = eu.id_usuario ";
				$cadena_sql.="WHERE u.id_usuario=".$variable;
				$cadena_sql.=" GROUP BY u.id_usuario ";
				break;

			case "roleList":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_subsistema ID, ";
				$cadena_sql.="nombre ROL ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."subsistema ";
				break;

			case "menuList":
				$cadena_sql="SELECT ";
				$cadena_sql.="m.nombre NOMBRE, ";
				$cadena_sql.="m.padre PADRE, ";
				$cadena_sql.="m.rol ROL, ";
				$cadena_sql.="m.tema TEMA, ";
				$cadena_sql.="m.lenguaje IDIOMA, ";
				$cadena_sql.="m.parametro PARAMETRO, ";
				$cadena_sql.="(SELECT p.nombre FROM {$prefijo}pagina p WHERE p.id_pagina=m.id_pagina ) PAGINA, ";
				$cadena_sql.="m.id_menu IDMENU ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."menu m ";
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.=$prefijo."subsistema s ";
				$cadena_sql.="ON ";
				$cadena_sql.="m.rol = s.id_subsistema ";
				$cadena_sql.="WHERE ";
				$cadena_sql.="m.rol ='".$variable."' ";

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


		}

		return $cadena_sql;

	}
}
?>
