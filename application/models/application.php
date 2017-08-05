<?php

class application extends CI_Model {
	
	
	// constructor
	public function __construct() {
		parent::__construct();
	}
	
	public function get_help($type)
	{
		global $MYSQL;
		$sql = sprintf("SELECT * FROM %s WHERE Language = '%s'", $MYSQL['tbl_help'], $type);
		$query = $this->db->query($sql);
		$result = $query->result();

		if(count($result) == 0)
			return "";
		return $result[0]->Content;
	}
	public function set_help($content, $lang_type)
	{
		global $MYSQL;
		$strsql = sprintf("UPDATE %s SET Content = '%s' WHERE Language = $lang_type", $MYSQL['tbl_help'], $content);
		$this->db->query($strsql);
	}

	public function get_policy($type)
	{
		global $MYSQL;
		
		$strsql = sprintf("SELECT * FROM %s WHERE Language = '$type'", $MYSQL['tbl_policy']);
		
		$query = $this->db->query($strsql);
		$result = $query->result();
		
		return $result[0]->Content;
	}
	
	public function set_policy($content, $lang_type)
	{
		global $MYSQL;
		$strsql = sprintf("UPDATE %s SET Content = '%s' WHERE Language = $lang_type", $MYSQL['tbl_policy'], $content);	

		$query = $this->db->query($strsql);		

	}

	public function get_update_lastindex()
	{
		global $MYSQL;
		$strsql = sprintf("SELECT MAX(UpdateID) AS LASTINDEX FROM %s", $MYSQL['tbl_update']);
		$query = $this->db->query($strsql);
		$result = $query->result();

		return $result[0]->LASTINDEX;
	}

	public function set_update($index, $content, $type)
	{
		global $MYSQL;

		$strsql = sprintf("INSERT INTO %s (`UpdateID`,`Language`, `Content`) VALUES('%s', '%s', '%s')", $MYSQL['tbl_update'], $index, $type, $content);
		$this->db->query($strsql);
	}

	public function get_download_manual($start_date, $end_date)
	{
		global $MYSQL;

		$strsql = sprintf("SELECT * FROM %s WHERE ((1)", $MYSQL['tbl_manual']);


		if($start_date != "")
		{
			$start_date = date("Y-m-d 00:00:00", strtotime($start_date));
			$strsql = $strsql . " AND (Date > '$start_date')";
		}
		if ($end_date != "") {
			$end_date = date("Y-m-d 23:59:59", strtotime($end_date));
			$strsql = $strsql . " AND (Date <= '$end_date')";
		}
		$strsql = $strsql . " AND (Date IS NOT NULL)";
		$strsql = $strsql . ") ORDER BY Date DESC";
		
		$query = $this->db->query($strsql);
		$result = $query->result();

		return $result;
	}

	public function get_donate_photo($start_date, $end_date)
	{
		global $MYSQL;

		$strsql = sprintf("SELECT * FROM %s WHERE ((1)", $MYSQL['tbl_photo']);


		if($start_date != "")
		{
			$start_date = date("Y-m-d 00:00:00", strtotime($start_date));
			$strsql = $strsql . " AND (Date > '$start_date')";
		}
		if ($end_date != "") {
			$end_date = date("Y-m-d 23:59:59", strtotime($end_date));
			$strsql = $strsql . " AND (Date <= '$end_date')";
		}
		$strsql = $strsql . " AND (DonateDate IS NOT NULL)";
		$strsql = $strsql . ") ORDER BY DonateDate DESC";
		
		$query = $this->db->query($strsql);
		$result = $query->result();

		return $result;
	}

	public function getPhoto($crane_type, $crane_id)
	{
		global $MYSQL;

		$strsql = sprintf("SELECT Photo AS Photo FROM %s WHERE CraneType = '$crane_type' AND CraneID = '$crane_id' ORDER BY DonateDate DESC", $MYSQL['tbl_photo']);
		$query = $this->db->query($strsql);
		$result = $query->result();

		if(count($result) < 1)
			return "/www/images/nophoto.png";
		if($result[0]->Photo == "")
			return "/www/images/nophoto.png";
		return $result[0]->Photo;
	}

	public function getPhotos($crane_type, $crane_id) {
		global $MYSQL;

		$strsql = sprintf("SELECT ID AS id, Photo AS image FROM %s WHERE CraneType = '$crane_type' AND CraneID = '$crane_id'", $MYSQL['tbl_photo']);
		$query = $this->db->query($strsql);
		$result = $query->result();

		if(count($result) < 1)
			return array();

		return $result;
	}

	public function setPhotoToCrane($photo_id) {
		global $MYSQL;

		date_default_timezone_set("Europe/Amsterdam");
		$date = date('y/m/d H:i:s');
		$strsql = sprintf("UPDATE %s SET DonateDate = '$date' WHERE ID = '%s'", 
			$MYSQL['tbl_photo'], $photo_id);

		$this->db->query($strsql);

		$strsql = sprintf("SELECT Photo AS Photo FROM %s WHERE ID = '$photo_id' ORDER BY DonateDate DESC", $MYSQL['tbl_photo']);
		$query = $this->db->query($strsql);
		$result = $query->result();

		if(count($result) < 1)
			return "/www/images/nophoto.png";
		if($result[0]->Photo == "")
			return "/www/images/nophoto.png";
		return $result[0]->Photo;
	}

	public function delete_admin($admin_id) {
		global $MYSQL;
		$strsql = sprintf("DELETE FROM %s WHERE ad_id = '%s'", $MYSQL['tbl_administrator'], $admin_id);
		echo $strsql;
		$this->db->query($strsql);

		return true;
	}
	public function delete_photo($crane_type, $crane_id)
	{
		global $MYSQL;

		$strsql = sprintf("UPDATE %s SET Photo = '', Email = NULL, DonateDate = NULL WHERE CraneType = '%s' AND CraneID = '%s'", 
			$MYSQL['tbl_photo'], $crane_type, $crane_id);

		$this->db->query($strsql);

		return "/www/images/nophoto.png";
	}

	public function getDonateEmail($crane_type, $crane_id)
	{
		global $MYSQL;

		$strsql = sprintf("SELECT Email AS Email FROM %s WHERE CraneType = '$crane_type' AND CraneID = '$crane_id'", $MYSQL['tbl_photo']);
		$query = $this->db->query($strsql);
		$result = $query->result();

		if(count($result) < 1)
			return "";
		return $result[0]->Email;
	}

	public function delete_spec($crane_type, $crane_id)
	{
		global $MYSQL;
		$strsql = sprintf("UPDATE %s SET Specification = '' WHERE CraneType = '%s' AND CraneID = '%s'", $MYSQL['tbl_spec'], $crane_type, $crane_id);
		$this->db->query($strsql);		
	}

	public function delete_manual($crane_type, $crane_id)
	{
		global $MYSQL;
		$strsql = sprintf("UPDATE %s SET Manual = '' WHERE CraneType = '%s' AND CraneID = '%s'", $MYSQL['tbl_manual'], $crane_type, $crane_id);
		$this->db->query($strsql);	
	}

	public function addPhoto($crane_type, $crane_id, $donate_email, $photo_url)
	{
		global $MYSQL;
		date_default_timezone_set("Europe/Amsterdam");
		$date = date('Y-m-d h:i:s');
		$strsql = sprintf("INSERT INTO %s (CraneType, CraneID, Photo, Email, DonateDate) VALUES('%s', '%s', '%s', '%s', '%s')", $MYSQL['tbl_photo'], $crane_type, $crane_id, $photo_url, $donate_email, $date);
		$this->db->query($strsql);
		return true;
	}

	public function removePhoto($crane_type, $crane_id)
	{
		global $MYSQL;

		$strsql = sprintf("DELETE FROM %s WHERE CraneType = '%s' AND CraneID = '%s'", $MYSQL['tbl_photo'], $crane_type, $crane_id);
		echo $strsql;
		$this->db->query($strsql);

		return true;
	}

	public function removePdfInfo($crane_type, $crane_id)
	{
		global $MYSQL;

		$strsql = sprintf("DELETE FROM %s WHERE CraneType = '%s' AND CraneID = '%s'", $MYSQL['tbl_manual'], $crane_type, $crane_id);
		$this->db->query($strsql);

		$strsql = sprintf("DELETE FROM %s WHERE CraneType = '%s' AND CraneID = '%s'", $MYSQL['tbl_spec'], $crane_type, $crane_id);
		$this->db->query($strsql);		

		return true;
	}

	public function getEmailList($type) {
		global $MYSQL;

		$strsql = sprintf("SELECT EmailAddr AS Email FROM %s WHERE RegType = '$type'", $MYSQL['tbl_email_register']);
		$query = $this->db->query($strsql);
		$result = $query->result();

		if(count($result) < 1)
			return array();
		return $result;
	}
}
?>
