<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>Crane Admin Page</title>

<!-- 1140px Grid styles for IE -->
<!--[if lte IE 9]><link rel="stylesheet" href="css/ie.css" type="text/css" media="screen" /><![endif]-->

<!-- The 1140px Grid -->
<link rel="stylesheet" href="<?php echo $baseDir ?>/www/_layout/1140.css" type="text/css" media="screen" />

<link rel="stylesheet" href="<?php echo $baseDir ?>/www/_layout/styles.css" type="text/css" media="screen" />

<!--css3-mediaqueries-js - http://code.google.com/p/css3-mediaqueries-js/ - Enables media queries in some unsupported browsers-->
<script type="text/javascript" src="<?php echo $baseDir ?>/www/_layout/scripts/css3-mediaqueries.js"></script>

	
<!-- Fonts -->
<link href='http://fonts.googleapis.com/css?family=Droid+Sans:regular,bold|PT+Sans+Narrow:regular,bold|Droid+Serif:iamp;v1' rel='stylesheet' type='text/css' />


<!-- Scripts -->
<!--<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js?ver=1.8'></script>-->
<script type='text/javascript' src='<?php echo $baseDir ?>/www/js/jquery-1.8.1.min.js'></script>

<!-- Charts -->
<script type='text/javascript' src='<?php echo $baseDir ?>/www/_layout/scripts/jquery.raphael/raphael-min.js'></script>
<script type='text/javascript' src='<?php echo $baseDir ?>/www/_layout/scripts/jquery.morris/morris.min.js'></script>


<!-- WYSISYG Editor -->
<script type='text/javascript' src='<?php echo $baseDir ?>/www/_layout/scripts/nicEdit/nicEdit.js'></script>


<!-- Forms Elemets -->
<script type='text/javascript' src='<?php echo $baseDir ?>/www/_layout/scripts/jquery.uniform/jquery.uniform.min.js'></script>
<link rel='stylesheet' href='<?php echo $baseDir ?>/www/_layout/scripts/jquery.uniform/uniform.default.css' type='text/css' media='screen' />


<!-- Table sorter -->
<script type='text/javascript' src='<?php echo $baseDir ?>/www/_layout/scripts/jquery.tablesorter/jquery.tablesorter.min.js'></script>
<script type='text/javascript' src='<?php echo $baseDir ?>/www/_layout/scripts/table.resizable/resizable.tables.js'></script>


<script type='text/javascript' src='<?php echo $baseDir ?>/www/_layout/custom.js'></script>
<script type="text/javascript" src='<?php echo $baseDir ?>/www/js/jquery-ui.js'></script>
<link rel="stylesheet" href="<?php echo $baseDir ?>/www/css/jquery-ui.css" />
<script type='text/javascript'>
	function logout() {
		// TODO : clear session info...
		window.location.href = "../index.php";
	}
	function gohome() {
		window.location.href = "<?php echo $baseDir; ?>/admin/main";
	}
	function isnophoto(url) {
		return (url.indexOf('www/images/nophoto.png') >= 0) ? true : false;
	}
	function getphotopath(url) {
		var start = url.indexOf('/www');
		var end = url.indexOf(')') ;
		var mp_url = url.substring(start, end);
		return mp_url;
	}
</script>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"> 