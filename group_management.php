<?php

	function createGroup($data=array()){
		$con = mysqli_connect("localhost","root","root","tnp");
        if (mysqli_connect_errno()){
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        $query = "INSERT INTO groups(group_id, name, description) VALUES('" . $data['group_id'] . "', '" . $data['name'] . "', '" . $data['description']  . "') ON DUPLICATE KEY UPDATE name=VALUES(name), description=VALUES(description), group_id=LAST_INSERT_ID(group_id)";
        $results = mysqli_query($con, $query);
        if(!$results){
          die('Invalid query: ' . mysqli_error($con));
        }

        $id = mysqli_insert_id($con); 

        $new_members = array();
        if(is_array($data['sensor'])){
	        for($x = 0; $x < count($data['sensor']); $x++){
	        	$query = "INSERT INTO group_membership(group_id, sensor_id) VALUES('" . $id . "', '" . $data['sensor'][$x]  . "') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)";
		        $results = mysqli_query($con, $query);
		        if (!$results) {
		          die('Invalid query: ' . mysqli_error($con));
		        }
                array_push($new_members, $data['sensor'][$x]);
	        }
	    }else{
	    	$query = "INSERT INTO group_membership(group_id, sensor_id) VALUES('" . $id . "', '" . $data['sensor'] . "') ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)";
	        $results = mysqli_query($con, $query);
	        if(!$results){	
	           die('Invalid query: ' . mysqli_error($con));
	        }
            array_push($new_members, $data['sensor']);
	    }

        //if the group already exists, remove memebers that are not in the new list
        if(intval($data['group_id']) != 0){
            $query = "SELECT * FROM group_membership WHERE group_id = " . $data['group_id'];
            $results = mysqli_query($con, $query);
            if(!$results) {
                die('Invalid query: ' . mysqli_error($con));
            }

            while($row = @mysqli_fetch_assoc($results)){
                if(!in_array($row['sensor_id'], $new_members)){
                    $query = "DELETE FROM group_membership WHERE group_id = " . $row['group_id'] . " AND sensor_id = " . $row['sensor_id'] . ";";
                    $dresults = mysqli_query($con, $query);
                    if(!$dresults){    
                        die('Invalid query: ' . mysqli_error($con));
                    }
                }
                
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

	if(isset($_POST['Function']) && function_exists($_POST['Function'])){
        call_user_func($_POST['Function'], $_POST['Data']);
    }else{
        echo("Function not set or does not exist");
    }
?>