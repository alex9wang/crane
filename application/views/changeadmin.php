<?php
	include("www/include/header.inc");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
    "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"> 
<html dir="ltr" lang="en-US" xmlns="http://www.w3.org/1999/xhtml">

<head>
	<link rel="shortcut icon" href="<?php echo $baseDir; ?>/www/images/icon.png">
	<script type='text/javascript'>
		$(document).ready(function() {
		});
		function changeAdmin() {
			var oldpwd = $('#input_oldpwd').eq(0).attr('value');
			var password = $('#input_password').eq(0).attr('value');
			var confirm = $('#input_confirm').eq(0).attr('value');

			if (oldpwd == "") {
				alert('Voer hier het oude wachtwoord in');
				return;
			}
			if (password == "") {
				alert('Voer het nieuwe wachtwoord in');
				return;
			}
			if (password != confirm) {
				alert('Voer nogmaals het nieuwe wachtwoord in');
				return;
			}
			$('.preview').css("display", "block");
			$('.preview').spin();
			$.post("<?php echo $baseDir; ?>/admin/change_administrator", {old_pwd : oldpwd, new_password : password}, function(data) {
				$('.preview').spin(false);
				$('.preview').css("display", "none");

			    if(data == "1")
				    alert("Oude wachtwoord niet correct, voer deze nogmaals in");
				else if(data == "2")
				{
					alert('Admin account succesvol aangepast');
					window.location.href = "../index.php";
				}
				
			});
		}
	</script>
	<script type="text/javascript" src="<?php echo $baseDir; ?>/www/js/spin.js"></script>
</head>

<body>
	 
	<div id="header-wrapper" class="container">
		<div id="user-account" class="row" >
			<div class="threecol"> <span>Dashboard</span> </div>
			<div class="ninecol last">
			<?php
				echo "<a href='javascript:logout();'>Uitloggen</a><span>|</span><span>Welkom, <strong>$administrator</strong></span>";
			?>
			</div>
		</div>

		<div id="user-options" class="row">
			<div class="logo" style="background-image: url('<?=$baseDir?>/www/_layout/images/back-logo.png');"></div>
		</div>
	</div>
	
	<div class="container">
		<div class="row">
		
			<div id="sidebar" class="threecol">
				<ul id="navigation">
					<li class="first"><a href="<?php echo $baseDir; ?>/admin/main">Home<span class="icon-dashboard"></span> </a></li> 
					<!--li class="sub" style = "border-top: 1px solid rgb(204, 204, 204);"-->
					<li class="sub active">
						<a href="#l">Basis management<img src="<?=$baseDir?>/www/_layout/images/back-nav-sub-pin.png" alt="" /> <span class="icon-grid"></span></a>
						<ul>
							<li class="current"><a href="<?php echo $baseDir; ?>/admin/changeadmin">Verander admin wachtwoord</a></li>
							<li><a href="<?php echo $baseDir; ?>/admin/addadmin">Voeg administrator toe</a></li>
							<li><a href="<?php echo $baseDir; ?>/admin/adminlist">Overzicht administrators</a></li>
						</ul>
					</li> 
					<li class="sub">
						<a href="#l">Applicatie management<img src="<?=$baseDir?>/www/_layout/images/back-nav-sub-pin.png" alt="" /> <span class="icon-info"></span></a>
						<ul>
							<li><a href="<?php echo $baseDir; ?>/admin/userpolicy">Gebruikersvoorwaarden</a></li>
							<li><a href="<?php echo $baseDir; ?>/admin/update">Update info aanpassen</a></li>
							<li><a href="<?php echo $baseDir; ?>/admin/help">Help informatie</a></li>
						</ul>
					</li> 
					<li class="sub">
						<a href="#l">Totaaloverzicht Applicatie<img src="<?=$baseDir?>/www/_layout/images/back-nav-sub-pin.png" alt="" /> <span class="icon-ques"></span></a>
						<ul>
							<li><a href="<?php echo $baseDir; ?>/admin/download_manual">Laatst gedownloade handleiding</a></li>
							<li><a href="<?php echo $baseDir; ?>/admin/donate_photo">Laatst gedoneerde foto</a></li>				
						</ul>
					</li> 
					<li class="sub">
						<a href="#l">Kraan management<img src="<?=$baseDir?>/www/_layout/images/back-nav-sub-pin.png" alt="" /> <span class="icon-calculator"></span></a>
						<ul>
							<li><a href="<?php echo $baseDir; ?>/admin/crane">Beschikbare kranen</a></li>
							<li><a href="<?php echo $baseDir; ?>/admin/addcrane">Voeg kraan toe</a></li>
							<li><a href="<?php echo $baseDir; ?>/admin/cranetabel">Complete kraantabel </a></li>							
						</ul>
					</li> 	
					<li class="sub">
						<a href="#l">Mailinglist gebruikers<img src="<?=$baseDir?>/www/_layout/images/back-nav-sub-pin.png" alt="" /> <span class=""></span></a>
						<ul>
							<li><a href="<?php echo $baseDir; ?>/admin/download_emaillist1">Gedoneerde foto’s</a></li>
							<li><a href="<?php echo $baseDir; ?>/admin/download_emaillist2">Aangevraagde tabellen</a></li>
						</ul>
					</li> 				
				</ul>
			</div>
			
			<div id="content" class="ninecol last">
								
				<div class="panel-wrapper">
					<div class="panel">

						<div class="title">
							<div class="headline first">
								<label>Verander wachtwoord </label> 
							</div>
						</div>
						
						<div class="content">
						<!-- ## Panel Content  -->
							<form id="imageform" method="POST" enctype="multipart/form-data" action="#" style="clear:both" target="submit-catch">
								<div class="group fixed">
									<label>Oude wachtwoord</label>
									<input id="input_oldpwd" type="text" placeholder="Vul hier het bestaande wachtwoord in......" />
								</div>
		                        <div class="group fixed">
									<label>Nieuw wachtwoord</label>
									<input id="input_password" type="password" placeholder="Vul hier een nieuw wachtwoord in..." />
								</div>
								<div class="group fixed">
									<label>Bevestiging nieuwe wachtwoord</label>
									<input id="input_confirm" type="password" placeholder="Vul hier nogmaals het nieuwe wachtwoord in..." />
								</div>
		                        <br/>
								<div class="group fixed">
									<label></label><button type="submit" class="button-blue" onclick="changeAdmin(); return false;"> &nbsp; &nbsp; &nbsp;OK&nbsp; &nbsp; &nbsp; </button>
								</div>

							</form>
						    
                    	</div>
				</div>
			</div>			
		</div>
	</div>
	</div>
	<div class="preview"></div>
	<script type="text/javascript">
		$.fn.spin = function(opts) {
		    this.each(function() {
		      var $this = $(this),
		          data = $this.data();

		      if (data.spinner) {
		        data.spinner.stop();
		        delete data.spinner;
		      }
		      if (opts !== false) {
		        data.spinner = new Spinner($.extend({color: $this.css('color')}, opts)).spin(this);
		      }
		    });
		    return this;
		};		
		
	</script>		
</body>
	
</html>
	