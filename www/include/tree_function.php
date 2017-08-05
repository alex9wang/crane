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
	$table_name="aptalime.apt_menus";
	$node_id="menu_id";
	$node_name="menu_name";
	$node_desc="menu_url";
	$node_pid="parent_id";
	$node_sort="menu_order";
	$node_type="visible";
	$node_type_value="yes";
	$nodeId = $_REQUEST['nodeId'];
	$expand = $_REQUEST['expand'];
	$sqlWhere="$node_pid=$pid";
	if($node_type_value!="") $sqlWhere.=" and $node_type='$node_type_value'";
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
		$is=is_sibling($treeRow[$node_pid], $treeRow[$node_sort]);
		
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
	global $table_name, $node_id, $node_pid, $node_type, $node_type_value;
	$table_name="aptalime.apt_menus";
	$node_id="menu_id";
	$node_name="menu_name";
	$node_desc="menu_url";
	$node_pid="parent_id";
	$node_sort="menu_order";
	$node_type="visible";
	$node_type_value="yes";
	$sqlWhere="$node_pid=$id";
	if($node_type_value!="") $sqlWhere.=" and $node_type='$node_type_value'";
	$isLeafSql="select $node_id from $table_name where $sqlWhere";
	$isLeafRes=@mysql_query($isLeafSql);
	$isLeafNum=@mysql_num_rows($isLeafRes);
	if($isLeafNum>0) return 0; else return 1;
}

function is_sibling($pid,$sort)
{
	global $table_name, $node_id, $node_pid, $node_sort, $node_type, $node_type_value;
	$table_name="aptalime.apt_menus";
	$node_id="menu_id";
	$node_name="menu_name";
	$node_desc="menu_url";
	$node_pid="parent_id";
	$node_sort="menu_order";
	$node_type="visible";
	$node_type_value="yes";
	$sqlWhere="$node_pid=$pid and $node_sort>$sort";
	if($node_type_value!="") $sqlWhere.=" and $node_type='$node_type_value'";
	$isSiblSql="select $node_id from $table_name where $sqlWhere";
	$isSiblRes=@mysql_query($isSiblSql);
	$isSiblNum=@mysql_num_rows($isSiblRes);
	if($isSiblNum>0) return 1; else return 0;
}

function is_firstRoot($id)
{
	global $table_name, $node_id, $node_pid, $node_type, $node_type_value;
	$table_name="aptalime.apt_menus";
	$node_id="menu_id";
	$node_name="menu_name";
	$node_desc="menu_url";
	$node_pid="parent_id";
	$node_sort="menu_order";
	$node_type="visible";
	$node_type_value="yes";
	$sqlWhere="$node_pid=0 and $node_id<$id";
	if($node_type_value!="") $sqlWhere.=" and $node_type='$node_type_value'";
	$isfRootSql="select $node_id from $table_name where $sqlWhere";
	$isfRootRes=@mysql_query($isfRootSql);
	$isfRootNum=@mysql_num_rows($isfRootRes);
	if($isfRootNum>0) return 0; else return 1;
}	
?>