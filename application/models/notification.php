<?php
// GCM / APNS관리
class notification extends CI_Model {
    function __construct()
    {
    	global $MYSQL;
        parent::__construct();
    }

	function gcmsend($from_id, $tokenid, $param)
	{
		// ProjectID : natural-night-442
		// ServerKey : AIzaSyAfibClPJv94NIPec9E0Jz_uQf7WgR7uxY
		$apikey = "AIzaSyBYbi0d66HEAp-a8o6fu-0wkYAnZVOqAMA";
		
/*------------Send 	to GCM Server-----------*/
		// 파라메터 만들기
		$tokenarr[0] = $tokenid;
		$headers = array(
			'Content-Type: application/json',
			'Authorization: key='.$apikey
		);
		
		$arr = array();
		$arr['data'] = array();
		$arr['data']['Category'] = $param["category"];
		$arr['data']['NotifyID'] = $param["nid"];
		$arr['data']['PartnerID'] = $param["mid"];
		$arr['data']['View'] = $param["view"];
		$arr['data']['From'] = $from_id;
		$arr['data']['Content'] = $param["content"];
		$arr['data']['Time'] = now();
		$arr['registration_ids'] = $tokenarr;
		
		$ret = false;
		$log = "";
		try {			
			// 전송
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://android.googleapis.com/gcm/send');
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($arr));
			$response = curl_exec($ch);
			$json_result = json_decode($response);
			curl_close($ch);	
			
			if ($json_result != null && $json_result->failure == 0)
				$ret = true;
				
			$log = $response;
		}
		catch (Exception $e) {
			$log = $e->getMessage();
		}
		
		// 로그기록
		if ($ret)		
			tolog("GCM SUCCESS=>".$tokenid.":".$log);
		else		
			tolog("GCM FAIL=>".$tokenid.":".$log);
		return $ret;
	}
	
	function apnssend($from_id, $tokenid, $param) {
		$ret = false;
		$time = now();
		// For Development
		$apnsHost = 'gateway.sandbox.push.apple.com';
		$apnsCert = 'apns-dev.pem';

		// For Real Service
		//$apnsHost = 'gateway.push.apple.com';
		//$apnsCert = 'apns-product.pem';

		$apnsPort = 2195;
		$from_str = ($from_id == "!Broadcast!") ? "Server" : $from_id;
		
		// 요청파라메터 생성
		if ($param["view"] == 1)
		{			
			$payload = array(
				'NotifyID'=>$param["nid"],
				'Category'=>$param["category"],
				'PartnerID'=>$param["mid"],
				'aps'=>array(
					'alert'=>$param["content"],
					'badge' => 1,
					'sound' => 'default'
					)
			);
		}
		else
		{
			$payload = array(
				'NotifyID'=>$param["nid"],
				'Category'=>$param["category"],
				'PartnerID'=>$param["mid"],
				'aps'=>array(
					'badge' => 1,
					'sound' => 'default'
					)
			);
		}
		$payload = json_encode($payload, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);

		// APNS전송
		try {			
			$streamContext = stream_context_create();
			stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
			$apns = stream_socket_client('ssl://'.$apnsHost.':'.$apnsPort, $error, $errorString, 20, STREAM_CLIENT_CONNECT, $streamContext);
			if($apns)
			{
				$apnsMessage = chr(0).chr(0).chr(32).pack('H*', str_replace(' ', '', $tokenid)).chr(0).chr(strlen($payload)).$payload;
				fwrite($apns, $apnsMessage);
	            $ret = true;
			}
			if($apns) fclose($apns);
			
		}
		catch (Exception $e)
		{
			tolog('Caught exception: '.$e->getMessage()."\n");
		}
		// 로그기록
		if ($ret) tolog("APNS SEND=>".$tokenid.": SUCCESS");
		else tolog("APNS SEND=>".$tokenid.": FAIL");
		
		return $ret;
	}
	
	function send($to, $param)
	{
		$this->load->model("token");
		$tokens = $this->token->getmems($to);
		if ($tokens == null)
			return false;
		
		$android = array();
		$iphone = array();
		
		foreach($tokens as $token)
		{
			if($token->tk_phoneos == 1)	// Android
			{
				if (!$this->gcmsend("SOULMATE SERVER", $token->tk_token, $param))
					return false;
			}
			else if($token->tk_phoneos == 2)	// iPhone
			{
				if (!$this->apnssend("SOULMATE SERVER", $token->tk_token, $param))
					return false;
			}
		}	
		return true;
	}
}
?>