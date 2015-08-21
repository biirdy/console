<?php

	if(!isset($_GET['sensor_id']) && !isset($_GET['dst_id'])){
		die("sensor_id or dst_id not set");
	}
	
	if(isset($_GET['type'])){
		if(strcmp($_GET['type'], "rtt") == 0){
			$table = "rtts";
		}else if(strcmp($_GET['type'], "tcp") == 0){
			$table = "bw";
		}else if(strcmp($_GET['type'], "udp") == 0){
			$table = "udps";
		}else if(strcmp($_GET['type'], "dns") == 0){
			$table = "dns";
		}else{
			die("Unrecognised type");
		}
	}else{
		die("No type set.");
	}

	// Opens a connection to a MySQL server
	$con = mysqli_connect("localhost","root","root","tnp");
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	// Select all the rows in the markers table
	$query = "SELECT * FROM " . $table . " WHERE sensor_id = " . $_GET['sensor_id'] . " AND dst_id = " . $_GET['dst_id'];
	$results = mysqli_query($con, $query);
	if (!$results) {
	  die('Invalid query: ' . mysqli_error());
	}

	$data = array();

    while ($row = @mysqli_fetch_assoc($results)){
    	$data[] = $row;
    }

    echo json_encode($data);

    mysqli_close($con);
?>