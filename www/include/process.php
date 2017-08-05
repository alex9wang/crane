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
	//$filename = md5(mt_rand()) . '.jpg';
	$filename = $_GET['filename'];
	$crane_type = $_GET['crane_type'];
	$crane_id = $_GET['crane_id'];
	$email = $_GET['email'];
	$filename = get_new_filename('../upload/', $filename);
    
    
    

	// Read RAW data
	$data = file_get_contents('php://input');

	// Read string as an image file
	$image = file_get_contents('data://' . substr($data, 5));

	// Save to disk
	if ( ! file_put_contents('../upload/' . $filename, $image)) {
		header('HTTP/1.1 503 Service Unavailable');
		exit();
	}

	// Clean up memory
	unset($data);
	unset($image);

	if ($crane_type > 0 && $crane_id > 0) {
		// insert member_photo
		$username = "tableaud";
        $password = "JO22015kole";
        $database = "tableaud-2";
		// $username = "root";
		// $password = "";
		// $database = "tableaud-2";
        
        //print_r("test1");
			
		// Opens a connection to a mySQL server
		$connection = mysqli_connect ("localhost", $username, $password);
		if (!$connection) { die("Not connected : " . mysqli_error()); }

		// Set the active mySQL database
		$db_selected = mysqli_select_db($connection, $database);
		if (!$db_selected) { die ("Can\'t use db : " . mysqli_error()); }
        
        //print_r($crane_type);
        //print_r($crane_id);
		
		$query = "SELECT * FROM cr_photo WHERE CraneType = '$crane_type' AND CraneID = '$crane_id' LIMIT 1";
		$query = mysqli_query($connection, $query);
		
		date_default_timezone_set("Europe/Amsterdam");
		$date = date('Y/m/d H:i:s');
		$photofile = '/www/upload/' . $filename;

		// if($query->num_rows > 0)
		// {			
		// 	$query = "UPDATE cr_photo SET Photo = '$photofile', DonateDate = '$date', Email = '$email' WHERE CraneType = $crane_type AND CraneID = $crane_id";
		// 	mysqli_query($connection, $query);
		// }
		// else
		{
			$query = "INSERT INTO cr_photo (CraneType, CraneID, Photo, DonateDate, Email) VALUES('$crane_type', '$crane_id', '$photofile', '$date', '$email')";
			mysqli_query($connection, $query);
		}
		
	}
	// Return file URL
	echo $filename;
?>