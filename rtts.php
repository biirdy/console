<?php
	// Opens a connection to a MySQL server
	$con = mysqli_connect("localhost","root","root","tnp");
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	// Select all the rows in the markers table
	$rtt_query = "SELECT * FROM rtts WHERE sensor_id = " . $_GET['sensor_id'];
	$rtt_results = mysqli_query($con, $rtt_query);
	if (!$rtt_results) {
	  die('Invalid query: ' . mysqli_error());
	}

	$data = array();

    while ($row = @mysqli_fetch_assoc($rtt_results)){
    	$data[] = $row;
    }

    echo json_encode($data);

    mysqli_close($con);
?>