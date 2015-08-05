<?php

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

	/*if(isset($_POST['filter']) && isset($_POST['filter_id'])){
		if(strcmp($_POST['filter'], "group")){
			//$where = "WHERE gid =" . $_POST['id'];

			//get list of sensors in group
			//where sensor_id and recipient_id in...

		}elseif(strcmp($_POST['filter'], "schedule")){
			//$where = "WHERE sid =" . $_POST['id'];

			//get list of sensors in schedule
			//where sensor_id and recipient_id in...
			//probably going to have to put sid in results tables

		}else{
			die("Invalid filter")
		}
	}else{
		//$where = '';
	}*/



	$query = "SELECT * FROM (SELECT sensor_id, dst_id, " . $column . " as feature FROM " . $table .  " ORDER BY time DESC) AS " . $column . " GROUP BY sensor_id, dst_id";	

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