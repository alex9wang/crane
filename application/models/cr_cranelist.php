<?php

class cr_cranelist extends CI_Model {
	
	var $tbl_telecrane_tabel;
	var $tbl_telecrane_load;
	var $tbl_towercrane_tabel;
	var $tbl_towercrane_load;
    var $tbl_photo;
    var $tbl_specification;
	
    function __construct()
    {
    	global $MYSQL;
		
		$this->tbl_telecrane_tabel = $MYSQL['tbl_telecrane_tabel'];
		$this->tbl_telecrane_load = $MYSQL['tbl_telecrane_load'];
		$this->tbl_towercrane_tabel = $MYSQL['tbl_towercrane_tabel'];
		$this->tbl_towercrane_load = $MYSQL['tbl_towercrane_load']; 
		$this->tbl_photo 			= $MYSQL['tbl_photo'];
        $this->tbl_specification = $MYSQL['tbl_specification'];

        parent::__construct();
    }
	
	function get_crane($cranetype, $length, $weight)
	{		
		global $MYSQL;
		//$nearest_length = $this->get_nearestlength($cranetype, $length);
		
		if($cranetype == 1)
		{
		
			$strsql = sprintf("SELECT * FROM %s tabel JOIN %s weight", $MYSQL['tbl_telecrane_tabel'], $MYSQL['tbl_telecrane_load']);
			$strsql = $strsql . sprintf(" ON tabel.CraneID = weight.CraneID AND (weight.Length + 0) >= %s AND (weight.Capacity + 0) >= %s", $length, $weight);
			$strsql = $strsql . sprintf(" JOIN %s photo ON tabel.CraneID = photo.CraneID AND photo.CraneType = %s", $MYSQL['tbl_photo'], $cranetype);
            $strsql = $strsql . sprintf(" JOIN %s specication ON tabel.CraneID = specication.CraneID AND specication.CraneType = %s", $MYSQL['tbl_specification'], $cranetype);
			$strsql = $strsql . sprintf(" ORDER BY (weight.Length + 0),(weight.Capacity + 0) LIMIT 0, 10");					
		}
		else if($cranetype == 2)
		{
			
			$strsql = sprintf("SELECT * FROM %s tabel JOIN %s weight", $MYSQL['tbl_towercrane_tabel'], $MYSQL['tbl_towercrane_load']);
			$strsql = $strsql . sprintf(" ON tabel.CraneID = weight.CraneID AND (weight.Length + 0) > %s AND (weight.Capacity + 0) >= %s", $length, $weight);
			$strsql = $strsql . sprintf(" JOIN %s photo ON tabel.CraneID = photo.CraneID AND photo.CraneType = %s", $MYSQL['tbl_photo'], $cranetype);
            $strsql = $strsql . sprintf(" JOIN %s specication ON tabel.CraneID = specication.CraneID AND specication.CraneType = %s", $MYSQL['tbl_specification'], $cranetype);
			$strsql = $strsql . sprintf(" ORDER BY (weight.Length + 0),(weight.Capacity + 0) LIMIT 0, 10");			
		}
		
		$result = $this->dbop->execSQL($strsql);
		
		
		// if(count($result)==0)
		// {
		// 	$crane_list = $this->get_crane_one($cranetype, $nearest_length, $weight);
		// 	return $crane_list;
		// }		
		
		return $result;
	}
	
	function get_nearestlength($cranetype, $length)
	{
		if($cranetype == 1)
		   $strsql = sprintf("SELECT min(Length + 0) AS LEN FROM %s WHERE (Length + 0) >= %s", $this->tbl_telecrane_load, $length);
		else
		   $strsql = sprintf("SELECT min(Length + 0) AS LEN FROM %s WHERE (Length + 0) >= %s", $this->tbl_towercrane_load, $length);
		   
		
		$result_len = $this->dbop->execSQL($strsql);
		$len =  $result_len[0]->LEN;   	   
		return $len;
	}
	
	function get_crane_one($cranetype, $nearest_length, $weight)
	{
		if($cranetype == 1)
		{
			$strsql = sprintf("SELECT * FROM %s tabel JOIN %s weight ON tabel.CraneID = weight.CraneID AND (weight.Length + 0 )= $nearest_length AND (weight.Capacity + 0) >= $weight ORDER BY (weight.Capacity + 0) LIMIT 0", $this->tbl_telecrane_tabel, $this->tbl_telecrane_load);			
			
		}
		else if($cranetype == 2)
		{
			$strsql = sprintf("SELECT * FROM %s tabel JOIN %s weight ON tabel.CraneID = weight.CraneID AND (weight.Length + 0 )= $nearest_length AND (weight.Capacity + 0) >= $weight ORDER BY (weight.Capacity + 0) LIMIT 0", $this->tbl_towercrane_tabel, $this->tbl_towercrane_load);	
		}
		
		$result = $this->dbop->execSQL($strsql);
		if(count($result)==0)
			return null;
			
		return $list;
	}
	
	
	function search_crane($crane_type, $crane_brand, $crane_model, $crane_tonnage)
	{
        global $MYSQL;
		if($crane_type == 1)
		{
			if(strcmp($crane_model, "")!=0)
			{
				//$strsql = sprintf("SELECT * FROM %s WHERE Brand='$crane_brand' AND Type='$crane_model' LIMIT 0, 10", $this->tbl_telecrane_tabel);
				$strsql = sprintf("SELECT * FROM %s tabel JOIN %s photo ON tabel.CraneID = photo.CraneID AND photo.CraneType = %s", $this->tbl_telecrane_tabel, $this->tbl_photo, $crane_type);
                $strsql = $strsql . sprintf(" JOIN %s specication ON tabel.CraneID = specication.CraneID AND specication.CraneType = %s", $MYSQL['tbl_specification'], $crane_type);
				$strsql = $strsql . sprintf(" WHERE tabel.Brand = '%s' AND tabel.Type = '%s' LIMIT 0, 10", $crane_brand, $crane_model);
			}
			else
			{
				if(strcmp($crane_tonnage, "")!=0)
				{
					//$strsql = sprintf("SELECT * FROM %s WHERE Brand='$crane_brand' AND (Tonnage + 0) > $crane_tonnage LIMIT 0, 10", $this->tbl_telecrane_tabel);
					$strsql = sprintf("SELECT * FROM %s tabel JOIN %s photo ON tabel.CraneID = photo.CraneID AND photo.CraneType = %s", $this->tbl_telecrane_tabel, $this->tbl_photo, $crane_type);
                    $strsql = $strsql . sprintf(" JOIN %s specication ON tabel.CraneID = specication.CraneID AND specication.CraneType = %s", $MYSQL['tbl_specification'], $crane_type);
					$strsql = $strsql . sprintf(" WHERE tabel.Brand = '%s' AND (tabel.Tonnage + 0) > %s LIMIT 0, 10", $crane_brand, $crane_tonnage);
				}
				else
				{
					//$strsql = sprintf("SELECT * FROM %s WHERE Brand='$crane_brand' LIMIT 0, 10", $this->tbl_telecrane_tabel);
					$strsql = sprintf("SELECT * FROM %s tabel JOIN %s photo ON tabel.CraneID = photo.CraneID AND photo.CraneType = %s", $this->tbl_telecrane_tabel, $this->tbl_photo, $crane_type);
                    $strsql = $strsql . sprintf(" JOIN %s specication ON tabel.CraneID = specication.CraneID AND specication.CraneType = %s", $MYSQL['tbl_specification'], $crane_type);
					$strsql = $strsql . sprintf(" WHERE tabel.Brand = '%s' LIMIT 0, 10", $crane_brand);
				}
			}
		}
		else
		{
			if(strcmp($crane_model, "") != 0)
			{
				//$strsql = sprintf("SELECT * FROM %s WHERE Brand='$crane_brand' AND Model='$crane_model'LIMIT 0, 10", $this->tbl_towercrane_tabel);
				$strsql = sprintf("SELECT * FROM %s tower JOIN %s photo ON tower.CraneID = photo.CraneID AND photo.CraneType = %s", $this->tbl_towercrane_tabel, $this->tbl_photo, $crane_type);
                $strsql = $strsql . sprintf(" JOIN %s specication ON tower.CraneID = specication.CraneID AND specication.CraneType = %s", $MYSQL['tbl_specification'], $crane_type);
				$strsql = $strsql . sprintf(" WHERE tower.Brand = '%s' AND tower.Model = '%s' LIMIT 0, 10", $crane_brand, $crane_model);
			}
			else
			{
				//$strsql = sprintf("SELECT * FROM %s WHERE Brand='$crane_brand' LIMIT 0, 10", $this->tbl_towercrane_tabel);
				$strsql = sprintf("SELECT * FROM %s tower JOIN %s photo ON tower.CraneID = photo.CraneID AND photo.CraneType = %s", $this->tbl_towercrane_tabel, $this->tbl_photo, $crane_type);
                $strsql = $strsql . sprintf(" JOIN %s specication ON tower.CraneID = specication.CraneID AND specication.CraneType = %s", $MYSQL['tbl_specification'], $crane_type);
				$strsql = $strsql . sprintf(" WHERE tower.Brand = '%s' LIMIT 0, 10", $crane_brand);
			}
		}
		
		
		$result = $this->dbop->execSQL($strsql);
		
		
		if(count($result)==0)
			return null;
		else
			return $result;
	}
	
}
?>