<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include("www/include/constants.php");
include("www/include/global.php");

include("www/include/sms/suremcfg.php");
include("www/include/sms/common.php");

$data['baseDir'] = $baseDir;
class testproc extends CI_Controller {
########## SMS 시험 ##########
	public function sms_viewpage()
	{
		global $data;
		$this->load->view("t_sendsms", $data);
		
	}
	public function sendsms()
	{	
		// 클라이언트측 일련번호
		$member = $this->input->post("member");
		// 호출번호 EX)"011"
		$callphone1 = $this->input->post("callphone1");
		// 호출번호2 EX)"234"
		$callphone2 = $this->input->post("callphone2");
		// 호출번호3 EX)"5678"
		$callphone3 = $this->input->post("callphone3");
		// 요청통보문 80Byte
		$callmessage = $this->input->post("callmessage");
		// 예약 날짜 EX) "20030617" 즉시 전송시 "00000000"
		$rdate = $this->input->post("rdate");
		// 예약 시간 EX) "190000"	 즉시 전송시 "000000"
		$rtime = $this->input->post("rtime");
		// 회신번호1 EX) "011"
		$reqphone1 = $this->input->post("reqphone1");
		// 회신번호2 EX) "1234"
		$reqphone2 = $this->input->post("reqphone2");
		// 회신번호 EX) "5678"
		$reqphone3 = $this->input->post("reqphone3");
		// 호출명
		$callname = $this->input->post("callname");
		
		$packettest = new SuremPacket;
		if ($rdate== "" || $rtime=="" )
		{
			$rdate="00000000";
			$rtime="000000";
		}
		
		$result=$packettest->sendsms($member,$callphone1,$callphone2,$callphone3,$callmessage,$rdate,$rtime,$reqphone1,$reqphone2,$reqphone3,$callname);
		
		$packetresult = new SuremResult($result);
		
		echo '
			<script language="javascript">
				alert("'.$packetresult->getMsg().'");
				history.go(-1);
			</script>';
	}
########## 이메일전송시험 시험 ##########
	public function mailtest()
	{
		$this->load->model("mail");
		$email = $this->input->get("email");
		if ($this->mail->send($email, "시험메일", "안녕하세요, 시험메일입니다."))
			echo $email."로 메일이 전송되였습니다.";
		else
			echo $email."로 메일전송이 실패하였습니다.";
	}
########## 자료기지 조작 ##########
	// 회원정보초기화
	public function init_db_member()
	{
		global $MYSQL;	
		echo "회원자료기지초기화중...<br/>";
		try 
		{			
			// 회원-기본정보
			$sql = "DELETE FROM ".$MYSQL['tbl_member_basis'];
			$this->dbop->execQuery($sql);
			echo "회원기본정보가 초기화되였습니다.<br/>";
			
			// 회원-상세정보
			$sql = "DELETE FROM ".$MYSQL['tbl_member_detail'];
			$this->dbop->execQuery($sql);
			echo "회원상세정보가 초기화되였습니다.<br/>";
			
			// 회원-연애정보
			$sql = "DELETE FROM ".$MYSQL['tbl_member_love'];
			$this->dbop->execQuery($sql);
			echo "회원연애정보가 초기화되였습니다.<br/>";
			
			// 회원-설정정보
			$sql = "DELETE FROM ".$MYSQL['tbl_member_setting'];
			$this->dbop->execQuery($sql);
			echo "회원설정정보가 초기화되였습니다.<br/>";
			
			// 회원-사진정보
			$sql = "DELETE FROM ".$MYSQL['tbl_member_photo'];
			$this->dbop->execQuery($sql);
			echo "회원사진정보가 초기화되였습니다.<br/>";			
			
			echo "회원자료기지초기화가 완료되였습니다.";
		}
		catch (Exception $e)
		{
			echo "자료기지초기화가 실패하였습니다. : ".$e->getMessage();
		}
	}
	
	// 리력자료기지 초기화
	public function init_db_history()
	{
		global $MYSQL;		
		echo "리력자료기지초기화중...<br/>";
		try 
		{			
			// 질문답변
			$sql = "DELETE FROM ".$MYSQL['tbl_member_answer'];
			$this->dbop->execQuery($sql);
			echo "질문답변리력이 초기화되였습니다.<br/>";
			
			// 초대신청
			$sql = "DELETE FROM ".$MYSQL['tbl_invite_apply'];
			$this->dbop->execQuery($sql);
			echo "초대신청리력이 초기화되였습니다.<br/>";
			
			// 친구초대리력
			$sql = "DELETE FROM ".$MYSQL['tbl_invite_history'];
			$this->dbop->execQuery($sql);
			echo "친구초대리력이 초기화되였습니다.<br/>";
			
			// 성원별 초대결과
			$sql = "DELETE FROM ".$MYSQL['tbl_invite_member'];
			$this->dbop->execQuery($sql);
			echo "성원별초대결과가 초기화되였습니다.<br/>";
			
			// 출석상태
			$sql = "DELETE FROM ".$MYSQL['tbl_attend'];
			$this->dbop->execQuery($sql);
			echo "출석상태리력이 초기화되였습니다.<br/>";
			
			// 참여상태
			$sql = "DELETE FROM ".$MYSQL['tbl_participate'];
			$this->dbop->execQuery($sql);
			echo "참여상태가 초기화되였습니다.<br/>";
			
			// 매칭상태
			$sql = "DELETE FROM ".$MYSQL['tbl_match'];
			$this->dbop->execQuery($sql);
			echo "매칭상태가 초기화되였습니다.<br/>";
				
			// 질문리력
			$sql = "DELETE FROM ".$MYSQL['tbl_query'];
			$this->dbop->execQuery($sql);
			echo "일별 질문리력이 초기화되였습니다.<br/>";
			
			// 채팅리력
			$sql = "DELETE FROM ".$MYSQL['tbl_chat'];
			$this->dbop->execQuery($sql);
			echo "채팅리력이 초기화되였습니다.<br/>";
			
			// 채팅알림
			$sql = "DELETE FROM ".$MYSQL['tbl_chatnotify'];
			$this->dbop->execQuery($sql);
			echo "채팅알림리력이 초기화되였습니다.<br/>";
			
			// 채팅방관리
			$sql = "DELETE FROM ".$MYSQL['tbl_chatroom'];
			$this->dbop->execQuery($sql);
			echo "채팅방관리리력이 초기화되였습니다.<br/>";
			
			// 코인지불리력
			$sql = "DELETE FROM ".$MYSQL['tbl_coinlog'];
			$this->dbop->execQuery($sql);
			echo "코인지불리력이 초기화되였습니다.<br/>";
			
			// 지불관리리력
			$sql = "DELETE FROM ".$MYSQL['tbl_bill'];
			$this->dbop->execQuery($sql);
			echo "지불관리리력이 초기화되였습니다.<br/>";
			
			// 결제리력
			$sql = "DELETE FROM ".$MYSQL['tbl_billhistory'];
			$this->dbop->execQuery($sql);
			echo "결제리력이 초기화되였습니다.<br/>";
			
			// 무료코인로그
			$sql = "DELETE FROM ".$MYSQL['tbl_freecoin_log'];
			$this->dbop->execQuery($sql);
			echo "무료코인로그리력이 초기화되였습니다.<br/>";
			
			// 히스토리
			$sql = "DELETE FROM ".$MYSQL['tbl_history'];
			$this->dbop->execQuery($sql);
			echo "히스토리가 초기화되였습니다.<br/>";
			
			// 푸시리력
			$sql = "DELETE FROM ".$MYSQL['tbl_push'];
			$this->dbop->execQuery($sql);
			echo "푸시리력이 초기화되였습니다.<br/>";
			
			// 수동푸시리력
			$sql = "DELETE FROM ".$MYSQL['tbl_pushmanual'];
			$this->dbop->execQuery($sql);
			echo "수동푸시자료가 초기화되였습니다.<br/>";
			
			// 버그리력
			$sql = "DELETE FROM ".$MYSQL['tbl_bug'];
			$this->dbop->execQuery($sql);
			echo "버그리력이 초기화되였습니다.<br/>";
			
			// 인증코드관리
			$sql = "DELETE FROM ".$MYSQL['tbl_authcode'];
			$this->dbop->execQuery($sql);
			echo "인증코드관리리력이 초기화되였습니다.<br/>";
			
			// 신고리력
			$sql = "DELETE FROM ".$MYSQL['tbl_report'];
			$this->dbop->execQuery($sql);
			echo "신고자료가 초기화되였습니다.<br/>";
			
			// 로그인리력
			$sql = "DELETE FROM ".$MYSQL['tbl_loginhistory'];
			$this->dbop->execQuery($sql);
			echo "로그인리력이 초기화되였습니다.<br/>";
			
			// 호감도리력
			$sql = "DELETE FROM ".$MYSQL['tbl_favorscore'];
			$this->dbop->execQuery($sql);
			echo "호감도리력이 초기화되였습니다.<br/>";
			
			// 토큰정보
			$sql = "DELETE FROM ".$MYSQL['tbl_token'];
			$this->dbop->execQuery($sql);
			echo "토큰정보리력이 초기화되였습니다.<br/>";
			
			// 공지사항
			$sql = "DELETE FROM ".$MYSQL['tbl_notice'];
			$this->dbop->execQuery($sql);
			echo "공지사항리력이 초기화되였습니다.<br/>";		
			
			echo "리력자료기지초기화가 완료되였습니다.";
		}
		catch (Exception $e)
		{
			echo "자료기지초기화가 실패하였습니다. : ".$e->getMessage();
		}
	}
	
	// 자료기지 전체 초기화
	public function init_db($pwd, $type)
	{
		if ($pwd != "ejongmandb")
		{
			echo "자료기지를 조작할수 없습니다.";
			return;
		}
		if ($type == "All")
		{
			$this->init_db_member();
			$this->init_db_history();
		}
		else if ($type == "Member")
		{
			$this->init_db_member();
		}
		else if ($type == "History")
		{			
			$this->init_db_history();
		}
		else
		{
			echo "자료기지조작이 실패하였습니다";
		}
	}
########## 수동조작처리 ##########
	// 매칭초기화
	public function match_init()
	{
		$this->load->model("match");
		$this->load->model("participate");
		
		$this->match->match_init();
		$this->participate->init();
	}
	
	// 자동답변처리
	public function auto_answer($memid)
	{
		global $C_FUNC, $C_MSG;
		
		$this->load->model("member");
		$this->load->model("query");
		$this->load->model("participate");
		
		// 멤버검사
		if (!$this->member->is_reg($memid))
		{
			echo "Unknown User";
			return;
		}
		
		if ($this->member->is_man($memid))
			$queries = $this->query->select_for_man();
		else
			$queries = $this->query->select_for_woman();
		
		if (count($queries) < 7)
		{
			echo "Uncorrect Query";
			return;
		}		
		
		$ans1 = $queries[0]['Ans'.mt_rand(1,2)];
		$ans2 = $queries[1]['Ans'.mt_rand(1,2)];
		$ans3 = $queries[2]['Ans'.mt_rand(1,2)];
		$ans4 = $queries[3]['Ans'.mt_rand(1,4)];
		$ans5 = $queries[4]['Ans'.mt_rand(1,4)];
		$ans6 = $queries[5]['Ans'.mt_rand(1,4)];
		$ans7 = $queries[6]['Ans'.mt_rand(1,4)];
		
		$this->load->model("member_answer");
		if ($this->member_answer->fortoday($memid, $ans1, $ans2, $ans3, $ans4, $ans5, $ans6, $ans7))
		{
			$this->participate->reg($memid);
			$this->participate->answer($memid);
			echo "OK:".$ans1.",".$ans2.",".$ans3.",".$ans4.",".$ans5.",".$ans6.",".$ans7;
		}
		else
		{
			echo "Fail:".$ans1.",".$ans2.",".$ans3.",".$ans4.",".$ans5.",".$ans6.",".$ans7;
		}
	}
	
	// 시험코드
	public function test()
	{
		$this->load->model("member_basis");
		$this->load->model("member_detail");
		
		global $MYSQL;
		$sql = "SELECT * FROM ".$MYSQL['tbl_member_detail'];
		$mds = $this->dbop->execSQL($sql);
		
		for ($i=0; $i<count($mds); $i++)
		{
			if (!$this->member_basis->is_reg($mds[$i]->md_mem))
				echo $mds[$i]->md_mem;
		}
	}
	
	public function copy_table(){	
		global $MYSQL;
		
		$strsql = sprintf("SELECT * FROM %s", $MYSQL['tbl_telecrane_tabel']);
		$result = $this->dbop->execSQL($strsql);

		for($i = 0; $i<count($result); $i++)
		{
			$strsql = sprintf("INSERT INTO %s (`CraneType`, `CraneID`, `Manual`) VALUES('1', '%s', '')", $MYSQL['tbl_manual'], $result[$i]->CraneID);
			$this->dbop->execQuery($strsql);			
		}		
		$strsql = sprintf("SELECT * FROM %s", $MYSQL['tbl_towercrane_tabel']);
		$result = $this->dbop->execSQL($strsql);

		for($i = 0; $i<count($result); $i++)
		{
			$strsql = sprintf("INSERT INTO %s (`CraneType`, `CraneID`, `Manual`) VALUES('2', '%s', '')", $MYSQL['tbl_manual'], $result[$i]->CraneID);
			$this->dbop->execQuery($strsql);			
		}	
	}
	
	function test_email()
	{		
		$to = "donatephoto@kraantabel.com";
		$subject = "Test mail";
		$message = "Hello! This is a simple email message.";
		$from = "dwang042@gmail.com";
		$headers = "From:" . $from;
		mail($to,$subject,$message, $headers);
		echo "Mail Sent.";
		
	}
	
	function test_email_CI()
	{
		$this->load->model("cr_email");
		$result = $this->cr_email->send_spec(1, 20, "dwang042@gmail.com");
		echo $result;
	}
}

?>