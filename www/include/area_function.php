<?php	
	/********************************************************************
	 *
	 *	filename	: tree_function.php
	 *	desc		: make tree control
	 * 	writer		: zhongge han
	 * 	date		: 2010-02-09
	 *
	 ********************************************************************/
?>
<?php error_reporting (E_ALL ^ E_NOTICE); ?>
<?php
function add_node($did="treeNode", $pid=0, $state="", $depth=1)
{
	global $baseDir;
	$table_name="aptalime.apt_area";
	$node_id="ar_id";
	$node_name="ar_name";
	$node_desc="ar_code";
	$node_pid="ar_parent";
	$node_sort="ar_order";
	$nodeId = $_REQUEST['nodeId'];
	$expand = $_REQUEST['expand'];
	$sqlWhere= $pid == 0 ? "$node_pid=$node_id" : "$node_pid=$pid and $node_pid<>$node_id";
	$treeSql="select $node_id, $node_name, $node_pid, $node_sort, $node_desc from $table_name where $sqlWhere order by $node_sort";
	$treeRes=@mysql_query($treeSql);	
	$is_exp=strpos($expand,"$".$pid."#");
	$div_display=($is_exp===false)?" style='display:none'":" style='display:block'";		
	if($depth==1)	$div_display=" style='display:block'";	
	$depth++;
	echo "<div id='$did"."$pid'$div_display>";	
	while($treeRow=@mysql_fetch_array($treeRes))
	{
		$il=is_leaf($treeRow[$node_id]);
		$is=is_sibling($treeRow[$node_pid], $treeRow[$node_sort], $treeRow[$node_id] == $treeRow[$node_pid]);
		
		echo "<table cellpadding='0' cellspacing='0' border=0><tr>";		
		for($i=0;$i<strlen($state);$i++)
		{
			$st=substr($state,$i,1);
			if($st==1)
				echo "<td><img src='$baseDir/www/images/tree/line0.gif'></td>";
			else
				echo "<td><div style='width:19px'></div>";
		}
		if($il==1)
		{
			if($is==1)
			{
				echo "<td><img src='$baseDir/www/images/tree/line2.gif'></td>";
			}
			else
			{
				echo "<td><img src='$baseDir/www/images/tree/line1.gif'></td>";
			}
			echo "<td><img src='$baseDir/www/images/tree/node2.gif'></td>";
		}
		else
		{
			$is_exp1=strpos($expand,"$".$treeRow[$node_id]."#");
			$img_name=($is_exp1===false)?"expand":"collapse";
			echo "<td><a href=\"javascript:toggleNodeClick($treeRow[$node_id],'$did')\">";
			if($is==1)
			{
				if($depth==2)
				{
					if(is_firstRoot($treeRow[$node_id]))
						echo "<img src='$baseDir/www/images/tree/".$img_name."3.gif' id='img$treeRow[$node_id]' class='tree_img'>";
					else
						echo "<img src='$baseDir/www/images/tree/".$img_name."1.gif' id='img$treeRow[$node_id]' class='tree_img'>";
				}
				else 
					echo "<img src='$baseDir/www/images/tree/".$img_name."1.gif' id='img$treeRow[$node_id]' class='tree_img'>";
			}
			else
			{
				echo "<img src='$baseDir/www/images/tree/".$img_name."2.gif' id='img$treeRow[$node_id]' class='tree_img'>";
			}
			echo "</a></td>";
			
			if($depth==2)
				echo "<td><img src='$baseDir/www/images/tree/node0.gif'></td>";
			else
				echo "<td><img src='$baseDir/www/images/tree/node1.gif'></td>";
		}
		$arr_param=explode("-",$nodeId);
		$id_param=$arr_param[0];		
		$selbgStyle=($id_param==$treeRow[$node_id])?" bgcolor='#DDDDDD'":"";
		echo "<td class='tree_child'$selbgStyle><a href='#' class='tree_anchor' onclick=\"node_click($treeRow[$node_id], $treeRow[$node_pid], '$did', this)\">".$treeRow[$node_name]."</a></td>";
		echo "</tr></table>";
		add_node($did, $treeRow[$node_id],$state.$is, $depth);
	}
	echo "</div>";
}

function is_leaf($id)
{
	global $table_name, $node_id, $node_pid;
	$table_name="aptalime.apt_area";
	$node_id="ar_id";
	$node_name="ar_name";
	$node_desc="ar_code";
	$node_pid="ar_parent";
	$node_sort="ar_order";
	$sqlWhere="$node_pid=$id and $node_id<>$node_pid";
	$isLeafSql="select $node_id from $table_name where $sqlWhere";
	$isLeafRes=@mysql_query($isLeafSql);
	$isLeafNum=@mysql_num_rows($isLeafRes);
	if($isLeafNum>0) return 0; else return 1;
}

function is_sibling($pid,$sort, $isroot)
{
	global $table_name, $node_id, $node_pid, $node_sort;
	$table_name="aptalime.apt_area";
	$node_id="ar_id";
	$node_name="ar_name";
	$node_desc="ar_code";
	$node_pid="ar_parent";
	$node_sort="ar_order";
	
	$sqlWhere=$isroot == 1 ? "$node_pid = $node_id and $node_sort>$sort" : "$node_pid=$pid and $node_sort>$sort";
	$isSiblSql="select $node_id from $table_name where $sqlWhere";
	$isSiblRes=@mysql_query($isSiblSql);
	$isSiblNum=@mysql_num_rows($isSiblRes);
	if($isSiblNum>0) return 1; else return 0;
}

function is_firstRoot($id)
{
	global $table_name, $node_id, $node_pid;
	$table_name="aptalime.apt_area";
	$node_id="ar_id";
	$node_name="ar_name";
	$node_desc="ar_code";
	$node_pid="ar_parent";
	$node_sort="ar_order";
	$sqlWhere="$node_id<>$node_pid and $node_id<$id";
	$isfRootSql="select $node_id from $table_name where $sqlWhere";
	$isfRootRes=@mysql_query($isfRootSql);
	$isfRootNum=@mysql_num_rows($isfRootRes);
	if($isfRootNum>0) return 0; else return 1;
}	
?>