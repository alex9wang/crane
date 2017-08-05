<?php
include("suremcfg.php");

// 슈어엠파케트
class SuremPacket
{
	var $szCmd;
	var $szType;
	var $szDate;
	var $szTime;
	var $szUsercode;
	var $szUsername;
	var $szDeptcode;
	var $szDeptname;
	var $szStatus;
	var $szCallphone1;
	var $szCallphone2;
	var $szCallphone3;
	var $szCallmessage;
	var $szCallurl;
	var $szRdate;
	var $szRtime;
	var $szDummy;
	var $lMember;
	var $szReqphone1;
	var $szReqphone2;
	var $szReqphone3;
	var $szReqname;
	var $szReserved;

	var $ServiceID;
	var $TotalPrice;
	var $CallPrice;
	var $SeqNum;

	function addnull($index)
	{
		$str = "";

		for ($i=0;$i<$index;$i++)
			$str .= chr(0x00);

		return $str;
	}


	function getconnect($servername)
	{
		$errno = 1;
		if( $servername == "testserver")
		{
			$SERVER_IP = gethostbyname ("test.surem.com"); // 사용하지 않는 DNS, 실제 존재하지 않음
		}

		else
		{
			$SERVER_IP = gethostbyname ("messenger.surem.com"); //실제 서버 IP 얻기
		}

		$PORT = 8080;  //포트 번호


		$TIME_OUT = 15;

		$fp = fsockopen( $SERVER_IP, $PORT, $errno, $errstr, $TIME_OUT);	//소켓 연결


		if(!$fp && $servername == "testserver" )
			return "-2";

		//소켓 연결 실패시 다른 서버로 연결 시도
		if( !$fp)
		{
			$SERVER_IP = gethostbyname ("messenger3.surem.com"); //실제 서버 IP 얻기
			$fp = fsockopen( $SERVER_IP, $PORT, $errno, $errstr, $TIME_OUT);	//소켓 연결

			//다른 서버도 실패시 최종 에러 처리
			if(!$fp)
				$result="-1";
			else
				$result=$fp;
		}
		else 
			$result = $fp;

		//연결 성공시 파일 포인터 반환
		return $fp;
	}

	function disconnect($fp)
	{
		fclose($fp);
	}
	function int2byte( $i ) 
	{

		settype($i,"integer");

		$temp = sprintf("%c",($i&0xFF));
		$temp = $temp.sprintf("%c",($i>>8&0xFF));
		$temp = $temp.sprintf("%c",($i>>16&0xFF));
		$temp = $temp.sprintf("%c",($i>>24&0xFF));

		return $temp;
	}
	function byte2str($i)
	{

		$a = substr($i,0,1);
		$b = substr($i,1,1);
		$c = substr($i,2,1);
		$d = substr($i,3,1);


		$temp = ord($a) *1 + ord($b)*256 + ord($c) * 65536;

		return $temp;



	}



	function int2byte2( $i ) 
	{

		settype($i,"integer");

		$temp = sprintf("%c",($i&0xFF));
		$temp = $temp.sprintf("%c",($i>>8&0xFF));

		return $temp;
	}


	function sendsms($member,$callphone1,$callphone2,$callphone3,$callmessage,$rdate,$rtime,$reqphone1,$reqphone2,$reqphone3,$callname)
	{

		global $usercode,$username,$deptcode,$deptname;

		$cdate = mktime();

		 $this->szCmd = "B";
		 $this->szType = "C";
		 $this->szDate = date('Ymd',$cdate).$this->addnull(2);
		 $this->szTime = date('His',$cdate).$this->addnull(2);
		 $this->szUsercode = $usercode . $this->addnull(30 - strlen($usercode));
		 $this->szUsername = $username . $this->addnull(16 - strlen($username));
		 $this->szDeptcode = $deptcode . $this->addnull(12 - strlen($deptcode));
		 $this->szDeptname = $deptname . $this->addnull(16 - strlen($deptname));

		if( $rdate == "00000000" && $rtime=="000000")		
			$this->szStatus = chr(0x00);
		else
			$this->szStatus = "R";


		$this->szCallphone1 = $callphone1.$this->addnull(4 - strlen($callphone1));
		$this->szCallphone2 = $callphone2.$this->addnull(4 - strlen($callphone2));
		$this->szCallphone3 = $callphone3.$this->addnull(4 - strlen($callphone3));
		$this->szCallmessage = $callmessage .$this->addnull(120 - strlen($callmessage));

		$this->szRdate = $rdate.$this->addnull(2);
		$this->szRtime = $rtime.$this->addnull(2);
		$this->szDummy = $this->addnull(3);
		$this->lMember = $this->int2byte($member);
		$this->szReqphone1 = $reqphone1 . $this->addnull(4 - strlen($reqphone1));
		$this->szReqphone2 = $reqphone2 . $this->addnull(4 - strlen($reqphone2));
		$this->szReqphone3 = $reqphone3 . $this->addnull(4 - strlen($reqphone3));
		$this->szReqname = $callname . $this->addnull(32 - strlen($callname));
		$this->szReserved = $this->addnull(32);
		
		$fp=$this->getconnect("messenger");

		if($fp=="-1")
		{
			return "-1";
		}

$Str = $this->szCmd.$this->szType.$this->szDate.$this->szTime.$this->szUsercode.$this->szUsername;
$Str = $Str .$this->szDeptcode.$this->szDeptname.$this->szStatus.$this->szCallphone1.$this->szCallphone2.$this->szCallphone3;
$Str = $Str.$this->szCallmessage.$this->szRdate.$this->szRtime.$this->szDummy.$this->lMember;
$Str = $Str.$this->szReqphone1.$this->szReqphone2.$this->szReqphone3.$this->szReqname.$this->szReserved;

		fwrite($fp, $Str,328);
		flush();

		socket_set_timeout($fp, 10);

		$res=fread($fp,400);

		$this->disconnect($fp);

		return $res;
	}

	function sendurl($member,$callphone1,$callphone2,$callphone3,$callmessage,$callurl,$rdate,$rtime,$reqphone1,$reqphone2,$reqphone3,$callname)
	{

		global $usercode,$username,$deptcode,$deptname;

		$cdate = mktime();

		 $this->szCmd = "I";
		 $this->szType = "C";
		 $this->szDate = date('Ymd',$cdate).$this->addnull(2);
		 $this->szTime = date('His',$cdate).$this->addnull(2);
		 $this->szUsercode = $usercode . $this->addnull(30 - strlen($usercode));
		 $this->szUsername = $username . $this->addnull(16 - strlen($username));
		 $this->szDeptcode = $deptcode . $this->addnull(12 - strlen($deptcode));
		 $this->szDeptname = $deptname . $this->addnull(16 - strlen($deptname));

		if( $rdate == "00000000" && $rtime=="000000")		
			$this->szStatus = chr(0x00);
		else
			$this->szStatus = "R";


		$this->szCallphone1 = $callphone1.$this->addnull(4 - strlen($callphone1));
		$this->szCallphone2 = $callphone2.$this->addnull(4 - strlen($callphone2));
		$this->szCallphone3 = $callphone3.$this->addnull(4 - strlen($callphone3));


		$this->szCallmessage = $callmessage .$this->addnull(80 - strlen($callmessage));
		$this->szCallurl = $callurl .$this->addnull(62 - strlen($callurl));

		$this->szRdate = $rdate.$this->addnull(2);
		$this->szRtime = $rtime.$this->addnull(2);
		$this->szReqphone1 = $reqphone1 . $this->addnull(4 - strlen($reqphone1));
		$this->szReqphone2 = $reqphone2 . $this->addnull(4 - strlen($reqphone2));
		$this->szReqphone3 = $reqphone3 . $this->addnull(4 - strlen($reqphone3));
		$this->szReqname = $callname . $this->addnull(32 - strlen($callname));

		$this->szDummy = $this->addnull(1);
		$this->lMember = $this->int2byte($member);


		$this->szReserved = $this->addnull(4);
		
		$fp=$this->getconnect("messenger");

		if($fp=="-1")
		{
			return "-1";
		}

$Str = $this->szCmd.$this->szType.$this->szDate.$this->szTime.$this->szUsercode.$this->szUsername;
$Str = $Str .$this->szDeptcode.$this->szDeptname.$this->szStatus.$this->szCallphone1.$this->szCallphone2.$this->szCallphone3;
$Str = $Str .$this->szCallmessage.$this->szCallurl.$this->szRdate.$this->szRtime;
$Str = $Str.$this->szReqphone1.$this->szReqphone2.$this->szReqphone3.$this->szReqname;
$Str = $Str.$this->szDummy.$this->lMember.$this->addnull(4).$this->addnull(4).$this->szReserved;

		fwrite($fp, $Str,328);
		flush();

		socket_set_timeout($fp, 10);

		$res=fread($fp,400);

		$this->disconnect($fp);

		return $res;	

	}

	function  batchsms($rdate,$rtime,$filename)
	{

		global $usercode,$username,$deptcode,$deptname;		


		$fp= fopen($filename,"r");

		if(!$fp)
		{
			return "-1";
		}


		$buf=fread($fp,filesize($filename));


		$line=split("\n",$buf);

		$num=count($line);

		$arr = split("\t",$line[0]);	

		 $this->szCmd = "U";
		 $this->szType = "C";
		 $this->szDate = date('Ymd',$cdate).$this->addnull(2);
		 $this->szTime = date('His',$cdate).$this->addnull(2);
		 $this->szUsercode = $usercode . $this->addnull(30 - strlen($usercode));
		 $this->szUsername = $username . $this->addnull(16 - strlen($username));
		 $this->szDeptcode = $deptcode . $this->addnull(12 - strlen($deptcode));
		 $this->szDeptname = $deptname . $this->addnull(16 - strlen($deptname));

		if( $rdate == "00000000" && $rtime=="000000")		
			$this->szStatus = chr(0x00);
		else
			$this->szStatus = "R";


		$this->szCallphone1 = $arr[2].$this->addnull(4 - strlen($arr[2]));
		$this->szCallphone2 = $arr[3].$this->addnull(4 - strlen($arr[3]));
		$this->szCallphone3 = $arr[4].$this->addnull(4 - strlen($arr[4]));
		$this->szCallmessage = $arr[8] .$this->addnull(120 - strlen($arr[8]));

		$this->szRdate = $rdate.$this->addnull(2);
		$this->szRtime = $rtime.$this->addnull(2);
		$this->szDummy = $this->addnull(3);
		$this->lMember = $this->int2byte($arr[0]);
		$this->szReqphone1 = $arr[5] . $this->addnull(4 - strlen($arr[5]));
		$this->szReqphone2 = $arr[6] . $this->addnull(4 - strlen($arr[6]));
		$this->szReqphone3 = $arr[7] . $this->addnull(4 - strlen($arr[7]));
		$this->szReqname = $arr[1] . $this->addnull(32 - strlen($arr[1]));
		$this->szReserved = $num.$this->addnull(16-strlen($num));

$Str = $this->szCmd.$this->szType.$this->szDate.$this->szTime.$this->szUsercode.$this->szUsername;
$Str = $Str .$this->szDeptcode.$this->szDeptname.$this->szStatus.$this->szCallphone1.$this->szCallphone2.$this->szCallphone3;
$Str = $Str .$this->szCallmessage.$this->szRdate.$this->szRtime.$this->szDummy.$this->addnull(4);
$Str = $Str.$this->szReqphone1.$this->szReqphone2.$this->szReqphone3.$this->szReqname;
$Str = $Str.$this->lMember.$this->addnull(4).$this->addnull(4).$this->addnull(4).$this->szReserved;

		$fsoc=$this->getconnect("messenger");

		fwrite($fsoc, $Str,328);
		flush();

		socket_set_timeout($fsoc, 10);

		$res=fread($fsoc,400);

		$result =substr($res,94,1);	

		for($idx=0;$idx<$num;$idx++)
		{
			$line[$idx]=nl2br($line[$idx]);
			$line[$idx]=str_replace("<br />",chr(0x00),$line[$idx]);
		}
	
		for($idx=1;$idx<$num;$idx++)
		{
			$arr = split("\t",$line[$idx]);	


			$arr[8]=$arr[8].chr(0x00);

			$this->szCallphone1 = $arr[2].$this->addnull(5 - strlen($arr[2]));
			$this->szCallphone2 = $arr[3].$this->addnull(5 - strlen($arr[3]));
			$this->szCallphone3 = $arr[4].$this->addnull(5 - strlen($arr[4]));

			$this->szReqphone1 = $arr[5] . $this->addnull(5 - strlen($arr[5]));
			$this->szReqphone2 = $arr[6] . $this->addnull(5 - strlen($arr[6]));
			$this->szReqphone3 = $arr[7] . $this->addnull(5 - strlen($arr[7]));
			$this->szReqname = $arr[1] . $this->addnull(16 - strlen($arr[1]));
			$this->szReserved = $this->addnull(8);
			$this->szCallmessage = $arr[8] .$this->addnull(120 - strlen($arr[8]));
			$this->szRdate = $rdate.$this->addnull(1);
			$this->szRtime = $rtime.$this->addnull(1);



$Str = $this->szCallphone1.$this->szCallphone2.$this->szCallphone3.$this->szStatus;
$Str = $Str.$this->szReqphone1.$this->szReqphone2.$this->szReqphone3.$this->szReqname.$this->addnull(1);
$Str = $Str.$this->int2byte($arr[0]).$this->addnull(4).$this->szCallmessage;
$Str = $Str.$this->szRdate.$this->szRtime.$this->szReserved;

			fwrite($fsoc, $Str,200);
			flush();
			socket_set_timeout($fsoc, 10);
			$res=fread($fsoc,200);

//			개별적인 동보 Packet의 결과를 처리하고 싶을때 소스 코드 삽입.
//			
//			if (substr($res,15,1) == "O" )
//				{ ... }  

		}

		socket_set_timeout($fsoc, 10);
		$res=fread($fsoc,400);
	
		
		$this->disconnect($fsoc);

		return $res;

	}


	function  batchurl($rdate,$rtime,$filename)
	{

		global $usercode,$username,$deptcode,$deptname;		


		$fp= fopen($filename,"r");

		if(!$fp)
	{
			return "-1";
		}


		$buf=fread($fp,filesize($filename));

		$line=split("\n",$buf);

		$num=count($line);

		for($idx=0;$idx<$num;$idx++)
		{
			$line[$idx]=nl2br($line[$idx]);
			$line[$idx]=str_replace("<br />",chr(0x00),$line[$idx]);
		}

		$arr = split("\t",$line[0]);	

		 $this->szCmd = "V";
		 $this->szType = "C";
		 $this->szDate = date('Ymd',$cdate).$this->addnull(2);
		 $this->szTime = date('His',$cdate).$this->addnull(2);
		 $this->szUsercode = $usercode . $this->addnull(30 - strlen($usercode));
		 $this->szUsername = $username . $this->addnull(16 - strlen($username));
		 $this->szDeptcode = $deptcode . $this->addnull(12 - strlen($deptcode));
		 $this->szDeptname = $deptname . $this->addnull(16 - strlen($deptname));

		if( $rdate == "00000000" && $rtime=="000000")		
			$this->szStatus = chr(0x00);
		else
			$this->szStatus = "R";
		$this->szCallphone1 = $arr[2].$this->addnull(4 - strlen($arr[2]));
		$this->szCallphone2 = $arr[3].$this->addnull(4 - strlen($arr[3]));
		$this->szCallphone3 = $arr[4].$this->addnull(4 - strlen($arr[4]));
		$this->szCallmessage = $arr[9] .$this->addnull(80 - strlen($arr[9]));
		$this->szCallurl = $arr[8] .$this->addnull(62 - strlen($arr[8]));

		$this->szRdate = $rdate.$this->addnull(2);
		$this->szRtime = $rtime.$this->addnull(2);
		$this->szDummy = $this->addnull(1);
		$this->lMember = $this->int2byte($arr[0]);
		$this->szReqphone1 = $arr[5] . $this->addnull(4 - strlen($arr[5]));
		$this->szReqphone2 = $arr[6] . $this->addnull(4 - strlen($arr[6]));
		$this->szReqphone3 = $arr[7] . $this->addnull(4 - strlen($arr[7]));
		$this->szReqname = $arr[1] . $this->addnull(32 - strlen($arr[1]));
		$this->szReserved = $this->int2byte($num);

$Str = $this->szCmd.$this->szType.$this->szDate.$this->szTime.$this->szUsercode.$this->szUsername;
$Str = $Str .$this->szDeptcode.$this->szDeptname.$this->szStatus.$this->szCallphone1.$this->szCallphone2.$this->szCallphone3;
$Str = $Str .$this->szCallmessage.$this->szCallurl.$this->szRdate.$this->szRtime;
$Str = $Str.$this->szReqphone1.$this->szReqphone2.$this->szReqphone3.$this->szReqname;
$Str = $Str.$this->szDummy.$this->lMember.$this->addnull(4).$this->addnull(4).$this->szReserved;

		$fsoc=$this->getconnect("messenger");

		fwrite($fsoc, $Str,328);
		flush();

		socket_set_timeout($fsoc, 10);

		$res=fread($fsoc,400);

		$result =substr($res,94,1);	
	
		for($idx=1;$idx<$num;$idx++)
		{
			$arr = split("\t",$line[$idx]);	
				
//			echo $arr[9];						
	
			$arr[9]=$arr[9].chr(0x00);

			$this->szCallphone1 = $arr[2].$this->addnull(5 - strlen($arr[2]));
			$this->szCallphone2 = $arr[3].$this->addnull(5 - strlen($arr[3]));
			$this->szCallphone3 = $arr[4].$this->addnull(5 - strlen($arr[4]));

			$this->szCallmessage = $arr[9] .$this->addnull(80 - strlen($arr[9]));
			$this->szCallurl = $arr[8] .$this->addnull(62 - strlen($arr[8]));


			$this->szReqphone1 = $arr[5] . $this->addnull(5 - strlen($arr[5]));
			$this->szReqphone2 = $arr[6] . $this->addnull(5 - strlen($arr[6]));
			$this->szReqphone3 = $arr[7] . $this->addnull(5 - strlen($arr[7]));
			$this->szReqname = $arr[1] . $this->addnull(16 - strlen($arr[1]));
			$this->szReserved = $this->addnull(2);
			$this->szCallmessage = $arr[9] .$this->addnull(80 - strlen($arr[9]));
			$this->szCallurl = $arr[8] .$this->addnull(62 - strlen($arr[8]));
			$this->szRdate = $rdate.$this->addnull(1);
			$this->szRtime = $rtime.$this->addnull(1);



$Str = $this->szCallphone1.$this->szCallphone2.$this->szCallphone3.$this->szStatus;
$Str = $Str.$this->szReqphone1.$this->szReqphone2.$this->szReqphone3.$this->szReqname.$this->addnull(1);
$Str = $Str.$this->int2byte($arr[0]).$this->addnull(4).$this->szCallmessage;
$Str = $Str.$this->szCallurl.$this->addnull(2);

			fwrite($fsoc, $Str,200);
			flush();
			socket_set_timeout($fsoc, 10);
			$res=fread($fsoc,200);

//			개별적인 동보 Packet의 결과를 처리하고 싶을때 소스 코드 삽입.
//			
//			if (substr($res,15,1) == "O" )
//				{ ... }  

		}

		socket_set_timeout($fsoc, 10);
		$res=fread($fsoc,400);
	
		
		$this->disconnect($fsoc);

		return $res;

	}

	function reserveCancel($member,$date,$callphone1,$callphone2,$callphone3)
	{

		global $usercode,$username,$deptcode,$deptname;

		$cdate = mktime();

		$this->szCmd = "C";
		$this->szType = "C";
		$this->szDate = $date.$this->addnull(2);
		$this->szTime = $this->addnull(8);
		$this->szUsercode = $usercode . $this->addnull(30 - strlen($usercode));
		$this->szUsername = $this->addnull(16);
		$this->szDeptcode = $deptcode . $this->addnull(12 - strlen($deptcode));
		$this->szDeptname = $deptname . $this->addnull(16 - strlen($deptname));

		$this->szStatus = "1";

		$this->szCallphone1 = $callphone1.$this->addnull(4 - strlen($callphone1));
		$this->szCallphone2 = $callphone2.$this->addnull(4 - strlen($callphone2));
		$this->szCallphone3 = $callphone3.$this->addnull(4 - strlen($callphone3));
		$this->szCallmessage = $this->addnull(120);

		$this->szRdate = $this->addnull(10);
		$this->szRtime = $this->addnull(8);
		$this->szDummy = $this->addnull(3);
		$this->lMember = $this->int2byte($member);
		$this->szReqphone1 = $this->addnull(4);
		$this->szReqphone2 = $this->addnull(4);
		$this->szReqphone3 = $this->addnull(4);
		$this->szReqname = $this->addnull(32);
		$this->szReserved = $this->addnull(16);

		$this->SeqNum = $this->addnull(4);
		$this->ServiceID = $this->addnull(4);
		$this->TotalPrice = $this->addnull(4);
		$this->CallPrice = $this->addnull(4);
		
		$fp=$this->getconnect("messenger");

		if($fp=="-1")
		{
			return "-1";
		}

$Str = $this->szCmd.$this->szType.$this->szDate.$this->szTime.$this->szUsercode.$this->szUsername;
$Str = $Str .$this->szDeptcode.$this->szDeptname.$this->szStatus.$this->szCallphone1.$this->szCallphone2.$this->szCallphone3;
$Str = $Str .$this->szCallmessage.$this->szRdate.$this->szRtime.$this->szDummy.$this->SeqNum;
$Str = $Str.$this->szReqphone1.$this->szReqphone2.$this->szReqphone3.$this->szReqname.$this->lMember.$this->ServiceID.$this->TotalPrice.$this->CallPrice.$this->szReserved;


		fwrite($fp, $Str,328);
		flush();

		socket_set_timeout($fp, 10);

		$res=fread($fp,400);

		$this->disconnect($fp);

		return $res;
	}

	function reserveUpdate($member,$date,$rdate,$rtime,$callphone1,$callphone2,$callphone3)
	{

		global $usercode,$username,$deptcode,$deptname;

		$cdate = mktime();

		$this->szCmd = "C";
		$this->szType = "E";
		$this->szDate = $date.$this->addnull(2);
		$this->szTime = $this->addnull(8);
		$this->szUsercode = $usercode . $this->addnull(30 - strlen($usercode));
		$this->szUsername = $this->addnull(16);
		$this->szDeptcode = $deptcode . $this->addnull(12 - strlen($deptcode));
		$this->szDeptname = $deptname . $this->addnull(16 - strlen($deptname));

		$this->szStatus = "1";

		$this->szCallphone1 = $callphone1.$this->addnull(4 - strlen($callphone1));
		$this->szCallphone2 = $callphone2.$this->addnull(4 - strlen($callphone2));
		$this->szCallphone3 = $callphone3.$this->addnull(4 - strlen($callphone3));
		$this->szCallmessage = $this->addnull(120);

		$this->szRdate = $rdate.$this->addnull(2);
		$this->szRtime = $rtime.$this->addnull(2);
		$this->szDummy = $this->addnull(3);
		$this->lMember = $this->int2byte($member);
		$this->szReqphone1 = $this->addnull(4);
		$this->szReqphone2 = $this->addnull(4);
		$this->szReqphone3 = $this->addnull(4);
		$this->szReqname = $this->addnull(32);
		$this->szReserved = $this->addnull(16);

		$this->SeqNum = $this->addnull(4);
		$this->ServiceID = $this->addnull(4);
		$this->TotalPrice = $this->addnull(4);
		$this->CallPrice = $this->addnull(4);
		
		$fp=$this->getconnect("messenger");

		if($fp=="-1")
		{
			return "-1";
		}

$Str = $this->szCmd.$this->szType.$this->szDate.$this->szTime.$this->szUsercode.$this->szUsername;
$Str = $Str .$this->szDeptcode.$this->szDeptname.$this->szStatus.$this->szCallphone1.$this->szCallphone2.$this->szCallphone3;
$Str = $Str .$this->szCallmessage.$this->szRdate.$this->szRtime.$this->szDummy.$this->SeqNum;
$Str = $Str.$this->szReqphone1.$this->szReqphone2.$this->szReqphone3.$this->szReqname.$this->lMember.$this->ServiceID.$this->TotalPrice.$this->CallPrice.$this->szReserved;


		fwrite($fp, $Str,328);
		flush();

		socket_set_timeout($fp, 10);

		$res=fread($fp,400);

		$this->disconnect($fp);

		return $res;
	}

	function checkMoney()
	{

		global $usercode,$username,$deptcode,$deptname;

		$cdate = mktime();

		$this->szCmd = "J";
		$this->szType = "C";
		$this->szDate = date('Ymd',$cdate).$this->addnull(2);
		$this->szTime = date('His',$cdate).$this->addnull(2);
		$this->szUsercode = $usercode . $this->addnull(30 - strlen($usercode));
		$this->szUsername = $this->addnull(16);
		$this->szDeptcode = $deptcode . $this->addnull(12 - strlen($deptcode));
		$this->szDeptname = $deptname . $this->addnull(16 - strlen($deptname));

		$this->szStatus = "S";

		$this->szCallphone1 = $this->addnull(4 - strlen($callphone1));
		$this->szCallphone2 = $this->addnull(4 - strlen($callphone2));
		$this->szCallphone3 = $this->addnull(4 - strlen($callphone3));
		$this->szCallmessage = $this->addnull(120);

		$this->szRdate = $this->addnull(10);
		$this->szRtime = $this->addnull(8);
		$this->szDummy = $this->addnull(3);
		$this->lMember = $this->addnull(4);
		$this->szReqphone1 = $this->addnull(4);
		$this->szReqphone2 = $this->addnull(4);
		$this->szReqphone3 = $this->addnull(4);
		$this->szReqname = $this->addnull(32);
		$this->szReserved = $this->addnull(16);

		$this->SeqNum = $this->addnull(4);
		$this->ServiceID = $this->addnull(4);
		$this->TotalPrice = $this->addnull(4);
		$this->CallPrice = $this->addnull(4);
		
		$fp=$this->getconnect("messenger");

		if($fp=="-1")
		{
			return "-1";
		}

$Str = $this->szCmd.$this->szType.$this->szDate.$this->szTime.$this->szUsercode.$this->szUsername;
$Str = $Str .$this->szDeptcode.$this->szDeptname.$this->szStatus.$this->szCallphone1.$this->szCallphone2.$this->szCallphone3;
$Str = $Str .$this->szCallmessage.$this->szRdate.$this->szRtime.$this->szDummy.$this->SeqNum;
$Str = $Str.$this->szReqphone1.$this->szReqphone2.$this->szReqphone3.$this->szReqname.$this->lMember.$this->ServiceID.$this->TotalPrice.$this->CallPrice.$this->szReserved;


		fwrite($fp, $Str,328);
		flush();

		socket_set_timeout($fp, 10);

		$res=fread($fp,400);

		$this->disconnect($fp);

		return $res;
	}

}		

// 슈어엠전송결과처리
class SuremResult
{
	// 슈어엠전송결과문자렬
	var $szResult;
	//  결과해석패턴
	var $szPattern;
	// 슈어엠전송성공여부
	var $isSuccess;
	// 통보하려는 결과문자렬
	var $szMsg;
	
    function __construct($Result)
    {
		$this->szResult = $Result;
		$this->szPattern = 0;
		$this->isSuccess = false;
		$this->szMsg = "";
		$this->interpret();
    }
	
	public function getMsg() { return $this->szMsg; }
	public function isSuccess() { return $this->isSuccess; }
	public function getPattern() { return $this->szPattern; }
	
	private function interpret()
	{
		if ($this->szResult == "")
			return false;
	
		global $sms_errmsg;
		$this->szPattern = ord(substr($this->szResult,94,1));
		switch ($this->szPattern)
		{
			case 11:
				$this->szMsg = $sms_errmsg[0];
				$this->isSuccess = false;
				break;
			case 21:
				$this->szMsg = $sms_errmsg[1];
				$this->isSuccess = false;
				break;
			case 22:
				$this->szMsg = $sms_errmsg[2];
				$this->isSuccess = false;
				break;
			case 23:
				$this->szMsg = $sms_errmsg[3];
				$this->isSuccess = false;
				break;
			case 67: // "C"
				$this->szMsg = $sms_errmsg[4];
				$this->isSuccess = false;
				break;
			case 68: // "D"
				$this->szMsg = $sms_errmsg[5];
				$this->isSuccess = false;
				break;
			case 69: // "E"
				$this->szMsg = $sms_errmsg[6];
				$this->isSuccess = false;
				break;
			case 73: // "I"
				$this->szMsg = $sms_errmsg[7];
				$this->isSuccess = false;
				break;
			case 77: // "M"
				$this->szMsg = $sms_errmsg[8];
				$this->isSuccess = false;
				break;
			case 78: // "N"
				$this->szMsg = $sms_errmsg[9];
				$this->isSuccess = false;
				break;
			case 79: // "O"
				$this->szMsg = $sms_errmsg[10];
				$this->isSuccess = true;
				break;
			case 80: // "P"
				$this->szMsg = $sms_errmsg[11];
				$this->isSuccess = false;
				break;
			case 82: // "R"
				$this->szMsg = $sms_errmsg[12];
				$this->isSuccess = false;
				break;
			case 84: // "T"
				$this->szMsg = $sms_errmsg[13];
				$this->isSuccess = false;
				break;
			case 85: // "U"
				$this->szMsg = $sms_errmsg[14];
				$this->isSuccess = false;
				break;
			case 90: // "Z"
				$this->szMsg = $sms_errmsg[15];
				$this->isSuccess = false;
				break;
			case 99: // "c"
				$this->szMsg = $sms_errmsg[16];
				$this->isSuccess = false;
				break;
			default:
				$this->szMsg = $sms_errmsg[1];
				$this->isSuccess = false;
				break;
		}		
		return true;
	}
}
?>