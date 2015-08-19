<?php
    function get_schedule_sensors($sid){

    	$con = mysqli_connect("localhost","root","root","tnp");
		if (mysqli_connect_errno()){
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}

		$ids = array();

		$query = "SELECT * FROM schedule_measurements WHERE schedule_id = " . $sid;
		$results = mysqli_query($con, $query);
		if (!$results) {
		  die('Invalid query: ' . mysqli_error());
		}

		while($measurement = @mysqli_fetch_assoc($results)){

			//source
			//single sensor
			if(intval($measurement['source_type']) == 0){
				array_push($ids, intval($measurement['source_id']));
			//group
			}else if(intval($measurement['source_type']) == 1){

				$group_query 	= "SELECT sensor_id FROM group_membership WHERE group_id = " . $measurement['source_id'];
				$group_results 	= mysqli_query($con, $query);
				if (!$group_results) {
					die('Invalid query: ' . mysqli_error());
				}

				while($group = @mysqli_fetch_assoc($group_results)){
					array_push($ids, intval($group['sensor_id']));
				}
			}

			//destination
			//single sensor
			if(intval($measurement['destination_type']) == 0){
				array_push($ids, intval($measurement['destination_id']));
			//group
			}else if(intval($measurement['destination_type']) == 1){

				$group_query 	= "SELECT sensor_id FROM group_membership WHERE group_id = " . $measurement['destination_id'];
				$group_results 	= mysqli_query($con, $query);
				if (!$group_results) {
					die('Invalid query: ' . mysqli_error());
				}

				while($group = @mysqli_fetch_assoc($group_results)){
					array_push($ids, intval($group['sensor_id']));
				}
			}

		}
		return array_unique($ids);
    }

    $con = mysqli_connect("localhost","root","root","tnp");
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	$column = "";
	$table 	= "";

	if(isset($_GET['feature'])){
		if(strcmp($_GET['feature'], "feature-bw") == 0){
			$column = "speed";
			$table 	= "bw";
		}elseif(strcmp($_GET['feature'], "feature-rtt") == 0){
			$column = "avg";
			$table 	= "rtts";
		}elseif(strcmp($_GET['feature'], "feature-jitter") == 0){
			$column = "jitter";
			$table 	= "udps";
		}elseif(strcmp($_GET['feature'], "feature-packetloss") == 0){
			$column = "packet_loss";
			$table 	= "udps";
		}else{
			die("Invalid feature");
		}
	}else{
		die("No feature set");
	}

	$filter_query 	= "";

	if(isset($_GET['filter_type']) && isset($_GET['filter_id'])){
		if(strcmp($_GET['filter_type'], "group") == 0){
			$filter_query 	= " WHERE sensor_id IN (SELECT sensor_id FROM group_membership WHERE group_id = " . $_GET['filter_id'] . ") AND dst_id IN (SELECT sensor_id FROM group_membership WHERE group_id = " . $_GET['filter_id'] . ") "; 
		}else if(strcmp($_GET['filter_type'], "schedule") == 0){
			$ids 			= join(',', get_schedule_sensors($_GET['filter_id']));
			$filter_query  	= " WHERE sensor_id IN ($ids) AND dst_id IN ($ids)";
		}
	}

	$query = "SELECT * FROM (SELECT sensor_id, dst_id, " . $column . " as feature FROM " . $table .  " ORDER BY time DESC) AS " . $column . $filter_query . " GROUP BY sensor_id, dst_id";	

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