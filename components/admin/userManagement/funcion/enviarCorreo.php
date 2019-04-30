<?
		$this->mail->IsSMTP();
		$this->mail->From     = "info@reservame.com.ec";
		$this->mail->FromName = "RESERVAME";
		$this->mail->Host     = "smtpout.secureserver.net";
		//$this->mail->Mailer   = "smtp";
		$this->mail->SMTPAuth = true;
		$this->mail->Username = "info@reservame.com.ec";
		$this->mail->Password = "RESER2011vame";
		$this->mail->Timeout  = 200;
		$this->mail->Port  = 80;
		$this->mail->Charset  = "utf-8";
		$this->mail->IsHTML(false);
		$this->mail->SMTPDebug = 1;

		$this->mail->SMTPSecure = "http";
		
		
		
		   
		$fecha = date("d-M-Y  h:i:s A");

		$sujeto= "Registro Reservame"."\n\n";
		$cuerpo.="Bienvenido a Reservame.  Tus datos de acceso son:\n";
		$cuerpo.="Usuario: " .$email."\n";
		$cuerpo.="Clave Acceso: " .$clave."\n";
		
		$this->mail->Body    = $cuerpo;
		$this->mail->Subject = $sujeto;
		$this->mail->AddAddress($email);

			 
		if(!$this->mail->Send())
		{
			$this->mensaje['error'][] = $this->mail->ErrorInfo;
		}

		$this->mail->ClearAllRecipients();




?>
