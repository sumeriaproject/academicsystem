<?php

if(!isset($GLOBALS["autorizado"])) {
	include("../index.php");
	exit;
}

include_once("core/manager/Context.class.php");
include_once("core/connection/Sql.class.php");

//Para evitar redefiniciones de clases el nombre de la clase del archivo sqle debe corresponder al nombre del bloque
//en camel case precedida por la palabra sql

class SqlfichaFamiliar extends sql {
	
	
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

		 
		switch($tipo) {
			 
			/**
			 * Clausulas específicas
			 */
			 
			case "companyByUser":
				$cadena_sql="SELECT ";
				$cadena_sql.="u.id_usuario IDUSER, ";
				$cadena_sql.="e.id_establecimiento IDCOMPANY, ";
				$cadena_sql.="e.id_parent IDPARENT ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."usuario u ";
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.=$prefijo."usuario_establecimiento ue ";
				$cadena_sql.="ON ";
				$cadena_sql.="u.id_usuario = ue.id_usuario ";
				$cadena_sql.="INNER JOIN ";
				$cadena_sql.=$prefijo."establecimiento e ";
				$cadena_sql.="ON ";
				$cadena_sql.="ue.id_establecimiento = e.id_establecimiento ";
				$cadena_sql.="WHERE u.id_usuario=".$variable;
				break;

			case "companyList":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_establecimiento IDCOMPANY, ";
				$cadena_sql.="id_parent IDPARENT ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."establecimiento ";
				$cadena_sql.="WHERE id_parent=".$variable;
				break;
				
			case "categoryListCommerce":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_claTipoReserva IDCATCOMMERCE, ";
				$cadena_sql.="nombre NAME ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."clasificacion_tipo_reseva ";
				$cadena_sql.="WHERE estado='1'";
				break;

				
			case "updateDataCompany":
				$cadena_sql="UPDATE ";
				$cadena_sql.=$prefijo."establecimiento ";
				$cadena_sql.="SET ";
				$cadena_sql.="nombre='".$variable['nombre']."',";
				$cadena_sql.="descripcion='".$variable['descripcion']."',";
				$cadena_sql.="contacto='".$variable['contacto']."',";
				$cadena_sql.="url='".$variable['url']."',";
				$cadena_sql.="email='".$variable['email']."',";
				$cadena_sql.="telefonos='".$variable['telefono']."',";
				$cadena_sql.="direccion='".$variable['direccion']."' ";
				$cadena_sql.="WHERE id_establecimiento=".$variable['optionValue'];

				break;

			case "updateDataCommerceBasic":
				$cadena_sql="UPDATE ";
				$cadena_sql.=$prefijo."tipo_reserva ";
				$cadena_sql.="SET ";
				$cadena_sql.="id_claTipoReserva='".$variable['commercetype']."',";
				$cadena_sql.="metodo_reserva='".$variable['method']."',";
				$cadena_sql.="nombre='".$variable['nombre']."',";
				$cadena_sql.="descripcion='".$variable['descripcion']."',";
				$cadena_sql.="capacidad='".$variable['capacidad']."',";
				$cadena_sql.="direccion='".$variable['direccion']."' ";
				$cadena_sql.="WHERE id_tipoReserva=".$variable['optionValue'];
				break;

			case "updateDataCommerceTime":
				$cadena_sql="UPDATE ";
				$cadena_sql.=$prefijo."tipo_reserva ";
				$cadena_sql.="SET ";
				$cadena_sql.="intervalo_reserva='".$variable['intervalo']."', ";
				$cadena_sql.="hora_inicio='".$variable['horapertura']."', ";
				$cadena_sql.="hora_cierre='".$variable['horacierre']."' ";
				$cadena_sql.="WHERE id_tipoReserva=".$variable['optionValue'];
				break;

			case "deleteDataCommerceFeatures":
				$cadena_sql="DELETE ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."tipo_reserva_filtrador  ";
				$cadena_sql.="WHERE id_tipoReserva=".$variable['optionValue'];
				break;

			case "deleteDataCompany":
				$cadena_sql="DELETE ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."establecimiento  ";
				$cadena_sql.="WHERE id_establecimiento=".$variable['optionValue'];
				break;

			case "deleteDataCommmerce":
				$cadena_sql="DELETE ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."tipo_reserva  ";
				$cadena_sql.="WHERE id_establecimiento=".$variable['optionValue'];
				break; 
				
			case "insertDataCommerceFeatures":
				$cadena_sql="INSERT INTO ";
				$cadena_sql.=$prefijo."tipo_reserva_filtrador  ";
				$cadena_sql.="VALUES( ";
				$cadena_sql.="'".$variable['optionValFeature']."',";
				$cadena_sql.="'".$variable['optionValue']."',";
				$cadena_sql.="'1'";
				$cadena_sql.=") ";
				break;


			case "companyListbyID":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_establecimiento IDCOMPANY, ";
				$cadena_sql.="id_parent IDPARENT, ";
				$cadena_sql.="nombre NOMBRE, ";
				$cadena_sql.="descripcion DESCRIPCION, ";
				$cadena_sql.="contacto CONTACTO, ";
				$cadena_sql.="url URL, ";
				$cadena_sql.="email EMAIL, ";
				$cadena_sql.="telefonos TELEFONOS, ";
				$cadena_sql.="direccion DIRECCION ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."establecimiento ";
				$cadena_sql.="WHERE id_establecimiento IN (".$variable.") ";
				$cadena_sql.="AND estado<>0";
				break;


			case "commerceListbyCompany":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_tipoReserva IDCOMMERCE, ";
				$cadena_sql.="id_establecimiento IDCOMPANY, ";
				$cadena_sql.="nombre NAME, ";
				$cadena_sql.="tr.id_claTipoReserva IDTYPE, ";
				$cadena_sql.="(SELECT nombre FROM {$prefijo}clasificacion_tipo_reseva ctr WHERE ctr.id_claTipoReserva=tr.id_claTipoReserva) NAMETYPE, ";
				$cadena_sql.="capacidad CAPACITY, ";
				$cadena_sql.="metodo_reserva METHOD, ";
				$cadena_sql.="hora_inicio STARTTIME, ";
				$cadena_sql.="hora_cierre ENDTIME, ";
				$cadena_sql.="descripcion DESCRIPTION, ";
				$cadena_sql.="intervalo_reserva INTERVALO, ";
				//$cadena_sql.="contacto CONTACTO, ";
				//$cadena_sql.="url URL, ";
				//$cadena_sql.="email EMAIL, ";
				//$cadena_sql.="telefonos TELEFONOS, ";
				$cadena_sql.="direccion ADDRESS ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."tipo_reserva tr ";
				$cadena_sql.="WHERE id_establecimiento IN (".$variable.") ";
				$cadena_sql.="AND estado<>0";
				break;

			case "companyListAll":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_establecimiento IDCOMPANY, ";
				$cadena_sql.="id_parent IDPARENT ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."establecimiento ";
				break;

			case "commerceFilterList":
				$cadena_sql="SELECT ";
				$cadena_sql.="id_filtroOpcion IDOPTION ";
				$cadena_sql.="FROM ";
				$cadena_sql.=$prefijo."tipo_reserva_filtrador ";
				$cadena_sql.="WHERE id_tipoReserva IN (".$variable.") ";
				$cadena_sql.="AND estado<>0";
				break;

		}

		//echo "<br/><br/>$tipo=".$cadena_sql;

		return $cadena_sql;

	}
}
?>
