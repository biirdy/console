<?php

    function do_call($request) {
      
        $url = "http://localhost:8081/RPC2";
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

    function createSchedule($data=array()){
        $request = '';
        $result = 0;

        $seconds = intval($data['hours']) * 60 * 60;
        $seconds = $seconds + (intval($data['minutes']) * 60);
        $seconds = $seconds + intval($data['seconds']);

        //add description to db
        $con = mysqli_connect("localhost","root","root","tnp");
        if (mysqli_connect_errno()){
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        $query = "INSERT INTO schedules(name, description, period) VALUES('" . $data['name'] . "', '" . $data['description'] . "', '" . $seconds . "')";
        $results = mysqli_query($con, $query);
        if (!$results) {
          die('Invalid query: ' . mysqli_error($con));
        }

        $id = mysqli_insert_id($con); 

        for($x = 0; $x < count($data['measurement']); $x++){
            $measurement = $data['measurement'][$x];

            $delay_seconds = intval($measurement['delay-hours']) * 60 * 60;
            $delay_seconds = $delay_seconds + (intval($measurement['delay-minutes']) * 60);
            $delay_seconds = $delay_seconds + intval($measurement['delay-seconds']);

            if(strcmp($measurement['type-radio'], 'rtt') == 0){
                $request = xmlrpc_encode_request('add_rtt_schedule.request', array(0, intval($id), intval($measurement['source']), intval($measurement['source-type']), intval($measurement['destination']), intval($measurement['destination-type']), intval($measurement['rtt-details-itr']), $seconds, $delay_seconds));
            }elseif(strcmp($measurement['type-radio'], 'tcp') == 0){
                $request = xmlrpc_encode_request('add_tcp_schedule.request', array(0, intval($id), intval($measurement['source']), intval($measurement['source-type']), intval($measurement['destination']), intval($measurement['destination-type']), intval($measurement['tcp-details-dur']), $seconds, $delay_seconds));
            }elseif(strcmp($measurement['type-radio'], 'udp') == 0){
                $request = xmlrpc_encode_request('add_udp_schedule.request', array(0, intval($id), intval($measurement['source']), intval($measurement['source-type']), intval($measurement['destination']), intval($measurement['destination-type']), intval($measurement['udp-details-speed']), intval($measurement['udp-details-size']), intval($measurement['udp-details-dur']), intval($measurement['udp-details-dscp']), $seconds, $delay_seconds));
            }elseif(strcmp($measurement['type-radio'], 'dns') == 0) {
                $request = xmlrpc_encode_request('add_dns_schedule.request', array(0, intval($id), intval($measurement['source']), intval($measurement['source-type']), $measurement['dns-details-dn'], $measurement['dns-details-server'], $seconds, $delay_seconds));
            }else{
                echo("Invalid measurement type " . $measurement['type-radio']);
                exit;
            }

            $response = do_call($request);
            $responde = (substr($response, strpos($response, "\r\n\r\n")+4));

            if(is_numeric(xmlrpc_decode($response))){
                $result += 1;
            }
        }

        echo $result;

        mysqli_close($con);
    }

    function stopSchedule($data=array()){
        // Opens a connection to a MySQL server
        $con = mysqli_connect("localhost","root","root","tnp");
        if (mysqli_connect_errno()){
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        $query = "SELECT * FROM schedule_measurements WHERE schedule_id = " . $data['sid'];
        $results = mysqli_query($con, $query);
        if (!$results) {
            die('Invalid query: ' . mysqli_error());
        }

        $result = '';

        while($measurement = @mysqli_fetch_assoc($results)){
            $pid = intval($measurement['pid']);

            if($pid != 0){
                $request = xmlrpc_encode_request("stop_schedule.request", array(intval($measurement_id['measurement_id']), $pid));

                $response = do_call($request);
                $response = (substr($response, strpos($response, "\r\n\r\n")+4));
                $result =  $result . xmlrpc_decode($response);
            }
        }
        echo $result;
    }

    function startSchedule($data=array()){

        $con = mysqli_connect("localhost","root","root","tnp");
        if (mysqli_connect_errno()){
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        $query = "SELECT * FROM schedules WHERE schedule_id = " . $data['sid'];
        $results = mysqli_query($con, $query);
        if (!$results) {
            die('Invalid query: ' . mysqli_error($con));
        }
        $schedule = @mysqli_fetch_assoc($results);

        $query = "SELECT * FROM schedule_measurements WHERE schedule_id = " . $data['sid'];
        $results = mysqli_query($con, $query);
        if (!$results) {
            die('Invalid query: ' . mysqli_error($con));
        }

        while($measurement = @mysqli_fetch_assoc($results)){
            //get params
            $query = "SELECT * FROM schedule_params WHERE measurement_id = " . $measurement['measurement_id'];
            $param_results = mysqli_query($con, $query);
            if (!$param_results) {
                die('Invalid query: ' . mysqli_error($con));
            } 

            //make param array
            while($param = @mysqli_fetch_assoc($param_results)){
                $params[$param['param']] = $param['value'];
            }

            //send request
            if(strcmp($measurement['method'], 'rtt') == 0){
                $request = xmlrpc_encode_request('add_rtt_schedule.request', array(intval($measurement['measurement_id']), intval($measurement['schedule_id']), intval($measurement['source_id']), intval($measurement['source_type']), intval($measurement['destination_id']), intval($measurement['destination_type']), intval($params['iterations']), intval($schedule['period']), intval($measurement['delay'])));
            }elseif(strcmp($measurement['method'], 'tcp') == 0){
                $request = xmlrpc_encode_request('add_tcp_schedule.request', array(intval($measurement['measurement_id']), intval($measurement['schedule_id']), intval($measurement['source_id']), intval($measurement['source_type']), intval($measurement['destination_id']), intval($measurement['destination_type']), intval($params['duration']), intval($schedule['period']), intval($measurement['delay'])));
            }elseif(strcmp($measurement['method'], 'udp') == 0){
                $request = xmlrpc_encode_request('add_udp_schedule.request', array(intval($measurement['measurement_id']), intval($measurement['schedule_id']), intval($measurement['source_id']), intval($measurement['source_type']), intval($measurement['destination_id']), intval($measurement['destination_type']), intval($params['send_speed']), intval($params['packet_size']), intval($params['duration']), intval($params['dscp_flag']), intval($schedule['period']), intval($measurement['delay'])));
            }elseif(strcmp($measurement['method'], 'dns') == 0) {
                $request = xmlrpc_encode_request('add_dns_schedule.request', array(intval($measurement['measurement_id']), intval($measurement['schedule_id']), intval($measurement['source_id']), intval($measurement['source_type']), $params['domain_name'], $params['server'], intval($measurement['period']), intval($measurement['delay'])));
            }else{
                echo("Invalid measurement type " . $measurement['method']);
                exit;
            }

            $response = do_call($request);
            $responde = (substr($response, strpos($response, "\r\n\r\n")+4));
            $result = $result . xmlrpc_decode($response);

        }

        echo $result;
    }

    function deleteSchedule($data=array()){

        stopSchedule($data);

        $con = mysqli_connect("localhost","root","root","tnp");
        if (mysqli_connect_errno()){
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        $query = "SELECT * FROM schedule_measurements WHERE schedule_id = " . $data['sid'];
        $results = mysqli_query($con, $query);
        if (!$results) {
            die('Invalid query: ' . mysqli_error($con));
        }

        while($measurement = @mysqli_fetch_assoc($results)){
            $query = "DELETE FROM schedule_params WHERE measurement_id = " . $measurement['measurement_id'];
            $m_results = mysqli_query($con, $query);
            if (!$m_results) {
              die('Invalid query: ' . mysqli_error($con));
            }

            $query = "DELETE FROM schedule_measurements WHERE measurement_id = " . $measurement['measurement_id'];
            $m_results = mysqli_query($con, $query);
            if (!$m_results) {
              die('Invalid query: ' . mysqli_error($con));
            }
        }

        $query = "DELETE FROM schedules WHERE schedule_id = " . $data['sid'];
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

    exit;
?>