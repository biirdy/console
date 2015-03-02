<?php
	
	// Opens a connection to a MySQL server
	$con = mysqli_connect("localhost","root","root","tnp");
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	$query = "SELECT description FROM sensors WHERE sensor_id = " . $_GET['sensor_id'];

	$results = mysqli_query($con, $query);
	if (!$results) {
	  die('Invalid query: ' . mysqli_error($con));
	}

	$data = array();

    while ($row = @mysqli_fetch_assoc($results)){
    	echo $row['description'];
    }

    mysqli_close($con);
?>