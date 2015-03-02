<?php
	
	// Opens a connection to a MySQL server
	$con = mysqli_connect("localhost","root","root","tnp");
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	$query = "UPDATE sensors SET active=false, end='" . date("Y-m-d H:i:s") . "' WHERE sensor_id = " . $_POST['sensor_id'];
	$results = mysqli_query($con, $query);
	if (!$results) {
	  die('Invalid query: ' . mysqli_error($con));
	}

    mysqli_close($con);
?>