<?php
	include("www/include/header.inc");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
    "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"> 
<html dir="ltr" lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link rel="shortcut icon" href="<?php echo $baseDir; ?>/www/images/icon.png">
	<link type="text/css" rel="stylesheet" href="<?php echo $baseDir; ?>/www/css/calendar.css" />
	<script language="javascript" src="<?php echo $baseDir; ?>/www/js/calendar-1.5.js"></script>
	<script type='text/javascript'>
		
		$(document).ready(function() {			
			
		});		
		
		function check($type){
			if($type == 0)
			{
				$('div.checker span').eq(0).addClass("checked");
				$('div.checker span').eq(1).removeClass("checked");		
				document.getElementById("extra_yes").checked = true;
				document.getElementById("extra_no").checked = false;
			}
			else
			{
				$('div.checker span').eq(0).removeClass("checked");
				$('div.checker span').eq(1).addClass("checked");		
				document.getElementById("extra_yes").checked = false;
				document.getElementById("extra_no").checked = true;
			}
		}

		function onClickDeletePhoto(){
			if (!confirm("Wil je deze foto verwijderen"))
				return;
			var children = document.querySelectorAll("div.gallery-photo");
			var count = children.length;
			
			// insert photo to gallery-element
			for (var i = 0; i < count; i++) {										
				children[i].style.backgroundImage = "url('/crane/www/images/nophoto.png')";				
			}	
		}

		function onClickSave(){

			var crane_type = $("#select-cranetype option:selected").index();
			if (crane_type == 0) { alert('Please Choose Crane Type'); return; }

			var marken = $('input').eq(0).attr('value');
			if(marken == "")
			{
				alert("Please fill out Merk");
				return;
			}
			var model = $('input').eq(1).attr('value');
			if(crane_type == 2){
				if(model == "")
				{
					alert("Please fill out Model Type");
					return;
				}	
			}			
			var typenum = $('input').eq(2).attr('value');
			if(typenum == "")
			{
				alert("Please fill out Type");
				return;
			}
			var size = $('input').eq(3).attr('value');
			if(size == "")
			{
				alert("Please fill out Stempelbasis");
				return;
			}
			var maxlen = $('input').eq(4).attr('value');
			if(maxlen == "")
			{
				alert("Please fill out Draagarm lengte");
				return;
			}
			var tonnage = $('input').eq(5).attr('value');
			
			if(crane_type == 1){			
			   if(tonnage == "")
			   {
			   		alert("Please fill out Tonnage kraan");
					return;
			   }			
			}
			
			var extra = "";
			if($('div.checker span').eq(0).hasClass("checked"))
				extra = "Y";
			else
				extra = "N";
			if(extra == "")
			{
				if(crane_type == 2)
				{
					alert("Please Check Extra Equipment");
					return;
				}				
			}

			var notition = $('textarea').eq(0).attr('value');

			var donate_email = $('input#donate_email').attr('value');

			var children = document.querySelectorAll("div.gallery-photo");
			var url = children[0].style.backgroundImage;
			url = getphotopath(url); 
			url = (isnophoto(url)) ? '' : url;
			// var spec_url = document.getElementById("spec_hidden").value;
			// var manual_url = document.getElementById("manual_hidden").value;
			// alert(spec_url);
			$('.preview').css("display", "block");
			$('.preview').spin();

			$.post("<?php echo $baseDir; ?>/admin/add_crane", {
					crane_type:crane_type,	
					marken:marken,				
					model:model,
					type:typenum,
					size:size,
					maxlen:maxlen,
					tonnage:tonnage,
					extra:extra,
					notition:notition,
					// spec_url: spec_url,
					// manual_url: manual_url,
					donate_email: donate_email,
					photo_url:url
				}, function(data) {
					$('.preview').spin(false);
					$('.preview').css("display", "none");

					onClickEmpty();
					alert("Toevoegen successvol");
			});
			
		}

		function onClickEmpty()
		{			
			$("#select-cranetype").val('all');
			$('input').eq(0).val('');
			$('input').eq(1).val('');
			$('input').eq(2).val('');
			$('input').eq(3).val('');
			$('input').eq(4).val('');
			$('input').eq(5).val('');
			$('div.checker span').removeClass('checked');
			$('textarea').eq(0).val('');
			$('input#donate_email').val('');


		}
		function onDelete($type)
		{
			if($type == 1)
			{
				if (!confirm("Wilt u deze sneltabel verwijderen?"))
					return;
				$('.preview').css("display", "block");
				$('.preview').spin();

				$.post("<?php echo $baseDir; ?>/admin/delete_spec", {
					crane_type : g_crane_type,
					crane_id : g_crane_id
					}, function(data) {
						$('.preview').spin(false);
						$('.preview').css("display", "none");
						alert("Delete Specification Successful");
					});
			}
			else if($type == 2)
			{
				if (!confirm("Wilt u deze complete tabel  verwijderen?"))
					return;
				$('.preview').css("display", "block");
				$('.preview').spin();

				$.post("<?php echo $baseDir; ?>/admin/delete_manual", {
					crane_type : g_crane_type,
					crane_id : g_crane_id
					}, function(data) {
						$('.preview').spin(false);
						$('.preview').css("display", "none");
						alert("Delete Manual Successful");
				});
			}
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
					<li class="sub">
						<a href="#l">Basis management<img src="<?=$baseDir?>/www/_layout/images/back-nav-sub-pin.png" alt="" /> <span class="icon-grid"></span></a>
						<ul>
							<li><a href="<?php echo $baseDir; ?>/admin/changeadmin">Verander admin wachtwoord</a></li>
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
					<li class="sub active">
						<a href="#l">Kraan management<img src="<?=$baseDir?>/www/_layout/images/back-nav-sub-pin.png" alt="" /> <span class="icon-calculator"></span></a>
						<ul>
							<li><a href="<?php echo $baseDir; ?>/admin/crane">Beschikbare kranen</a></li>
							<li class="current"><a href="<?php echo $baseDir; ?>/admin/addcrane">Voeg kraan toe</a></li>
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
								<label>Voeg kraan toe</label>
							</div>							
						</div>		
						<div class="tabs">
							<ul>
								<li class="active"><a href="#" rel="tab-01-content">Kraan basisinfo</a></li>
								<li><a href="#" rel="tab-02-content">Handleiding kraan</a></li>
								<li class="last"><a href="#" rel="tab-03-content">Foto kraan</a></li>
							</ul>
						</div>
						<div class="tabs-content" style="height: auto;">
						<!-- ## Panel Content  -->
							<div id="tab-01-content" class="active" style="width:100%; ">
								<div class="container" style="padding-left:5%; padding-top:20px; margin-bottom:30px">
									<div class="group fixed" style="margin-bottom:18px">
										<label style="width:158px;">Kraan Type*</label>
										<select id="select-cranetype">
											<option value="all">【Kraan Type】</option>;
											<option value="tele">Telecsoopkraan</option>;
											<option value="tower">Torenkraan</option>;
										</select>
									</div>
									<div class="group fixed" style="display:inline; float:left; margin-right:40px">
										<label style="width:160px;">Merk</label>
										<input class="datetime" id="merken" placeholder="Vul hier het merk in" style="width:230px;">
										<div style="height:18px;"></div>
										<label style="width:160px;">Model (Voor Torenkraan)</label>
										<input class="datetime" id="model" placeholder="Vul hier het model in" style="width:230px;">
										<div style="height:18px;"></div>
										<label style="width:160px;">Type</label>
										<input class="datetime" id="typenum" placeholder="Vul hier het type in" style="width:230px;">
										<div style="height:18px;"></div>
										<label style="width:160px;">Stempelbasis</label>
										<input class="datetime" id="size" placeholder="Vul hier de stempelbasis in" style="width:230px;">
										<div style="height:18px;"></div>
										<label style="width:160px;">Draagarm lengte</label>
										<input class="datetime" id="maxlen" placeholder="Vul hier de draagarm lengte in" style="width:230px;">										
										<div style="height:18px;"></div>
										<label style="width:160px;">Tonnage Kraan</label>
										<input class="datetime" id="tonnage" placeholder="Vul hier de tonnage van de kraan in" style="width:230px;">
										
									</div>

									<div class="" style="margin-left:50px;">
										<br/>
										<label style="width:160px;">Gebruik Extra Equipment?</label>
										<br/>
										<br/>
										<input class='checkbox' id="extra_yes" type='checkbox' onclick="check(0);" /><label style="margin-left:10px; margin-right:50px;">Ja</label>										
										<input class='checkbox' id="extra_no" type='checkbox' onclick="check(1);" /><label style="margin-left:10px; margin-right:50px;">Nee</label>
										<br/>
										<br/>
										<label style="width:160px;">Notitie</label>
										<br/>
										<textarea id="notition" style="width:300px; height:130px; padding:10px; resize:vertical"></textarea>
									</div>
									<br/>
								</div>								
							</div>
							<div id="tab-02-content">
								<div class="container" style="padding-left:20%;">
									<div style="height:10px;"></div>
									<div class="group fixed" style="margin-top:10px;">
										<br/>
										<label style="margin-right:40px;margin-top:10px">Upload specifieke kraantabel</label>
										<div id="uniform-undefined" class="uploader" style="">
										    <input type="file" id="upload_spec" size="19" style="opacity: 0;"></input>
										    <span class="filename" style="-moz-user-select: none;"></span>
										    <span class="action" style="-moz-user-select: none;"></span>
										    <input type="hidden" id="spec_hidden">
										</div>
										<button class="button-blue" onclick="onDelete(1);" style="width:80px;margin:5px;">Delete</button>
									</div>
									<div class="group fixed" style="margin-top:30px;">
										<br/>
										<label style="margin-right:40px; margin-top:10px">Upload complete handleiding</label>
										<div id="uniform-undefined" class="uploader" style="">
										    <input type="file" id="upload_manual" size="19" style="opacity: 0;"></input>
										    <span class="filename" style="-moz-user-select: none;"></span>
										    <span class="action" style="-moz-user-select: none;"></span>
										    <input type="hidden" id="manual_hidden">
										</div>
										<button class="button-blue" onclick="onDelete(2);" style="width:80px;margin:5px;">Delete</button>
									</div>
									<br/>
								</div>
								<div style="margin:30px;"></div>
							</div>
							<div id="tab-03-content">
								<div class="container" style="padding-left:5%;">
									<div class="group fixed" style="height:30px;">										
									</div>
									<div class="group fixed">
										<label style="width:120px; padding-left:25%">E-mailadres</label>										
										<input class="datetime" id="donate_email" placeholder="E-mailadres" style="width:250px;">
									</div>
									<div class="group fixed" style="height:20px;">										
									</div>
									<div class="" style="float:left;width:240px; padding-left:150px; margin-bottom:30px;">
									    <div class="gallery-photo" style="width:240px; background-image: url(<?php echo $baseDir . "/www/images/nophoto.png" ; ?>);" data="1"></div>									    
									</div>
									<div class="" style="float:left;width:30%;padding-left:0px;">
								        <div class="" style="height:0px;text-align:center;">
								        </div>
								        <div class="" style="text-align:center;">
								            <div id="preview"></div>
								            <div class="group fixed">
								                <label></label>
								                <div id="uniform-undefined" class="uploader" style="">
								                    <input type="file" id="upload_image" size="19" style="opacity: 0;"></input>
								                    <span class="filename" style="-moz-user-select: none;"></span>
								                    <span class="action" style="-moz-user-select: none;"></span>								                    
								                </div>
								                <div class="photos"></div>
								            </div>
								            <script src="<?php echo $baseDir; ?>/www/js/upload.js"></script>
								            <div style="height:10px;"></div>
								            <button class="button-blue" onclick="onClickDeletePhoto();" style="width:120px;margin:5px;">Verwijderen</button>
								        </div>
								    </div>								    
								</div>
							</div>	
						<!-- ## / Panel Content  -->
						</div>
						
					</div>		
				</div>
				<div class="" style="text-align:center;">
					<button class="button-blue" style="width:70px;" onclick="onClickSave();">&nbsp;&nbsp;Opslaan&nbsp;&nbsp;</button>
					<button class="button-blue" style="width:70px;" onclick="onClickEmpty();">&nbsp;&nbsp;Clear&nbsp;&nbsp;</button>
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
	