<?php

class cr_specification extends CI_Model {
	
	var $tbl_manual;
		
    function __construct()
    {
    	global $MYSQL;
		
		$this->tbl_manual  = $MYSQL['tbl_manual'];
		$this->tbl_specification = $MYSQL['tbl_specification'];
		
        parent::__construct();
    }
	
	function set_manual_download($crane_type, $crane_id)
	{
		$download_time = date('Y-m-d H:i:s');
		$strsql = sprintf("UPDATE %s SET Date='$download_time' WHERE CraneType=$crane_type AND CraneID=$crane_id AND Manual IS NOT NULL", $this->tbl_manual);
		
		$this->dbop->execQuery($strsql);
	}
	
	function get_manual($crane_type, $crane_id)
	{
		$strsql = sprintf("SELECT Manual AS Manual FROM %s WHERE CraneType=$crane_type AND CraneID=$crane_id LIMIT 1", $this->tbl_manual);
		
		$result = $this->dbop->execSQL($strsql);
		if(count($result)==0)
			return null;
		if($result[0]->Manual == "")
			return null;
		return $result[0]->Manual;
	}
	
	function get_specification($crane_type, $crane_id) {
		$strsql = sprintf("SELECT Specification AS Specification FROM %s WHERE CraneType=$crane_type AND CraneID=$crane_id LIMIT 1", $this->tbl_specification);
		
		$result = $this->dbop->execSQL($strsql);
		if(count($result)==0)
			return null;
		if($result[0]->Specification == "")
			return null;
		return $result[0]->Specification;
	}

}
?>