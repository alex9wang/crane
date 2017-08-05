<?php
// 기초모듈들을 정의한다.
class basis extends CI_Model {
	var $tbl_kongji, $tbl_kongji_apply;
	// constructor
	public function __construct() {
		parent::__construct();
	}

	public function get_admin($keyvalue)
	{
		global $MYSQL;

		$strsql = sprintf("SELECT * FROM %s", $MYSQL['tbl_administrator']);
		
		// print $strsql;

		$query = $this->db->query($strsql);
		$result = $query->result();
		return $result;
	}


	// run query
	public function run_sql($strsql) {
		$query = $this->db->query($strsql);
		return $query->result();
	}
	public function run_sql_no_result($strsql) {
		$query = $this->db->query($strsql);
	}
	// Initialize session.
	public function sess_init($admin = "", $password = "") {
		global $ADMSESS, $MYSQL;
		unset($_SESSION["ADMSESS"]);
		$ADMSESS['admin'] = "";
		$ret = 1;

		if (($admin == "") || ($password == "")) return $ret;
		
		$strsql = sprintf("SELECT * FROM %s WHERE ad_admin = '$admin';", $MYSQL['tbl_administrator']);
		$query = $this->db->query($strsql);
		$result = $query->result();
		
		$count = count($result);
		if ($count < 1) return $ret;

		$ret = 2;
		for ($i = 0; $i < $count; $i = $i + 1) {
			if ($password != $result[$i]->ad_password) {
				continue;
			}
			$ADMSESS['admin'] = $admin;
			$ret = 3;
			$_SESSION["ADMSESS"] = $ADMSESS;

			break;
		}
		date_default_timezone_set("Europe/Amsterdam");
		$date = date('y/m/d H:i:s');
		$sql = sprintf("UPDATE %s SET ad_last_login='$date' WHERE ad_admin = '$admin' AND ad_password = '$password';", $MYSQL['tbl_administrator']);
		$this->db->query($sql);
		return $ret;
	}
	public function add_administrator($new_id, $new_password)
	{
		global $MYSQL;
		$strsql = sprintf("INSERT INTO %s (`ad_admin`,`ad_password`) VALUES('%s', '%s')", $MYSQL['tbl_administrator'], $new_id, $new_password);
		$this->db->query($strsql);
	}
	// change administrator info
	public function change_administrator_info($current_admin, $old_pwd, $new_password)
	{
		global $MYSQL;
		
		$strsql = sprintf("SELECT * FROM %s WHERE ad_admin='%s' AND ad_password='%s'", $MYSQL['tbl_administrator'], $current_admin, $old_pwd );
		
		$query = $this->db->query($strsql);
		$result = $query->result();
		$count = count($result);
		
		if($count < 1)  return "1";     // old password is not correct
		
		$strsql = sprintf("UPDATE %s SET ad_password='%s' WHERE ad_admin = '%s' AND ad_password='%s'", $MYSQL['tbl_administrator'], $new_password, $current_admin, $old_pwd);
		
		$this->db->query($strsql);
		return "2";					  // change password is successful.
	}
}
?>
