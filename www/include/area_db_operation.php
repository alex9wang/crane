<?php error_reporting (E_ALL ^ E_NOTICE); ?>
<?php	
	/********************************************************************
	 *
	 *	filename	: tree_db_operation.php
	 *	desc		: Operation for tree control, view, modify, insert, delete
	 * 	writer		: zhongge han
	 * 	date		: 2010-02-11
	 *
	 ********************************************************************/
	$nodeId = $_REQUEST['nodeId'];

	$insertMode = $_REQUEST['insertMode'];
	$modifyMode = $_REQUEST['modifyMode'];
	$deleteMode = $_REQUEST['deleteMode'];

	$val1 = $_REQUEST['val1'];
	$val2 = $_REQUEST['val2'];
	$val3 = $_REQUEST['val3'];
	$val4 = $_REQUEST['val4'];

	$arr_param=explode("-",$nodeId);
	$id_param=$arr_param[0];
	$pid_param=$arr_param[1];
	if($insertMode==2)
	{
		$sqlWhere = $id_param == "" ? "$node_pid=$node_id" : "$node_pid='$id_param'";
		$orderMaxSql="select max(`$node_sort`) from $table_name where $sqlWhere";
		$orderMaxRes=@mysql_query($orderMaxSql);
		$orderMaxRow=@mysql_fetch_array($orderMaxRes);
		$orderMax=$orderMaxRow[0]+1;
		if($id_param=="") $id_param=0;
		$insSql="insert into $table_name (`$node_name`, `$node_desc`, `$node_pid`, `$node_sort`) values ('$val1', '$val2', '$id_param', '$orderMax')";
		@mysql_query($insSql);
		$last_id = @mysql_insert_id();
		if ($id_param == 0)
		{
			@mysql_query("update $table_name set $node_pid='$last_id' where $node_id=$last_id limit 1");
		}
	}
	if($modifyMode==1)
	{
		$modifySql="update $table_name set `$node_name`='$val1', `$node_desc`='$val2' where `$node_id`=$id_param";

		@mysql_query($modifySql);
	}
	if($modifyMode==2)
	{		
		if($val3==$pid_param)
		{			
			$getSortSql="select `$node_sort` from $table_name where `$node_id`=$id_param";
			$getSortRes=@mysql_query($getSortSql);
			$getSortRow=@mysql_fetch_array($getSortRes);
			$old_sort=$getSortRow[0];
			
			$orderMaxSql="select max(`$node_sort`) from $table_name where $node_pid='$pid_param'";
			$orderMaxRes=@mysql_query($orderMaxSql);
			$orderMaxRow=@mysql_fetch_array($orderMaxRes);
			$orderMax=$orderMaxRow[0];
			if($val4>$orderMax) $val4=$orderMax;
			
			if($old_sort<$val4)
				$orderIncSql="update $table_name set $node_sort=$node_sort-1 where $node_pid=$val3 and $node_sort>$old_sort and $node_sort<=$val4";
			else
				$orderIncSql="update $table_name set $node_sort=$node_sort+1 where $node_pid=$val3 and $node_sort<$old_sort and $node_sort>=$val4";			
			@mysql_query($orderIncSql);
			
			$modifySql="update $table_name set `$node_name`='$val1', `$node_desc`='$val2', `$node_pid`='$val3', `$node_sort`='$val4' where `$node_id`=$id_param";
			@mysql_query($modifySql);			
		}
		else
		{
			$orderIncSql="update $table_name set $node_sort=$node_sort+1 where $node_pid=$val3 and $node_sort>=$val4";
			@mysql_query($orderIncSql);
			
			$getSortSql="select `$node_sort` from $table_name where `$node_id`=$id_param";
			$getSortRes=@mysql_query($getSortSql);
			$getSortRow=@mysql_fetch_array($getSortRes);
			$old_sort=$getSortRow[0];
			$orderDecSql="update $table_name set $node_sort=$node_sort-1 where $node_pid=$pid_param and $node_sort>$old_sort";
			@mysql_query($orderDecSql);
			
			$orderMaxSql="select max(`$node_sort`) from $table_name where $node_pid='$val3'";
			$orderMaxRes=@mysql_query($orderMaxSql);
			$orderMaxRow=@mysql_fetch_array($orderMaxRes);
			$orderMax=$orderMaxRow[0];
			if($val4>$orderMax+1) $val4=$orderMax+1;

			$modifySql="update $table_name set `$node_name`='$val1', `$node_desc`='$val2', `$node_pid`='$val3', `$node_sort`='$val4' where `$node_id`=$id_param";
			@mysql_query($modifySql);
		}
	}
	if($modifyMode>=3)
	{
		$sqlWhere = $id_param == $pid_param ? "$node_pid=$node_id" : "$node_pid='$pid_param'";
		$orderMaxSql="select max(`$node_sort`) from $table_name where $sqlWhere";
		$orderMaxRes=@mysql_query($orderMaxSql);
		$orderMaxRow=@mysql_fetch_array($orderMaxRes);
		$orderMax=$orderMaxRow[0];
		$orderMinSql="select min(`$node_sort`) from $table_name where $sqlWhere";
		$orderMinRes=@mysql_query($orderMinSql);
		$orderMinRow=@mysql_fetch_array($orderMinRes);
		$orderMin=$orderMinRow[0];		
		$getSortSql="select `$node_sort` from $table_name where `$node_id`=$id_param";
		$getSortRes=@mysql_query($getSortSql);
		$getSortRow=@mysql_fetch_array($getSortRes);
		$old_sort=$getSortRow[0];

		if($old_sort>$orderMin && $modifyMode==3)
		{			
			$nodeDownPushSql="update $table_name set $node_sort=$node_sort+1 where $sqlWhere and $node_sort=$old_sort-1";
			@mysql_query($nodeDownPushSql);
			$nodeUpPushSql="update $table_name set $node_sort=$node_sort-1 where $node_id=$id_param";
			@mysql_query($nodeUpPushSql);
		}
		if($old_sort<$orderMax && $modifyMode==4)
		{			
			$nodeUpPushSql="update $table_name set $node_sort=$node_sort-1 where $sqlWhere and $node_sort=$old_sort+1";
			@mysql_query($nodeUpPushSql);
			$nodeDownPushSql="update $table_name set $node_sort=$node_sort+1 where $node_id=$id_param";
			@mysql_query($nodeDownPushSql);
		}
	}
	if($deleteMode==1)
	{
		$deleteSql="delete from $table_name where $node_id=$id_param";
		@mysql_query($deleteSql);
		$nodeId="";
		$id_param="";
		$pid_param="";
	}
	
	if($node_regno == "")
		$node_regno = $node_name;
	$viewSql="select $node_name, $node_desc, $node_sort from $table_name where $node_id='$id_param'";
	$viewRes=@mysql_query($viewSql);
	$viewRow=@mysql_fetch_array($viewRes);
	$nd_name=$viewRow[0]; $nd_desc=$viewRow[1]; $nd_order=$viewRow[2]; $nd_regno=$viewRow[3];
	$viewSql1="select $node_name from $table_name where $node_id='$pid_param'";
	$viewRes1=@mysql_query($viewSql1);
	$viewRow1=@mysql_fetch_array($viewRes1);
	$nd_pname=($viewRow1[0]=="" && $id_param!="")?"nothing":$viewRow1[0];
?>