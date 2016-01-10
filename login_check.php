<?php
	session_start(); // Starting Session
	$error=''; // Variable To Store Error Message

	if (isset($_POST['submit'])) {
		if (empty($_POST['username']) || empty($_POST['password'])) {
			$error = "Username or Password is invalid";
		}else{
			// Define $username and $password
			$username=$_POST['username'];
			$password=$_POST['password'];
			// Establishing Connection with Server by passing server_name, user_id and password as a parameter
			$connection = mysqli_connect("localhost", "root", "root", "weperf_users");
			// To protect MySQL injection for Security purpose
			$username = stripslashes($username);
			$password = stripslashes($password);
			$username = mysqli_real_escape_string($connection, $username);
			$password = mysqli_real_escape_string($connection, $password);
		
			// SQL query to fetch information of registerd users and finds user match.
			$query = mysqli_query($connection, "select * from users where password='$password' AND username='$username'");
			$rows = mysqli_num_rows($query);

			if ($rows == 1) {

				$user = @mysqli_fetch_assoc($query);
				
				// Initializing session
				$_SESSION['user_id'] = $user['id'];
				$_SESSION['user_admin'] = $user['admin'];

				// Redirecting to Requested page	
				header("location: index.php"); 		
			} else {
				$error = "Username or Password is incorrect";
			}
			mysqli_close($connection); // Closing Connection
		}
	}
?>