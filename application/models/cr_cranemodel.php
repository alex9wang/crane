<?php

class cr_cranemodel extends CI_Model {
	
	var $tbl_telecrane_kind;
	var $tbl_towercrane_kind;
	
		
    function __construct()
    {
    	global $MYSQL;
		
		$this->tbl_telecrane_kind  = $MYSQL['tbl_telecrane_kind'];
		$this->tbl_towercrane_kind = $MYSQL['tbl_towercrane_kind'];
		
			
        parent::__construct();
    }
	
	function get_crane_model($crane_type)
	{
		if($crane_type == 1)
			$strsql = sprintf("SELECT * FROM %s WHERE ParentID = 0", $this->tbl_telecrane_kind);
		else if($crane_type == 2)
			$strsql = sprintf("SELECT * FROM %s WHERE ParentID = 0", $this->tbl_towercrane_kind);
			
		$result = $this->dbop->execSQL($strsql);
		
		if(count($result)==0)
			return null;
			
		return $result;
	}	

	function get_crane_serial($crane_type, $crane_id)
	{
		if($crane_type == 1)
			$strsql = sprintf("SELECT * FROM %s WHERE ParentID = $crane_id", $this->tbl_telecrane_kind);
		else
			$strsql = sprintf("SELECT * FROM %s WHERE ParentID = $crane_id", $this->tbl_towercrane_kind);		
		
		$result = $this->dbop->execSQL($strsql);
		
		$crane_serial_list = array();
		for($i=0; $i<count($result); $i++)
		{
			$crane_serial_list[] = $result[$i]->CraneKind;
		}
		
		if(count($crane_serial_list)==0)
			return null;
		
		return $crane_serial_list;
	}
}
?>