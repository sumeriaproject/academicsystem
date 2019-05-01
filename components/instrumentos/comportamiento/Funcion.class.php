<?php
if(!isset($GLOBALS["autorizado"])){
	include("../index.php");
	exit;
}

include_once("core/auth/Sesion.class.php");
include_once("core/manager/Context.class.php");
include_once("core/builder/Inspector.class.php");
include_once("core/builder/Mensaje.class.php");
include_once("core/crypto/Encriptador.class.php");
include_once("plugin/mail/class.phpmailer.php");
include_once("plugin/mail/class.smtp.php");
include_once("plugin/html2pdf/html2pdf.class.php");
include_once("plugin/fpdf/fpdf.php");
include_once("class/Array.class.php");

class Funcioncomportamiento{

	var $sql;
	var $funcion;
	var $lenguaje;
	var $ruta;
	var $context;
	var $inspector;
	var $error;
	var $resource;
	var $crypto;
	var $mensaje;
	var $status;


	function action(){

		//Evitar que se ingrese codigo HTML y PHP en los campos de texto
		//Campos que se quieren excluir de la limpieza de código. Formato: nombreCampo1|nombreCampo2|nombreCampo3
		$excluir="";
		$_REQUEST=$this->inspector->cleanPHPHTML($_REQUEST);

		$option=isset($_REQUEST['option'])?$_REQUEST['option']:"list";


		switch($option){
			case "processList":
				$variable["option"]="list";
				$variable["grado"]=$_REQUEST['grado'];
				$variable["sede"]=$_REQUEST['sede'];
        $variable["periodo"]=$_REQUEST['periodo'];
				$this->getComportamiento($variable);
			break;

      case "actualizarNota":
        $result = $this->procesarNota($_REQUEST);
        echo "Registro actualizado";
        /*if(!$result->status){
            echo json_encode($result);
            return false;
        }
        echo json_encode($result);*/

      break;
		}
	}

  function procesarNota($variable){

    $respuesta= new StdClass();

    if(isset($variable['nota']) && !empty($variable['periodo']) ) {
      $variable['nota'] = str_replace(",",".",$variable['nota']);
      $variable['nota'] = filter_var($variable['nota'],FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION);
      //1. Validar nota menor a 5
      if($variable['nota']>5){
        echo "La nota máxima es 5";
        return false;
      }
      //2. Consultar si la notas existen
      $cadenaSql = $this->sql->get("notaPorEstudiante",$variable);
      $notaPorEstudiante=$this->resource->execute($cadenaSql,"busqueda");
      //3. Si ya existe actualizar registro
      if(is_array($notaPorEstudiante)){
        $cadenaSql = $this->sql->get("actualizarNotaPerido",$variable);
        $notaPorEstudiante=$this->resource->execute($cadenaSql,"");
      }
      //4. Si no existe insertar
      else{
        $cadenaSql = $this->sql->get("insertarNotaPerido",$variable);
        $notaPorCriterio=$this->resource->execute($cadenaSql,"");
      }
    }else if(isset($variable['obs'])) {

      //1. Consultar si la notas existen
      $cadenaSql = $this->sql->get("notaPorEstudiante",$variable);
      $notaPorEstudiante=$this->resource->execute($cadenaSql,"busqueda");
      //2. Si ya existe actualizar registro
      if(is_array($notaPorEstudiante)){
        $cadenaSql = $this->sql->get("actualizarObsPerido",$variable);
        $notaPorEstudiante=$this->resource->execute($cadenaSql,"");
      }
      //3. Si no existe insertar
      else{
        $cadenaSql = $this->sql->get("insertarObsPerido",$variable);
        $notaPorCriterio=$this->resource->execute($cadenaSql,"");
      }
    }
  }



  function getComportamiento($variable) {

    $this->context->render("comportamiento",$variable);
  }

	function __construct() {

		$this->context=Context::singleton();
		$this->session=Sesion::singleton();
		$this->sessionId=$this->session->getValue('idUsuario');

		$this->inspector=Inspector::singleton();

		$this->ruta=$this->context->getVariable("rutaBloque");

		$this->miMensaje=Mensaje::singleton();
		$this->mail=new phpmailer();
		$this->enlace=$this->context->getVariable("host").$this->context->getVariable("site")."?".$this->context->getVariable("enlace");
		$conexion="aplicativo";
		$this->resource=$this->context->fabricaConexiones->getRecursoDB($conexion);
		$this->sorter=orderArray::singleton();


	}

	public function setRuta($unaRuta){
		$this->ruta=$unaRuta;
		//Incluir las funciones
	}

	function setSql($a){
		$this->sql=$a;
	}

	function setFuncion($funcion){
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
