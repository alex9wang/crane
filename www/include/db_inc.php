<?php error_reporting (E_ALL ^ E_NOTICE); ?>
<?php
	$link=mysql_connect("192.168.1.105","root","")or die("connection failed.");
	
	function sql_get_value($sql)
	{
		$result=@mysql_query($sql);
		$row=@mysql_fetch_array($result);
		return $row[0];
	}
	function sql_get_row($sql)
	{
		$result=@mysql_query($sql);
		$row=@mysql_fetch_array($result);
		return $row;
	}
	function sql_get_result($sql)
	{
		$result=@mysql_query($sql);
		return $result;
	}
	function sql_execute($sql)
	{
		@mysql_query($sql);
	}
?>
