<?php
############## 글로벌 정의 ################################################################################################################
	##########################################
	# 관리 Web Site Global Server-Side Script
	# MYSQL Configuration
	##########################################
	
	$MYSQL['homeURL']			= "http://".$_SERVER["HTTP_HOST"];


	/////////////////////////  			For Crane App        //////////////////////
	
	### admin account
	$MYSQL['tbl_administrator']		= "cr_administrator";				// admin account
	
	$MYSQL['tbl_telecrane_tabel'] 	= "cr_telecrane_tabel";			
	$MYSQL['tbl_telecrane_load'] 	= "cr_telecrane_load";
	$MYSQL['tbl_telecrane_kind'] 	= "cr_telecrane_kind";
	
	$MYSQL['tbl_towercrane_tabel'] 	= "cr_towercrane_tabel";			
	$MYSQL['tbl_towercrane_load'] 	= "cr_towercrane_load";
	$MYSQL['tbl_towercrane_kind'] 	= "cr_towercrane_kind";
    $MYSQL['tbl_specification']     = "cr_specification";
	
	$MYSQL['tbl_email_register']	= "cr_email_register";
	$MYSQL['tbl_policy']			= "cr_userpolicy";	
	$MYSQL['tbl_update']			= "cr_update";	
	$MYSQL['tbl_spec']				= "cr_specification";
	$MYSQL['tbl_manual']			= "cr_manual";
	$MYSQL['tbl_photo']				= "cr_photo";
	$MYSQL['tbl_help'] 				= "cr_help";
	
	#####################################################################
	$MYSQL['dieStr']			= "</script><script>top.location.href='".$MYSQL['homeURL']."';</script>";	
	
	#####################################################################
	date_default_timezone_set("Asia/Seoul");	
##############  ################################################################################################################
	##########################################
	# 
	##########################################
	class memberinfo {
		var $id;
		var $email;
		var $userid;
		var $pwd;
		var $sex;
		var $phone;
		var $age;
		var $birthyear;
		var $city;
		var $area;
		var $job;
		var $height;
		var $coin;
		var $regdate;
		var $character;
		var $aspect;
		var $charm;
		var $hope;
		var $hello;
		var $lv_charmscore1;
		var $lv_charmscore2;
		var $lv_charmscore3;
		var $lv_charmscore4;
		var $lv_charmscore5;
		var $lv_charmscore6;
		var $lv_minage;
		var $lv_maxage;
		var $myqa;
		var $lv_city;
		var $lv_area;
		var $lv_rest;
		var $mainphoto;
		var $photo1;
		var $photo2;
		var $photo3;
		var $photo4;
		var $passqa;
		var $chat;
		var $selcouple;
		var $specialqa;
		var $profileview;
		var $notice;
		
		function memberinfo() {
			
		}
	}
	##########################################
	# 
	##########################################
	class sess_info
	{
		function sess_info($sessname){ session_start();}
		function checkUser() { 
			return true;
			global $ADMSESS; return ($ADMSESS['user_index'] > 0);
		}
		function checkAdmin() {
			return true;
			global $ADMSESS; return ($ADMSESS['user_index'] <= 1); 
		}
	}

	##########################################
	# 
	##########################################
	class SimpleImage {
	   
	   var $image;
	   var $image_type;
	 
	   function load($filename) {
	      $image_info = getimagesize($filename);
	      $this->image_type = $image_info[2];
	      $imgWidth = $image_info[0];
	      $imgHeight = $image_info[1];
	      
	      if( $this->image_type == IMAGETYPE_JPEG ) {
	         $this->image = imagecreatefromjpeg($filename);
	      } elseif( $this->image_type == IMAGETYPE_GIF ) {
	         $this->image = imagecreatefromgif($filename);
	      } elseif( $this->image_type == IMAGETYPE_PNG ) {
	         $this->image = imagecreatefrompng($filename);
	      }
	   }
	   
	   function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {
	      if( $image_type == IMAGETYPE_JPEG ) {
	         imagejpeg($this->image,$filename,$compression);
	      } elseif( $image_type == IMAGETYPE_GIF ) {
	         imagegif($this->image,$filename);         
	      } elseif( $image_type == IMAGETYPE_PNG ) {
	         imagepng($this->image,$filename);
	      }   
	      if( $permissions != null) {
	         chmod($filename,$permissions);
	      }
	   }
	   function getWidth() {
	      return imagesx($this->image);
	   }
	   function getHeight() {
	      return imagesy($this->image);
	   }
	   function resizeToHeight($height) {
	      $ratio = $height / $this->getHeight();
	      $width = $this->getWidth() * $ratio;
	      $this->resize($width,$height);
	   }
	   function resizeToWidth($width) {
	      $ratio = $width / $this->getWidth();
	      $height = $this->getheight() * $ratio;
	      $this->resize($width,$height);
	   }
	   function scale($scale) {
	      $width = $this->getWidth() * $scale/100;
	      $height = $this->getheight() * $scale/100; 
	      $this->resize($width,$height);
	   }
	   function resize($width,$height) {
	      $new_image = imagecreatetruecolor($width, $height);
	      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
	      $this->image = $new_image;
	   }
	   function resize2($width,$height) {
	      $new_image = imagecreatetruecolor($width, $height);
	      imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $width, $height);
	      $this->image = $new_image;
	   }
	   function ratio_scale($ratio) // $ratio = $height / $width
	   {
	   	   $height = $this->getHeight();
	   	   $width = $this->getWidth();
	   	   
	   	   if($height > $width * $ratio){
	   	   	   $diff_x = 0;
	   	   	   $diff_y = ($height - $width * $ratio) / 2;
	   	   	   $height2 = $width * $ratio;
	   	   	   $width2 = $width;
	   	   } else {
	   	   	   $diff_x = ($width - $height / $ratio) / 2;
	   	   	   $diff_y = 0;
	   	   	   $height2 = $height;
	   	   	   $width2 = $height / $ratio;
	   	   }
	      $new_image = imagecreatetruecolor($width2, $height2);
	      imagecopyresampled($new_image, $this->image, 0, 0, $diff_x, $diff_y, $width2, $height2, $width2, $height2);
	      $this->image = $new_image;
	   }
	}
	
	##########################################
	# 
	##########################################
	class CFieldClass
	{
		var $fieldName, $fieldCaption, $fieldWidth, $inputMust, $priKey, $doubleKey, $doubleIndex;
		
		function CFieldClass($fieldName, $fieldCaption, $fieldWidth, $inputMust = true, $priKey = false, $doubleKey = true, $doubleIndex = 0)
		{
			$this->fieldName 		= $fieldName;
			$this->fieldCaption 	= $fieldCaption;
			$this->fieldWidth 		= $fieldWidth;
			$this->inputMust 		= $inputMust;
			$this->priKey 			= $priKey;
			$this->doubleKey 		= $doubleKey;
			$this->doubleIndex		= $doubleIndex;
		}
		
		function GetPriFieldObj($paraArray)
		{
			for($i=0; $i<count($paraArray); $i++)
				if($paraArray[$i]->priKey)
					return $paraArray[$i];
		}
				
		function GetDoubleArray($paraArray)
		{
			$retArray = Array();
			
			for($i=0; $i<count($paraArray); $i++)
				if($paraArray[$i]->doubleKey)
					array_push($retArray, $paraArray[$i]);
			
			return $retArray;
		}
	}
############## ################################################################################################################
	function GenConfimationCode($num = 5, $space = true, $retDirect = false){

		$strDisp = "";
		$hourVal1 	= date("H") % 9 + 1;
		$hourVal2 	= (date("H") - (date("H") % 10))/10 + 1;
		$minVal1	= date("i") % 10 + 1;
		$minVal2 	= (date("i") - (date("i") % 9))/9 + 1;
		$secVal1	= date("s") % 11 + 1;
		$secVal2 	= (date("s") - (date("s") % 8))/8 + 1;

		$strDisp = sprintf("$hourVal1 $secVal1 $minVal1 $minVal2 $hourVal2 $secVal2");
		$strDisp = crypt($strDisp);
		if(strlen($strDisp) > 3) $strDisp = substr($strDisp, 3);
		$strDisp = str_replace("\\", "", $strDisp);
		$strDisp = str_replace("/", "", $strDisp);
		$strDisp = str_replace(".", "", $strDisp);

		if($retDirect) return $strDisp;
		
		$strDisp = str_replace("l", "J", $strDisp);
		$strDisp = str_replace("I", "J", $strDisp);
		$strDisp = str_replace("1", "J", $strDisp);
		$strDisp = str_replace("0", "8", $strDisp);
		$strDisp = str_replace("O", "o", $strDisp);	
		
		$strArray = array();

		for($i=0; $i<$num; $i++) $strArray[$i] = "";
		$i = 1;
		$strArray[0] = substr($strDisp, 0, 1);
		for($j=1; $j<strlen($strDisp); $j++)
		{
			$ret = true;
			$patStr = substr($strDisp, $j, 1);
			for($k=0; $k<$i; $k++)
				if($patStr == $strArray[$k]){
					$ret = false;
					break;
				}
			
			if($ret){
				$strArray[$i] = $patStr;
				$i++;
				if($i == $num) break;	
			}
		}

		$strDisp = $strArray[0];
		for($i=1; $i<$num; $i++){
			if($space) $strDisp .= " ";
			$strDisp .= $strArray[$i];		
		}
		
		return $strDisp;
	}
	function DispPagination($totalPage, $pageNum, $funcName = 'SwitchPage', $prevStr = '전페지', $nextStr = '다음페지')
	{
		if($totalPage <= 0) return;
		echo '<div class="page"><span>' .($pageNum+1) . '/' . ($totalPage+1) . '&nbsp;&nbsp;&nbsp;</span>';
		if ($pageNum>0) echo '<a href="javascript:'.$funcName.'(0);">처음</a> ';
		if ($pageNum>0) echo '<a href="javascript:'.$funcName.'('.($pageNum - 1).');">'.$prevStr.'</a> ';
		$gap = 1;
		$initGap = true;
		for($i=0; $i<=$totalPage; $i++){
			if($pageNum == $i){
				echo '<a class="selected">'.($i+1).'</a> ';
				$initGap = true;
			} else if(($i < $gap) || ($i + $gap > $totalPage) || (($i >= $pageNum-$gap) && ($i <= $pageNum+$gap))) {
				echo '<a href="javascript:'.$funcName.'('.$i.');">'.($i+1).'</a> ';
				$initGap = true;
			} else {
				if($initGap){
					echo '<a class="gap">…</a> ';
					$initGap = false;
				}
			}
		}
		if($pageNum != $totalPage) echo ' <a href="javascript:'.$funcName.'('.($pageNum + 1).');">'.$nextStr.'</a>';
		if($pageNum < $totalPage) echo ' <a href="javascript:'.$funcName.'('.($totalPage).');">마지막페지</span>';
		echo '</div>';
	}
	function CheckNumeric()
	{
		foreach($_POST as $field_name => $field_value)
		{
			$field_name = strtolower($field_name);
			if(($field_name == "checkuser")||($field_name == "checkadmin")||($field_name == "checkblogger")||($field_name == "mysess")||($field_name == "adminsess")) return false;
			if((strpos ($field_name, "str")===false) && (!(strpos ($field_name, "idx")===false)))
				if(!is_numeric($field_value) && ($field_value != "")) return false;
		}
		
		foreach($_GET as $field_name => $field_value)
		{
			if(($field_name == "checkuser")||($field_name == "checkadmin")||($field_name == "checkblogger")||($field_name == "mysess")||($field_name == "adminsess")) return false;
			$field_name = strtolower($field_name);
			if((strpos ($field_name, "str")===false) && (!(strpos ($field_name, "idx")===false)))
				if(!is_numeric($field_value) && ($field_value != "")) return false;
		}
		
		return true;
	}
	function GetPostValue()
	{
		$list = null;
		 
		// Building the list
		foreach($_POST as $field_name => $field_value)
			$list .= $field_value;
		
		foreach($_GET as $field_name => $field_value)
		      //$list .= "<strong>{$field_name}</strong>: {$field_value}<br />\r\n";
			$list .= $field_value;
		 
		// Trimming the ends of the list from any unneeded white spaces
		$list = trim($list);
		 
		 $list = strtoupper($list);
		 $list = html_entity_decode($list);
		// Returning the list of variables.
		return $list;
	}	
	function CheckSQLInjection($strIn)
	{
		$ret = true;
		
		$patternStrArr  = array('SELECT','DELETE','FROM','INFORMATION','UPDATE','SET','WHERE');
		for($i=0; $i<count($patternStrArr); $i++)
		{
			if(!(strpos ($strIn, $patternStrArr[$i])===false)){
				$ret = false;
				return false;
			}
		}
		return $ret;
	}
	function delete_directory($dirname) 
	{
		$dir_handle = "";
	   if (is_dir($dirname))
	      $dir_handle = opendir($dirname);
	   if (!$dir_handle)
	      return false;
	   while($file = readdir($dir_handle)) {
	      if ($file != "." && $file != "..") {
	         if (!is_dir($dirname."/".$file))
	            unlink($dirname."/".$file);
	         else
	            delete_directory($dirname.'/'.$file);    
	      }
	   }
	   closedir($dir_handle);
	   rmdir($dirname);
	   return true;
	}	
	function tolog($data)
	{		
		$data = "[SOULMATE][IP:".$_SERVER['REMOTE_ADDR']."]".$data;
		log_message('error', $data);
	}
	function error($file, $func, $line, $disp="")
	{
		$data = "[SOULMATE][IP:".$_SERVER['REMOTE_ADDR']."]\tThe error is as follows.\n";
		$data = $data."[FilePath:".$file."],";
		$data = $data."[Line:".$line."],";
		$data = $data."[Function:".$func."],";
		$data = $data."[Description:".$disp."]";

		log_message('error', $data);
	}
#################  #########################################################################################################
	function now()
	{
		return date("Y-m-d H:i:s");
	}
	function today()
	{
		return date("Y-m-d");
	}
	function nowtime()
	{
		return date("H:i:s");
	}
	function year()
	{
		return date("Y");
	}
	function todaystart()
	{
		return date("Y-m-d 00:00:00");
	}
	function todayend()
	{
		return date("Y-m-d 23:59:59");
	}
	function daybefore($d)
	{
		return date("Y-m-d", time()-$d*24*60*60);
	}
	function daystart()
	{
		return date("Y-m-d", 0);
	}
	function todate($date)
	{
		return date("Y-m-d", strtotime($date));
	}	
	function tobegintime($date)
	{
		return todate($date)." 00:00:00";
	}
	function toendtime($date)
	{
		return todate($date)." 23:59:59";
	}
	function toyear($date)
	{
		return date("Y", strtotime($date));
	}
	function monthbefore($m)
	{
		$str = "-".$m." month";
		return date('Y-m-1', strtotime($str));
	}
	function prev_month_start()
	{
		return date('Y-m-1', strtotime("-1 month"));
	}
	function prev_month_end()
	{
		$year = date('Y', strtotime("-1 month"));
		$month = date('m', strtotime("-1 month"));
		$days = cal_days_in_month(CAL_GREGORIAN, $month, $year);
		return $year . "-" . $month . "-" . $days;
	}
	function prev_week_start()
	{
		$prev_week_end = prev_week_end();
		return date('Y-m-d', strtotime('-6 days', strtotime($prev_week_end)));

	}
	function prev_week_end()
	{
		return date('Y-m-d', strtotime('this Saturday -1 week'));
	}
##############################################################################################################################	
	function json_capsule($arr)
	{
		$data = json_encode($arr);
		$json_result = "{\"result\":[";
		$json_result .= $data;
		$json_result .= "]}";
		tolog($json_result);
		
		return $json_result;
	}	
	function e2k_sex($sex)
	{
		if ($sex == "Man") return "남자";
		if ($sex == "Woman") return "여자";
		return '';
	}
	function get_city_area($city, $area)
	{
		if ($city != "") {
			return $city . "," . $area;
		}
		return $area;
	}
##############################################################################################################################
	$baseFileDir = $_SERVER["SCRIPT_NAME"];
	$baseDir = substr($baseFileDir, 0, strrpos($baseFileDir, "/"));
	$postStr = GetPostValue();
	
	$sess_info = new sess_info("");	
	if (isset($_SESSION["ADMSESS"])) $ADMSESS = $_SESSION["ADMSESS"];
	$checkUser = $sess_info->checkUser();
	$checkAdmin = $sess_info->checkAdmin();
?>