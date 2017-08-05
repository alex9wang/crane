<?php
	include("www/include/header.inc");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
    "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd"> 
<html dir="ltr" lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link rel="shortcut icon" href="<?php echo $baseDir; ?>/www/images/icon.png">
	<script src="<?php echo $baseDir; ?>/www/js/ckeditor/ckeditor.js"></script>
	<script type='text/javascript'>
		var editor1;
		var editor2;
		CKEDITOR.config.height = '45%';
		
		$(document).ready(function() {
		});
		function onClickSave() {
			var update_dutch    = editor1.getData();
			var update_german   = editor2.getData();
			var update_french	= editor3.getData();
			
			if(update_dutch == "")
			{
				alert("Invoeren update info Nederlands");
				return;
			}
			else if(update_german == "")
			{
				alert("Invoeren update info Duits");
				return;
			}
			else if(update_french == "")
			{
				alert("Invoeren update info Frans");
				return;
			}
			// save text of update
			$('.preview').css("display", "block");
			$('.preview').spin();
			$.post("<?php echo $baseDir; ?>/admin/set_update", {update_dutch : update_dutch, update_german: update_german, update_french: update_french}, function(data) {
			     $('.preview').spin(false);
				 $('.preview').css("display", "none");
			     alert("App Update geslaagd");
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
					<li class="sub">
						<a href="#l">Basis management<img src="<?=$baseDir?>/www/_layout/images/back-nav-sub-pin.png" alt="" /> <span class="icon-grid"></span></a>
						<ul>
							<li><a href="<?php echo $baseDir; ?>/admin/changeadmin">Verander admin wachtwoord</a></li>
							<li><a href="<?php echo $baseDir; ?>/admin/addadmin">Voeg administrator toe</a></li>
							<li><a href="<?php echo $baseDir; ?>/admin/adminlist">Overzicht administrators</a></li>
						</ul>
					</li> 
					<li class="sub active">
						<a href="#l">Applicatie management<img src="<?=$baseDir?>/www/_layout/images/back-nav-sub-pin.png" alt="" /> <span class="icon-info"></span></a>
						<ul>
							<li><a href="<?php echo $baseDir; ?>/admin/userpolicy">Gebruikersvoorwaarden</a></li>
							<li class="current"><a href="<?php echo $baseDir; ?>/admin/update">Update info aanpassen</a></li>
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

						<div class="tabs">
							<ul>
								<li class="active"><a href="#" rel="tab-01-content">Nederlands</a></li>
								<li class=""><a href="#" rel="tab-02-content">Duits</a></li>
								<li class="last"><a href="#" rel="tab-03-content">Frans</a></li>
							</ul>


							<div style="float:right;width:auto;height:45px;border-left:1px solid rgb(217,217,217);text-align:center;">
								<div style="padding:6px;">
									<button class="button-red" onclick="onClickSave();">Bewaren</button>
								</div>
							</div>

							<div class="headline" style="float:left;padding-left:132px;">
								<!-- <label>Update Title</label>  -->
							</div>
							<div class="headline" style="display:none; float:left;padding-right:15px;">								
								<label>Update Title : </label>
								<input class="datetime" id="update_header" value="" style="width:200px;" placeholder="Update Title">								
							</div>
							
						</div>

                    	<div class="tabs-content" style="padding:20px;">
						<!-- ## Panel Content  -->
						
							<div id="tab-01-content" class="active" >
								<textarea cols="80" id="editor1" name="editor1" rows="10">
									
								</textarea>
								<script>
									editor1 = CKEDITOR.replace( 'editor1', {
										language: 'en',
										toolbarGroups: [
											{ name: 'document',		groups: [ 'mode', 'document' ] },
									 		{ name: 'clipboard',	groups: [ 'clipboard', 'undo' ] },
									 		{ name: 'editing',		groups: [ 'find', 'selection', 'spellchecker' ] },
											{ name: 'insert' },
									 		'/',
											{ name: 'basicstyles',	groups: [ 'basicstyles', 'cleanup' ] },
											{ name: 'paragraph',	groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
											{ name: 'links' },
											'/',
											{ name: 'styles' },
											{ name: 'colors' },
											{ name: 'tools' },
											{ name: 'others' }
										]
									});
								</script>
							</div>
							
							<div id="tab-02-content" class="" >
							
								<textarea cols="80" id="editor2" name="editor2" rows="10">
									
								</textarea>
								<script>
									editor2 = CKEDITOR.replace( 'editor2', {
										language: 'en',
										toolbarGroups: [
											{ name: 'document',		groups: [ 'mode', 'document' ] },
									 		{ name: 'clipboard',	groups: [ 'clipboard', 'undo' ] },
									 		{ name: 'editing',		groups: [ 'find', 'selection', 'spellchecker' ] },
											{ name: 'insert' },
									 		'/',
											{ name: 'basicstyles',	groups: [ 'basicstyles', 'cleanup' ] },
											{ name: 'paragraph',	groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
											{ name: 'links' },
											'/',
											{ name: 'styles' },
											{ name: 'colors' },
											{ name: 'tools' },
											{ name: 'others' }
										]
									});
								</script>
							</div>
							
							<div id="tab-03-content" class="" >
							
								<textarea cols="80" id="editor3" name="editor3" rows="10">
									
								</textarea>
								<script>
									editor3 = CKEDITOR.replace( 'editor3', {
										language: 'en',
										toolbarGroups: [
											{ name: 'document',		groups: [ 'mode', 'document' ] },
									 		{ name: 'clipboard',	groups: [ 'clipboard', 'undo' ] },
									 		{ name: 'editing',		groups: [ 'find', 'selection', 'spellchecker' ] },
											{ name: 'insert' },
									 		'/',
											{ name: 'basicstyles',	groups: [ 'basicstyles', 'cleanup' ] },
											{ name: 'paragraph',	groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
											{ name: 'links' },
											'/',
											{ name: 'styles' },
											{ name: 'colors' },
											{ name: 'tools' },
											{ name: 'others' }
										]
									});
								</script>
							</div>

						<!-- ## / Panel Content  -->
						</div>

						<div class="group fixed">
							
						</div>

						<br/>
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
	