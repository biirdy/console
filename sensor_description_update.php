<?php
	
	// Opens a connection to a MySQL server
	$con = mysqli_connect("localhost","root","root","tnp");
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	$query = "UPDATE sensors SET description='" . $_POST['description'] . "' WHERE sensor_id = " . $_POST['sensor_id'];
	echo $query;
	$results = mysqli_query($con, $query);
	if (!$results) {
	  die('Invalid query: ' . mysqli_error($con));
	}

    mysqli_close($con);
?>