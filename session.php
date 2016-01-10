<?php
	// Establishing Connection with Server by passing server_name, user_id and password as a parameter
	$connection = mysqli_connect("localhost", "root", "root", "weperf_users");
	
	session_start();// Starting Session
	// Storing Session
	$user_check=$_SESSION['user_id'];
	// SQL Query To Fetch Complete Information Of User
	$ses_sql=mysqli_query($connection, "select id from users where id='$user_check'");
	$row = mysqli_fetch_assoc($ses_sql);
	$login_session =$row['id'];
	if(!isset($login_session)){
		mysqli_close($connection); // Closing Connection
		header('Location: login.php'); // Redirecting To Home Page
	}
?>