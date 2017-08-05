<?php
	header('Content-Type: text/html; charset=utf-8'); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<meta http-equiv="Content-Type" content="text/html; charset=utf8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8">
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
<meta http-equiv="imagetoolbar" content="no"/>
<meta name="apple-mobile-web-app-capable" content="yes"/>

<script language="javascript" src="<?=$baseDir?>/www/js/jquery.js"></script>
<script language="javascript" src="<?=$baseDir?>/www/js/bootstrap.min.js"></script>
<script language="javascript" src="<?=$baseDir?>/www/js/global.js"></script>
<script src="<?=$baseDir?>/www/js/custom.js"></script>
<script src="<?=$baseDir?>/www/js/jquery.jgrowl_minimized.js"></script>
<!--<script type="text/javascript" src="<?php echo $baseDir; ?>/www/js/jquery.min.js"></script>-->
<script type="text/javascript" src="<?=$baseDir?>/www/js/scripts.js"></script>
<script src="<?=$baseDir?>/www/js/less-1.3.3.min.js"></script>

<link href="<?=$baseDir?>/www/css/bootstrap.css" media="screen" rel="stylesheet" type="text/css">
<link href="<?=$baseDir?>/www/css/global.css" media="screen" rel="stylesheet" type="text/css">
<link type="text/css" href="<?=$baseDir?>/www/css/style.css" rel="stylesheet" />
<link type="text/css" href="<?=$baseDir?>/www/css/jquery.jgrowl.css" rel="stylesheet" />
<link href="<?=$baseDir?>/www/css/bootstrap.min.css" rel="stylesheet">
<link href="<?=$baseDir?>/www/css/bootstrap-responsive.min.css" rel="stylesheet">
<link href="<?=$baseDir?>/www/css/style.css" rel="stylesheet">

<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?=$baseDir?>/www/images/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?=$baseDir?>/www/images/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?=$baseDir?>/www/images/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="<?=$baseDir?>/www/images/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon" href="<?=$baseDir?>/www/images/favicon.png">

<STYLE>
	.frame_box {background: white;bottom: 0px;left: 260px;overflow-y: auto;position: absolute;right: 0px;top: 75px;width: auto;}	
</STYLE>

<html>
<head>
<title>Dashboard Crane App</title>
</head>
<body>
<div class="container-fluid" style="padding-top:100px;">
	<div class="row-fluid" align='center'>
		<div class="span3">
		</div>
		<div class="span6">
		<h2>Administration JO-2 (Crane)</h2>
		<br/><br/>
			<form class="form-horizontal">
				<h5>Vul uw gebruikersnaam en wachtwoord in</h5>
				<div style="border:2px solid #CCCCCC; width:420px; border-radius:5px;">
					<div class="control-group" style="width:400px; margin-top:30px;">
						<label class="control-label" for="admin_id" style="width:100px;">Gebruikersnaam</label>
						<div class="controls" style="margin-left:5px;">
							<input id="admin_id" type="text" value="" placeholder="Vul hier uw gebruikersnaam in......">
						</div>
					</div>
					<div class="control-group" style="width:400px;">
						 <label class="control-label" for="admin_pwd" style="width:100px;">Wachtwoord</label>
						<div class="controls" style="margin-left:5px;">
							<input id="admin_pwd" type="password" value="" placeholder="Vul hier uw wachtwoord in.......">
						</div>
					</div>
					<div class="control-group" style="width:400px;">
						<label class="control-label" for="admin_id" style="width:100px;">&nbsp;</label>
						<div class="controls" style="margin-left:5px;">
							<button id="login" type="button" class="btn" style="width:220px;">OK</button>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="span3">
		</div>
	</div>
</div>
<?php
	include("www/include/footer.inc");
?>
<script language="javascript">
	var height_rate = ($(window).height() - 190) / $(window).height() * 100;
	$(".container-fluid").height(height_rate+"%");
	$(function() {
		$("INPUT").keydown(function(e){
			if (e.keyCode != 13) return;

			for(i=0; i< $("INPUT").length; i++){
				if($("INPUT").eq(i).attr("value") == ''){
					$("INPUT").eq(i).focus();
					return false;
				}
			}
			
			$.post("<?php echo $baseDir; ?>/admin/admin_check", {user_id:$("INPUT").eq(0).attr("value"), user_pwd:$("INPUT").eq(1).attr("value")}, function(data) {
				if (data == "1" || data == "2") {
					if (data == "1") {
						alert("Uw gebruikersnaam en wachtwoord zijn verkeerd probeer het nog een keer");
					}
					if (data == "2") {
						alert("Het wachtwoord is verkeerd probeer het nog eens");
					}
					$("INPUT").attr("value", "");
					$("INPUT").eq(0).focus();
				}
				else if (data == 3) {
					window.location.href = "<?php echo $baseDir; ?>/admin/main";
				}
			});
			
		});
		$("#login").click(function(e) {
			$.post("<?php echo $baseDir; ?>/admin/admin_check", {user_id:$("INPUT").eq(0).attr("value"), user_pwd:$("INPUT").eq(1).attr("value")}, function(data) {
				if (data == "1" || data == "2") {
					if (data == "1") {
						alert("Uw gebruikersnaam en wachtwoord zijn verkeerd probeer het nog een keer");
					}
					if (data == "2") {
						alert("Het wachtwoord is verkeerd probeer het nog eens");
					}
					$("INPUT").attr("value", "");
					$("INPUT").eq(0).focus();
				}
				else if (data == "3") { // Login Success, Go to main page
					window.location.href = "<?php echo $baseDir; ?>/admin/main";
				}
			});
		});
		$("INPUT").eq(0).focus();
	})
</script>
</body>
</html>
