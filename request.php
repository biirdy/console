<?php

	function do_call($request) {
      
        $url = "http://localhost:8080/RPC2";
        $header[] = "Content-type: text/xml";
        $header[] = "Content-length: ".strlen($request);

        $ch = curl_init();   

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        
        $data = curl_exec($ch);       
        if (curl_errno($ch)) {
            print curl_error($ch);
        } else {
            curl_close($ch);
            return $data;
        }
    }

	if(isset($_POST['request_type'])){

		$request = ''; 

		if(strcmp($_POST['request_type'], 'ping') == 0){
			//should check if all posts are set
			//exec('sudo /usr/bin/network-sensor-server-rpc-client ping '. $_POST['sensor_id'] . ' ' . $_POST['rtt_ittr'], $output, $return);
			$request = xmlrpc_encode_request('ping.request', array(intval($_POST['sensor_id']), intval($_POST['dst_id']), intval($_POST['rtt_ittr'])));
		}else if(strcmp($_POST['request_type'], 'iperf') == 0){
			//should check if all posts are set
			//exec('sudo /usr/bin/network-sensor-server-rpc-client iperf '. $_POST['sensor_id'] . ' ' . $_POST['bw_time'], $output, $return);
			$request = xmlrpc_encode_request('iperf.request', array(intval($_POST['sensor_id']), intval($_POST['dst_id']), intval($_POST['bw_time'])));
		}else if(strcmp($_POST['request_type'], 'udp') == 0){
			//should check if all posts are set
			//exec('sudo /usr/bin/network-sensor-server-rpc-client udp '. $_POST['sensor_id'] . ' ' . $_POST['udp_speed'] . ' ' . $_POST['udp_size'] . ' ' . $_POST['udp_time'] . ' ' . $_POST['udp_dscp'], $output, $return);
			$request = xmlrpc_encode_request('udp.request', array(intval($_POST['sensor_id']), intval($_POST['dst_id']), intval($_POST['udp_speed']), intval($_POST['udp_size']), intval($_POST['udp_time']), intval($_POST['udp_dscp'])));
		}else if(strcmp($_POST['request_type'], 'dns') == 0){
			//should check if all posts are set
			//exec('sudo /usr/bin/network-sensor-server-rpc-client dns '. $_POST['sensor_id'], $output, $return);
			$request = xmlrpc_encode_request('ping.request', array(intval($_POST['sensor_id'])));
		}else{
			echo("Unknown request type");
			exit;
		}
		/*foreach ($output as $value) {
       		echo($value."\n");
        }*/
        
        $response = do_call($request);
    	echo $response;

	}else{
		echo("Missing request type");
	}
	exit;
?>