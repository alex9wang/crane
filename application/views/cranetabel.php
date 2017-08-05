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
		
		var lock = 0;
		var current_crane = 'Telecrane';
		var current_page = "<?php echo $current_page ?>";
		var total_count	= 0;

		$(document).ready(function() {	
			current_crane = 'Telecrane';
			onClickSearch('Telecrane', current_page);
		});

		function SwitchToTele(){
			current_crane = 'Telecrane';
			current_page = 1;
			onClickSearch('Telecrane', current_page);
		}
		function SwitchToTower(){
			current_crane = 'Towercrane';
			current_page = 1;
			onClickSearch('Towercrane', current_page);
		}
		function doSearch(keyname, page_index) {
			$('.preview').css("display", "block");
			$('.preview').spin();
			// searching..
			$.post("<?php echo $baseDir; ?>/admin/get_cranetabel", {
					current_page : page_index,
					keyname : keyname
				}, function(data) {
				$('.preview').spin(false);
				$('.preview').css("display", "none");

				lock = 0;
								
				var results = data.split("|||");
				var html = "";
				html += "<table class='' style='text-align:center'>";
				html += results[0];
				html += "</table>";
				var el = document.getElementById('tbl-all-cranes');
				el.innerHTML = html;				
				el = document.getElementById('lbl-total-count');
				html = "Total : <span style='color:red;'><b>" + results[1] + "</b></span>";
				el.innerHTML = html;
				total_count = results[1];

				el = document.getElementById('navigation-bar');
				el.innerHTML = results[2];
			});
		}
		function onClickSearch(keyname, page_index) {	
			
			if (keyname == 'Telecrane') {				
				keyname = 'tele_crane';
			}
			else if (keyname == 'Towercrane') {
				keyname = 'tower_crane';
			}

			doSearch(keyname, page_index);			
		}
		function onSelectPage(command) {
			if (lock > 0) return retrun;
			lock = 1;
			current_page = command;
			onClickSearch(current_crane, current_page);
		}

		$(function() {
		    $('input.2').on("input", function() {
			    alert("Change to " + this.value);
			});
		});

		function onSave(){
			//alert($('input.2').eq(10).val());

			var load_array = new Array();
			
			for(i=0; i<total_count; i++)
			{
				var sub_array = new Array();
				var crane_id = 0;
				for(j=0; j<10; j++)
				{
					tag_type = 'input.' + (i + 1);
					if($(tag_type).eq(j).val() == null)
						break;
					var load = new Array();
					load[0] = $(tag_type).eq(j).attr('craneid');
					load[1] = $(tag_type).eq(j).attr('radius');
					load[2] = $(tag_type).eq(j).val();
					sub_array[j] = load;					
				}
				
				load_array[i] = sub_array;
			}
			$('.preview').css("display", "block");
			$('.preview').spin();
			console.log(load_array);
			$.post("<?php echo $baseDir; ?>/admin/update_cranetabel", {
					load:JSON.stringify(load_array),
					current_crane: current_crane
				}, function(data) {
					$('.preview').spin(false);
					$('.preview').css("display", "none");
					alert("Save Successful");
				
			});
		}
	</script>
	<script type="text/javascript" src="<?php echo $baseDir; ?>/www/js/spin.js"></script>
</head>

<body>
	 
	<div id="header-wrapper" class="container">
		<div id="user-account" class="row" >
			<div class="threecol"> <span>Kranen Admin Page</span> </div>
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
							<li><a href="<?php echo $baseDir; ?>/admin/crane">Beschikbare kranen</a></li>
							<li><a href="<?php echo $baseDir; ?>/admin/addcrane">Voeg kraan toe</a></li>
							<li class="current"><a href="<?php echo $baseDir; ?>/admin/cranetabel">Complete kraantabel </a></li>							
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
								<label>Complete kraantabel</label> 
							</div>
						</div>
						
						<div class="title">
							<div class="headline first" style="width:200px;">
								<label id='lbl-total-count'>Total : </label> 
							</div>						
							
							<div class="headline" style="float:right; margin-right:30px;">								
								<button class="button-red" style="" onclick="SwitchToTele(); return false;">Telecsoopkraan</button>
								<button class="button-red" style="width:90px" onclick="SwitchToTower(); return false;">Torenkraan</button>
							</div>
						</div>
						<div class="content">
							<div id="tbl-all-cranes"></div>
						</div>
					</div>

					<div class="bottom-bar">
					<div style="float:left;width:20%;text-align:left;">
						<!-- <button class="button" onclick="onClickDeleteSelected();" style="width:120px;height:32px;">Remove Cranes</button> -->
						
					</div>
					<div style="float:left;width:60%;margin-left:20%; margin-top:20px; margin-bottom:30px;" id="navigation-bar"></div>
					<div style="float:left; text-align:right;">
						<button class="button-blue" onclick="onSave();" style="width:80px;margin-top:20px; margin-left:5px;">Save</button>
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
	