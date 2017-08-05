<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include("www/include/constants.php");
include("www/include/global.php");

include("www/include/sms/suremcfg.php");
include("www/include/sms/common.php");

$data['baseDir'] = $baseDir;
/*****************************************************************************************************************************

******************************************************************************************************************************/

class netproc extends CI_Controller {
	public function history($type, $content="", $memid="-1")
	{
		$this->load->model("history");
		$this->history->add($type, $memid, $content);
	}
	
	public function str2sql($string)
	{
		return implode("\'", explode("'", $string));
	}
	
	public function get_cranelist()
	{
		$this->load->model("cr_cranelist");
		
		$cranetype = $this->input->post("CraneType");
		$length    = $this->input->post("Length"); 
		$weight	   = $this->input->post("Weight");				

		$crane_list = $this->cr_cranelist->get_crane($cranetype, $length, $weight);
		if($crane_list == null)
			echo(json_encode(array("Response"=>"Fail")));
		else
			echo json_encode(array("Response"=>"Success", "CraneType"=>$cranetype, "List"=>$crane_list));
	}
	
	public function get_specification()
	{
		$this->load->model("cr_email");
		$this->load->model("cr_specification");
		
		
		$cranetype 	= $this->input->post("CraneType");
		$craneId 	= $this->input->post("CraneID");
		$email 		= $this->input->post("Email");
        $msg    = $this->input->post("message");
		
		$this->cr_email->register($email, 1);   //$reg_type: 1:spec_reg,  2: donate_reg
		
		$spec_url = $this->cr_specification->get_manual($cranetype, $craneId);
		if($spec_url == null)
		{
			echo(json_encode(array("Response"=>"Empty")));
			return;
		}
		$result = $this->cr_email->send_spec_message($email, $spec_url, $msg);
		
		if($result)
		{
			$this->cr_specification->set_manual_download($cranetype, $craneId);
			echo(json_encode(array("Response"=>"Success", "message"=>$msg)));
		}
		else
			echo(json_encode(array("Response"=>"Fail")));	
	}
	
	public function get_crane_model()
	{
		$this->load->model("cr_cranemodel");
		
		$cranetype = $this->input->post("CraneType");
		
		$crane_model_list = $this->cr_cranemodel->get_crane_model($cranetype);
		
		if($crane_model_list == null)
			echo((json_encode(array("Response"=>"Fail"))));
		else
			echo((json_encode(array("Response"=>"Success", "List"=>$crane_model_list))));
	}
	
	public function get_crane_serial()
	{
		$this->load->model("cr_cranemodel");
		
		$cranetype = $this->input->post("CraneType");
		$craneid = $this->input->post("CraneID");
		
		$crane_serial_list = $this->cr_cranemodel->get_crane_serial($cranetype, $craneid);
		
		if($crane_serial_list == null)
			echo((json_encode(array("Response"=>"Fail"))));
		else
			echo((json_encode(array("Response"=>"Success", "List"=>$crane_serial_list))));
	}
	
	public function search_crane()
	{
		$this->load->model("cr_cranelist");
		
		$crane_type = $this->input->post("CraneType");
		$crane_brand = $this->input->post("Brand");
		$crane_model = $this->input->post("Type");
		$crane_tonnage = $this->input->post("Tonnage");
		
		$crane_list = $this->cr_cranelist->search_crane($crane_type, $crane_brand, $crane_model, $crane_tonnage);
		
		if(count($crane_list)==0)
			echo((json_encode(array("Response"=>"Fail"))));
		else
			echo((json_encode(array("Response"=>"Success", "CraneType"=>$crane_type, "List"=>$crane_list))));
	}
	
	public function donate_photo()
	{
		$this->load->model("cr_email");
		
		$crane_email = $this->input->post("Email");
        $msg = $this->input->post("Message");
		
		$this->cr_email->register($email, 2);
		
		$result = $this->cr_email->reply_donate($crane_email, $msg);
		
		if($result)
			echo((json_encode(array("Response"=>"Success"))));
		else
			echo((json_encode(array("Response"=>"Fail"))));
	}
	
	public function get_policy()
	{
		$this->load->model("cr_policy");		
				
		$result = $this->cr_policy->get_policy();
					
		if($result==null)
			echo((json_encode(array("Response"=>"Fail"))));
		else
			echo((json_encode(array("Response"=>"Success", "List"=>$result))));
		
	}
	public function get_help()
	{
		$this->load->model("cr_policy");		
				
		$result = $this->cr_policy->get_help();
					
		if($result==null)
			echo((json_encode(array("Response"=>"Fail"))));
		else
			echo((json_encode(array("Response"=>"Success", "List"=>$result))));
	}

	public function get_update()
	{
		$this->load->model("cr_update");
		
		$dutch_result = $this->cr_update->get_update(1);
		$german_result = $this->cr_update->get_update(2);
		$french_result = $this->cr_update->get_update(3);		
		
		echo((json_encode(array("Response"=>"Success", "Dutch_List"=>$dutch_result, "German_List"=>$german_result, "French_List"=>$french_result))));
	}

	// download image
	public function download_image()
	{
		global $C_FUNC, $C_MSG;

		// 입력
		//$Width = $this->input->get("Width");
		//$Rate = $this->input->get("Quality");
		$Path = $this->input->get("Filename");

		// 이메지다운로드
		$this->load->model("cr_download_image");
		$imgret = $this->cr_download_image->download_img(1, 100, $Path);
	}
}

?>