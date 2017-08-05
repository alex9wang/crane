function tree_insert(id)
{
	tf=document.treeForm;
	tf.nodeId.value=id;
	tf.insertMode.value=1;
	tf.submit();
}
function node_modify(id)
{
	tf=document.treeForm;
	tf.updateMode.value=1;
	tf.nodeId.value=id;
	tf.submit();
}
function ins_ok(id)
{
	tf=document.treeForm;
	tf.insertMode.value=2;
	tf.val1.value=document.getElementById('ins_name').value;
	if(tf.val1.value=="")
	{
		alert('Please input correctly.');
		return;
	}
	tf.val2.value=document.getElementById('ins_desc').value;
	if (document.getElementById('reg_no') != null)
	{
		tf.val5.value=document.getElementById('reg_no').value;
	}
	tf.nodeId.value=id;
	tf.submit();
}
function ins_cancel(id)
{
	tf=document.treeForm;
	tf.nodeId.value=id;

	tf.submit();
}
function node_update(id)
{
	tf=document.treeForm;
	tf.val1.value=document.getElementById('upd_name').value;
	tf.val2.value=document.getElementById('upd_desc').value;
	tf.val3.value=document.getElementById('upd_pid').value;
	tf.val4.value=document.getElementById('upd_order').value;
	if (document.getElementById('reg_no') != null)
	{
		tf.val5.value=document.getElementById('reg_no').value;
	}
	if(isNaN(tf.val4.value) || tf.val4.value<1)
	{
		alert('Please correctly input!');
		return;
	}
	tf.nodeId.value=id;
	tf.updateMode.value=0;
	tf.modifyMode.value=document.getElementById('modifyType').value;
	tf.submit();
}
function upd_cancel(id)
{
	tf=document.treeForm;
	tf.nodeId.value=id;
	tf.updateMode.value=0;
	tf.submit();
}
function orderTextChange()
{
	document.getElementById('modifyType').value=2;
}
function order_up(id)
{
	tf=document.treeForm;	
	tf.nodeId.value=id;	
	tf.modifyMode.value=3;
	tf.submit();
}
function order_down(id)
{
	tf=document.treeForm;	
	tf.nodeId.value=id;
	tf.modifyMode.value=4;
	tf.submit();
}
function node_del(id)
{
	if(!confirm("Do you want to delete really?")) return;
	tf=document.treeForm;	
	tf.nodeId.value=id;	
	tf.deleteMode.value=1;
	tf.submit();
}
function open_win()
{
	window.open("material_sort_tree.php", "sel",'toolbar=no,menubar=no,location=no,status=no,scrollbars=yes,resizable=yes,top=50,left=100,width=400, height=400');
}