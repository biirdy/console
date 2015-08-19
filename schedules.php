<?php
	// Opens a connection to a MySQL server
	$con = mysqli_connect("localhost","root","root","tnp");
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	//if sensor_id is set only get individual sensor
	$query = "SELECT * FROM schedules";
	
	$results = mysqli_query($con, $query);
	if (!$results) {
	  die('Invalid query: ' . mysqli_error());
	}

	$data = array();

    while ($schedule = @mysqli_fetch_assoc($results)){

    	$query = "SELECT * FROM schedule_measurements WHERE schedule_id = " . $schedule['schedule_id'];
    	$m_results = mysqli_query($con, $query);
		if (!$m_results) {
			die('Invalid query: ' . mysqli_error());
		}

		$schedule['measurements'] 	= array();
		$schedule['active'] 		= 0;
		$schedule['faults']			= 0; 
		while($measurement = @mysqli_fetch_assoc($m_results)){

			$query = "SELECT * FROM schedule_params WHERE measurement_id = " . $measurement['measurement_id'];
			$p_results = mysqli_query($con, $query);
			if (!$p_results) {
				die('Invalid query: ' . mysqli_error());
			}

			$measurement['params'] = array();
			while($param = @mysqli_fetch_assoc($p_results)){
				array_push($measurement['params'], $param);
			}

			//count number of active measurements and fualts
			if(intval($measurement['active']) == 1){
				$schedule['active'] += 1;
			}
			if(intval($measurement['status']) != 1){
				$schedule['faults'] += 1;
			}

			//get source name
			if(intval($measurement['source_type']) == 1){
				$query = "SELECT name FROM groups WHERE group_id = " . $measurement['source_id'];
			}else{
				$query = "SELECT description AS name FROM sensors WHERE sensor_id = " . $measurement['source_id'];
			}
			$src_results = mysqli_query($con, $query);
			if (!$src_results) {
				die('Invalid query: ' . mysqli_error());
			}
			$row = @mysqli_fetch_assoc($src_results);
			$measurement['source_name'] = $row['name'];

			//get destination name
			if($measurement['destination_type'] == 1)
				$query = "SELECT name FROM groups WHERE group_id = " . $measurement['destination_id'];
			else
				$query = "SELECT description AS name FROM sensors WHERE sensor_id = " . $measurement['destination_id'];
			$dst_results = mysqli_query($con, $query);
			if (!$dst_results) {
				die('Invalid query: ' . mysqli_error());
			}
			$row = @mysqli_fetch_assoc($dst_results);
			$measurement['destination_name'] = $row['name'];

			array_push($schedule['measurements'], $measurement);
		}

		//set overall active based on count
		if($schedule['active'] == count($schedule['measurements'])){
			$schedule['active'] = 1;
		}else{
			$schedule['active'] = 0;
		}

    	$data[] = $schedule;
    }

    echo json_encode($data);

    mysqli_close($con);
?>