<?php

class cr_policy extends CI_Model {
	
	var $tbl_policy;
	var $tbl_help;
	
	
    function __construct()
    {
    	global $MYSQL;
		
		$this->tbl_policy  = $MYSQL['tbl_policy'];
		$this->tbl_help = $MYSQL['tbl_help'];
		
        parent::__construct();
    }
	
	function get_policy()
	{
		$strsql = sprintf("SELECT * FROM %s", $this->tbl_policy);
		
		$result = $this->dbop->execSQL($strsql);

		if(count($result)==0)
			return null;
		else
			return $result;
	}
	
	function get_help()
	{
		$strsql = sprintf("SELECT * FROM %s", $this->tbl_help);
		
		$result = $this->dbop->execSQL($strsql);		
		
		if(count($result)==0)
			return null;
		else
			return $result;
	}

}
?>