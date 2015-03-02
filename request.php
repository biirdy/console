<?php
	if(isset($_POST['request_type'])){
		if(strcmp($_POST['request_type'], 'ping') == 0){
			//should check if all posts are set
			exec('sudo /usr/bin/network-sensor-server-rpc-client ping '. $_POST['sensor_id'] . ' ' . $_POST['rtt_ittr'], $output, $return);
		}else if(strcmp($_POST['request_type'], 'iperf') == 0){
			//should check if all posts are set
			exec('sudo /usr/bin/network-sensor-server-rpc-client iperf '. $_POST['sensor_id'] . ' ' . $_POST['bw_time'], $output, $return);
		}else if(strcmp($_POST['request_type'], 'udp') == 0){
			//should check if all posts are set
			exec('sudo /usr/bin/network-sensor-server-rpc-client udp '. $_POST['sensor_id'] . ' ' . $_POST['udp_speed'] . ' ' . $_POST['udp_size'] . ' ' . $_POST['udp_time'] . ' ' . $_POST['udp_dscp'], $output, $return);
		}else if(strcmp($_POST['request_type'], 'dns') == 0){
			//should check if all posts are set
			exec('sudo /usr/bin/network-sensor-server-rpc-client dns '. $_POST['sensor_id'], $output, $return);
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