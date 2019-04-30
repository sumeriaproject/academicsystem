<?
include_once("core/manager/Configurador.class.php");
include_once("core/auth/Sesion.class.php");
include_once("plugin/filter/generadorFiltros.class.php");

class ViewfichaFamiliar{

	var $ruta;
	var $sql;
	var $funcion;
	var $lenguaje;
	var $formulario;
	var $enlace;
	var $miConfigurador;
	var $companies;
	
	function __construct()
	{
	
		$this->miConfigurador=Configurador::singleton();
		$this->miRecursoDB=$this->miConfigurador->fabricaConexiones->getRecursoDB("aplicativo");
		$this->enlace=$this->miConfigurador->getVariableConfiguracion("host").$this->miConfigurador->getVariableConfiguracion("site")."?".$this->miConfigurador->getVariableConfiguracion("enlace");
		$this->miSesion=Sesion::singleton();
		$this->idSesion=$this->miSesion->getValorSesion('idUsuario');
		if($this->idSesion==""){
			echo "<br/><br/>***********Sesion cerrada**********<br/>";
		}
		
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

		$formSaraData="pagina=companyManagement";
		$formSaraData.="&optionValue=".$id;

		$option="&option=edit";
		$link['edit']=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($formSaraData.$option,$this->enlace);

	
		$option="&option=view";
		$link['view']=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($formSaraData.$option,$this->enlace);
		

		$formSaraData="jxajax=main";
		$formSaraData.="&action=companyManagement";
		$formSaraData.="&bloque=companyManagement";
	   	$formSaraData.="&bloqueGrupo=backReservame";
		$formSaraData.="&optionProcess=processDelete";
		$formSaraData.="&optionValue=".$id;
		$link['delete']=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($formSaraData,$this->enlace);
		

		$formSaraData="pagina=companyManagement";
		$formSaraData.="&bloque=companyManagement";
		$formSaraData.="&tema=admin";
		$formSaraData.="&bloqueGrupo=backReservame";
		$formSaraData.="&optionProcess=list";
		$link['postDelete']=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($formSaraData,$this->enlace);
		
		return $link;
	}




	function html(){
		

		$this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");
 		$option = isset($_REQUEST['option'])?$_REQUEST['option']:"edit";


		switch($option){
			case "list":
				$this->showList();
				break;
			case "new":
				$this->showNew();
				break;
			case "edit":
				$this->showEdit($_REQUEST['optionValue']);
				break;
			case "view":
				$this->showEdit($_REQUEST['optionValue']);
				break;

		}
		
	}
	

	/**
	* Consulta el listado de establecimientos asociados a cada usuario tambien denominado nivel de acceos 
	*/
	function companyByUser(){

		$cadena_sql=$this->sql->cadena_sql("companyByUser",$this->idSesion);
		$result=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");

		$cadena_sql=$this->sql->cadena_sql("companyListAll");
		$allCompanies=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");


		$this->companies=array();      
		

		$i=0;
		while(isset($result[$i]['IDCOMPANY'])){

			$this->companyByParent($result[$i]['IDCOMPANY'],$allCompanies);

			$i++;
		}

		return $this->companies;
	}


	function companyByParent($parent,$allCompanies) {
		
		/*$cadena_sql=$this->sql->cadena_sql("companyList",$parent);
		$result=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");*/
		$allCompaniesOrderByParent=$this->orderArrayKeyBy($allCompanies,"IDPARENT");

		//si el id tiene hijos asociados recorremos sus hijos
		if(array_key_exists($parent,$allCompaniesOrderByParent)){

			foreach($allCompaniesOrderByParent[$parent] as $key=>$value){
				$this->companyByParent($value['IDCOMPANY'],$allCompanies);
			}
		
		}
		/*if(is_array($result)){

			$i=0;
			while(isset($result[$i]['IDCOMPANY'])){

				$this->companyByParent($result[$i]['IDCOMPANY'],$allCompanies);
			
				$i++;
			}
		
		}*/else{
		
			//si el id no tiene hijos entonces lo agregamos a las empresas o establecimientos q pertenecen al usuario			
			$this->companies[]=$parent;
	
		}


	}	
    
    
    
	function loadFiltersByCommerce($type,$idcommerce) {

		$filter=new generadorFiltros();

		switch($type){
			case "1"://restaurantes
				$component=$filter->filterComponentList('GTR_RESTAURANTES');
				$component=$this->orderArrayKeyBy($component,"NOMBRE_COMPONENTE");
			break;
		}

		$cadena_sql=$this->sql->cadena_sql("commerceFilterList",$idcommerce);
		$commerce=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
 		$commerce=$this->orderArrayKeyBy($commerce,"IDOPTION");


		//se rescatan las opciones listado principal  si la opcion la encontramos las opciones asociadas al comercio
		//marcamos la opcion como true 

		foreach($component as $keyComponent=>$option){
			foreach($option as $keyOption=>$value){

				if (array_key_exists($value["ID_OPCION"],$commerce)){

				 	$component[$keyComponent][$keyOption]["CHECKED"]="true";	

				}else{

				 	$component[$keyComponent][$keyOption]["CHECKED"]="false";	

				}
			}
		}


	  return $component;

	} 




	function showEdit($id){

		$cadena_sql=$this->sql->cadena_sql("companyListbyID",$id);
		$company=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
		$company=$company[0];

		$cadena_sql=$this->sql->cadena_sql("commerceListbyCompany",$id);
		$commerce=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
		
		$cadena_sql=$this->sql->cadena_sql("categoryListCommerce");
		$categoryListCommerce=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");		
		


		$formSaraDataCommerce="bloque=fichaFamiliar";
		$formSaraDataCommerce.="&bloqueGrupo=estudiante";
		$formSaraDataCommerce.="&jxajax=main";
		$formSaraDataCommerce.="&action=fichaFamiliar";
		$formSaraDataCommerce.="&optionProcess=processEditCommerce";
		$formSaraDataCommerce.="&optionValue=1010001";
		$formSaraDataCommerce=$this->miConfigurador->fabricaConexiones->crypto->codificar_url($formSaraDataCommerce,$this->enlace);
		


		include_once($this->ruta."/html/edit.php");
	}


	function showView($id){

		$cadena_sql=$this->sql->cadena_sql("companyListbyID",$id);
		$company=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
		$company=$company[0];


		include_once($this->ruta."/html/view.php");
	}

	function showList(){

		$cadena_sql=$this->sql->cadena_sql("companyListbyID",implode(",",$this->companies));
		$companyList=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");

		include_once($this->ruta."/html/list.php");
	}

	function showNew(){

		$cadena_sql=$this->sql->cadena_sql("companyList");
		$companyList=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");

		$cadena_sql=$this->sql->cadena_sql("roleList");
		$roleList=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");

		$formSaraData="bloque=userManagement";
		$formSaraData.="&bloqueGrupo=admin";
		$formSaraData.="&action=userManagement";
		$formSaraData.="&option=processNew";
		$formSaraData=$this->miConfigurador->fabricaConexiones->crypto->codificar($formSaraData);

		include_once($this->ruta."/html/new.php");
	}


	function orderArrayKeyBy($array,$key){

		$newArray=array();

		foreach($array as $name=>$value){
			$newArray[$value[$key]][]=$array[$name];
		}
		/*echo "<pre>";
		var_dump($newArray);
		echo "</pre>";*/
		return $newArray;
	}






}
?>
