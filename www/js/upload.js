
var g_filename = "";
var g_filename_spec = "";
var g_filename_manual = "";
var g_crane_type = "";
var g_crane_id = ""
// Once files have been selected

document.querySelector('input[type=file]#upload_image').addEventListener('change', function(event) {
	
	// var email = $('#donate_email').val();
	// if(email == "")
	// {
	// 	alert("Please fill out donate email address");
	// 	return;
	// }
	email = "donate@kraantabel.com";

// Read files
	var files = event.target.files;
	
	// Iterate through files
	for (var i = 0; i < files.length; i++) {

		// Ensure it's an image
		if (files[i].type.match(/image.*/)) {
		
			g_filename = files[i]["name"];

			// Load image
			var reader = new FileReader();
			reader.onload = function (readerEvent) {
				var image = new Image();
				image.onload = function (imageEvent) {

					// Resize image
					var canvas = document.createElement('canvas'),
						max_size = 1200,
						width = image.width,
						height = image.height;
					if (width > height) {
						if (width > max_size) {
							height *= max_size / width;
							width = max_size;
						}
					} else {
						if (height > max_size) {
							width *= max_size / height;
							height = max_size;
						}
					}
					canvas.width = width;
					canvas.height = height;
					canvas.getContext('2d').drawImage(image, 0, 0, width, height);

					// Upload image
					var xhr = new XMLHttpRequest();
					if (xhr.upload) {
						$('.preview').css("display", "block");
						$('.preview').spin();
						// Update progress
						xhr.upload.addEventListener('progress', function(event) {
							
						}, false);

						// File uploaded / failed
						xhr.onreadystatechange = function(event) {
							if (xhr.readyState == 4) {
								$('.preview').spin(false);
								$('.preview').css("display", "none");
								if (xhr.status == 200) {
									
									g_filename = xhr.responseText;
									g_filename = g_filename.trim();
									// get photo elements
									var children = document.querySelectorAll("div.gallery-photo");
									var count = children.length;
									
									// insert photo to gallery-element
									for (var i = 0; i < count; i++) {										
										children[i].style.backgroundImage = "url('/crane/www/upload/" + g_filename + "')";
										
										var el = document.getElementById('main-photo').firstChild.nextSibling;
										el.style.backgroundImage = "url('/crane/www/upload/" + g_filename + "')";
									}

								} else {
								
									$('.preview').spin(false);
									$('.preview').css("display", "none");
									alert("Upload Failed. Please try again.");
								}
							}
						}

						// Start upload
						xhr.open("post", "../www/include/process.php?filename=" + g_filename + "&crane_type=" + g_crane_type + "&crane_id=" + g_crane_id + "&email=" + email , true);
						//xhr.open("post", "process.php" , true);
						xhr.send(canvas.toDataURL('image/jpeg'));

					}

				}

				image.src = readerEvent.target.result;

			}
			reader.readAsDataURL(files[i]);
		}

	}

	// Clear files
	event.target.value = '';
});

document.querySelector('input[type=file]#upload_manual').addEventListener('change', function(event) {
	
// Read files
	var files = event.target.files;
	
	// Iterate through files
	for (var i = 0; i < files.length; i++) {
		
		// Ensure it's an image
//		if (files[i].type.match(/application.pdf/)) 
        {
			
			g_filename = files[i]["name"];
			
			// Load image
			var reader = new FileReader();
			reader.onload = function (readerEvent) {				
				
				// Upload image
				var xhr = new XMLHttpRequest();
				if (xhr.upload) {
					$('.preview').css("display", "block");
					$('.preview').spin();
					// Update progress
					xhr.upload.addEventListener('progress', function(event) {
						var percent = parseInt(event.loaded / event.total * 100);
/*							progressElement.style.width = percent+'%';*/
					}, false);

					// File uploaded / failed
					xhr.onreadystatechange = function(event) {
						if (xhr.readyState == 4) {
							if (xhr.status == 200) {
								$('.preview').spin(false);
								$('.preview').css("display", "none");
								$('input[hidden]#manual_hidden').val(xhr.responseText);
								alert("Upload Successful!");								
							} else {
								$('.preview').spin(false);
								$('.preview').css("display", "none");
								alert("Save Failed. Please try again");
							}
						}
					}

					// Start upload
					xhr.open("post", "../www/include/process_pdf.php?filename=" + g_filename + "&crane_type=" + g_crane_type + "&crane_id=" + g_crane_id + "&type=2" , true);
					//xhr.open("post", "process.php" , true);
					xhr.send(readerEvent.target.result);
				}
			}
			reader.readAsDataURL(files[i]);
		}

	}

	// Clear files
	event.target.value = '';
});

document.querySelector('input[type=file]#upload_spec').addEventListener('change', function(event) {
	
// Read files
	var files = event.target.files;
	
	// Iterate through files
	for (var i = 0; i < files.length; i++) {
		
		// Ensure it's an image
//		if (files[i].type.match(/application.pdf/)) 
        {
			
			g_filename = files[i]["name"];

			// Load image
			var reader = new FileReader();
			reader.onload = function (readerEvent) {				
				
				// Upload image
				var xhr = new XMLHttpRequest();
				if (xhr.upload) {
					$('.preview').css("display", "block");
					$('.preview').spin();
					// Update progress
					xhr.upload.addEventListener('progress', function(event) {
						var percent = parseInt(event.loaded / event.total * 100);
/*							progressElement.style.width = percent+'%';*/
					}, false);

					// File uploaded / failed
					xhr.onreadystatechange = function(event) {
						if (xhr.readyState == 4) {
							if (xhr.status == 200) {
								$('.preview').spin(false);
								$('.preview').css("display", "none");
								$('input[hidden]#spec_hidden').val(xhr.responseText);
								alert("Upload Successful!");								
							} else {
								$('.preview').spin(false);
								$('.preview').css("display", "none");
								alert("Upload Failed. Please try again");
							}
						}
					}

					// Start upload
					xhr.open("post", "../www/include/process_pdf.php?filename=" + g_filename + "&crane_type=" + g_crane_type + "&crane_id=" + g_crane_id + "&type=1" , true);
					//xhr.open("post", "process.php" , true);
					xhr.send(readerEvent.target.result);
				}
			}
			reader.readAsDataURL(files[i]);
		}

	}

	// Clear files
	event.target.value = '';
});