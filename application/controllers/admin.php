<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include("www/include/global.php");
include("www/include/ijmfuncs.php");

$data['baseDir'] = $baseDir;

class admin extends CI_Controller {
	// First call
	public function index()
	{
		global $data;
		$this->sess_init();
		$this->load->view("logon", $data);
	}
	// Session initialize
	public function sess_init($user_id="", $user_pwd="")
	{
		$this->load->model("basis");
		$ret = $this->basis->sess_init($user_id, $user_pwd);
		return $ret;
	}
	// User verification
	public function admin_check()
	{
		foreach($_POST as $field_name => $field_value) $$field_name = $field_value;
		if (!isset($user_id) || !isset($user_pwd))
			exit;
		
		echo $this->sess_init($user_id, $user_pwd);
	}
	// check login
	public function __check_user()
	{
		global $ADMSESS;
		global $baseDir;
		
		if (!isset($_SESSION["ADMSESS"])) {
			echo "<script>window.parent.location.href='$baseDir';</script>";
		}
	}
	// first page after login
	public function main()
	{
		$this->__check_user();
		global $data;
		$data['administrator'] = $this->get_admin_name();		
		$this->load->view("main", $data);
	}
	// change administrator
	public function changeadmin()
	{
		$this->__check_user();
		global $data;
		$data['administrator'] = $this->get_admin_name();
		$this->load->view("changeadmin", $data);
	}
	public function change_administrator()
	{
		$old_pwd = $_POST['old_pwd'];
		$new_password = $_POST['new_password'];
		if ($old_pwd == "") exit;
		if ($new_password == "") exit;

		$this->__check_user();
		global $ADMSESS;
		$current_admin = $ADMSESS['admin'];

		$this->load->model("basis");
		$ret = $this->basis->change_administrator_info($current_admin, $old_pwd, $new_password);
		
		echo $ret;
	}
	public function addadmin()
	{
		$this->__check_user();
		global $data;
		$data['administrator'] = $this->get_admin_name();
		$this->load->view("addadmin", $data);
	}
	public function add_administrator()
	{
		$this->__check_user();
		if(!isset($_POST['new_admin']) || !isset($_POST['new_password']))
		{
			global $data;
			$data['administrator'] = $this->get_admin_name();
			$this->load->view("addadmin", $data);			
		}	
		else
		{
			$new_admin = $_POST['new_admin'];
			$new_password = $_POST['new_password'];
			if ($new_admin == "") exit;
			if ($new_password == "") exit;
			
			
			$this->load->model("basis");
			$this->basis->add_administrator($new_admin, $new_password);
		}
		
	}
	function get_admin_name()
	{
		global $ADMSESS;
		return $ADMSESS['admin'];
	}

	public function adminlist()
	{
		$this->__check_user();
		global $data;
		$data['administrator'] = $this->get_admins();
		$this->load->view("adminlist", $data);
	}

	public function get_admins()
	{
		global $baseDir;

		$this->load->model('basis');
		
		@$command 		= $_POST['command'];
		@$current_stage 	= $_POST['current_stage'];
		@$current_page 	= $_POST['current_page'];
		@$keyname 		= $_POST['keyname'];
		@$keyvalue 		= $_POST['keyvalue'];		


    $admin_list = $this->get_admin($keyvalue);
    $total_admin_count = count($admin_list);

    $pages = (int)(($total_admin_count + 4) / 5);
    $stages = (int)(($pages + 9) / 10);
    $next_page = $current_page;
    $next_stage = $current_stage;

    if ($command == 'save') {
      ;
    }
    else if ($command == 'refresh') {
      ; // skip
    }
    else if ($command == 'search') {
      $next_page = 1;
      $next_stage = 1;
    }
    else if ($command == 'prev') {
      $next_stage = $current_stage - 1;
      if ($next_stage < 1) {
        echo 'ERROR';
        return;
      }
      $next_page = 1;
    }
    else if ($command == 'next') { 
      $next_stage = $current_stage + 1;
      if ($next_stage > $stages) {
        echo 'ERROR';
        return;
      }
      $next_page = 1;
    }
    else {
      $next_page = intval($command);
      if ($next_page < 1 || $next_page > 10) {
        echo 'ERROR';
        return;
      }
    }
    if (($next_stage - 1) * 10 + $next_page > $pages) {
      $next_stage = 1;
      $next_page = 1;
    }

			$html = "";			
			$html = $html . $total_admin_count . "|||";
			$html = $html . $next_stage . "|||";			
			$html = $html . $next_page . "|||";

			$start_index = ($next_stage - 1) * 50 + ($next_page - 1) * 5;
			$count = min(5, $total_admin_count - $start_index);


			for ($j = 0; $j < $count; $j++) {
				$i = $j + $start_index;
				

        $admin_id = $admin_list[$i]->ad_id;
        $admin_name = $admin_list[$i]->ad_admin;
        $ad_last_login = $admin_list[$i]->ad_last_login; 
        
				$html = $html . "<tr class='cla-tr-$i' name='" . $admin_id . "'>";
				$html = $html . "<td style='text-align:center;'><input class='checkbox' type='checkbox' adminid='" . $admin_id ."'/></td>";
				$html = $html . "<td style='text-align:center;'><div>" . $admin_name . ": Last logged in : ". $ad_last_login."</div></td>";
				$html = $html . "<td style='text-align:center;width:70px;'>
									<button style='width:70px' onclick='javascript:onClickDetails(\"$admin_id\");'>Edit</button>
									<button style='width:70px' onclick='javascript:onClickDelete(\"$admin_id\");'>Remove</button>
								</td>";
				$html = $html . "</tr>";
			}
			for (; $j < 5; $j++) {
				$i = $j + $start_index;
				$html = $html . "<tr class='cla-tr-$i' name='none' style='height:70.5px;'>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "</tr>";
			}
			
			// navigation
			$html = $html . "|||";
			$temp = "";
			$pages = $pages - ($next_stage - 1) * 10;
			$count = min($pages, 10);
			for ($i = 1; $i <= $count; $i++) {
				$text = ($next_stage - 1) * 10 + $i;
				if ($i == $next_page) {
					$temp = $temp . "<li class='cla-page-$i active'><a href='javascript:onSelectPage($i);'>$text</a></li>";
				}
				else {
					$temp = $temp . "<li class='cla-page-$i'><a href='javascript:onSelectPage($i);'>$text</a></li>";
				}
			}
			
			$html = $html . "<ul class='page-navigation'>";
			
			if ($stages > 1) {
				$html = $html . "<li><a href='javascript:onSelectPage(\"prev\");' class='prev' name='prev'></a></li>";
				$html = $html . $temp;
				if ($pages > 10) $html = $html . "<li><a href='javascript:onSelectPage(\"next\");' class='next' name='next'></a></li>";
			}
			else {
				if ($pages > 1) {
					$html = $html . $temp;
				}
			}

			$html = $html . "</ul>";			
			echo $html;

	}

	public function get_admin($keyvalue)
	{
		$this->load->model('basis');
		$admin_details = $this->basis->get_admin($keyvalue);
		return $admin_details;
	}


	public function help()
	{
		$this->__check_user();
		global $data;
		$data['administrator'] = $this->get_admin_name();
		$data['help_dutch'] = sqlTextDecode($this->get_help(1));// 1: dutch, 2: german, 3: french;
		$data['help_german'] = sqlTextDecode($this->get_help(2));
		$data['help_french'] = sqlTextDecode($this->get_help(3));
		$this->load->view("help", $data);
	}
	public function get_help($type)
	{
		$this->__check_user();
		$this->load->model("application");
		$ret = $this->application->get_help($type);
		return $ret;
	}
	public function set_help()
	{
		$help_dutch = $_POST['help_dutch'];
		$help_german = $_POST['help_german'];
		$help_french = $_POST['help_french'];
		
		$help_dutch = sqlTextEncode($help_dutch);
		$help_german = sqlTextEncode($help_german);
		$help_french = sqlTextEncode($help_french);
		
		$this->load->model("application");
		$ret = $this->application->set_help($help_dutch, 1);
		$ret = $this->application->set_help($help_german, 2);
		$ret = $this->application->set_help($help_french, 3);
		echo $ret;
	}

	public function userpolicy()
	{
		$this->__check_user();
		global $data;
		$data['administrator'] = $this->get_admin_name();
		$data['policy_dutch'] = sqlTextDecode($this->get_policy(1));// 1: dutch, 2: german, 3: french;
		$data['policy_german'] = sqlTextDecode($this->get_policy(2));
		$data['policy_french'] = sqlTextDecode($this->get_policy(3));
		$this->load->view("agreement", $data);
	}

	public function get_policy($type)
	{
		$this->__check_user();
		$this->load->model("application");
		$ret = $this->application->get_policy($type);
		return $ret;
	}
	public function set_policy()
	{
		$policy_dutch = $_POST['policy_dutch'];
		$policy_german = $_POST['policy_german'];
		$policy_french = $_POST['policy_french'];
		
		$policy_dutch = sqlTextEncode($policy_dutch);
		$policy_german = sqlTextEncode($policy_german);
		$policy_french = sqlTextEncode($policy_french);
		
		$this->load->model("application");
		$ret = $this->application->set_policy($policy_dutch, 1);
		$ret = $this->application->set_policy($policy_german, 2);
		$ret = $this->application->set_policy($policy_french, 3);
		echo $ret;
	}
	public function update()
	{
		$this->__check_user();
		global $data;
		$data['administrator'] = $this->get_admin_name();		
		$this->load->view("update", $data);
	}
	
	public function set_update()
	{		
		$update_dutch  = $_POST['update_dutch'];
		$update_german = $_POST['update_german'];
		$update_french = $_POST['update_french'];

		$update_dutch  = sqlTextEncode($update_dutch);
		$update_german = sqlTextEncode($update_dutch);
		$update_french = sqlTextEncode($update_dutch);

		$this->load->model("application");

		$last_index = $this->application->get_update_lastindex();

		$ret = $this->application->set_update($last_index + 1, $update_dutch,  1);
		$ret = $this->application->set_update($last_index + 1, $update_german, 2);
		$ret = $this->application->set_update($last_index + 1, $update_french, 3);
	}
	public function download_manual()
	{
		$this->__check_user();
		global $data;
		$data['administrator'] = $this->get_admin_name();
		$this->load->view("download_manual", $data);
	}
	
	public function search_manual()
	{
		global $baseDir;

		$this->load->model("application");
		$this->load->model("crane");

		$start_date = $_POST['start_date'];
		$end_date = $_POST['end_date'];
		

		$result = $this->application->get_download_manual($start_date, $end_date);

		$html = "";
		for($i = 0; $i < count($result); $i++)
		{
			$crane_type = $result[$i]->CraneType;
			$crane_id = $result[$i]->CraneID;
			$date = $result[$i]->Date;

			$photo = $this->application->getPhoto($crane_type, $crane_id);

			$crane_result = $this->crane->getCrane($crane_type, $crane_id);

			if($crane_result == null)
				continue;

			$brand = $crane_result->Brand;
			$type = $crane_result->Type;
			if($crane_type == 2)
				$model = $crane_result->Model;
			$size = $crane_result->Size;
			$maxlen = $crane_result->MaxLen;
			if($crane_type == 1)
				$tonnage = $crane_result->Tonnage;
			$notition = $crane_result->Notition;
			
			$html = $html . "<tr class='cla-tr-$i'>";
			$html = $html . "<td style='text-align:center;'>
								<div class='table-photo-item' style='background-image: url(" . $baseDir . "/" . $photo . ");'>
									<div style='color:#003070;text-align:right;margin:5px;text-shadow: 0px 0px 1px white;'>" . ($i + 1) . "</div>
								</div>
							</td>";
			$html = $html . "<td style='text-align:center;'><div>" . $brand . "</div></td>";							
			if($crane_type == 1)							
				$html = $html . "<td style='text-align:center;'><div>" . $type . "</div></td>";
			else
				$html = $html . "<td style='text-align:center;'><div>" . $model . " " . $type . "</div></td>";
			$html = $html . "<td style='text-align:left; padding-left:10px;'><div>" . "1. " . $type . "<br/>" . "2. " . $size . "<br/>" . "3. " . $maxlen . "<br/>" . "4. " . $tonnage . "</div></td>";
			$html = $html . "<td style='text-align:center;'><div>" . $notition . "</div></td>";
			$html = $html . "<td style='text-align:center;'><div>" . $date . "</div></td>";
			$html = $html . "</tr>";
		}

		for (; $i < 5; $i++) {				
				$html = $html . "<tr class='cla-tr-$i'>";
				$html = $html . "<td style='text-align:center;'>
								<div class='table-photo-item' style='background-image: url(" . $baseDir . "/www/images/nophoto.png);'></div>
							    </td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";				
				$html = $html . "</tr>";
		}

		echo $html;

	}
	
	public function donate_photo()
	{
		$this->__check_user();
		global $data;
		$data['administrator'] = $this->get_admin_name();
		$this->load->view("donate_photo", $data);
	}
	public function search_photo()
	{
		global $baseDir;

		$this->load->model("application");
		$this->load->model("crane");

		$start_date = $_POST['start_date'];
		$end_date = $_POST['end_date'];
		

		$result = $this->application->get_donate_photo($start_date, $end_date);

		$html = "";
		for($i = 0; $i < count($result); $i++)
		{
			$crane_type = $result[$i]->CraneType;
			$crane_id = $result[$i]->CraneID;
			$date = $result[$i]->DonateDate;
			$photo = $result[$i]->Photo;
			$email = $result[$i]->Email;

			$crane_result = $this->crane->getCrane($crane_type, $crane_id);

			if($crane_result == null)
				continue;

			$brand = $crane_result->Brand;
			$type = $crane_result->Type;
			if($crane_type == 2)
				$model = $crane_result->Model;
			
			$html = $html . "<tr class='cla-tr-$i'>";
			$html = $html . "<td style='text-align:center;'>
								<div class='table-photo-item' style='background-image: url(" . $baseDir . "/" . $photo . ");'>
									<div style='color:#003070;text-align:right;margin:5px;text-shadow: 0px 0px 1px white;'>" . ($i + 1) . "</div>
								</div>
							</td>";
			$html = $html . "<td style='text-align:center;'><div>" . $email . "</div></td>";							
			$html = $html . "<td style='text-align:center;'><div>" . $brand . "</div></td>";							
			if($crane_type == 1)							
				$html = $html . "<td style='text-align:center;'><div>" . $type . "</div></td>";
			else
				$html = $html . "<td style='text-align:center;'><div>" . $model . " " . $type . "</div></td>";			
			$html = $html . "<td style='text-align:center;'><div>" . $date . "</div></td>";
			$html = $html . "</tr>";
		}

		for (; $i < 5; $i++) {				
				$html = $html . "<tr class='cla-tr-$i'>";
				$html = $html . "<td style='text-align:center;'>
								<div class='table-photo-item' style='background-image: url(" . $baseDir . "/www/images/nophoto.png);'></div>
							    </td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";			
				$html = $html . "</tr>";
		}

		echo $html;	
	}
	
	public function crane()
	{
		$this->__check_user();
		global $data;

		$data['administrator'] = $this->get_admin_name();
		$data['current_page'] = 1;
		$data['current_stage'] = 1;
		if (isset($_GET["stage"]) && (isset($_GET["page"]))) {
			$data['current_stage'] = $_GET["stage"];
			$data['current_page'] = $_GET["page"];
		}

		$this->load->view("crane", $data);
	}
	
	public function get_cranes()
	{
		global $baseDir;

		$this->load->model('application');

		$command 		= $_POST['command'];
		$current_stage 	= $_POST['current_stage'];
		$current_page 	= $_POST['current_page'];
		$keyname 		= $_POST['keyname'];
		$keyvalue 		= $_POST['keyvalue'];

		if($keyname == 'all')
		{
			$tele_crane_list = $this->get_telecrane($keyvalue);
			$tower_crane_list = $this->get_towercrane($keyvalue);

			$total_crane_count = count($tele_crane_list) + count($tower_crane_list);

			$pages = (int)(($total_crane_count + 4) / 5);
			$stages = (int)(($pages + 9) / 10);
			$next_page = $current_page;
			$next_stage = $current_stage;

			if ($command == 'save') {
				;
			}
			else if ($command == 'refresh') {
				; // skip
			}
			else if ($command == 'search') {
				$next_page = 1;
				$next_stage = 1;
			}
			else if ($command == 'prev') {
				$next_stage = $current_stage - 1;
				if ($next_stage < 1) {
					echo 'ERROR';
					return;
				}
				$next_page = 1;
			}
			else if ($command == 'next') { 
				$next_stage = $current_stage + 1;
				if ($next_stage > $stages) {
					echo 'ERROR';
					return;
				}
				$next_page = 1;
			}
			else {
				$next_page = intval($command);
				if ($next_page < 1 || $next_page > 10) {
					echo 'ERROR';
					return;
				}
			}
			if (($next_stage - 1) * 10 + $next_page > $pages) {
				$next_stage = 1;
				$next_page = 1;
			}

			$html = "";			
			$html = $html . $total_crane_count . "|||";
			$html = $html . $next_stage . "|||";			
			$html = $html . $next_page . "|||";

			$start_index = ($next_stage - 1) * 50 + ($next_page - 1) * 5;
			$count = min(5, $total_crane_count - $start_index);


			for ($j = 0; $j < $count; $j++) {
				$i = $j + $start_index;
				
				if($i < count($tele_crane_list))
				{
					$crane_type = 1;
					$crane_id = $tele_crane_list[$i]->CraneID;
					$photo 	  = $this->application->getPhoto(1, $crane_id);
					$brand 	  = $tele_crane_list[$i]->Brand;
					$type_num = $tele_crane_list[$i]->Type;
					$size     = $tele_crane_list[$i]->Size;
					$maxlen   = $tele_crane_list[$i]->MaxLen;
					$tonnage  = $tele_crane_list[$i]->Tonnage . " ton's";
					$notition = $tele_crane_list[$i]->Notition;
				}
				else
				{
					$crane_type = 2;
					$crane_id = $tower_crane_list[$i - count($tele_crane_list)]->CraneID;
					$photo    = $this->application->getPhoto(2, $crane_id);
					$brand    = $tower_crane_list[$i - count($tele_crane_list)]->Brand;
					$type_num = $tower_crane_list[$i - count($tele_crane_list)]->Type;
					$size     = $tower_crane_list[$i - count($tele_crane_list)]->Size;
					$maxlen   = $tower_crane_list[$i - count($tele_crane_list)]->MaxLen;
					$tonnage  = "";
					$notition = $tower_crane_list[$i - count($tele_crane_list)]->Notition;
				}


				$html = $html . "<tr class='cla-tr-$i' name='" . $type_num . "'>";
				$html = $html . "<td style='text-align:center;'><input class='checkbox' type='checkbox' cranetype='" . $crane_type . "' craneid='" . $crane_id ."'/></td>";
				$html = $html . "<td style='text-align:center;'>
									<div class='table-photo-item' style='background-image: url(" . $baseDir . "/" . $photo . "); '>
										<div style='color:#003070;text-align:right;margin:5px;text-shadow: 0px 0px 1px white;'>" . ($i + 1) . "</div>
									</div>
								</td>";
				$html = $html . "<td style='text-align:center;'><div>" . $brand . "</div></td>";
				$html = $html . "<td style='text-align:center;'><div>" . $type_num . "</div></td>";								
				$html = $html . "<td style='text-align:left; padding-left:10px;'><div>" . "1. " . $type_num . "<br/>" . "2. " . $size . "<br/>" . "3. " . $maxlen . "<br/>" . "4. " . $tonnage . "</div></td>";
				$html = $html . "<td style='text-align:center;'><div>" . $notition . "</div></td>";
				$html = $html . "<td style='text-align:center;width:70px;'>
									<button style='width:70px' onclick='javascript:onClickDetails(\"$crane_type\", \"$crane_id\");'>Edit</button>
									<button style='width:70px' onclick='javascript:onClickDelete(\"$crane_type\", \"$crane_id\");'>Remove</button>
								</td>";
				$html = $html . "</tr>";
			}
			for (; $j < 5; $j++) {
				$i = $j + $start_index;
				$html = $html . "<tr class='cla-tr-$i' name='none' style='height:70.5px;'>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "</tr>";
			}
			
			// navigation
			$html = $html . "|||";
			$temp = "";
			$pages = $pages - ($next_stage - 1) * 10;
			$count = min($pages, 10);
			for ($i = 1; $i <= $count; $i++) {
				$text = ($next_stage - 1) * 10 + $i;
				if ($i == $next_page) {
					$temp = $temp . "<li class='cla-page-$i active'><a href='javascript:onSelectPage($i);'>$text</a></li>";
				}
				else {
					$temp = $temp . "<li class='cla-page-$i'><a href='javascript:onSelectPage($i);'>$text</a></li>";
				}
			}
			
			$html = $html . "<ul class='page-navigation'>";
			
			if ($stages > 1) {
				$html = $html . "<li><a href='javascript:onSelectPage(\"prev\");' class='prev' name='prev'></a></li>";
				$html = $html . $temp;
				if ($pages > 10) $html = $html . "<li><a href='javascript:onSelectPage(\"next\");' class='next' name='next'></a></li>";
			}
			else {
				if ($pages > 1) {
					$html = $html . $temp;
				}
			}

			$html = $html . "</ul>";			
			echo $html;

		}
		else if($keyname == 'tele_crane')
		{
			$tele_crane_list = $this->get_telecrane($keyvalue);

			$total_crane_count = count($tele_crane_list);
			$pages = (int)(($total_crane_count + 4) / 5);
			$stages = (int)(($pages + 9) / 10);
			$next_page = $current_page;
			$next_stage = $current_stage;

			if ($command == 'save') {
				;
			}
			else if ($command == 'refresh') {
				; // skip
			}
			else if ($command == 'search') {
				$next_page = 1;
				$next_stage = 1;
			}
			else if ($command == 'prev') {
				$next_stage = $current_stage - 1;
				if ($next_stage < 1) {
					echo 'ERROR';
					return;
				}
				$next_page = 1;
			}
			else if ($command == 'next') { 
				$next_stage = $current_stage + 1;
				if ($next_stage > $stages) {
					echo 'ERROR';
					return;
				}
				$next_page = 1;
			}
			else {
				$next_page = intval($command);
				if ($next_page < 1 || $next_page > 10) {
					echo 'ERROR';
					return;
				}
			}
			if (($next_stage - 1) * 10 + $next_page > $pages) {
				$next_stage = 1;
				$next_page = 1;
			}

			$html = "";			
			$html = $html . $total_crane_count . "|||";
			$html = $html . $next_stage . "|||";			
			$html = $html . $next_page . "|||";

			$start_index = ($next_stage - 1) * 50 + ($next_page - 1) * 5;
			$count = min(5, $total_crane_count - $start_index);


			for ($j = 0; $j < $count; $j++) {
				$i = $j + $start_index;
				
				$crane_type = 1;
				$crane_id = $tele_crane_list[$i]->CraneID;
				$photo 	  = $this->application->getPhoto(1, $crane_id);
				$brand 	  = $tele_crane_list[$i]->Brand;
				$type_num = $tele_crane_list[$i]->Type;
				$size     = $tele_crane_list[$i]->Size;
				$maxlen   = $tele_crane_list[$i]->MaxLen;
				$tonnage  = $tele_crane_list[$i]->Tonnage . " ton's";
				$notition = $tele_crane_list[$i]->Notition;				

				$html = $html . "<tr class='cla-tr-$i' name='" . $type_num . "'>";
				$html = $html . "<td style='text-align:center;'><input class='checkbox' type='checkbox' cranetype='" . $crane_type . "' craneid='" . $crane_id ."'/></td>";
				$html = $html . "<td style='text-align:center;'>
									<div class='table-photo-item' style='background-image: url(" . $baseDir . "/" . $photo . ");'>
										<div style='color:#003070;text-align:right;margin:5px;text-shadow: 0px 0px 1px white;'>" . ($i + 1) . "</div>
									</div>
								</td>";
				$html = $html . "<td style='text-align:center;'><div>" . $brand . "</div></td>";
				$html = $html . "<td style='text-align:center;'><div>" . $type_num . "</div></td>";								
				$html = $html . "<td style='text-align:left; padding-left:10px;'><div>" . "1. " . $type_num . "<br/>" . "2. " . $size . "<br/>" . "3. " . $maxlen . "<br/>" . "4. " . $tonnage . "</div></td>";
				$html = $html . "<td style='text-align:center;'><div>" . $notition . "</div></td>";
				$html = $html . "<td style='text-align:center;width:70px;'>
									<button style='width:70px' onclick='javascript:onClickDetails(\"$crane_type\", \"$crane_id\");'>Edit</button>
									<button style='width:70px' onclick='javascript:onClickDelete(\"$crane_type\", \"$crane_id\");'>Remove</button>
								</td>";
				$html = $html . "</tr>";
			}
			for (; $j < 5; $j++) {
				$i = $j + $start_index;
				$html = $html . "<tr class='cla-tr-$i' name='none' style='height:70.5px;'>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "</tr>";
			}
			
			// navigation
			$html = $html . "|||";
			$temp = "";
			$pages = $pages - ($next_stage - 1) * 10;
			$count = min($pages, 10);
			for ($i = 1; $i <= $count; $i++) {
				$text = ($next_stage - 1) * 10 + $i;
				if ($i == $next_page) {
					$temp = $temp . "<li class='cla-page-$i active'><a href='javascript:onSelectPage($i);'>$text</a></li>";
				}
				else {
					$temp = $temp . "<li class='cla-page-$i'><a href='javascript:onSelectPage($i);'>$text</a></li>";
				}
			}
			
			$html = $html . "<ul class='page-navigation'>";
			
			if ($stages > 1) {
				$html = $html . "<li><a href='javascript:onSelectPage(\"prev\");' class='prev' name='prev'></a></li>";
				$html = $html . $temp;
				if ($pages > 10) $html = $html . "<li><a href='javascript:onSelectPage(\"next\");' class='next' name='next'></a></li>";
			}
			else {
				if ($pages > 1) {
					$html = $html . $temp;
				}
			}

			$html = $html . "</ul>";

			$html = $html . "|||" . $this->get_admin_name();

			echo $html;
		}
		else if($keyname == 'tower_crane')
		{
			$tower_crane_list = $this->get_towercrane($keyvalue);
			$total_crane_count = count($tower_crane_list);

			$pages = (int)(($total_crane_count + 4) / 5);
			$stages = (int)(($pages + 9) / 10);
			$next_page = $current_page;
			$next_stage = $current_stage;

			if ($command == 'save') {
				;
			}
			else if ($command == 'refresh') {
				; // skip
			}
			else if ($command == 'search') {
				$next_page = 1;
				$next_stage = 1;
			}
			else if ($command == 'prev') {
				$next_stage = $current_stage - 1;
				if ($next_stage < 1) {
					echo 'ERROR';
					return;
				}
				$next_page = 1;
			}
			else if ($command == 'next') { 
				$next_stage = $current_stage + 1;
				if ($next_stage > $stages) {
					echo 'ERROR';
					return;
				}
				$next_page = 1;
			}
			else {
				$next_page = intval($command);
				if ($next_page < 1 || $next_page > 10) {
					echo 'ERROR';
					return;
				}
			}
			if (($next_stage - 1) * 10 + $next_page > $pages) {
				$next_stage = 1;
				$next_page = 1;
			}

			$html = "";			
			$html = $html . $total_crane_count . "|||";
			$html = $html . $next_stage . "|||";			
			$html = $html . $next_page . "|||";

			$start_index = ($next_stage - 1) * 50 + ($next_page - 1) * 5;
			$count = min(5, $total_crane_count - $start_index);


			for ($j = 0; $j < $count; $j++) {
				$i = $j + $start_index;
				
				$crane_type = 2;
				$crane_id = $tower_crane_list[$i]->CraneID;
				$photo    = $this->application->getPhoto(2, $crane_id);
				$brand    = $tower_crane_list[$i]->Brand;
				$type_num = $tower_crane_list[$i]->Type;
				$size     = $tower_crane_list[$i]->Size;
				$maxlen   = $tower_crane_list[$i]->MaxLen;
				$tonnage  = "";
				$notition = $tower_crane_list[$i]->Notition;		

				$html = $html . "<tr class='cla-tr-$i' name='" . $type_num . "'>";
				$html = $html . "<td style='text-align:center;'><input class='checkbox' type='checkbox' cranetype='" . $crane_type . "' craneid='" . $crane_id ."'/></td>";
				$html = $html . "<td style='text-align:center;'>
									<div class='table-photo-item' style='background-image: url(" . $baseDir . "/" . $photo . ");'>
										<div style='color:#003070;text-align:right;margin:5px;text-shadow: 0px 0px 1px white;'>" . ($i + 1) . "</div>
									</div>
								</td>";
				$html = $html . "<td style='text-align:center;'><div>" . $brand . "</div></td>";
				$html = $html . "<td style='text-align:center;'><div>" . $type_num . "</div></td>";								
				$html = $html . "<td style='text-align:left; padding-left:10px;'><div>" . "1. " . $type_num . "<br/>" . "2. " . $size . "<br/>" . "3. " . $maxlen . "<br/>" . "4. " . $tonnage . "</div></td>";
				$html = $html . "<td style='text-align:center;'><div>" . $notition . "</div></td>";
				$html = $html . "<td style='text-align:center;width:70px;'>
									<button style='width:70px' onclick='javascript:onClickDetails(\"$crane_type\", \"$crane_id\");'>Edit</button>
									<button style='width:70px' onclick='javascript:onClickDelete(\"$crane_type\", \"$crane_id\");'>Remove</button>
								</td>";
				$html = $html . "</tr>";
			}
			for (; $j < 5; $j++) {
				$i = $j + $start_index;
				$html = $html . "<tr class='cla-tr-$i' name='none' style='height:70.5px;'>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "<td style='text-align:center;'></td>";
				$html = $html . "</tr>";
			}
			
			// navigation
			$html = $html . "|||";
			$temp = "";
			$pages = $pages - ($next_stage - 1) * 10;
			$count = min($pages, 10);
			for ($i = 1; $i <= $count; $i++) {
				$text = ($next_stage - 1) * 10 + $i;
				if ($i == $next_page) {
					$temp = $temp . "<li class='cla-page-$i active'><a href='javascript:onSelectPage($i);'>$text</a></li>";
				}
				else {
					$temp = $temp . "<li class='cla-page-$i'><a href='javascript:onSelectPage($i);'>$text</a></li>";
				}
			}
			
			$html = $html . "<ul class='page-navigation'>";
			
			if ($stages > 1) {
				$html = $html . "<li><a href='javascript:onSelectPage(\"prev\");' class='prev' name='prev'></a></li>";
				$html = $html . $temp;
				if ($pages > 10) $html = $html . "<li><a href='javascript:onSelectPage(\"next\");' class='next' name='next'></a></li>";
			}
			else {
				if ($pages > 1) {
					$html = $html . $temp;
				}
			}

			$html = $html . "</ul>";



			echo $html;
		}
	}

	public function get_telecrane($keyvalue)
	{
		$this->load->model('crane');
		$tele_crane_list = $this->crane->get_tele_crane($keyvalue);
		return $tele_crane_list;
	}
	public function get_towercrane($keyvalue)
	{
		$this->load->model('crane');
		$tower_crane_list = $this->crane->get_tower_crane($keyvalue);
		return $tower_crane_list;
	}
	
	public function cranedetail()
	{
		$this->__check_user();
		
		$this->load->model('crane');
		$this->load->model('application');
		$this->load->model('cr_specification');

		global $data;

		$data['administrator'] = $this->get_admin_name();
		$data['current_stage'] = 1;
		$data['current_page'] = 1;
		if (isset($_GET["stage"]) && (isset($_GET["page"]))) {
			$data['current_stage'] = $_GET["stage"];
			$data['current_page'] = $_GET["page"];
		}

		$crane_type = $_GET['crane_type'];
		$crane_id	= $_GET['crane_id'];

		$data['crane_type'] = $crane_type;
		$data['crane_id']	= $crane_id;

		$target_crane = $this->crane->getCrane($crane_type, $crane_id);

		if($target_crane == null)
		{
			return;
		}	

		$data['brand'] = $target_crane->Brand;
		if($crane_type == 1)
			$data['type']  = $target_crane->Type;
		else if($crane_type == 2)
			$data['type']  = $target_crane->Model . " " . $target_crane->Type;
		
		$data['size']  = $target_crane->Size;
		$data['maxlen'] = $target_crane->MaxLen;
		if($crane_type == 1)
			$data['tonnage'] = $target_crane->Tonnage;
		else
			$data['tonnage'] = "";
		$data['notition'] = $target_crane->Notition;

		if($crane_type == 2)
			$data['extra'] = "N";
		else
			$data['extra'] = $target_crane->Extra;

		$photo = $this->application->getPhoto($crane_type, $crane_id);
		$data['photo'] = $photo;

		$email = $this->application->getDonateEmail($crane_type, $crane_id);
		$data['email'] = $email;
		$data['specification'] = $this->cr_specification->get_specification($crane_type, $crane_id);
		$data['manual'] = $this->cr_specification->get_manual($crane_type, $crane_id);
		
		$this->load->view("cranedetail", $data);
	}

	public function get_photos() {
		
		$this->load->model('application');
		
		global $data;
		$crane_type = $_POST['crane_type'];
		$crane_id = $_POST['crane_id'];
		$photos = $this->application->getPhotos($crane_type, $crane_id);
		$ret = json_encode($photos);
		echo $ret;
	}

	public function set_photo_to_crane() {
		$this->load->model('application');
		global $data;
		$photo_id = $_POST['id'];
		$photo = $this->application->setPhotoToCrane($photo_id);
		
		echo $photo;	
	}
	
	public function update_basis_info()
	{
		global $baseDir;

		$this->load->model('crane');

		$crane_type 		= $_POST['crane_type'];
		$crane_id		 	= $_POST['crane_id'];
		$model 				= $_POST['model'];
		$type 				= $_POST['type'];
		$size 				= $_POST['size'];
		$maxlen     		= $_POST['maxlen'];
		$tonnage    		= $_POST['tonnage'];
		$extra      		= $_POST['extra'];
		$notition   		= $_POST['notition'];

		$ret = $this->crane->updateCrane($crane_type, $crane_id, $model, $type, $size, $maxlen, $tonnage, $extra, $notition);

		$value = $model . "|" . $type;
		echo $value;
	}

	public function delete_admin() {
		global $baseDir;
		$this->load->model('application');
		$ad_id = $_POST["ad_id"];

		$ret = $this->application->delete_admin($ad_id);
	}

	public function delete_photo()
	{
		
		$this->load->model('application');

		$crane_type = $_POST['crane_type'];
		$crane_id = $_POST['crane_id'];

		$ret = $this->application->delete_photo($crane_type, $crane_id);

		echo $ret;
	}
	
	public function delete_spec()
	{
		global $baseDir;
		$this->load->model('application');

		$crane_type = $_POST['crane_type'];
		$crane_id = $_POST['crane_id'];

		$ret = $this->application->delete_spec($crane_type, $crane_id);
	}

	public function delete_manual()
	{
		global $baseDir;
		$this->load->model('application');

		$crane_type = $_POST['crane_type'];
		$crane_id = $_POST['crane_id'];

		$ret = $this->application->delete_manual($crane_type, $crane_id);
	}

	public function addcrane()
	{
		$this->__check_user();
		global $data;

		$data['administrator'] = $this->get_admin_name();
		$this->load->view("addcrane", $data);
	}

	public function add_crane()
	{
		$this->load->model('crane');
		$this->load->model('application');

		$crane_type 	= $_POST['crane_type'];
		$marken			= $_POST['marken'];
		$model 			= $_POST['model'];
		$type 			= $_POST['type'];
		$size 			= $_POST['size'];
		$maxlen 		= $_POST['maxlen'];
		$tonnage 		= $_POST['tonnage'];
		$extra 			= $_POST['extra'];
		$notition 		= $_POST['notition'];
		// $spec_url 		= $_POST['spec_url'];
		// $manual_url 	= $_POST['manual_url'];
		$donate_email 	= $_POST['donate_email'];
		$photo_url 		= $_POST['photo_url'];

		$crane_id = $this->crane->addCrane($crane_type, $marken, $model, $type, $size, $maxlen, $tonnage, $extra, $notition);
		
		$ret = $this->application->addPhoto($crane_type, $crane_id, $donate_email, $photo_url);
		echo $ret;
	}

	public function delete_crane()
	{
		$this->load->model('crane');
		$this->load->model('application');

		$crane_type = $_POST['crane_type'];
		$crane_id = $_POST['crane_id'];

		$this->crane->removeCrane($crane_type, $crane_id);
		$this->application->removePhoto($crane_type, $crane_id);
		$this->application->removePdfInfo($crane_type, $crane_id);

		echo true;
	}	
	
	public function delete_selected_member()
	{
		$this->load->model('crane');
		$this->load->model('application');

		$type_index = array();
		$id_index = array();
		$type_index[0] = $_POST['type_index1'];
		$id_index[0] = $_POST['id_index1'];
		$type_index[1] = $_POST['type_index2'];
		$id_index[1] = $_POST['id_index2'];
		$type_index[2] = $_POST['type_index3'];
		$id_index[2] = $_POST['id_index3'];
		$type_index[3] = $_POST['type_index4'];
		$id_index[3] = $_POST['id_index4'];
		$type_index[4] = $_POST['type_index5'];
		$id_index[4] = $_POST['id_index5'];

		for($i=0; $i<5; $i++)
		{
			if($type_index[$i] == "")
				continue;

			$this->crane->removeCrane($type_index[$i], $id_index[$i]);
			$this->application->removePhoto($type_index[$i], $id_index[$i]);
			$this->application->removePdfInfo($type_index[$i], $id_index[$i]);
		}

		echo true;
	}
	
	public function cranetabel()
	{
		$this->__check_user();
		global $data;

		$data['administrator'] = $this->get_admin_name();
		$data['current_page'] = 1;
		if (isset($_GET["page"])) {
			$data['current_page'] = $_GET["page"];
		}
		$this->load->view("cranetabel", $data);
	}
	
	public function get_cranetabel()
	{
		$this->load->model('crane');

		$current_page 	= $_POST['current_page'];
		$keyname 		= $_POST['keyname'];
		echo $keyname;
		$crane_type = 0;
		if($keyname == 'tele_crane')
		{
			$result = $this->crane->get_craneAll(1);
			$crane_type = 1;
		}	
		else
		{
			$result = $this->crane->get_craneAll(2);
			$crane_type = 2;
		}

		$html = "";
		$html = $html . "<thead>";
		$html = $html . "<tr style='text-align:center'>";
		$html = $html . "<th style='text-align:center;width:10px;'>No</th>";
		$html = $html . "<th style='text-align:center;width:70px;'>Merk</th>";
		$html = $html . "<th style='text-align:center;width:90px;'>Type</th>";
		if($crane_type == 1)
			$html = $html . "<th style='text-align:center;width:30px;'>Tonnage</th>";

		$result_header = $this->crane->get_header($crane_type);
		$col_num = min(10, count($result_header));
		$start_index = ($current_page - 1) * 10;
		for($i=0; $i<$col_num; $i++)
		{
			$index = $start_index + $i;
			if($index >= count($result_header))
			{
				$html = $html . "<th style='text-align:center;width:10px;'></th>";	
				continue;
			}
			$html = $html . "<th style='text-align:center;width:10px;'>" . $result_header[$index]->Length . "m </th>";
		}
		for(; $i<10; $i++)
			$html = $html . "<th style='text-align:center;width:10px;'></th>";	
		$html = $html . "</tr>";
		$html = $html . "</thead><tbody>";

		for($i=0; $i<count($result); $i++)
		{
			$brand = $result[$i]->Brand;
			$type = $result[$i]->Type;
			$model = "";
			if($crane_type == 2)
				$model = $result[$i]->Model;

			$tonnage = "";
			if($crane_type == 1)
				$tonnage = $result[$i]->Tonnage;

			$crane_id = $result[$i]->CraneID;

			$html = $html . "<tr style='text-align:center; height:30px;' >";
			$html = $html . "<td style='text-align:center;width:10px; color:rgb(0,0,0);'>" . ($i + 1) . "</td>";
			$html = $html . "<td style='text-align:center;width:70px;color:rgb(0,0,0);'>" . $brand . "</td>";
			if($crane_type == 1)
			{
				$html = $html . "<td style='text-align:center;width:70px;color:rgb(0,0,0);'>" . $type . "</td>";
				$html = $html . "<td style='text-align:center;width:30px;color:rgb(0,0,0);'>" . $tonnage. " ton </td>";
			}	
			else if($crane_type == 2)
				$html = $html . "<td style='text-align:center;width:70px;color:rgb(0,0,0);'>" . $model . " " . $type . "</td>";
			

			$result_tabel = $this->crane->get_cranetabel($crane_type, $crane_id);
			
			
			if(count($result_tabel) > ($current_page - 1) * 10)
				$col_num = min(10, count($result_tabel) - ($current_page - 1) * 10);
			else {
				if ($current_page > 1)
					$col_num = min(10, count($result_tabel) % (($current_page - 1) * 10));
				else
					$col_num = 10;
			}

			$total_num = count($result_tabel);
			$start_index = ($current_page - 1) * 10;
			if ($total_num == 0) {
				$col_num = min(10, count($result_header));
				$start_index = ($current_page - 1) * 10;
				for($j=0; $j<$col_num; $j++)
				{
					$index = $start_index + $j;
					if($index >= count($result_header))
					{
						$html = $html . "<td style='text-align:center;width:30px;'></td>";
						continue;
					}	
					$style = "width:100%; height:100%; text-align:center;";
					$capacity = "";
					$len = $result_header[$index]->Length;
					// $temp = "<input type='text' class='" . ($i + 1) . "' craneID='" . $crane_id . "' style='width:100%; height:100%; text-align:center;' value='" . $result_tabel[$index]->Capacity . "'></input>";
					$temp = "<input type='text' class='" . ($i + 1) . "' craneID='" . $crane_id . "' radius='" . $len . "' style='" . $style . "' value='" . $capacity . "'></input>";
					$html = $html . "<td style='text-align:center; padding:0px;'>" . $temp . "</td>";
				}
				for(; $j<10; $j++)
					$html = $html . "<td style='text-align:center;width:30px;'></td>";	
				$html = $html . "</tr></tbody>";
			}
			else {
				for($j=0; $j<$col_num; $j++)
				{
					$index = $start_index + $j;
					if($index >= count($result_tabel))
					{
						//$html = $html . "<td style='text-align:center;width:30px;'></td>";
						break;
						//continue;
					}	
					$style = "width:100%; height:100%; text-align:center;";
					$capacity = $result_tabel[$index]->Capacity;
					$len = $result_tabel[$index]->Length;
					// $temp = "<input type='text' class='" . ($i + 1) . "' craneID='" . $crane_id . "' style='width:100%; height:100%; text-align:center;' value='" . $result_tabel[$index]->Capacity . "'></input>";
					$temp = "<input type='text' class='" . ($i + 1) . "' craneID='" . $crane_id . "' radius='" . $len . "' style='" . $style . "' value='" . $capacity . "'></input>";
					$html = $html . "<td style='text-align:center; padding:0px;'>" . $temp . "</td>";
				}
				for(; $j<10; $j++) {
					$index = $start_index + $j;
					if($index >= count($result_header))
					{
						$html = $html . "<td style='text-align:center;width:30px;'></td>";
						continue;
					}	
					$style = "width:100%; height:100%; text-align:center;";
					$capacity = "";
					$len = $result_header[$index]->Length;
					// $temp = "<input type='text' class='" . ($i + 1) . "' craneID='" . $crane_id . "' style='width:100%; height:100%; text-align:center;' value='" . $result_tabel[$index]->Capacity . "'></input>";
					$temp = "<input type='text' class='" . ($i + 1) . "' craneID='" . $crane_id . "' radius='" . $len . "' style='" . $style . "' value='" . $capacity . "'></input>";
					$html = $html . "<td style='text-align:center; padding:0px;'>" . $temp . "</td>";
				}
				$html = $html . "</tr></tbody>";
			}
		}

		$html = $html . "|||" . count($result);

		// navigation
		$html = $html . "|||";
		$temp = "";
		$pages = count($result_header) / 10;
		if(count($result_header) % 10 != 0)
			$pages++;

		$count = min($pages, 10);
		for ($i = 1; $i <= $count; $i++) {
			//$text = ($current_page - 1) * 10 + $i;
			if ($i == $current_page) {
				$temp = $temp . "<li class='cla-page-$i active'><a href='javascript:onSelectPage($i);'>$i</a></li>";
			}
			else {
				$temp = $temp . "<li class='cla-page-$i'><a href='javascript:onSelectPage($i);'>$i</a></li>";
			}
		}
		
		$html = $html . "<ul class='page-navigation'>";
		
		$html = $html . $temp;			

		$html = $html . "</ul>";			
		echo $html;
	}

	public function update_cranetabel()
	{
		$this->load->model('crane');

		$temp_array 	= $_POST['load'];
		$current_crane  = $_POST['current_crane'];
		
		$result_array = json_decode($temp_array);

		for($i=0; $i<count($result_array); $i++)
		{
			$sub_array = $result_array[$i];			

			$this->crane->update_cranetabel($sub_array, $current_crane);
		}

		echo true;
	}

	public function download_emaillist1() {
		$this->load->model("application");
		$this->load->model("general");
		$result = $this->application->getEmailList(1);
		$header = array("Email");
		
		$this->general->downloadExcelEmailByType($header, $result, "emaillist1.xls");
	}

	public function download_emaillist2() {
		$this->load->model("application");
		$this->load->model("general");
		$result = $this->application->getEmailList(2);
		$header = array("Email");
		
		$this->general->downloadExcelEmailByType($header, $result, "emaillist2.xls");
	}
	
}
?>