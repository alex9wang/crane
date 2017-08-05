<?php

class cr_email extends CI_Model {
	
	var $tbl_telecrane_tabel;
	var $tbl_towercrane_tabel;
	var $tbl_crane_email;
	var $from;
	
    function __construct()
    {
    	global $MYSQL;
		
		$this->tbl_telecrane_tabel  = $MYSQL['tbl_telecrane_tabel'];
		$this->tbl_towercrane_tabel = $MYSQL['tbl_towercrane_tabel'];
		$this->tbl_crane_email		= $MYSQL['tbl_email_register'];
		
		$this->from					= "donatephoto@kraantabel.com";
        parent::__construct();
    }
	
	function register($email, $reg_type)
	{
		if(!$this->check_email($email, $reg_type))  //True: registered,  False:  not register
		{
			$this->email_register($email, $reg_type);
		}
	}
	
	function check_email($email, $reg_type)
	{
		$strsql = sprintf("SELECT * FROM %s WHERE EmailAddr = '$email' AND RegType = $reg_type", $this->tbl_crane_email);
		
		$result = $this->dbop->execSQL($strsql);
		
		if(count($result)>0)
			return true;
		else
			return false;
	}
	
	function email_register($email, $reg_type)
	{
		$strsql = sprintf("INSERT INTO %s (`EmailAddr`, `RegType`) VALUES('$email', '$reg_type')", $this->tbl_crane_email);
		
		$this->dbop->execQuery($strsql);
	}
	
	function send($email, $subject, $msg)
	{
		
		$config = Array(
		  'charset' => 'utf-8',
		  'wordwrap' => TRUE
		);
        
        if ($msg == "1") {
            $msg = "Beste gebruiker van deze app,\nbedankt voor het verzenden van een foto van uw kraan. We plaatsen deze foto, indien er een plaats beschikbaar is, zo snel mogelijk in de app.\nMet vriendelijke groet\nJO-2";    
        }
        else if ($msg == 2) {
            $msg = "Cher utilisateur de cette application,\nMerci d'envoyer une photo de votre grue. Nous mettons cette image, si une place est disponible, dès que possible dans l'application.\nSincèrement\nJO-2";    
        }
        else {
            $msg = "Sehr geehrter Nutzer dieser App\nThanks, um ein Foto von Ihrem Krahn zu senden. Wir haben das Bild, wenn ein Platz zur Verfügung steht, so schnell wie möglich in den App.\nMit Freundlichen Grüßen\nJO-2";    
        }
        
        $msg = utf8_encode($msg);

		$this->load->library('email');
		$config['charset'] = 'utf-8';
		$config['wordwrap'] = TRUE;
		$this->email->initialize($config);

	    $this->email->from($this->from, "JO-2"); 
	    $this->email->to($email);
	    $this->email->subject($subject);
	    $this->email->message($msg);
		
	    if($this->email->send())
	    {
	      	return true;
	    }
	    else
	    {
			echo($this->email->print_debugger());
	        return false;
	    }
	}

		
	function send_specification($email, $spec_url)
	{
		$message = "Test message";
		$config = Array(
		  'charset' => 'utf-8',
		  'wordwrap' => TRUE
		);

		$this->load->library('email');
		$config['charset'] = 'utf-8';
		$config['wordwrap'] = TRUE;
		$this->email->initialize($config);

	    $this->email->from($this->from, "me"); 
	    $this->email->to($email);
	    $this->email->subject('Kraan Full Manual');
	    $this->email->message($message);
		
		$this->email->attach("." . $spec_url);
		
	    if($this->email->send())
	    {
	      	return true;
	    }
	    else
	    {
			echo($this->email->print_debugger());
	        return false;
	    }
	}
    
    function send_specification_message($email, $spec_url, $msg)
    {
        $config = Array(
          'charset' => 'utf-8',
          'wordwrap' => TRUE
        );
        if ($msg == "1") {
            $msg = "Beste gebruiker van deze app,\nhierbij uw aangevraagde tabel. Bedankt voor het aanvragen van een complete kraantabel.\nMet vriendelijke groet\nJO-2";    
        }
        else if ($msg == 2) {
            $msg = "Cher utilisateur de cette application,\ninclure votre tableau demandé. Merci pour demander une table complète de la grue.\nSincèrement\nJO-2";    
        }
        else {
            $msg = "Sehr geehrter Nutzer dieser App,\ngehören Ihr Tabelle. Vielen Dank für Ihr Interesse an dem eine komplette Krantabelle\nMit Freundlichen Grüßen\nJO-2";    
        }
        
        $msg = utf8_encode($msg);
        $msg = "Sender Email : ".$email."\nFrom : JO-2(Complete cranetable)\n\n".$msg;

        $this->load->library('email');
        $config['charset'] = 'utf-8';
        $config['wordwrap'] = TRUE;
        $this->email->initialize($config);

        $this->email->from($this->from, "JO-2"); 
        $this->email->to($email);
        $this->email->subject('Complete cranetable(JO-2)');
        $this->email->message($msg);
        
        $this->email->attach("." . $spec_url);
        
        if($this->email->send())
        {
              return true;
        }
        else
        {
            echo($this->email->print_debugger());
            return false;
        }
    }
	
	function send_spec($email, $spec_url)
	{
		$result = $this->send_specification($email, $spec_url);
		return $result;
	}
    
    function send_spec_message($email, $spec_url, $msg)
    {
        $result = $this->send_specification_message($email, $spec_url, $msg);
        return $result;
    }
		
	function reply_donate($email, $msg)
	{
		return $this->send($email, "Donate photo", $msg);
	}
	


}
?>