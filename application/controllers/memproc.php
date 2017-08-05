<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include("www/include/constants.php");
include("www/include/global.php");

if(!$checkUser) exit($MYSQL['dieStr']);
$data['baseDir'] = $baseDir;

class memproc extends CI_Controller {	
	public function history($type, $content="", $memid="-1")
	{
		$this->load->model("history");
		$this->history->add($type, $memid, $content);
	}
	
	// Pay
	public function pay()
	{
		global $C_FUNC, $C_MSG;

		// 결제입력
		$payres = $_POST['payres'];
		$mid = $_POST['mid'];
		$mname = $_POST['mname'];
		$paymethod = $_POST['paymethod'];
		$price = $_POST['price'];
		$currency = $_POST['currency'];
		$buyername = $_POST['buyername'];
		$buyeremail = $_POST['buyeremail'];
		$buyerphone = $_POST['buyerphone'];
		$replycode = $_POST['replycode'];
		$replymsg = $_POST['replymsg'];

		$memid = $_POST['memid'];
		$storeid = $_POST['storeid'];
				
		// 히스토리 추가
		$this->load->model("billhistory");
		$this->billhistory->add($mid, $mname, $paymethod, $price, $currency, $buyername, $buyeremail, $buyerphone, $replycode, $replymsg);

		// 하트구입
		if ($payres == 1)
		{
			$this->load->model("push");
			$this->load->model("coin");
			$heartcount = $this->coin->buy($memid, $storeid, $paymethod);

			if ($heartcount < 0)
			{
				$this->history($C_FUNC['buy_item'], $C_MSG['fail'], $memid);				
				$this->push->send($memid, "하트구입이 실패하였습니다.");
			}
			else
			{
				$this->history($C_FUNC['buy_item'], $C_MSG['success'], $memid);
				$this->push->send($memid, "하트가 구입되였습니다. 하트리력을 확인해주세요");
			}
		}
	}
}
?>