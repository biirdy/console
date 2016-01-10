<?php

	include("session.php");

	// Opens a connection to a MySQL server
	$con = mysqli_connect("localhost","root","root","weperf_users");
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	if($_SESSION['user_admin'] == '1'){
		$query = "SELECT id, username, email, admin FROM users";
	}else{
		$query = "SELECT id, username, email, admin FROM users WHERE id='" . $_SESSION['user_id'] . "'";
	}

	$results = mysqli_query($con, $query);
	if (!$results) {
	  die('Invalid query: ' . mysqli_error());
	}

	$data = array();

	while($row = @mysqli_fetch_assoc($results)){
		$data[] = $row;
	}

	echo json_encode($data);

	mysqli_close($con);
?>