<?php
	if(isset($_POST['request_type'])){
		if(strcmp($_POST['request_type'], 'ping') == 0){
			//should check if all posts are set
			exec('sudo /home/ubuntu/server/rpc_request ping '. $_POST['sensor_id'] . ' ' . $_POST['rtt_ittr'], $output, $return);
		}else if(strcmp($_POST['request_type'], 'iperf') == 0){
			//should check if all posts are set
			exec('sudo /home/ubuntu/server/rpc_request iperf '. $_POST['sensor_id'] . ' ' . $_POST['bw_time'], $output, $return);
		}else if(strcmp($_POST['request_type'], 'udp') == 0){
			//should check if all posts are set
			exec('sudo /home/ubuntu/server/rpc_request udp '. $_POST['sensor_id'] . ' ' . $_POST['udp_speed'] . ' ' . $_POST['udp_size'] . ' ' . $_POST['udp_time'] . ' ' . $_POST['udp_dscp'], $output, $return);
		}else if(strcmp($_POST['request_type'], 'dns') == 0){
			//should check if all posts are set
			///
		}else{
			echo("Unknown request type");
			exit;
		}
		foreach ($output as $value) {
       		echo($value."\n");
        }
	}else{
		echo("Missing request type");
	}
	exit;
?>