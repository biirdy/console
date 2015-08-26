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
				$group_results 	= mysqli_query($con, $group_query);
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

	// Opens a connection to a MySQL server
	$con = mysqli_connect("localhost","root","root","tnp");
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	//if sensor_id is set only get individual sensor
	$query = '';
	if(isset($_GET['sensor_id'])){
		$query = "SELECT * FROM sensors WHERE sensor_id = " . $_GET['sensor_id'];
	}else if(isset($_GET['filter_type']) && isset($_GET['filter_id'])){
		if(strcmp($_GET['filter_type'], "group") == 0){
			$query = "SELECT * FROM sensors WHERE sensor_id IN (SELECT sensor_id FROM group_membership WHERE group_id = " . $_GET['filter_id'] . ")";	
		}else if(strcmp($_GET['filter_type'], "schedule") == 0){
			$ids 	= join(',', get_schedule_sensors($_GET['filter_id']));
			$query 	= "SELECT * FROM sensors WHERE sensor_id IN ($ids)";
		}else{
			die("Invalid filter type");
		}
	}else{
		$query = "SELECT * FROM sensors";
	}
	
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