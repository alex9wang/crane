<?php

class cr_update extends CI_Model {
	
	var $tbl_update;
	
	
    function __construct()
    {
    	global $MYSQL;
		
		$this->tbl_update  = $MYSQL['tbl_update'];
		
        parent::__construct();
    }
	
	function get_update($lang)
	{
		$strsql = sprintf("SELECT * FROM %s WHERE Language='%s'", $this->tbl_update, $lang);
		$result = $this->dbop->execSQL($strsql);
		
		if(count($result)==0)
			return null;
		else
			return $result;
	}
	
	

}
?>