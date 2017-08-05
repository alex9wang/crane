<?php
	include("www/include/header.inc");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
    "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"> 
<html dir="ltr" lang="en-US" xmlns="http://www.w3.org/1999/xhtml">

<head>
	<link rel="shortcut icon" href="<?php echo $baseDir; ?>/www/images/icon.png">
	<script type="text/javascript" src="<?php echo $baseDir; ?>/www/js/image-picker.min.js"></script>
	<link rel="stylesheet" href="<?php echo $baseDir ?>/www/css/image-picker.css" />
	<script type='text/javascript'>
		$(document).ready(function() {
			
			g_crane_type = '<?php echo $crane_type;?>';
			g_crane_id = '<?php echo $crane_id;?>';

			var chk_value = 0;
			var temp = '<?php echo $extra; ?>';

			if (temp == 'Y') {
				chk_value++;
			}
						
			if (chk_value == 1) {		
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
		    $( "#image-gallery" ).dialog({
      				autoOpen: false,
			      	show: {
			        	effect: "blind",
			        	duration: 500
			      	},
			      	hide: {
			        	effect: "explode",
			        	duration: 500
			      	}
			 });
			
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
		function saveBasis(){
			var crane_type = <?php echo $crane_type;?>;
			var crane_id = <?php echo $crane_id; ?>;
			var model = $('#model').val();
			var type = $('#typenum').val();
			var size = $('#size').val();
			var maxlen = $('#maxlen').val();
			var tonnage = "";
			<?php if($crane_type == 1)
			{
			?>
			tonnage = $('#tonnage').val();
			<?php
			}
			?>
			if(model == "")
			{
				alert("Please fill out Model Type value.");
				return;
			}
			else if(type == "")
			{
				alert("Please fill out Type Number value.");
				return;
			}
			else if(size == "")
			{
				alert("Please fill out Stempelbasis value.");
				return;
			}
			else if(maxlen == "")
			{
				alert("Please fill out Draagarm Lengte value.");
				return;
			}
			<?php if($crane_type == 1)
			{
			?>
				else if(tonnage == "")
				{
					alert("Please fill out Tonnage kraan value.");
					return;
				}
			<?php
			}
			?>
			var extra = "Y";
			if($('div.checker span').eq(0).hasClass("checked"))
				extra = "Y";
			else
				extra = "N";

			var notition = $('textarea').eq(0).attr('value');
			
			$('.preview').css("display", "block");
			$('.preview').spin();

			$.post("<?php echo $baseDir; ?>/admin/update_basis_info", {
					crane_type:crane_type,
					crane_id:crane_id,
					model:model,
					type:type,
					size:size,
					maxlen:maxlen,
					tonnage:tonnage,
					extra:extra,
					notition:notition
				}, function(data) {
					$('.preview').spin(false);
					$('.preview').css("display", "none");

					var result = data.split("|");
					$('span#brand').html(result[0]);
					$('span#type').html(result[1]);
					alert("Save Successful!");
			});

		}

		function onClickDeletePhoto()
		{
			if (!confirm("Wil je deze foto verwijderen"))
				return;

			$('.preview').css("display", "block");
			$('.preview').spin();
			$.post("<?php echo $baseDir; ?>/admin/delete_photo", {
				crane_type : g_crane_type,
				crane_id : g_crane_id
				}, function(data) {
					$('.preview').spin(false);
					$('.preview').css("display", "none");
					var children = document.querySelectorAll("div.gallery-photo");
					var count = children.length;
					
					// insert photo to gallery-element
					for (var i = 0; i < count; i++) {										
						children[i].style.backgroundImage = "url('" + "/crane" + data + "')";
						
						var el = document.getElementById('main-photo').firstChild.nextSibling;
						el.style.backgroundImage = "url('" + "/crane" + data + "')";
					}					
			});
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

		function onClickClose(crane_type, crane_id)
		{
			var stage = '<?php echo $current_stage; ?>';
			var page = '<?php echo $current_page; ?>';
			window.location.href = "<?php echo $baseDir; ?>/admin/crane?stage=" + stage + "&page=" + page;
		}

		function onClickPhoto(crane_type, crane_id) {
			$('.preview').css("display", "block");
			$('.preview').spin();

			$.post("<?php echo $baseDir; ?>/admin/get_photos", {
				crane_type : g_crane_type,
				crane_id : g_crane_id
				}, function(data) {
					$('.preview').spin(false);
					$('.preview').css("display", "none");
					console.log(data);
					result = JSON.parse(data);
					console.log(result);
					html ="";
					for (i = 0; i < result.length; i++) {
						image_data = result[i];
						html = html + "<option data-img-src='<?php echo $baseDir;?>" + image_data.image + "' value='" + image_data.id + "'>" + image_data.image + "</option>"
					}
					
					$('select#image-picker').empty();
					$('select#image-picker').append(html);
					$('select#image-picker').imagepicker({
						show_label: false,
						hide_select: false,
						limit: 1,
						clicked: function(e) {
							$('#image-gallery').dialog("close");	
							selectedPhoto($(this).val());	
						}
					});
					$('#image-gallery').dialog("open");	
			 });
			
		}

		function selectedPhoto(id) {
			$('.preview').css("display", "block");
			$('.preview').spin();

			$.post("<?php echo $baseDir; ?>/admin/set_photo_to_crane", {
				id : id,
				}, function(data) {
					$('.preview').spin(false);
					$('.preview').css("display", "none");
					console.log(data);
					url = "url(<?php echo $baseDir;?>" + data + ")";
					$("div.main-photo").css("background-image", url);
					$("div.gallery-photo").css("background-image", url);
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
				echo "<a href='javascript:logout();'>Logout</a><span>|</span><span>Welcome, <strong>$administrator</strong></span>";
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
							<li class="current"><a href="<?php echo $baseDir; ?>/admin/crane">Beschikbare kranen</a></li>
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
								<label>Bewerk kraan</label> 
							</div>
							<div class="collapse">collapse</div>
						</div>
						<div class="content">					
							<div id="main-photo" style="width:250px;padding:5px;float:left">
								<div class='main-photo' style='background-image: url(<?php echo $baseDir . $photo; ?>);'></div>
							</div>
							<div style="padding:5px;float:left">
								<label style="line-height:30px;left-margin:30px;"> &nbsp; </label><br/>
								<label style="line-height:30px;">Merk :&nbsp;</label><span id="brand"><?php echo $brand ?></span><br/>
								<label style="line-height:30px;">Type :&nbsp;</label><span id="type"><?php echo $type ?></span>
							</div>	
						</div>
					</div>
				</div>
				
				<div class="panel-wrapper">
					<div class="panel">

						<div class="tabs">
							<ul>
								<li class="active"><a href="#" rel="tab-01-content">Kraan basisinfo</a></li>
								<li><a href="#" rel="tab-02-content">Handleiding kraan</a></li>
								<li class="last"><a href="#" rel="tab-03-content">Foto kraan </a></li>
							</ul>
						</div>
						<div class="tabs-content" style="height: auto;">
						
							<div id="tab-01-content" class="active" style="width:100%;">
								<div class="container" style="padding-left:5%;">
									<div class="group fixed" style="display:inline; float:left; margin-right:50px">
										<br/>
										<br/>
										<label style="width:160px;">Merk</label>
										<input class="datetime" id="model" placeholder="Model Type" value="<?php echo $brand;?>" style="width:200px;">
										<div style="height:18px;"></div>
										<label style="width:160px;">Type</label>
										<input class="datetime" id="typenum" placeholder="Type Number" value="<?php echo $type;?>" style="width:200px;">
										<div style="height:18px;"></div>
										<label style="width:160px;">Stempelbasis</label>
										<input class="datetime" id="size" placeholder="Stempelbasis" value="<?php echo $size;?>" style="width:200px;">
										<div style="height:18px;"></div>
										<label style="width:160px;">Draagarm Lengte</label>
										<input class="datetime" id="maxlen" placeholder="Maximale draagarm lengte" value="<?php echo $maxlen;?>" style="width:200px;">
										<?php if($crane_type == 1){ ?>
										<div style="height:18px;"></div>
										<label style="width:160px;">Tonnage kraan</label>
										<input class="datetime" id="tonnage" placeholder="Tonnage Crane" value="<?php echo $tonnage;?>" style="width:200px;">
										<?php } ?>
									</div>

									<div class="" style="margin-left:50px;">
										<br/>
										<label style="width:160px;">Extra Equipment?</label>
										<br/>
										<br/>
										<input class='checkbox' id="extra_yes" type='checkbox' onclick="check(0);" /><label style="margin-left:10px; margin-right:50px;">Ja</label>										
										<input class='checkbox' id="extra_no" type='checkbox' onclick="check(1);" /><label style="margin-left:10px; margin-right:50px;">Nee</label>
										<br/>
										<br/>
										<label style="width:160px;">Notitie</label>
										<br/>
										<textarea id="notition" style="width:300px; height:100px;"><?php echo $notition?></textarea>
									</div>
									<br/>
								</div>
								<div class="" style="float:none; height:30px; text-align:center; margin:30px">
										<button class="button-blue" onclick="saveBasis();" style="width:70px;">&nbsp;&nbsp;Bewaren&nbsp;&nbsp;</button>
								</div>
							</div>
							
							<div id="tab-02-content">
								<div class="container" style="padding-left:20%;">
									<div style="height:10px;"></div>
									<div class="group fixed" style="margin-top:10px;">
										<br/>
										<label style="margin-right:40px;margin-top:10px">Upload specifieke kraantabel</label>
										<div><?php echo $specification?></div>
										<div id="uniform-undefined" class="uploader" style="">
										    <input type="file" id="upload_spec" size="19" style="opacity: 0;" value="111">
										    <span class="filename" style="-moz-user-select: none;">please select file</span>
										    <span class="action" style="-moz-user-select: none;"></span>
										    <input type="hidden" id="spec_hidden">
										</div>
										<button class="button-blue" onclick="onDelete(1);" style="width:80px;margin:5px;">Delete</button>
									</div>
									<div class="group fixed" style="margin-top:30px;">
										<br/>
										<label style="margin-right:40px; margin-top:10px">Upload Complete Manual</label>
										<div><?php echo $manual?></div>
										<div id="uniform-undefined" class="uploader" style="">
										    <input type="file" id="upload_manual" size="19" style="opacity: 0;">
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
									<!--<div class="group fixed">
										<label style="width:120px; padding-left:25%">E-mailadres</label>										
										<input class="datetime" id="donate_email" placeholder="Emailadres gebruiker" value="<?php echo $email;?>" style="width:250px;">
									</div>-->
									<div class="group fixed" style="height:20px;">										
									</div>
									<div class="" style="float:left;width:240px; padding-left:150px; margin-bottom:30px;">
									    <div class="gallery-photo" style="width:240px; background-image: url(<?php echo $baseDir . $photo; ?>);" onclick="javascript:onClickPhoto(<?php echo $crane_type?>, <?php echo $crane_id?>);" data="1"></div>									    
									</div>
									<div class="" style="float:left;width:30%;padding-left:0px;">
								        <div class="" style="height:0px;text-align:center;">
								        </div>
								        <div class="" style="text-align:center;">
								            <div id="preview"></div>
								            <div class="group fixed">
								                <label></label>
								                <div id="uniform-undefined" class="uploader" style="">
								                    <input type="file" id="upload_image" size="19" style="opacity: 0;">
								                    <span class="filename" style="-moz-user-select: none;"></span>
								                    <span class="action" style="-moz-user-select: none;"></span>
								                </div>
								                <div class="photos"></div>
								            </div>
								            <script src="<?php echo $baseDir; ?>/www/js/upload.js">
    										</script>
								            <div style="height:10px;"></div>
								            <button class="button-blue" onclick="onClickDeletePhoto();" style="width:120px;margin:5px;">Delete</button>
								        </div>
								    </div>
								    
								    <div style="clear:both;">
								    <div>DOOR GEBRUIKERS INGEZONDEN</div>
								    <br>
								    crane_type=<?php echo $crane_type;?><br>
								    <br>
								    crane_id=<?php echo $crane_id;?><br>
								    
								    </div>
								    								    
								</div>
							</div>					

						
						</div>
						
					</div>		
				</div>
				
				<div class="" style="float:right;">
					<button class="button-blue" onclick="onClickClose();">&nbsp;&nbsp;Close&nbsp;&nbsp;</button>
					<br/>
					<br/>
					<br/>
				</div>
			</div>
		</div>
	</div>
	<div class="preview"></div>
	<div id="image-gallery" title="Image Picker">
		<select id="image-picker" class="noUniform image-picker masonry show-html" >
			
		</select>
	<div>
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
	