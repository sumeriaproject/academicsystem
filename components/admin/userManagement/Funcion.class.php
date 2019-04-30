<?php
if(!isset($GLOBALS["autorizado"])){
	include("../index.php");
	exit;
}

include_once("core/auth/Sesion.class.php");
include_once("core/manager/Configurador.class.php");
include_once("core/builder/InspectorHTML.class.php");
include_once("core/builder/Mensaje.class.php");
include_once("core/crypto/Encriptador.class.php");
include_once("plugin/mail/class.phpmailer.php");
include_once("plugin/mail/class.smtp.php");

//Esta clase contiene la logica de negocio del bloque y extiende a la clase funcion general la cual encapsula los
//metodos mas utilizados en la aplicacion

//Para evitar redefiniciones de clases el nombre de la clase del archivo funcion debe corresponder al nombre del bloque
//en camel case precedido por la palabra Funcion

class FuncionuserManagement
{

	var $sql;
	var $funcion;
	var $lenguaje;
	var $ruta;
	var $miConfigurador;
	var $miInspectorHTML;
	var $error;
	var $miRecursoDB;
	var $crypto;
	var $mensaje;
	var $status;


	function processNew($variable){
		include_once($this->ruta."/funcion/processNew.php");
	}

	function processEdit($id){
		include_once($this->ruta."/funcion/processEdit.php");
	}

	function processEditStudent($id){
		include_once($this->ruta."/funcion/processEditStudent.php");
	}
  
	function processEditStudentEnroll($id){
		include_once($this->ruta."/funcion/processEditStudentEnroll.php");
	}

	function enviarCorreo($email,$clave)
	{
		include_once($this->ruta."/funcion/enviarCorreo.php");
	}

	function processDelete($id){
		include_once($this->ruta."/funcion/processDelete.php");
		return $status;
	}

	function redireccionar($option, $valor="") {
		include_once($this->ruta."/funcion/redireccionar.php");
	}

	function action() {

		//Evitar que se ingrese codigo HTML y PHP en los campos de texto
		//Campos que se quieren excluir de la limpieza de código. Formato: nombreCampo1|nombreCampo2|nombreCampo3
		$excluir="";
		$_REQUEST=$this->miInspectorHTML->limpiarPHPHTML($_REQUEST);

		$option=isset($_REQUEST['option'])?$_REQUEST['option']:"list";

		switch($option){
			case "processNew":

				$this->processNew($_REQUEST);

				if(!$this->status){
					$mensaje=implode("<br/>",$this->mensaje['error']);
					$this->redireccionar("falloRegistro",array($mensaje,$this->data));
				}else{
					$mensaje=implode("<br/>",$this->mensaje['exito']);

          $variable ="pagina=userManagement";
					$variable.="&tema=admin"; 
					$variable.="&option=edit";
					$variable.="&editRol=".$_REQUEST['role'][0];
					$variable.="&optionValue=".$this->new_id;
					$variable.="&mensaje=".$mensaje;
					$variable=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable,$this->enlace);

					echo "<script>location.replace('".$variable."')</script>";
				}

			break;
			case "processEdit":

        $this->processEdit($_REQUEST['optionValue']);

        if(!$this->status){
          $mensaje=implode("<br/>",$this->mensaje['error']);
					$this->redireccionar("falloRegistro",array($mensaje,$this->data));
        }else{
					$mensaje=implode("<br/>",$this->mensaje['exito']);

					$variable="pagina=userManagement";
					$variable.="&tema=admin";
					$variable.="&option=edit";
					$variable.="&optionValue=".$_REQUEST['userid'];
					$variable.="&mensaje=".$mensaje;
					$variable=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable,$this->enlace);

					echo "<script>location.replace('".$variable."')</script>";
        }
			break;
			case "processEditStudent":

        $this->processEditStudent($_REQUEST['optionValue']);

        if(!$this->status){
          $mensaje=implode("<br/>",$this->mensaje['error']);
					$this->redireccionar("falloRegistro",array($mensaje,$this->data));
        }else{
					$mensaje=implode("<br/>",$this->mensaje['exito']);

					$variable="pagina=userManagement";
					$variable.="&tema=admin";
					$variable.="&option=edit";
					$variable.="&editRol=3";
					$variable.="&optionValue=".$_REQUEST['userid'];
					$variable.="&mensaje=".$mensaje;
					$variable=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable,$this->enlace);

					echo "<script>location.replace('".$variable."')</script>";
        }
			break;
			case "processEditStudentEnroll":

        $this->processEditStudentEnroll($_REQUEST['optionValue']);

        $mensaje=implode("<br/>",$this->mensaje['exito']);

        $variable="pagina=userManagement";
        $variable.="&tema=admin";
        $variable.="&option=edit";
        $variable.="&editRol=3";
        $variable.="&enroll=true";
        $variable.="&optionValue=".$_REQUEST['optionValue'];
        $variable.="&mensaje=".$mensaje;
        $variable=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($variable,$this->enlace);

        echo "<script>location.replace('".$variable."')</script>";

			break;
			case "processDelete":

				$result=$this->processDelete($_REQUEST['optionValue']);

				if($result===TRUE){
						$variable["mensaje"]="El usuario se elimino correctamente";
				}else{
            $mensaje=implode("<br/>",$this->mensaje['error']);
						$variable["mensaje"]=$mensaje;
				}
				$variable["option"]="list";

				$this->miConfigurador->render("userManagement",$variable);

			break;
		}




	}


	function __construct()
	{

		$this->miConfigurador=Configurador::singleton();
		$this->miSesion=Sesion::singleton();
		$this->idSesion=$this->miSesion->getValorSesion('idUsuario');

		$this->miInspectorHTML=InspectorHTML::singleton();

		$this->ruta=$this->miConfigurador->getVariableConfiguracion("rutaBloque");

		$this->miMensaje=Mensaje::singleton();
		$this->mail=new phpmailer();
		$this->enlace=$this->miConfigurador->getVariableConfiguracion("host").$this->miConfigurador->getVariableConfiguracion("site")."?".$this->miConfigurador->getVariableConfiguracion("enlace");
		$conexion="aplicativo";
		$this->miRecursoDB=$this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		$this->pagina=new Pagina();


	}

	public function setRuta($unaRuta){
		$this->ruta=$unaRuta;
		//Incluir las funciones
	}

	function setSql($a)
	{
		$this->sql=$a;
	}

	function setFuncion($funcion)
	{
		$this->funcion=$funcion;
	}

	public function setLenguaje($lenguaje)
	{
		$this->lenguaje=$lenguaje;
	}

	public function setFormulario($formulario){
		$this->formulario=$formulario;
	}

}
?>
