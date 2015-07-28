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


        $seconds = intval($data['hours']) * 60 * 60;
        $seconds = $seconds + (intval($data['minutes']) * 60);
        $seconds = $seconds + intval($data['seconds']);

        if(strcmp($data['type-radio'], 'rtt') == 0){
            $request = xmlrpc_encode_request('add_rtt_schedule.request', array(intval(0), intval($data['from']), intval($data['to']), intval($data['rtt-details-itr']), $seconds));
        }elseif(strcmp($data['type-radio'], 'tcp') == 0){
            $request = xmlrpc_encode_request('add_tcp_schedule.request', array(0, intval($data['from']), intval($data['to']), intval($data['tcp-details-dur']), $seconds));
        }elseif(strcmp($data['type-radio'], 'udp') == 0){
            $request = xmlrpc_encode_request('add_udp_schedule.request', array(0, intval($data['from']), intval($data['to']), intval($data['udp-details-speed']), intval($data['udp-details-size']), intval($data['udp-details-dur']), intval($data['udp-details-dscp']), $seconds));
        }elseif(strcmp($data['type-radio'], 'dns') == 0) {
            $request = xmlrpc_encode_request('add_dns_schedule.request', array(0, intval($data['from']), $seconds));
        }else{
            echo("Invalid measurement type " . $data['type-radio']);
            exit;
        }

        $response = do_call($request);
        $responde = (substr($response, strpos($response, "\r\n\r\n")+4));
        $response = xmlrpc_decode($response);

        echo $response;

        //add description to db
        $con = mysqli_connect("localhost","root","root","tnp");
        if (mysqli_connect_errno()){
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        $query = "UPDATE schedules SET description='" . $data['description'] . "' WHERE schedule_id = " . intval($response);
        $results = mysqli_query($con, $query);
        if (!$results) {
          die('Invalid query: ' . mysqli_error($con));
        }

        mysqli_close($con);
    }

    function stopSchedule($data=array()){
        // Opens a connection to a MySQL server
        $con = mysqli_connect("localhost","root","root","tnp");
        if (mysqli_connect_errno()){
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        //if sensor_id is set only get individual sensor
        $query = "SELECT pid, active FROM schedules WHERE schedule_id = " . $data['sid'];

        $results = mysqli_query($con, $query);
        if (!$results) {
            die('Invalid query: ' . mysqli_error());
        }

        $row = @mysqli_fetch_assoc($results);
        $pid = intval($row['pid']);

        $response = 0;

        if($pid != 0){
            $request = xmlrpc_encode_request("stop_schedule.request", array(intval($data['sid']), $pid));

            $response = do_call($request);
            $response = (substr($response, strpos($response, "\r\n\r\n")+4));
            $response = xmlrpc_decode($response);
        }

        echo intval($response);
    }

    function startSchedule($data=array()){

        $con = mysqli_connect("localhost","root","root","tnp");
        if (mysqli_connect_errno()){
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        //get params
        $query = "SELECT * FROM schedule_params WHERE schedule_id = " . $data['sid'];
        $results = mysqli_query($con, $query);
        if (!$results) {
          die('Invalid query: ' . mysqli_error($con));
        }

        //make param array
        while($row = @mysqli_fetch_assoc($results)){
            $params[$row['param']] = $row['value'];
        }

        //get details
        $query = "SELECT * FROM schedules WHERE schedule_id = " . $data['sid'];
        $results = mysqli_query($con, $query);
        if (!$results) {
          die('Invalid query: ' . mysqli_error($con));
        }
        $row = @mysqli_fetch_assoc($results);

        //send request
        if(strcmp($row['method'], 'rtt') == 0){
            $request = xmlrpc_encode_request('add_rtt_schedule.request', array(intval($data['sid']), intval($row['sensor']), intval($row['recipient']), intval($params['iterations']), intval($row['period'])));
        }elseif(strcmp($row['method'], 'tcp') == 0){
            $request = xmlrpc_encode_request('add_tcp_schedule.request', array(intval($data['sid']), intval($row['sensor']), intval($row['recipient']), intval($params['duration']), intval($row['period'])));
        }elseif(strcmp($row['method'], 'udp') == 0){
            $request = xmlrpc_encode_request('add_udp_schedule.request', array(intval($data['sid']), intval($row['sensor']), intval($row['recipient']), intval($params['send_speed']), intval($params['packet_size']), intval($params['duration']), intval($params['dscp_flag']), intval($row['period'])));
        }elseif(strcmp($row['method'], 'dns') == 0) {
            $request = xmlrpc_encode_request('add_dns_schedule.request', array(intval($data['sid']), intval($row['sensor']), intval($row['period'])));
        }else{
            echo("Invalid measurement type " . $row['method']);
            exit;
        }

        $response = do_call($request);
        $responde = (substr($response, strpos($response, "\r\n\r\n")+4));
        $response = xmlrpc_decode($response);

        echo intval($response);

    }

    function deleteSchedule($data=array()){

        stopSchedule($data);

        $con = mysqli_connect("localhost","root","root","tnp");
        if (mysqli_connect_errno()){
            echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        $query = "DELETE FROM schedule_params WHERE schedule_id = " . $data['sid'];
        $results = mysqli_query($con, $query);
        if (!$results) {
          die('Invalid query: ' . mysqli_error($con));
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