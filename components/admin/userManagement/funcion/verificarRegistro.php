<?
if(!isset($GLOBALS["autorizado"]))
{
	include("index.php");
	exit;
}else{
	//Verificar si el email ya existe
	//$cadena_sql=$this->sql->cadena_sql("buscarUsuario",$_REQUEST["nombre"]);



	if(!empty($_POST))
	{
		$errors = array();
		$email = trim($_POST["email"]);
		$this->data['nombre']=$nombre = trim($_POST["nombre"]);
		$this->data['apellido']=$apellido = trim($_POST["apellido"]);
		$password = trim($_POST["password"]);
		$confirm_pass = trim($_POST["passwordc"]);
		$birthday= trim($_POST["birthday"]);
		$captcha = md5($_POST["captcha"]);
		
	
		if (!$this->miInspectorHTML->isValidEmail($email))
		{
			$this->mensaje['error'][] = "- Por favor ingresa un email valido";
		}else{
			
			$cadena_sql=$this->sql->cadena_sql("buscarEmail",$email);
			$registro=$this->miRecursoDB->ejecutarAcceso($cadena_sql,"busqueda");
			
			if(is_array($registro)){
				$this->mensaje['error'][] = "- Ya tenemos un registro con este correo.";
			}

		}	
		if ($captcha != $_SESSION['captcha'])
		{
			$this->mensaje['error'][] = "- El codigo de seguridad es incorrecto ";
		}
		if($this->miInspectorHTML->minMaxRange(8,25,$password))
		{
			$this->mensaje['error'][] = "- Tu clave debe tener entre 8 y 25 caracteres";
		}

		if($password != $confirm_pass)
		{
			$this->mensaje['error'][] = "- La confirmacion de la clave no coincide";
		}

		//End data validation

		if(count($this->mensaje['error']) == 0)
		{	
			$status=$this->nuevoUsuario($nombre,$apellido,$password,$email,$birthday);

			if(!$status)
			{
				$this->mensaje['error'][] = "Error!";
				return $this->status=FALSE;
			}
			else
			{
				//enviar email
				//mensae
				$this->mensaje['exito'][] = "Bienvenido! Tu registro fue exitoso";
				return $this->status=TRUE;
			}
		}else{

			return $this->status=FALSE;
		}
	}else{
	
		return $this->status=FALSE;

	}
	
	return $this->status=TRUE;


}
?>
