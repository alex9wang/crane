<?php
// SMS관련처리를 위한 모델
class sms extends CI_Model {
    function __construct()
    {
		parent::__construct();
	}
	
	// SMS송신
	public function send($phone, $callmessage)
	{
		// 전화번호 검출		
		if (($callphone1=substr($phone, 0, 3)) == FALSE)
			return false;
		if (($callphone2=substr($phone, 3, 4)) == FALSE)
			return false;
		if (($callphone3=substr($phone, 7, 4)) == FALSE)
			return false;
			
		// 클라이언트측 일련번호
		$member = "10";
		// 예약 날짜 EX) "20030617" 즉시 전송시 "00000000"
		$rdate = "00000000";
		// 예약 시간 EX) "190000"	 즉시 전송시 "000000"
		$rtime = "000000";
		// 회신번호1 EX) "011"
		$reqphone1 = "011";
		// 회신번호2 EX) "1234"
		$reqphone2 = "1234";//$this->input->post("reqphone2");
		// 회신번호 EX) "5678"
		$reqphone3 = "5678";
		// 호출명
		$callname = "이정만";
		
		$packettest = new SuremPacket;		
		$result=$packettest->sendsms($member,$callphone1,$callphone2,$callphone3,$callmessage,$rdate,$rtime,$reqphone1,$reqphone2,$reqphone3,$callname);
		
		$packetresult = new SuremResult($result);
		tolog ("SMS-->".$phone.":".$packetresult->getMsg());
			
		if ($packetresult->isSuccess())
			return true;
		return false;
	}
}
?>