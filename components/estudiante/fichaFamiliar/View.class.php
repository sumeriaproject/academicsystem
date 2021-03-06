<?
include_once("core/manager/Context.class.php");
include_once("core/auth/Sesion.class.php");
include_once("plugin/filter/generadorFiltros.class.php");

class ViewfichaFamiliar{

	var $ruta;
	var $sql;
	var $funcion;
	var $lenguaje;
	var $formulario;
	var $enlace;
	var $context;
	var $companies;
	
	function __construct()
	{
	
		$this->context=Context::singleton();
		$this->resource=$this->context->fabricaConexiones->getRecursoDB("aplicativo");
		$this->enlace=$this->context->getVariable("host").$this->context->getVariable("site")."?".$this->context->getVariable("enlace");
		$this->session=Sesion::singleton();
		$this->sessionId=$this->session->getValue('idUsuario');
		if($this->sessionId==""){
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
		$link['edit']=$this->context->fabricaConexiones->crypto->codificar_url($formSaraData.$option,$this->enlace);

	
		$option="&option=view";
		$link['view']=$this->context->fabricaConexiones->crypto->codificar_url($formSaraData.$option,$this->enlace);
		

		$formSaraData="jxajax=main";
		$formSaraData.="&action=companyManagement";
		$formSaraData.="&bloque=companyManagement";
	   	$formSaraData.="&bloqueGrupo=backReservame";
		$formSaraData.="&optionProcess=processDelete";
		$formSaraData.="&optionValue=".$id;
		$link['delete']=$this->context->fabricaConexiones->crypto->codificar_url($formSaraData,$this->enlace);
		

		$formSaraData="pagina=companyManagement";
		$formSaraData.="&bloque=companyManagement";
		$formSaraData.="&tema=admin";
		$formSaraData.="&bloqueGrupo=backReservame";
		$formSaraData.="&optionProcess=list";
		$link['postDelete']=$this->context->fabricaConexiones->crypto->codificar_url($formSaraData,$this->enlace);
		
		return $link;
	}




	function html(){
		

		$this->ruta = $this->context->getVariable("rutaBloque");
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

		$cadena_sql=$this->sql->get("companyByUser",$this->sessionId);
		$result=$this->resource->execute($cadena_sql,"busqueda");

		$cadena_sql=$this->sql->get("companyListAll");
		$allCompanies=$this->resource->execute($cadena_sql,"busqueda");


		$this->companies=array();      
		

		$i=0;
		while(isset($result[$i]['IDCOMPANY'])){

			$this->companyByParent($result[$i]['IDCOMPANY'],$allCompanies);

			$i++;
		}

		return $this->companies;
	}


	function companyByParent($parent,$allCompanies) {
		
		/*$cadena_sql=$this->sql->get("companyList",$parent);
		$result=$this->resource->execute($cadena_sql,"busqueda");*/
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

		$cadena_sql=$this->sql->get("commerceFilterList",$idcommerce);
		$commerce=$this->resource->execute($cadena_sql,"busqueda");
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

		$cadena_sql=$this->sql->get("companyListbyID",$id);
		$company=$this->resource->execute($cadena_sql,"busqueda");
		$company=$company[0];

		$cadena_sql=$this->sql->get("commerceListbyCompany",$id);
		$commerce=$this->resource->execute($cadena_sql,"busqueda");
		
		$cadena_sql=$this->sql->get("categoryListCommerce");
		$categoryListCommerce=$this->resource->execute($cadena_sql,"busqueda");		
		


		$formSaraDataCommerce="bloque=fichaFamiliar";
		$formSaraDataCommerce.="&bloqueGrupo=estudiante";
		$formSaraDataCommerce.="&jxajax=main";
		$formSaraDataCommerce.="&action=fichaFamiliar";
		$formSaraDataCommerce.="&optionProcess=processEditCommerce";
		$formSaraDataCommerce.="&optionValue=1010001";
		$formSaraDataCommerce=$this->context->fabricaConexiones->crypto->codificar_url($formSaraDataCommerce,$this->enlace);
		


		include_once($this->ruta."/html/edit.php");
	}


	function showView($id){

		$cadena_sql=$this->sql->get("companyListbyID",$id);
		$company=$this->resource->execute($cadena_sql,"busqueda");
		$company=$company[0];


		include_once($this->ruta."/html/view.php");
	}

	function showList(){

		$cadena_sql=$this->sql->get("companyListbyID",implode(",",$this->companies));
		$companyList=$this->resource->execute($cadena_sql,"busqueda");

		include_once($this->ruta."/html/list.php");
	}

	function showNew(){

		$cadena_sql=$this->sql->get("companyList");
		$companyList=$this->resource->execute($cadena_sql,"busqueda");

		$cadena_sql=$this->sql->get("roleList");
		$roleList=$this->resource->execute($cadena_sql,"busqueda");

		$formSaraData="bloque=userManagement";
		$formSaraData.="&bloqueGrupo=admin";
		$formSaraData.="&action=userManagement";
		$formSaraData.="&option=processNew";
		$formSaraData=$this->context->fabricaConexiones->crypto->codificar($formSaraData);

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
