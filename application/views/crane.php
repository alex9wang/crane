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
		var current_page = "<?php echo $current_page ?>";
		var current_stage = "<?php echo $current_stage ?>"

		$(document).ready(function() {			
			onClickSearch('refresh');
		});
		function onClickSaveAsExcel() {
			onClickSearch('save');
		}
		function onClickDeleteSelected() {
			if (!confirm("Wil je deze kraan verwijderen"))
				return;	
			
			var type_array = new Array('', '', '', '', '');
			var id_array = new Array('', '', '', '', '');
			var children = document.querySelectorAll('input.checkbox');
			for (var i = 1; i < children.length; i++) {
				if (children[i].checked != true) continue;
				id_array[i - 1] = children[i].getAttribute("craneid");
				type_array[i - 1] = children[i].getAttribute("cranetype");
			}
			
			$.post("<?php echo $baseDir; ?>/admin/delete_selected_member", {
				type_index1 : type_array[0],
				id_index1 : id_array[0],
				type_index2 : type_array[1],
				id_index2 : id_array[1],
				type_index3 : type_array[2],
				id_index3 : id_array[2],
				type_index4 : type_array[3],
				id_index4 : id_array[3],
				type_index5 : type_array[4],
				id_index5 : id_array[4]
				}, function(data) {
				onClickSearch('refresh');
			});
		}
		function onClickDetails(crane_type, crane_id) {
			window.location.href = '<?php echo $baseDir; ?>/admin/cranedetail?crane_type=' + crane_type + '&crane_id=' + crane_id + '&stage=' + current_stage + '&page=' + current_page;			
		}
		function onClickDelete(crane_type, crane_id) {
			if (!confirm("Wil je deze kraan verwijderen"))
				return;	
			$.post("<?php echo $baseDir; ?>/admin/delete_crane", {
				crane_type : crane_type,
				crane_id : crane_id}, function(data) {
				onClickSearch('refresh');
			});
		}		
		function onClickChangeState(index, state) {
			state = parseInt(state, 10);
			state = (state != 1) ? 1 : 2;
			$.post("<?php echo $baseDir; ?>/admin/set_member_state", {id : index, state : state}, function(data) {
				onClickSearch('refresh');
			});
		}
		function onChangeHeadCheckBox() {
			var checked = $('input.checkbox').eq(0).is(":checked") ? 1 : 0;
			var children = document.querySelectorAll('input.checkbox');
			for (var i = 1; i < children.length; i++) {
				children[i].checked = checked;
			}
		}
		
		function doSearch(command, keyname, keyvalue) {
			//alert(command);
			// searching..			
			$('.preview').css("display", "block");
			$('.preview').spin();

			$.post("<?php echo $baseDir; ?>/admin/get_cranes", {
					command : command,
					current_page : current_page,
					current_stage : current_stage,
					keyname : keyname,
					keyvalue : keyvalue
				}, function(data) {
				$('.preview').spin(false);
				$('.preview').css("display", "none");

				lock = 0;
				if (data == 'ERROR') {
					return;
				}
				if (data == 'SAVE_OK') {
					document.location.href = "<?php echo $baseDir; ?>" + "/www/excel/cranes.xls";
					return;
				}
				var check_result = data.indexOf("|||");
				if (check_result < 0) {
					alert('Can\'t access cranes.xls');
					return;
				}
				var navi = "";
				var results = data.split("|||");
				var total_count = parseInt(results[0], 10);
				current_stage = parseInt(results[1], 10);
				current_page = parseInt(results[2], 10);
				data = results[3];
				navi = results[4];
				var html = "";
				html += "<table class='' style='text-align:center'><thead>";
				html += "<tr style='text-align:center'>";
				html += "<th style='text-align:center;width:20px;'><input class='checkbox' type='checkbox' onchange='onChangeHeadCheckBox();' /></th>";
				html += "<th style='text-align:center;width:120px;'>Foto</th>";
				html += "<th style='text-align:center;width:70px;'>Merk</th>";
				html += "<th style='text-align:center;width:100px;'>Type Number</th>";
				html += "<th style='text-align:center;width:130px;'>Typenummer<br/>Stempelbasis<br/>Draagarm lengte<br/>Tons-kraan</th>";
				html += "<th style='text-align:center;width:auto;'>Notition</th>";
				html += "<th style='text-align:center;width:70px;'>Edit/Remove</th>";
				html += "</tr></thead><tbody>";
				html += data;
				html += "</tbody></table>";
				var el = document.getElementById('tbl-all-cranes');
				el.innerHTML = html;
				el = document.getElementById('navigation-bar');

				el.innerHTML = navi;
				el = document.getElementById('lbl-total-count');
				html = "Total : <span style='color:red;'><b>" + total_count + "</b></span>";
				el.innerHTML = html;
			});
		}
		function onClickSearch(command) {			
			
			var keyname = $("#search_other").eq(0).attr("value");

			if (keyname == 'Telecsoopkraan') {				
				keyname = 'tele_crane';
			}
			else if (keyname == 'Torenkraan') {
				keyname = 'tower_crane';
			}
			else {
				keyname = 'all';
			}
			var keyvalue = $('#search_key').eq(0).attr('value');
			// if (keyvalue == '') {
			// 	keyname = '';
			// }			
			doSearch(command, keyname, keyvalue);
			document.getElementById('search_key').focus();
		}
		function onSelectPage(command) {
			if (lock > 0) return retrun;
			lock = 1;
			onClickSearch(command);
		}
		function onClickAdd() {
			window.location.href = '<?php echo $baseDir; ?>/admin/addcrane';
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
							<li><a href="<?php echo $baseDir; ?>/admin/download_manual">Laatste gedownloade handleiding</a></li>
							<li><a href="<?php echo $baseDir; ?>/admin/donate_photo">Laatste gedoneerde foto</a></li>				
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
								<label>Beschikbare kranen</label> 
							</div>
						</div>
						
						<div class="title">
							<div class="headline first" style="width:200px;">
								<button class="button-red" onclick="onClickAdd(); return false;">Toevoegen</button>
								<label id='lbl-total-count' style="margin-left:5px;"></label> 
							</div>
							<div class="headline first" style="padding-left:150px;">
								<label>Zoeken :</label> 
							</div>
							<div class="headline">
								<select id="search_other">
									<option>【All】</option>;
									<option>Telecsoopkraan</option>;
									<option>Torenkraan</option>;
								</select>
							</div>
							<div class="headline">
								<input class="datetime" id="search_key" value="" style="width:200px;" placeholder="Merken">
								<button class="button-red" onclick="onClickSearch('search'); return false;">Zoeken</button>
							</div>
						</div>
						<div class="content">
							<div id="tbl-all-cranes"></div>
						</div>
					</div>
				</div>
				<div class="bottom-bar">
					<div style="float:left;width:20%;text-align:left;">
						<button class="button" onclick="onClickDeleteSelected();" style="width:120px;height:32px;">Verwijder kranen</button>
					</div>
					<div style="float:left;width:60%;" id="navigation-bar"></div>
					
				</div>
			</div>
		</div>
	</div>
	<div class="preview"></div>
	<script>
		$( "#search_key" ).keypress(function( event ) {
			if ( event.which != 13 ) return;
			onClickSearch('search');
		});		
	</script>
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
	