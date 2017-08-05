<?php
// 이메일전송
class mail extends CI_Model {
	var $from, $fromname;
    function __construct()
    {
		$this->from = "togethersuperpower222@gmail.com";
		$this->fromname = "이정만관리팀";
		
		parent::__construct();
	}
	
	// Send mail
	public function send($to, $subject, $message)
	{
		$this->load->library('email');
		
		//$config['protocol'] = 'sendmail';
		//$config['mailpath'] = '/usr/sbin/sendmail';
		//$config['charset'] = 'iso-8859-1';
		$config['charset'] = 'utf-8';
		$config['wordwrap'] = TRUE;
		$this->email->initialize($config);

		$this->email->from($this->from, $this->fromname);
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->message($message);

		$this->email->send();
		
		tolog($this->email->print_debugger());
		
		return true;
	}
	
	// Send UserID
	public function sendid($email, $userid)
	{
		$subject = "유저아이디 전송";
		$message = "안녕하세요. \n 당신의 유저아이디는 다음과 같습니다. \n".$userid."\n 유저아이디 다시는 잃어버리지 마세요.\n 이정만 관리팀.";
		return $this->send($email, $subject, $message);
	}
	
	// Send UserPassword
	public function sendpwd($email, $userpwd)
	{
		$subject = "유저패스워드 전송";
		$message = "안녕하세요. \n 당신의 유저패스워드는 다음과 같습니다. \n".$userpwd."\n 보안을 위해서 가입후 비밀번호를 변경시켜주세요.\n 이정만 관리팀.";
		return $this->send($email, $subject, $message);
	}
}
?>