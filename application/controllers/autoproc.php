<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include("www/include/constants.php");
include("www/include/global.php");

$data['baseDir'] = $baseDir;

class autoproc extends CI_Controller {
	// 자동프로쎄스 처리부분
	public function process()
	{
		echo "AUTOPROC START:".now();
				
		// 수동푸시 전송부분
		$this->pushmsg_manual_run(5);
		
		echo "AUTOPROC END:".now();
	}
	
	// Get SQL Result
	function get_sql_result($sql)
	{
		$query = $this->db->query($sql);
		return $query->result();
	}
	
	// 
	public function pushmsg_manual_run($minutes)
	{
		global $MYSQL;
		$current_time = date("Y-m-d H:i:s");
		$fivemin_beforetime = date("Y-m-d H:i:s", mktime(date("H"), date("i") - $minutes - 1, date("s"), date("m"), date("d"), date("Y")));
		$sql = sprintf("SELECT * FROM %s WHERE pm_state=0 AND CONCAT(pm_reserve_date, ' ', pm_reserve_time) >='$fivemin_beforetime' AND CONCAT(pm_reserve_date, ' ', pm_reserve_time) <= '$current_time'", $MYSQL['tbl_pushmanual']);
		$result = $this->dbop->execSQL($sql);
		foreach($result as $item)
		{
			$this->send_pushmsg($item->pm_id, $item->pm_type, $item->pm_content);
		}
	}
	
	// 
	function send_pushmsg($id, $type, $content)
	{
		global $MYSQL;
		$this->load->model("push");		
		$sql = sprintf("SELECT * FROM %s ORDER BY mb_id", $MYSQL['tbl_member_basis']);
		$result = $this->dbop->execSQL($sql);
		foreach($result as $item)
		{
			//$this->pushmsg->add("NOTIFY", $item->mem_id, $content, 1);
			$this->push->reg("NOTIFY", $item->mb_id, $content);
		}
		$sql = sprintf("UPDATE %s SET pm_state=1 WHERE pm_id='$id' LIMIT 1", $MYSQL['tbl_pushmanual']);
		$this->dbop->execQuery($sql);
		
		/*
		global $MYSQL;
		$where = "";
		switch($members)
		{
			case 1:
				$where .= " AND mem_state='yes'";
				break;
			case 2:
				$where .= " AND mem_state='matchstop'";
				break;
			case 3:
				$where .= " AND mem_sex='man'";
				break;
			case 4:
				$where .= " AND mem_sex='woman'";
				break;
		}
		$this->load->model("pushmsg");
		$sql = sprintf("SELECT * FROM %s WHERE 1 %s", $MYSQL['tbl_members'], $where);
		$result = $this->get_sql_result($sql);
		foreach($result as $item)
		{
			//$this->pushmsg->add("NOTIFY", $item->mem_id, $content, 1);
			$this->pushmsg->add("NOTIFY", $item->mem_id, $content);
		}
		$sql = sprintf("UPDATE %s SET pmm_state=1 WHERE pmm_id='$id' LIMIT 1", $MYSQL['tbl_pushmsg_manual']);
		$this->db->query($sql);
		echo $content;
		*/
	}
	
}
?>