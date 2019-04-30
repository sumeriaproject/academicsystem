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
include_once("plugin/html2pdf/html2pdf.class.php");
include_once("plugin/fpdf/fpdf.php");
include_once("class/Array.class.php");

class FuncionboletinFinal{

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


	function imprimirBoletin($variable)
    {
		$this->formulario->setSql($this->sql);
		$this->formulario->setRuta($this->ruta);

		//Rescato el listado de competencias para el grado actual
		$cadenaSql    = $this->sql->cadenaSql("competencias",$variable['grado']);
		$competencias = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
		$competenciasPorArea = $this->sorter->orderMultiKeyBy($competencias,"ID_AREA");

		//Consulto el listado de areas para el grado actual
		$cadenaSql = $this->sql->cadenaSql("areas",$variable['grado']);
		$areas = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");

		//Consultar nombres Sede, Curso, Area
		$cadenaSql = $this->sql->cadenaSql("sedeByID",$variable['sede']);
		$sedeByID  = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
		$sedeByID  = $sedeByID[0];

		$cadenaSql = $this->sql->cadenaSql("cursoByID",$variable['curso']);
		$cursoByID = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
		$cursoByID = $cursoByID[0];

        if( isset($variable['estudiante']) && !empty($variable['estudiante']) )
        {
          $cadenaSql = $this->sql->cadenaSql("estudianteByID",$variable['estudiante']);
          $listadoEstudiantes = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
        } else
        {
          $cadenaSql = $this->sql->cadenaSql("estudiantesPorCurso",$variable['curso']);
          $listadoEstudiantes = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
        }

        $this->loadPDFBoletin($listadoEstudiantes,$cursoByID,$sedeByID,$areas,$competenciasPorArea,$variable['grado']);

    }

    function loadPDFBoletin($listadoEstudiantes,$cursoByID,$sedeByID,$areas,$competenciasPorArea,$idGrado)
    {
		$pdf = new FPDF('P','mm','Letter'); //215.9 mm x 279.4 mm
		$pdf->SetMargins(10,10,10); //izq,arr,der
        $imageFolder = $this->miConfigurador->getVariableConfiguracion("raizDocumento").'/images/';

        $e=0;

        while(isset($listadoEstudiantes[$e][0])) {

            //Consulto las notas finales de los estudiante
            $variable['estudiante'] = $listadoEstudiantes[$e]['ID'];
            //Consultar las notas finales de los estudiante
            $cadenaSql = $this->sql->cadenaSql("notasFinalesPorEstudianteCriterios",$variable);
            $notasFinalesPorEstudianteCriterios = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
            $notasFinalesPorEstudianteCriterios = $this->sorter->orderKeyBy($notasFinalesPorEstudianteCriterios,"COMPETENCIA");

            $cadenaSql = $this->sql->cadenaSql("notasFinalesPorEstudianteCualitativa",$variable);
            $notasFinalesPorEstudianteCualitativa = $this->miRecursoDB->ejecutarAcceso($cadenaSql,"busqueda");
            $notasFinalesPorEstudianteCualitativa = $this->sorter->orderKeyBy($notasFinalesPorEstudianteCualitativa,"COMPETENCIA");

            //Organizar por competencias
            $notaEstudiante = $notasFinalesPorEstudianteCriterios + $notasFinalesPorEstudianteCualitativa;

            //Tipo de Boletin de Acuerdo al Grado del Estudiante
            switch($idGrado) {
                case 1: //Grado 0
                    include_once($this->ruta."/funcion/boletinPreescolar.php");
                break;
                default:
                    include_once($this->ruta."/funcion/boletinGeneral.php");
                break;
            }

            $e++;
        }

        $pdf->Output();
    }

	function action()
    {

		//Evitar que se ingrese codigo HTML y PHP en los campos de texto
		//Campos que se quieren excluir de la limpieza de código. Formato: nombreCampo1|nombreCampo2|nombreCampo3
		$excluir  = "";
		$_REQUEST = $this->miInspectorHTML->limpiarPHPHTML($_REQUEST);
		$option = isset($_REQUEST['option'])?$_REQUEST['option']:"list";
		switch($option)
        {
			case "processList":
				$variable["option"]="list";
				$variable["grado"]=$_REQUEST['grado'];
				$variable["sede"]=$_REQUEST['sede'];

				$this->miConfigurador->render("boletinFinal",$variable);

			break;
			case "imprimirBoletin":
				$this->imprimirBoletin($_REQUEST);
			break;
			case "imprimirBoletinLote":
				$this->imprimirBoletin($_REQUEST);
			break;
		}
	}


	function __construct()
	{
		$this->miConfigurador = Configurador::singleton();
		$this->miSesion = Sesion::singleton();
		$this->idSesion = $this->miSesion->getValorSesion('idUsuario');
		$this->miInspectorHTML = InspectorHTML::singleton();
		$this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");
		$this->miMensaje = Mensaje::singleton();
		$this->enlace = $this->miConfigurador->getVariableConfiguracion("host").$this->miConfigurador->getVariableConfiguracion("site")."?".$this->miConfigurador->getVariableConfiguracion("enlace");
		$conexion = "aplicativo";
		$this->miRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
		$this->sorter = orderArray::singleton();


	}

	public function setRuta($unaRuta)
    {
		$this->ruta = $unaRuta;
		//Incluir las funciones
	}

	function setSql($a)
    {
		$this->sql=$a;
	}

	function setFuncion($funcion){
		$this->funcion=$funcion;
	}

	public function setLenguaje($lenguaje)
	{
		$this->lenguaje=$lenguaje;
	}

	public function setFormulario($formulario)
    {
		$this->formulario=$formulario;
	}
}
