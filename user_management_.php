<?php

	include("session.php");

	function addUser($data=array()){

		if($_SESSION['user_admin'] != '1'){
			echo "Non admin user cannot create users";
			return;
		}

		$con = mysqli_connect("localhost","root","root","weperf_users");
		if (mysqli_connect_errno()){
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }        

		//update existing user
		if($data['user-id']){

			//update password
			if($data['user-pass']){

				if($data['user-pass'] != $data['user-pass-cnf']){
					die("Passwords do not match");
				}

				$query = "INSERT INTO users(id, username, email, password, admin) VALUES('" . $data['user-id'] . "', '" . $data['user-name'] . "', '" . $data['user-email'] . "', '" . $data['user-pass'] . "', '" . (isset($data['user-admin']) ? "1" : "0") . "') ON DUPLICATE KEY UPDATE username=VALUES(username), email=VALUES(email), admin=VALUES(admin), password=VALUES(password), id=LAST_INSERT_ID(id)";
			
			//no password update
			}else{
				$query = "INSERT INTO users(id, username, email, admin) VALUES('" . $data['user-id'] . "', '" . $data['user-name'] . "', '" . $data['user-email'] . "', '" . (isset($data['user-admin']) ? "1" : "0") . "') ON DUPLICATE KEY UPDATE username=VALUES(username), email=VALUES(email), admin=VALUES(admin), id=LAST_INSERT_ID(id)";
			}
		
		//create new user
		}else{

			if(!isset($data['user-name']) || !isset($data['user-email']) || !isset($data['user-pass']) || !isset($data['user-pass-cnf'])){
				die("Missing values");
			}

			if($data['user-pass'] != $data['user-pass-cnf']){
				die("Passwords do not match");
			}

			$query = "INSERT INTO users(username, email, password, admin) VALUES('" . $data['user-name'] . "', '" . $data['user-email'] . "', '" . $data['user-pass'] . "', '" . (isset($data['user-admin']) ? "1" : "0") . "')";
		}

		$results = mysqli_query($con, $query);
		if(!$results){	
			die('Invalid query: ' . mysqli_error($con));
		}

		echo(mysqli_insert_id($con));

		mysqli_close($con);
	}

	function deleteUser($data=array()){

		if($_SESSION['user_admin'] != '1' && $data['id'] != $_SESSION['user_id']){
			echo "Non admin user cannot delete another user";
			return;
		}

		$con = mysqli_connect("localhost","root","root","weperf_users");
		if (mysqli_connect_errno()){
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        //check if admin user
       	$query = "SELECT admin FROM users WHERE id='" . $data['id'] . "'";
       	$results = mysqli_query($con, $query);
        if (!$results) {
			die('Invalid query: ' . mysqli_error($con));
        }
        $admin = @mysqli_fetch_assoc($results)['admin'];

        

        if(strcmp($admin, "1") == 0){

        	//get number of admin users remaining
	        $query = "SELECT id FROM users WHERE admin=1";
	        $results = mysqli_query($con, $query);
	        if (!$results) {
				die('Invalid query: ' . mysqli_error($con));
	        }
	        $rows = mysqli_num_rows($results);

	        //check if removing last admin user
	        if($rows == 1){
	        	echo "Cannot remove last admin user";
	        	mysqli_close($con);
	        	return;
	        }
        }

        $query = "DELETE FROM users WHERE id=" . $data['id'];
        $results = mysqli_query($con, $query);
        if (!$results) {
			die('Invalid query: ' . mysqli_error($con));
        }

        mysqli_close($con);

        echo 1;
	}

	if(isset($_POST['Function']) && function_exists($_POST['Function'])){
		call_user_func($_POST['Function'], $_POST['Data']);
	}else{
		die("Function not set or does not exist");
	}

?>