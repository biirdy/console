<?php

	function createGroup($data=array()){
		$con = mysqli_connect("localhost","root","root","tnp");
        if (mysqli_connect_errno()){
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        $query = "INSERT INTO groups(name, description, num_sensors) VALUES('" . $data['name'] . "', '" . $data['description']  . "', '" . count($data['sensor']) . "')";
        $results = mysqli_query($con, $query);
        if (!$results) {
          die('Invalid query: ' . mysqli_error($con));
        }

        $id = mysqli_insert_id($con); 

        if(is_array($data['sensor'])){
	        for($x = 0; $x < count($data['sensor']); $x++){
	        	$query = "INSERT INTO group_membership(group_id, sensor_id) VALUES('" . $id . "', '" . $data['sensor'][$x]  . "')";
		        $results = mysqli_query($con, $query);
		        if (!$results) {
		        	echo $query;	
		          die('Invalid query: ' . mysqli_error($con));
		        }
	        }
	    }else{
	    	$query = "INSERT INTO group_membership(group_id, sensor_id) VALUES('" . $id . "', '" . $data['sensor'] . "')";
	        $results = mysqli_query($con, $query);
	        if (!$results) {
	        	echo $query;	
	          die('Invalid query: ' . mysqli_error($con));
	        }
	    }

        echo $id;	
	}

	function deleteGroup($data=array()){
		$con = mysqli_connect("localhost","root","root","tnp");
        if (mysqli_connect_errno()){
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        $query = "DELETE FROM group_membership WHERE group_id = " . $data['gid'];
        $results = mysqli_query($con, $query);
        if (!$results) {
          die('Invalid query: ' . mysqli_error($con));
        }

        $query = "DELETE FROM groups WHERE group_id = " . $data['gid'];
        $results = mysqli_query($con, $query);
        if (!$results) {
          die('Invalid query: ' . mysqli_error($con));
        }

        mysqli_close($con);

        echo 1;
	}

	//remove sensor from gorup
	function removeGroup($data=array()){
		$con = mysqli_connect("localhost","root","root","tnp");
        if (mysqli_connect_errno()){
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        $query = "DELETE FROM group_membership WHERE sensor_id = " . $data['sid'] . " AND  group_id = " . $data['gid'];
        $results = mysqli_query($con, $query);
        if (!$results) {
          die('Invalid query: ' . mysqli_error($con));
        }

        mysqli_close($con);

        echo 1;
	}

	if(isset($_POST['Function'])){
        call_user_func($_POST['Function'], $_POST['Data']);
    }else{
        echo("No such function");
    }
?>