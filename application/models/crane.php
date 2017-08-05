<?php
// 기초모듈들을 정의한다.
class crane extends CI_Model {
	
	
	// constructor
	public function __construct() {
		parent::__construct();
	}
	
	public function get_tele_crane($keyvalue)
	{
		global $MYSQL;

		$strsql = sprintf("SELECT * FROM %s", $MYSQL['tbl_telecrane_tabel']);

		if($keyvalue != "")
			$strsql = $strsql . " WHERE Brand = '$keyvalue'";

		$query = $this->db->query($strsql);
		$result = $query->result();
		return $result;
	}

	public function get_tower_crane($keyvalue)
	{
		global $MYSQL;

		$strsql = sprintf("SELECT * FROM %s", $MYSQL['tbl_towercrane_tabel']);

		if($keyvalue != "")
			$strsql = $strsql . " WHERE Brand = '$keyvalue'";
		
		$query = $this->db->query($strsql);
		$result = $query->result();
		return $result;
	}

	public function getCrane($crane_type, $crane_id)
	{
		global $MYSQL;

		if($crane_type == 1)
		{
			$strsql = sprintf("SELECT * FROM %s WHERE CraneID = $crane_id LIMIT 1", $MYSQL['tbl_telecrane_tabel']);			
		}
		else if($crane_type == 2)
		{
			$strsql = sprintf("SELECT * FROM %s WHERE CraneID = $crane_id LIMIT 1", $MYSQL['tbl_towercrane_tabel']);			
		}
		$query = $this->db->query($strsql);
		$result = $query->result();

		if(count($result) < 1)
			return null;
		return $result[0];
	}

	public function updateCrane($crane_type, $crane_id, $model, $type, $size, $maxlen, $tonnage, $extra, $notition)
	{
		global $MYSQL;

		if($crane_type == 1)
		{
			$strsql = sprintf("UPDATE %s SET `Brand` = '%s', `Type` = '%s', `Tonnage` = '%s', `Size` = '%s', `Extra` = '%s', `MaxLen` = '%s', `Notition` = '%s' WHERE CraneID = %s",
				$MYSQL['tbl_telecrane_tabel'], $model, $type, $tonnage, $size, $extra, $maxlen, $notition, $crane_id);			
		}
		else if($crane_type == 2)
		{
			$strsql = sprintf("UPDATE %s SET `Brand` = '%s', `Type` = '%s', `Size` = '%s', `MaxLen` = '%s', `Notition` = '%s' WHERE CraneID = $crane_id",
				$MYSQL['tbl_towercrane_tabel'], $model, $type, $size, $maxlen, $notition, $crane_id);	
		}

		$this->db->query($strsql);

		return true;
	}

	public function addCrane($crane_type, $marken, $model, $type, $size, $maxlen, $tonnage, $extra, $notition)
	{
		global $MYSQL;

		if($crane_type == 1)
		{
			$strsql = sprintf("INSERT INTO %s (Brand, Type, Tonnage, Size, Extra, MaxLen, Notition) VALUES('%s', '%s', '%s', '%s', '%s', '%s', '%s')", 
				$MYSQL['tbl_telecrane_tabel'], $marken, $type, $tonnage, $size, $extra, $maxlen, $notition);
		}
		else if($crane_type == 2)
		{
			$strsql = sprintf("INSERT INTO %s (Brand, Model, Type, Size, MaxLen, Notition) VALUES('%s', '%s', '%s', '%s', '%s', '%s')", 
				$MYSQL['tbl_towercrane_tabel'], $marken, $model, $type, $size, $maxlen, $notition);
		}

		$this->db->query($strsql);

		return mysql_insert_id();

	}

	public function removeCrane($crane_type, $crane_id)
	{
		global $MYSQL;

		if($crane_type == 1)
		{
			$strsql = sprintf("DELETE FROM %s WHERE CraneID = '%s'", $MYSQL['tbl_telecrane_tabel'], $crane_id);
		}
		else if($crane_type == 2)
		{
			$strsql = sprintf("DELETE FROM %s WHERE CraneID = '%s'", $MYSQL['tbl_towercrane_tabel'], $crane_id);
		}

		$this->db->query($strsql);
		return true;
	}

	public function get_header($crane_type)
	{
		global $MYSQL;

		if($crane_type == 1)
			$strsql = sprintf("SELECT * FROM %s GROUP BY Length", $MYSQL['tbl_telecrane_load']);
		else if($crane_type == 2)
			$strsql = sprintf("SELECT * FROM %s GROUP BY Length", $MYSQL['tbl_towercrane_load']);

		$query = $this->db->query($strsql);
		$result = $query->result();

		return $result;
	}

	public function get_cranetabel($crane_type, $crane_id)
	{
		global $MYSQL;

		if($crane_type == 1)
			$strsql = sprintf("SELECT * FROM %s WHERE CraneID = '%s'", $MYSQL['tbl_telecrane_load'], $crane_id);
		else if($crane_type == 2)
			$strsql = sprintf("SELECT * FROM %s WHERE CraneID = '%s'", $MYSQL['tbl_towercrane_load'], $crane_id);

		$query = $this->db->query($strsql);
		$result = $query->result();
		return $result;
	}

	public function get_craneAll($type)
	{
		global $MYSQL;

		if($type == 1)
			$strsql = sprintf("SELECT * FROM %s ORDER BY Tonnage ASC", $MYSQL['tbl_telecrane_tabel']);
		else if($type == 2)
			$strsql = sprintf("SELECT * FROM %s", $MYSQL['tbl_towercrane_tabel']);

		$query = $this->db->query($strsql);
		$result = $query->result();
		return $result;
	}

	public function update_cranetabel($load_array, $current_crane)
	{
		global $MYSQL;

		if($current_crane == 'Telecrane')
		{
			$strsql = sprintf("SELECT Length AS Len FROM %s", $MYSQL['tbl_telecrane_load']);
			$query = $this->db->query($strsql);
			$len_result = $query->result();

			for($i=0; $i<count($load_array); $i++)
			{
				$sub_array = $load_array[$i];
				$crane_id = $sub_array[0];
				$len = $sub_array[1];
				$capacity = $sub_array[2];
				$strsql = sprintf("SELECT * FROM %s WHERE CraneID = %s AND Length = '%s'", $MYSQL['tbl_telecrane_load'], $crane_id, $len); 
				$result = $this->db->query($strsql)->result();
				if (count($result) == 0)
 					$strsql = sprintf("INSERT INTO %s (CraneID, Length, Capacity) VALUES(%s, '%s', '%s')", $MYSQL['tbl_telecrane_load'], $crane_id, $len, $capacity);
 				else 
 					$strsql = sprintf("UPDATE %s SET Capacity = '%s' WHERE CraneID = %s AND Length = '%s'", $MYSQL['tbl_telecrane_load'], $capacity, $crane_id, $len);
				$this->db->query($strsql);				
			}
		}
		else if($current_crane == 'Towercrane')
		{
			$strsql = sprintf("SELECT Length AS Len FROM %s", $MYSQL['tbl_towercrane_load']);
			$query = $this->db->query($strsql);
			$len_result = $query->result();

			for($i=0; $i<count($load_array); $i++)
			{
				$sub_array = $load_array[$i];
				$crane_id = $sub_array[0];
				$len = $sub_array[1];
				$capacity = $sub_array[2];

				$strsql = sprintf("UPDATE %s SET Capacity = '%s' WHERE CraneID = %s AND Length = '%s'", $MYSQL['tbl_towercrane_load'], $capacity, $crane_id, $len);
				$this->db->query($strsql);				
			}
		}
	}
}
?>
