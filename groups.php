<?php
	// Opens a connection to a MySQL server
	$con = mysqli_connect("localhost","root","root","tnp");
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	//if sensor_id is set only get individual sensor
	$query = "SELECT * FROM groups";
	
	$results = mysqli_query($con, $query);
	if (!$results) {
	  die('Invalid query: ' . mysqli_error());
	}

	$data = array();

    while ($row = @mysqli_fetch_assoc($results)){
    	$query = "SELECT sensor_id FROM group_membership WHERE group_id = '" . $row['group_id'] . "'";
    	$mem_results = mysqli_query($con, $query);
		if (!$mem_results) {
	  		die('Invalid query: ' . mysqli_error());
		}

		$row['sensors'] = array();
		$active = 0;

		while($mem_row = @mysqli_fetch_assoc($mem_results)){
			$query = "SELECT * FROM sensors WHERE sensor_id = '" . $mem_row['sensor_id'] . "'";
			$sen_results = mysqli_query($con, $query);
			if (!$sen_results) {
		  		die('Invalid query: ' . mysqli_error());
			}
			$sensor = @mysqli_fetch_assoc($sen_results);

			//count number of active sensor
			if(intval($sensor['active']) == 1){
				$active ++;
			}

			array_push($row['sensors'], $sensor);
		}

		if($active == intval($row['num_sensors'])){
			$row['status'] = 0;		// All active
		}elseif($active == 0){
			$row['status'] = 2;		// All inactive
		}else{
			$row['status'] = 1;		// Some active
		}

    	$data[] = $row;
    }

    echo json_encode($data);

    mysqli_close($con);
?>