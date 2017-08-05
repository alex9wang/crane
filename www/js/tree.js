function toggleNodeClick(id, did)
{
	tn=did+id;
	ec_img="img"+id;
	img_url=document.getElementById(ec_img).src;
	img_type=img_url.substring(img_url.indexOf('.gif')-1,img_url.indexOf('.gif'));
	tf=document.treeForm;
	str_exp="$"+id.toString()+"#";
	if(document.getElementById(tn).style.display=='none')
	{
		document.getElementById(tn).style.display='block';
		document.getElementById(ec_img).src='/sadena/www/images/tree/collapse'+img_type+'.gif';
		tf.expand.value+=str_exp;
	}
	else
	{
		document.getElementById(tn).style.display='none';
		document.getElementById(ec_img).src='/sadena/www/images/tree/expand'+img_type+'.gif';
		tf.expand.value=tf.expand.value.substring(0, tf.expand.value.indexOf(str_exp))+tf.expand.value.substring(tf.expand.value.indexOf(str_exp)+str_exp.length);
	}
}

function node_click(id, pid, did, obj)
{
	tf=document.treeForm;
	tf.nodeId.value=id+"-"+pid;
	tf.submit();
}