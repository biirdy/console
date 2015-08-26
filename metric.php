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
		}else if(strcmp($_GET['type'], "dns_failure") == 0){
			$table = "dns_failure";
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
	if(strcmp($_GET['type'], "dns") == 0 || strcmp($_GET['type'], "dns_failure") == 0){
		$query = "SELECT * FROM " . $table . " WHERE sensor_id = " . $_GET['sensor_id']; 
	}else{
		$query = "SELECT * FROM " . $table . " WHERE sensor_id = " . $_GET['sensor_id'] . " AND dst_id = " . $_GET['dst_id'];
	}
	$results = mysqli_query($con, $query);
	if (!$results) {
	  die('Invalid query: ' . mysqli_error());
	}

	$data = array();

	//fill missing data with null
	if(isset($_GET['measurement_id'])){

		$query = "SELECT period FROM schedules WHERE schedule_id = (SELECT schedule_id FROM schedule_measurements WHERE measurement_id = " . $_GET['measurement_id'] . ")";
		$period_res = mysqli_query($con, $query);
		if (!$period_res) {
		  die('Invalid query: ' . mysqli_error());
		}
		$period = @mysqli_fetch_assoc($period_res);
		$period = intval($period['period']);

		$query = "SELECT delay FROM schedule_measurements WHERE measurement_id = " . $_GET['measurement_id']; 
		$delay_res = mysqli_query($con, $query);
		if (!$period_res) {
		  die('Invalid query: ' . mysqli_error());
		}
		$delay = @mysqli_fetch_assoc($delay_res);
		$delay = intval($delay['delay']);
		$interval = $delay + $period;

		$ltime = NULL;
		while ($row = @mysqli_fetch_assoc($results)){

			$curr = new DateTime($row['time']);

			if(is_null($ltime)){
				$ltime = $curr;
			}else if(abs($ltime->getTimestamp() - $curr->getTimestamp()) > $interval + 20){
				$nrow =	$row;
				$nrow['time'] = $curr->format('Y-m-d H:i:s');
				$nrow['defined'] = "none"; 
				$data[] = $nrow;
			}

			$ltime 	= $curr;
			$row['defined'] = 1; 
    		$data[] = $row;
    	}
	}else{
    	while ($row = @mysqli_fetch_assoc($results)){
    		$data[] = $row;
    	}
    }

    echo json_encode($data);

    mysqli_close($con);
?>