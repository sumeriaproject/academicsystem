  <?php
  include_once("core/manager/Configurador.class.php");
  include_once("core/auth/Sesion.class.php");
  include_once("class/controlAcceso.class.php");
  include_once("class/Array.class.php");
  include_once("plugin/fpdf/fpdf.php");

  class ViewuserManagement{

  	var $ruta;
  	var $sql;
  	var $funcion;
  	var $lenguaje;
  	var $formulario;
  	var $enlace;
  	var $miConfigurador;

  	function __construct(){

  		$this->miConfigurador  =  Configurador::singleton();
  		$this->miSesion        =  Sesion::singleton();
  		$this->miRecursoDB     =  $this->miConfigurador->fabricaConexiones->getRecursoDB("aplicativo");
  		$this->enlace          =  $this->miConfigurador->getVariableConfiguracion("host").$this->miConfigurador->getVariableConfiguracion("site")."?".$this->miConfigurador->getVariableConfiguracion("enlace");
  		$this->idSesion        =  $this->miSesion->getValorSesion('idUsuario');
  		$this->rol             =  $this->miSesion->getValorSesion('rol');

      if($this->idSesion == ""){
        $formSaraData="action=barraLogin";
        $formSaraData.="&bloque=barraLogin";
        $formSaraData.="&bloqueGrupo=gui";
        $formSaraData.="&opcionLogin=logout";
        $formSaraData=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($formSaraData,$this->enlace);
        echo "<script>location.replace('".$formSaraData."')</script>";
      }
  		$this->rol=$this->miSesion->getValorSesion('rol');
      $this->controlAcceso = new controlAcceso();
  		$this->controlAcceso->usuario = $this->idSesion;
  		$this->controlAcceso->rol = $this->rol;
      $this->organizador = orderArray::singleton();
  	}

  	public function setRuta($unaRuta){
  		$this->ruta=$unaRuta;
  	}

  	public function setLenguaje($lenguaje){
  		$this->lenguaje=$lenguaje;
  	}

  	public function setFormulario($formulario){
  		$this->formulario=$formulario;
  	}

  	function setSql($a)
  	{
  		$this->sql=$a;

  	}

  	function setFuncion($funcion){
  		$this->funcion=$funcion;

  	}

  	function getUrlLinksbyId($id){

  		$formSaraData.="pagina=userManagement";
  		$formSaraData.="&bloque=userManagement";
  		$formSaraData.="&bloqueGrupo=admin";
  		$formSaraData.="&optionValue=".$id;

  		$option="&option=edit";
  		$link['edit']=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($formSaraData.$option,$this->enlace);


  		$option="&option=view";
  		$link['view']=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($formSaraData.$option,$this->enlace);


  		$formSaraData="jxajax=main";
  		$formSaraData.="&action=userManagement";
  		$formSaraData.="&bloque=userManagement";
  	  $formSaraData.="&bloqueGrupo=admin";
  		$formSaraData.="&option=processDelete";
  		$formSaraData.="&optionValue=".$id;
  		$link['delete']=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($formSaraData,$this->enlace);

  		$formSaraData="pagina=userManagement";
  		$formSaraData.="&bloque=userManagement";
  		$formSaraData.="&tema=admin";
  		$formSaraData.="&bloqueGrupo=admin";
  		$formSaraData.="&option=list";
  		$link['postDelete']=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($formSaraData,$this->enlace);

  		return $link;
  	}


  	function html(){

  		$this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");
  		$option = isset($_REQUEST['option'])?$_REQUEST['option']:"listEstudiante";

  		switch($option){
  			case "list":
  				$this->showList($option);
  				break;
  			case "listEstudiante":
  				$_REQUEST['rol'] = 3;
  				if($this->rol == 1){
            $this->showList($option,3);
          }else{
            $this->showSimpleList();
          }
  				break;
  			case "listDocente":
  				$_REQUEST['rol'] = 2;
  				$this->showList($option,2);
  				break;
  			case "listAdministrador":
  				$_REQUEST['rol'] = 1;
          $this->showList($option);
  				break;
  			case "new":
  				$this->showNew();
  				break;
  			case "newStudent":
  				$this->showNewStudent();
  				break;
  			case "edit": 
          if(isset($_REQUEST['enroll'])) {
            $this->showEditEnroll($_REQUEST['optionValue']);
          }else { 
            $this->showEdit($_REQUEST['optionValue'],$_REQUEST['editRol']);
          }
  				break;
  			case "view":
  				$optionValue=isset($_REQUEST['optionValue'])?$_REQUEST['optionValue']:$this->idSesion;
  				$this->showView($optionValue);
  				break;
  			case "getMunicipio":
          echo $this->organizador->getMuncipiosColombia($_REQUEST['id']);
  				break;
  			case "printEnroll":
          $this->printEnroll($_REQUEST['optionValue']);
  				break;

  		}

  	}

    function printEnroll($id){
      
      $cadena_sql = $this->sql->cadena_sql("userListByID",$id);
  		$userDataByID=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
  		$userDataByID=$userDataByID[0];
      
  		$cadena_sql = $this->sql->cadena_sql("userEnrollByID",$id);
  		$userEnrollByID=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
  		$userEnrollByID=$userEnrollByID[0];
   
      $tipo_victima = array();
      $tipo_victima['DGA'] = "DESVINCULADOS DE GRUPOS ARMADOS";
      $tipo_victima['SD']  = "EN SITUACION DE DESPLAZAMIENTO";
      $tipo_victima['HAD'] = "HIJO DE ADULTOS DESMOVILIZADOS";
      $tipo_victima['NA']  = "NO APLICA";
      
      $discapacidad = array();
      $discapacidad['SP'] = "SORDERA PROFUNDA";
      $discapacidad['BV'] = "BAJA VISION DIAGNOSTICADA";
      $discapacidad['BA'] = "HIPOACUSIA-BAJA AUDICION";
      $discapacidad['PC'] = "PARALISIS CELEBRAL";
      $discapacidad['C']  = "CEGUERA";
      $discapacidad['A']  = "AUTISMO";
      $discapacidad['M']  = "MULTIPLE";
      $discapacidad['DC'] = "DEFICIENCIA COGNITIVA";
      $discapacidad['NA'] = "NO APLICA";
      
      $departamento = $this->organizador->getDeptosColombia();
   
      $sede = array();
      $sede['9']  = "BRISAS DE UPIN";
      $sede['16'] = "CANEY ALTO";
      $sede['2']  = "CANEY BAJO";
      $sede['10'] = "CHOAPAL"; 
      $sede['5']  = "FLORESTA"; 
      $sede['15'] = "MARAYAL";
      $sede['8']  = "MEDIOS DOS"; 
      $sede['13'] = "MIRALINDO";
      $sede['7']  = "SAN CARLOS";
      $sede['3']  = "SAN JUAN BOSCO";
      $sede['14'] = "SANTA LUCIA";
      $sede['4']  = "SARDINATA";
      $sede['6']  = "VEGA GRANDE";
      $sede['1']  = "VILLA REINA";
      $sede['12'] = "SAN ISIDRO";
                                              
      $cadena_sql  = $this->sql->cadena_sql("teacherList");
  		$teacherList = $this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
      $teacherList = $this->organizador->orderKeyBy($teacherList,'ID');
      //var_dump($teacherList); exit;
      
      $this->nextYear = ($this->miConfigurador->getVariableConfiguracion("anio"))*1+1;
      
      $pdf = new FPDF('P','mm','Letter'); //215.9 mm x 279.4 mm
  		$pdf->SetMargins(10,10,10); //izq,arr,der
      
      $pdf->AddPage();

        $y = $pdf->GetY();
        $pdf->Image('http://www.academia.ceruralrestrepo.com/cerural.jpg',10,10,25,25);
        $pdf->SetY($y);

        $pdf->SetFont('Arial','',10);
        $pdf->Cell(190,5,"DEPARTAMENTO DEL META - RESTREPO",0,0,'C');
        $pdf->Ln();
        $pdf->Cell(190,5,utf8_decode("SECRETARÍA DE EDUCACIÓN"),0,0,'C');
        $pdf->Ln();
        $pdf->SetFont('Arial','B',13);
        $pdf->Cell(190,5,"CENTRO EDUCATIVO RURAL DE RESTREPO",0,0,'C');
        $pdf->Ln();
        $pdf->SetFont('Arial','I',10);
        $pdf->Cell(190,5,"DANE 150606000396",0,0,'C');
        $pdf->Ln(6);
        $pdf->SetFont('Arial','B',13);
        $pdf->Cell(190,5,utf8_decode("FORMATO DE MATRICULA ".$this->nextYear),0,0,'C');
        $pdf->Ln(15);
        
        $pdf->SetFont('Arial','',9);
        
        $pdf->Cell(97.5,5,"TIPO DE ESTUDIANTE: ".$userEnrollByID['tipo_estudiante'],0);
        $pdf->Cell(97.5,5,"SEDE:".$sede[$userEnrollByID['sede_ingresa']],0); 
        $pdf->Ln(5);      
        $pdf->Cell(97.5,5,"DOCENTE: ".$teacherList[$userEnrollByID['docente_ingresa']]['NOMBRE']." ".$teacherList[$userEnrollByID['docente_ingresa']]['NOMBRE2']." ".$teacherList[$userEnrollByID['docente_ingresa']]['APELLIDO']." ".$teacherList[$userEnrollByID['docente_ingresa']]['APELLIDO2'],0);
        $pdf->Cell(97.5,5,"GRUPO: ".$userEnrollByID['grado_ingresa'],0);
         
        $pdf->Ln(6); 
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(195,6,utf8_decode("DATOS DE IDENTIFICACIÓN"),1);
        $pdf->Ln(6);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(97.5,5,"PRIMER NOMBRE: ".utf8_decode($userDataByID['NOMBRE']),1);
        $pdf->Cell(97.5,5,"SEGUNDO NOMBRE: ".utf8_decode($userDataByID['NOMBRE2']),1);
        $pdf->Ln(5);
        $pdf->Cell(97.5,5,"PRIMER APELLIDO: ".utf8_decode($userDataByID['APELLIDO']),1);
        $pdf->Cell(97.5,5,"SEGUNDO APELLIDO: ".utf8_decode($userDataByID['APELLIDO2']),1);
        $pdf->Ln(5);
        $pdf->Cell(97.5,5,utf8_decode("TIPO DE IDENTIFICACIÓN: ").$userEnrollByID['tipo_documento'],1);
        $pdf->Cell(97.5,5,utf8_decode("NÚMERO DE DOCUMENTO: ").$userDataByID['IDENT'],1); 
        $pdf->Ln(5);
        $pdf->Cell(97.5,5,utf8_decode("DEPTO DE EXPEDICIÓN: ").$departamento[$userEnrollByID['dept_exp_doc']],1);
        $pdf->Cell(97.5,5,utf8_decode("MUNCIPIO DE EXPEDICIÓN: ").$userEnrollByID['munc_exp_doc'],1); 
        $pdf->Ln(5);
        $pdf->Cell(97.5,5,utf8_decode("FECHA DE NACIMIENTO: ").$userEnrollByID['fecha_nacimiento'],1);
        $pdf->Cell(97.5,5,utf8_decode("GÉNERO: ").$userEnrollByID['genero'],1); 
        $pdf->Ln(5);
        $pdf->Cell(97.5,5,utf8_decode("DEPTO DE NACIMIENTO: ").$departamento[$userEnrollByID['dept_nacimiento']],1);
        $pdf->Cell(97.5,5,utf8_decode("MUNCIPIO DE NACIMIENTO: ").$userEnrollByID['munc_nacimiento'],1); 

        $pdf->Ln(9);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(195,6,utf8_decode("LUGAR DE RESIDENCIA"),1);
        $pdf->Ln(6);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(97.5,5,utf8_decode("DEPARTAMENTO: ").$departamento[$userEnrollByID['dept_residencia']],1);
        $pdf->Cell(97.5,5,utf8_decode("MUNCIPIO: ").$userEnrollByID['munc_residencia'],1); 
        $pdf->Ln(5);
        $pdf->Cell(97.5,5,utf8_decode("DIRECCIÓN: ").$userEnrollByID['dir_residencia'],1); 
        $pdf->Cell(48.75,5,utf8_decode("BARRIO: ").$userEnrollByID['barr_residencia'],1); 
        $pdf->Cell(48.75,5,utf8_decode("ZONA: ").$userEnrollByID['zona_residencia'],1); 
        
        $pdf->Ln(9);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(195,6,utf8_decode("INFORMACIÓN ACADÉMICA"),1);
        $pdf->Ln(6);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(65,5,utf8_decode("ÚLTIMO GRADO: ").$userEnrollByID['ult_grado'],1);
        $pdf->Cell(65,5,utf8_decode("ÚLTIMO AÑO: ").$userEnrollByID['ult_anio'],1);
        $pdf->Cell(65,5,utf8_decode("ESTADO: ").$userEnrollByID['ult_estado'],1);
        $pdf->Ln(5);
        $pdf->Cell(130,5,utf8_decode("ÚLTIMO PLANTEL: ").$userEnrollByID['ult_plantel'],1);
        $pdf->Cell(65,5,utf8_decode("INTERNO: ").$userEnrollByID['ult_interno'],1);
        
        $pdf->Ln(9);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(195,6,"SISTEMA DE SALUD",1);
        $pdf->Ln(6);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(97.5,5,utf8_decode("EPS AFILIADO: ").$userEnrollByID['EPS'],1);
        $pdf->Cell(97.5,5,utf8_decode("IPS ASIGNADA: ").$userEnrollByID['IPS'],1);
        $pdf->Ln(5);
        $pdf->Cell(97.5,5,utf8_decode("TIPO DE SANGRE: ").$userEnrollByID['tipo_sangre'],1);
        $pdf->Cell(97.5,5,utf8_decode("RH: ").$userEnrollByID['rh_sangre'],1);
        
        $pdf->Ln(9);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(195,6,"PROGRAMAS ESPECIALES (UNICAMENTE PARA LA POBLACION VICTIMA DEL CONFLICTO)",1);
        $pdf->Ln(6);
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(195,5,utf8_decode("TIPO DE VICTIMA: ").$tipo_victima[$userEnrollByID['tipo_victima']],1);
        $pdf->Ln(5);
        $pdf->Cell(97.5,5,utf8_decode("DEPARTAMENTO DE EXPULSIÓN: ").$departamento[$userEnrollByID['dept_expulsion']],1);
        $pdf->Cell(97.5,5,utf8_decode("MUNCIPIO DE EXPULSIÓN: ").$userEnrollByID['munc_expulsion'],1); 
        $pdf->Ln(5); 
        $pdf->Cell(97.5,5,utf8_decode("FECHA DE EXPULSIÓN: ").$userEnrollByID['fecha_expulsion'],1);
        $pdf->Cell(97.5,5,utf8_decode("CERTIFICADO: ").$userEnrollByID['certif_expulsion'],1); 
        
        $pdf->Ln(9);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(195,6,"DISCAPACIDADES Y CAPACIDADES EXCEPCIONALES",1);
        $pdf->Ln(6);
        $pdf->SetFont('Arial','',9);  
        $pdf->Cell(195,5,"TIPO DE DISCAPACIDAD: ".$discapacidad[$userEnrollByID['discapacidad']],1);
      
        $pdf->Ln(9);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(195,6,utf8_decode("INFORMACIÓN FAMILIAR"),1);
        $pdf->Ln(6); 
        $pdf->SetFont('Arial','',9);
        $pdf->Cell(97.5,5,"PRIMER NOMBRE: ".utf8_decode($userEnrollByID['acud_nombre']),1);
        $pdf->Cell(97.5,5,"SEGUNDO NOMBRE: ".utf8_decode($userEnrollByID['acud_nombre2']),1);
        $pdf->Ln(5);
        $pdf->Cell(97.5,5,"PRIMER APELLIDO: ".utf8_decode($userEnrollByID['acud_apellido']),1);
        $pdf->Cell(97.5,5,"SEGUNDO APELLIDO: ".utf8_decode($userEnrollByID['acud_apellido2']),1);
        $pdf->Ln(5);
        $pdf->Cell(97.5,5,utf8_decode("TIPO DE IDENTIFICACIÓN: ").$userEnrollByID['acud_tipo_documento'],1);
        $pdf->Cell(97.5,5,utf8_decode("NÚMERO DE DOCUMENTO: ").$userEnrollByID['acud_documento'],1); 
        $pdf->Ln(5);
        $pdf->Cell(97.5,5,utf8_decode("DEPTO DE EXPEDICIÓN: ").$departamento[$userEnrollByID['acud_dept_exp_doc']],1);
        $pdf->Cell(97.5,5,utf8_decode("MUNCIPIO DE EXPEDICIÓN: ").$userEnrollByID['acud_munc_exp_doc'],1); 
        $pdf->Ln(5);
        $pdf->Cell(97.5,5,utf8_decode("PARENTEZCO: ").$userEnrollByID['acud_parentezco'],1);
        $pdf->Cell(97.5,5,utf8_decode("ACUDIENTE: ").$userEnrollByID['acud_tipo'],1); 
        $pdf->Ln(5);
        $pdf->Cell(97.5,5,utf8_decode("DIRECCIÓN: ").$userEnrollByID['acud_dir_residencia'],1); 
        $pdf->Cell(48.75,5,utf8_decode("TELÉFONO: ").$userEnrollByID['acud_telefono1'],1); 
        $pdf->Cell(48.75,5,utf8_decode("TELÉF.TRABAJO: ").$userEnrollByID['acud_telefono2'],1); 
        $pdf->Ln(18);
        $pdf->SetFont('Arial','B',8); 
        $pdf->Cell(65,2,utf8_decode("_____________________________"),0,0,'C');
        $pdf->Cell(65,2,utf8_decode("_____________________________"),0,0,'C');
        //$pdf->Cell(65,2,utf8_decode("_____________________________"),0,0,'C');
        $pdf->Ln(3);
        $pdf->Cell(65,3,"FIRMA ESTUDIANTE",0,0,'C'); 
        $pdf->Cell(65,3,"FIRMA PADRE O ACUDIENTE",0,0,'C');
        //$pdf->Cell(65,3,"FIRMA DOCENTE",0,0,'C');
      
        $pdf->Output();
    }
    
  	function showEdit($id,$rol){

  		$cadena_sql = $this->sql->cadena_sql("userListByID",$id);
  		$userDataByID=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
  		$userDataByID=$userDataByID[0];
   
      //print_r($userDataByID,TRUE)
   
  		$cadena_sql = $this->sql->cadena_sql("espacioList");
  		$espacioList=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");

  		$cadena_sql = $this->sql->cadena_sql("sedeList");
  		$sedeList=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");

  		$cadena_sql = $this->sql->cadena_sql("roleList");
  		$roleList=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");

  		$cadena_sql = $this->sql->cadena_sql("courseList");
  		$courseList=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");

  		$cadena_sql = $this->sql->cadena_sql("courseByUser",$id);
  		$courseByUser=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
  		$courseByUser=explode(",",$courseByUser[0]['COURSES']);

  		$courseList=$this->orderArrayMultiKeyBy($courseList,'NAMESEDE');

  		$formSaraData="bloque=userManagement";
  		$formSaraData.="&bloqueGrupo=admin";
  		$formSaraData.="&action=userManagement";
  		$formSaraData.="&option=processEdit";
  		$formSaraData.="&optionValue=".$id;
  		$formSaraData=$this->miConfigurador->fabricaConexiones->crypto->codificar($formSaraData);

  		if($rol<>"3") {

  			$formSaraData="bloque=userManagement";
  			$formSaraData.="&bloqueGrupo=admin";
  			$formSaraData.="&action=userManagement";
  			$formSaraData.="&option=processEdit";
  			$formSaraData.="&optionValue=".$id;
  			$formSaraData=$this->miConfigurador->fabricaConexiones->crypto->codificar($formSaraData);

  			include_once($this->ruta."/html/edit.php");
  		}else {

  			$formSaraData  = "bloque=userManagement";
  			$formSaraData .= "&bloqueGrupo=admin";
  			$formSaraData .= "&action=userManagement";
  			$formSaraData .= "&option=processEditStudent";
  			$formSaraData .= "&optionValue=".$id;
  			$formSaraData  = $this->miConfigurador->fabricaConexiones->crypto->codificar($formSaraData);
          
        $formSaraDataEdit = "pagina=userManagement";
        $formSaraDataEdit .= "&bloque=userManagement";
        $formSaraDataEdit .= "&bloqueGrupo=admin";
        $formSaraDataEdit .= "&option=edit";
  			$formSaraDataEdit .= "&optionValue=".$id;
        $formSaraDataEdit .= "&editRol=3";
        $formSaraDataEdit = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($formSaraDataEdit,$this->enlace);

  			include_once($this->ruta."/html/editStudent.php");
  		}
  	}

  	function showEditEnroll($id,$rol=3){ 

  		$cadena_sql = $this->sql->cadena_sql("userListByID",$id);
  		$userDataByID=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
  		$userDataByID=$userDataByID[0];
      
  		$cadena_sql = $this->sql->cadena_sql("userEnrollByID",$id);
  		$userEnrollByID=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
  		$userEnrollByID=$userEnrollByID[0];
   
      //var_dump($userEnrollByID);
   
  		$cadena_sql = $this->sql->cadena_sql("espacioList");
  		$espacioList=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");

  		$cadena_sql = $this->sql->cadena_sql("sedeList");
  		$sedeList=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
      
  		$cadena_sql  = $this->sql->cadena_sql("teacherList");
  		$teacherList = $this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");

  		$cadena_sql = $this->sql->cadena_sql("roleList");
  		$roleList=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");

  		$cadena_sql = $this->sql->cadena_sql("courseList");
  		$courseList=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");

  		$cadena_sql = $this->sql->cadena_sql("courseByUser",$id);
  		$courseByUser=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
  		$courseByUser=explode(",",$courseByUser[0]['COURSES']);

  		$courseList=$this->orderArrayMultiKeyBy($courseList,'NAMESEDE');

      $formSaraData="bloque=userManagement";
      $formSaraData.="&bloqueGrupo=admin";
      $formSaraData.="&action=userManagement";
      $formSaraData.="&option=processEditStudentEnroll";
      $formSaraData.="&optionValue=".$id;
      $formSaraData=$this->miConfigurador->fabricaConexiones->crypto->codificar($formSaraData);
      
  		$formSaraDataMun = "pagina=userManagement";
  		$formSaraDataMun .= "&bloque=userManagement";
  		$formSaraDataMun .= "&bloqueGrupo=admin";
  		$formSaraDataMun .= "&option=getMunicipio";
  		$formSaraDataMun .= "&jxajax=getMunicipio";
  		$formSaraDataMun = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($formSaraDataMun,$this->enlace);
     
  		$formSaraDataPrint = "pagina=userManagement";
  		$formSaraDataPrint .= "&bloque=userManagement";
  		$formSaraDataPrint .= "&bloqueGrupo=admin";
  		$formSaraDataPrint .= "&option=printEnroll";
  		$formSaraDataPrint .= "&jxajax=userManagement";
      $formSaraDataPrint .= "&optionValue=".$id;
  		$formSaraDataPrint = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($formSaraDataPrint,$this->enlace);

      
      $departamentos = $this->organizador->getDeptosColombia();
      
      
      include_once($this->ruta."/html/editStudentEnroll.php");
  	}

  	function showView($id){

  		$cadena_sql = $this->sql->cadena_sql("userListByID",$id);
  		$userDataByID = $this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
  		$userDataByID = $userDataByID[0];

  		$cadena_sql = $this->sql->cadena_sql("roleList");
  		$roleList=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");

  		$cadena_sql = $this->sql->cadena_sql("sedeList");
  		$sedeList = $this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");

  		$cadena_sql = $this->sql->cadena_sql("espacioList");
  		$espacioList=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");

  		$link=$this->getUrlLinksbyId($id);

  		include_once($this->ruta."/html/view.php");
  	}

  	function showList($option,$rol){

  		$cadena_sql = $this->sql->cadena_sql("espacioList");
  		$espacioList=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
  		$espacioList=$this->orderArrayKeyBy($espacioList,"ID");

  		$cadena_sql = $this->sql->cadena_sql("sedeList");
  		$sedeList=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
  		$sedeList=$this->orderArrayKeyBy($sedeList,"ID");

  		$formSaraData="jxajax=main";
  		$formSaraData.="&pagina=userManagement";
  		$formSaraData.="&action=userManagement";
  		$formSaraData.="&bloque=userManagement";
  		$formSaraData.="&bloqueGrupo=admin";
  		$formSaraData.="&option=new";
  		$linkUserNew=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($formSaraData,$this->enlace);


  		$formSaraDataUrl = "pagina=userManagement";
  		$formSaraDataUrl .= "&option=".$option;
  		$formSaraDataUrl = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($formSaraDataUrl,$this->enlace);


  		$formSaraDataEdit = "pagina=userManagement";
  		$formSaraDataEdit .= "&bloque=userManagement";
  		$formSaraDataEdit .= "&bloqueGrupo=admin";
  		$formSaraDataEdit .= "&option=edit";
  		$formSaraDataEdit .= "&editRol=".$rol;
  		$formSaraDataEdit = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($formSaraDataEdit,$this->enlace);


  		$formSaraDataDelete="action=userManagement";
  		$formSaraDataDelete.="&bloque=userManagement";
  		$formSaraDataDelete.="&bloqueGrupo=admin";
  		$formSaraDataDelete.="&option=processDelete";
  		$formSaraDataDelete=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($formSaraDataDelete,$this->enlace);


  		$cadena_sql = $this->sql->cadena_sql("sedeList");
  		$sedeList=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
  		$sedeList=$this->orderArrayKeyBy($sedeList,"ID");

  		$cadena_sql = $this->sql->cadena_sql("courseList");
  		$courseList=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
  		$courseList=$this->orderArrayKeyBy($courseList,"IDCOURSE");

  		$cadena_sql = $this->sql->cadena_sql("roleList");
  		$roleList=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
  		$roleList=$this->orderArrayKeyBy($roleList,"ID");

  		$variable['sede']=isset($_REQUEST['sede'])?$_REQUEST['sede']:"";
  		$variable['rol']=isset($_REQUEST['rol'])?$_REQUEST['rol']:"";

  		$cadena_sql = $this->sql->cadena_sql("userList",$variable);
  		$userAllList=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
  		$userAllList=$this->orderArrayKeyBy($userAllList,"ID");



  		/*$cadena_sql = $this->sql->cadena_sql("UserListbySede",$sede);
  		$userListbySede=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
  		$userListbySede=$this->orderArrayKeyBy($userListbySede,"IDUSER");
  		*/

  		$cadena_sql = $this->sql->cadena_sql("UserListbyCourse");
  		$userListbyCourse=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
  		$userListbyCourse=$this->orderArrayKeyBy($userListbyCourse,"IDUSER");

  		$cadena_sql = $this->sql->cadena_sql("UserListbyRole");
  		$userListbyRole=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
  		$userListbyRole=$this->orderArrayMultiKeyBy($userListbyRole,"IDUSER");


  		//$userList=array_intersect_key($userAllList,$userListbySede);
  		$userList=$userAllList;//array_intersect_key($userAllList,$userListbySede);

  		include_once($this->ruta."/html/list.php");
  	}

  	function showSimpleList(){

      //1.Consulto el listado de sedes y grados permitidos para el usuario
  		$access = $this->controlAcceso->getAccesoCompleto();

  		$cadena_sql  = $this->sql->cadena_sql("UserListWithAcces",$variable);
  		$result      = $this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
      $userAllList = $this->organizador->orderMultiTwoKeyBy($result,"SEDE_ID","CURSO_ID");

      $formSaraDataEdit="pagina=userManagement";
  		$formSaraDataEdit.="&bloque=userManagement";
  		$formSaraDataEdit.="&bloqueGrupo=admin";
  		$formSaraDataEdit.="&option=view";
  		$formSaraDataEdit=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($formSaraDataEdit,$this->enlace);


  		include_once($this->ruta."/html/simpleList.php");
  	}

  	function showNew(){

  		$cadena_sql = $this->sql->cadena_sql("roleList");
  		$roleList = $this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");

  		$cadena_sql = $this->sql->cadena_sql("sedeList");
  		$sedeList = $this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");

  		$cadena_sql = $this->sql->cadena_sql("courseList");
  		$courseList = $this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
  		$courseList = $this->orderArrayMultiKeyBy($courseList,'NAMESEDE');
  		
      $s=0;

  		while(isset($sedeList[$s][0])){
  			$cadena_sql = $this->sql->cadena_sql("lastIdByCourse",$sedeList[$s]['ID']);
  			$result = $this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");

        $lastID = $result[0][0];

        do{
          $cadena_sql = $this->sql->cadena_sql("basicUserByID",$lastID);
          $exist = $this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");

          if(is_array($exist)) {
          	$lastID++;
          }

        } while (is_array($exist));

        $codes[$sedeList[$s]['NOMBRE']] = $lastID;

        $s++;
  		}

  		$formSaraData = "bloque=userManagement";
  		$formSaraData .= "&bloqueGrupo=admin";
  		$formSaraData .= "&action=userManagement";
  		$formSaraData .= "&option=processNew";
  		$formSaraData = $this->miConfigurador->fabricaConexiones->crypto->codificar($formSaraData);

  		include_once($this->ruta."/html/new.php");
  	}
    
    function showNewStudent()

    {

  		$cadena_sql = $this->sql->cadena_sql("roleList");
  		$roleList = $this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");

  		$cadena_sql = $this->sql->cadena_sql("sedeList");
  		$sedeList = $this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");

  		$cadena_sql = $this->sql->cadena_sql("courseList");
  		$courseList = $this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");

  		$courseList = $this->orderArrayMultiKeyBy($courseList,'NAMESEDE');
  			$s=0;
  		while(isset($sedeList[$s][0])){
  			$cadena_sql = $this->sql->cadena_sql("lastIdByCourse",$sedeList[$s]['ID']);
  			$result = $this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");

        $lastID = $result[0][0];

        do{
          $cadena_sql = $this->sql->cadena_sql("basicUserByID",$lastID);
          $exist = $this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");

          if(is_array($exist)) {
          	$lastID++;
          }

        } while (is_array($exist));

        $codes[$sedeList[$s]['NOMBRE']] = $lastID;

        $s++;
  		}

  		$formSaraData = "bloque=userManagement";
  		$formSaraData .= "&bloqueGrupo=admin";
  		$formSaraData .= "&action=userManagement";
  		$formSaraData .= "&option=processNew";
  		$formSaraData = $this->miConfigurador->fabricaConexiones->crypto->codificar($formSaraData);

  		include_once($this->ruta."/html/newStudent.php");
  	}

  	function orderArrayKeyBy($array,$key){
  		$newArray = array();
  		foreach($array as $name=>$value){
  			$newArray[$value[$key]]=$array[$name];
  		}
  		return $newArray;
  	}

  	function orderArrayMultiKeyBy($array,$key){

  		$newArray=array();

  		foreach($array as $name=>$value){
  			$newArray[$value[$key]][]=$array[$name];
  		}

  		return $newArray;
  	}

  }

