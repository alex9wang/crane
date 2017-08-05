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

		$(document).ready(function() {

			onClickSearch('refresh');

		});
		function onClickSaveAsExcel() {
			onClickSearch('save');
		}
		function onClickDeleteSelected() {
			var index_array = new Array('', '', '', '', '');
			var children = document.querySelectorAll('input.checkbox');
			for (var i = 1; i < children.length; i++) {
				if (children[i].checked != true) continue;
				index_array[i - 1] = children[i].parentNode.parentNode.getAttribute('name');
			}
			$.post("<?php echo $baseDir; ?>/admin/delete_member_selected_records", {
				index_1 : index_array[0],
				index_2 : index_array[1],
				index_3 : index_array[2],
				index_4 : index_array[3],
				index_5 : index_array[4] }, function(data) {
				onClickSearch('refresh');
			});
		}
		function onClickDetails(index) {
			window.location.href = '<?php echo $baseDir; ?>/admin/memdetail?id=' + index + '&stage=' + current_stage + '&page=' + current_page;
		}
		function onClickAnswer(index) {
			window.location.href = '<?php echo $baseDir; ?>/admin/answer?id=' + index + '&stage=' + current_stage + '&page=' + current_page;
		}
		function onClickDelete(index) {
			if (!confirm("Wil je deze foto verwijderen"))
				return;	
			$.post("<?php echo $baseDir; ?>/admin/delete_member_record", {id : index}, function(data) {
				onClickSearch('refresh');
			});
		}
		function onClickBalloon(index) {
			window.location.href = '<?php echo $baseDir; ?>/admin/balloon?id=' + index;
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
		function doSearch(start_date, end_date) {
			// searching..
			$('.preview').css("display", "block");
			$('.preview').spin();
			$.post("<?php echo $baseDir; ?>/admin/search_manual", {
					start_date : start_date,
					end_date : end_date
				}, function(data) {
				$('.preview').spin(false);
				$('.preview').css("display", "none");

				$('#search_start').eq(0).val('');
				$('#search_end').eq(0).val('');

				var html = "";
				html += "<table class='' style='text-align:center'><thead>";
				html += "<tr style='text-align:center'>";				
				html += "<th style='text-align:center;width:120px;'>Foto</th>";
				html += "<th style='text-align:center;width:80px;'>Merk</th>";
				html += "<th style='text-align:center;width:100px;'>Type</th>";
				html += "<th style='text-align:center;width:130px;'>Typenummer<br/>Stempelbasis<br/>Draagarm lengte<br/>Tons-kraan</th>";
				html += "<th style='text-align:center;width:auto;'>Notitie</th>";
				html += "<th style='text-align:center;width:70px;'>Datum upload</th>";
				html += "</tr></thead><tbody>";
				html += data;
				html += "</tbody></table>";
				var el = document.getElementById('tbl-all-cranes');
				el.innerHTML = html;
			});
		}
		function onClickSearch(command) {
			var start_date = $('#search_start').eq(0).attr('value');
			var end_date = $('#search_end').eq(0).attr('value');
			
			
			doSearch(start_date, end_date);
			document.getElementById('search_key').focus();
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
					<li class="sub active">
						<a href="#l">Totaaloverzicht Applicatie<img src="<?=$baseDir?>/www/_layout/images/back-nav-sub-pin.png" alt="" /> <span class="icon-ques"></span></a>
						<ul>
							<li class="current"><a href="<?php echo $baseDir; ?>/admin/download_manual">Laatst gedownloade handleiding</a></li>
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
								<label>Laatste 5 gedownloade handleidingen</label> 
							</div>
						</div>
						
						<div class="title">
							<div class="headline first">
								<!-- <input class="datetime" id="search_start" value="" placeholder="Start Date">&nbsp;
								<input class="datetime" id="search_end" value="" placeholder="End Date"> -->
							</div>
							
							<div class="headline" style="float:right;padding-right:15px;">
								<input class="datetime" id="search_start" value="" placeholder="Begin datum">&nbsp;
								<input class="datetime" id="search_end" value="" placeholder="Eind Datum">
								<button class="button-red" onclick="onClickSearch('search'); return false;">Zoeken</button>
							</div>
						</div>
						<div class="content">
							<div id="tbl-all-cranes"></div>
						</div>
					</div>
				</div>				
			</div>
		</div>
	</div>
	<script>
		$( "#search_key" ).keypress(function( event ) {
			if ( event.which != 13 ) return;
			onClickSearch('search');
		});
		var cal_3 = new Calendar({
			element: 'search_start',
			weekNumbers: true,
			startDay: 1,
			onOpen: function (element) {
				//do something
			}
		});

		var cal_4 = new Calendar({
			element: 'search_end',
			weekNumbers: true,
			startDay: 1,
			onOpen: function (element) {
				//do something
			}
		});
	</script>
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
	