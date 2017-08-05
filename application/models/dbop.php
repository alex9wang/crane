<?php
// 모든 DB관련조작 진행
class dbop extends CI_Model {
    function __construct()
    {
        parent::__construct();
    }

	function execSQL($sql)
	{
		try
		{
			$query = $this->db->query($sql);
			return $query->result();
		}
		catch (Exception $e)
		{
			tolog('Caught exception: '.$e->getMessage()."\n");
		}
		return null;
	}

	function execQuery($sql)
	{
		try
		{
			$this->db->query($sql);
			return true;
		}
		catch (Exception $e)
		{
			tolog('Caught exception: '.$e->getMessage()."\n");
		}
		return false;
	}
}
?>