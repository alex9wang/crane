<?php
	function get_new_filename($directory, $filename)
	{
		$newfn = $filename;
		$i = 1;
		for (;;) {
			if (file_exists($directory . $newfn)) {
				$len = strlen($filename);
				$newfn = substr($filename, 0, $len - 4);
				$newfn = $newfn . "[" . $i . "]";
				$newfn = $newfn . substr($filename, $len - 4);
				$i = $i + 1;
				continue;
			}
			break;
		}
		return $newfn;
	}
	// Generate filename
	$filename 	= md5(mt_rand()) . '.jpg';
	$filename 	= $_GET['filename'];
	$crane_type = $_GET['crane_type'];
	$crane_id 	= $_GET['crane_id'];
	$type = $_GET['type'];
	
	$filename 	= get_new_filename('../upload_pdf/', $filename);

	// Read RAW data
	$data = file_get_contents('php://input');

	// Read string as an image file
	$pdf = file_get_contents('data://' . substr($data, 5));

	// Save to disk
	if ( ! file_put_contents('../upload_pdf/' . $filename, $pdf)) {
		header('HTTP/1.1 503 Service Unavailable');
		exit();
	}

	// Clean up memory
	unset($data);
	unset($pdf);

	if ($crane_type > 0 && $crane_id > 0) {
		// insert member_photo
		$username = "root";
		$password = "";
		$database = "craneapp";
			
		// Opens a connection to a mySQL server
		$connection = mysqli_connect ("localhost", $username, $password);
		if (!$connection) { die("Not connected : " . mysqli_error()); }

		// Set the active mySQL database
		$db_selected = mysqli_select_db($connection, $database);
		if (!$db_selected) { die ("Can\'t use db : " . mysqli_error()); }
		
		$query = "SELECT * FROM cr_manual WHERE CraneType = '$crane_type' AND CraneID = '$crane_id' LIMIT 1, 1";
		$query = mysqli_query($connection, $query);
		
		$date = date('Y-m-d h:i:s');
		$pdffile = '/www/upload_pdf/' . $filename;

		if($query->num_rows > 0)
		{	
			//unlink($pdffile);		
			if($type == 1)
				$query = "UPDATE cr_specification SET Specification = '$pdffile' WHERE CraneType = $crane_type AND CraneID = $crane_id";
			else if($type ==  2)
				$query = "UPDATE cr_manual SET Manual = '$pdffile' WHERE CraneType = $crane_type AND CraneID = $crane_id";			
		}
		else
		{
			if($type == 1)
				$query = "INSERT INTO cr_specification (CraneType, CraneID, Specification) VALUES('$crane_type', '$crane_id', '$pdffile')";
			else if($type == 2)
				$query = "INSERT INTO cr_manual (CraneType, CraneID, Manual) VALUES('$crane_type', '$crane_id', '$pdffile')";
			
		}

		mysqli_query($connection, $query);
	}
	// Return file URL
	echo $filename;
?>