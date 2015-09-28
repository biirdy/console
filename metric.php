<?php
	if(!isset($_GET['measurement_id'])){
		die("measurement_id not set");
	}

	// Opens a connection to a MySQL server
	$con = mysqli_connect("localhost","root","root","tnp");
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	$query = "SELECT * FROM schedule_measurements WHERE measurement_id =" . $_GET['measurement_id'];
	$results = mysqli_query($con, $query);
	if(!$results){
		die('Invalid query1: ' . mysqli_error());
	}
	$measurement = @mysqli_fetch_assoc($results);
	$table = $measurement['method'];

	//dns measurements have no destination
	if(strcmp($table, "dns") == 0)
		$query = "SELECT * FROM " . $table . " WHERE measurement_id =" . $_GET['measurement_id'] . (isset($_GET['src_id']) ? " AND sensor_id =" . $_GET['src_id'] : "");
	else
		$query = "SELECT * FROM " . $table . " WHERE measurement_id =" . $_GET['measurement_id'] . (isset($_GET['src_id']) ? " AND sensor_id =" . $_GET['src_id'] : "" ) . (isset($_GET['dst_id']) ? " AND dst_id =" . $_GET['dst_id'] : "");
	$results = mysqli_query($con, $query);
	if (!$results){
	  die('Invalid query2: ' . $query);
	}

	// Fill missing data will nulls
	$data = array();
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

		//time since last is larger than measurement interval
		}else if(abs($ltime->getTimestamp() - $curr->getTimestamp()) > $interval + 20){
			//create fake point
			$nrow =	$row;
			$nrow['time'] = $curr->format('Y-m-d H:i:s');
			$nrow['defined'] = "none"; 
			$data[] = $nrow;
		}

		//push point
		$ltime 	= $curr;
		$row['defined'] = 1; 
		$data[] = $row;
	}

    echo json_encode($data);

    mysqli_close($con);
?>