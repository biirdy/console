<?php
	// Opens a connection to a MySQL server
	$con = mysqli_connect("localhost","root","root","tnp");
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	// Select all the rows in the markers table
	$bw_query = "SELECT * FROM bw WHERE sensor_id = " . $_GET['sensor_id'];
	$bw_results = mysqli_query($con, $bw_query);
	if (!$bw_results) {
	  die('Invalid query: ' . mysqli_error());
	}

	$data = array();

    while ($row = @mysqli_fetch_assoc($bw_results)){
    	$data[] = $row;
    }

    echo json_encode($data);

    mysqli_close($con);
?>