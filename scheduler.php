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
        
        //if already exists - stop
        if(intval($data['schedule_id']) != 0){
            stopSchedule(array('sid' => $data['schedule_id']));
        }

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

        $query = "INSERT INTO schedules(schedule_id, name, description, period) VALUES('" . (isset($data['schedule_id']) ? $data['schedule_id'] : 0) . "', '" . $data['name'] . "', '" . $data['description'] . "', '" . $seconds . "') ON DUPLICATE KEY UPDATE name=VALUES(name), description=VALUES(description), period=VALUES(period), schedule_id=LAST_INSERT_ID(schedule_id)";
        $results = mysqli_query($con, $query);
        if (!$results) {
            die('Invalid query: ' . mysqli_error($con));
        }

        $id = mysqli_insert_id($con); 
        $new_mes = array();
        for($x = 0; $x < count($data['measurement']); $x++){
            $measurement = $data['measurement'][$x];

            $delay_seconds = intval($measurement['delay-hours']) * 60 * 60;
            $delay_seconds = $delay_seconds + (intval($measurement['delay-minutes']) * 60);
            $delay_seconds = $delay_seconds + intval($measurement['delay-seconds']);

            if(strcmp($measurement['method'], 'rtt') == 0){
                $request = xmlrpc_encode_request('add_rtt_schedule.request', array((isset($measurement['measurement_id']) ? intval($measurement['measurement_id']) : 0), intval($id), intval($measurement['source_id']), intval($measurement['source_type']), intval($measurement['destination_id']), intval($measurement['destination_type']), intval($measurement['iterations']), $seconds, $delay_seconds));
            }elseif(strcmp($measurement['method'], 'tcp') == 0){
                $request = xmlrpc_encode_request('add_tcp_schedule.request', array((isset($measurement['measurement_id']) ? intval($measurement['measurement_id']) : 0), intval($id), intval($measurement['source_id']), intval($measurement['source_type']), intval($measurement['destination_id']), intval($measurement['destination_type']), intval($measurement['duration']), $seconds, $delay_seconds));
            }elseif(strcmp($measurement['method'], 'udp') == 0){
                $request = xmlrpc_encode_request('add_udp_schedule.request', array((isset($measurement['measurement_id']) ? intval($measurement['measurement_id']) : 0), intval($id), intval($measurement['source_id']), intval($measurement['source_type']), intval($measurement['destination_id']), intval($measurement['destination_type']), intval($measurement['speed']), intval($measurement['size']), intval($measurement['duration']), intval($measurement['dscp']), $seconds, $delay_seconds));
            }elseif(strcmp($measurement['method'], 'dns') == 0) {
                $request = xmlrpc_encode_request('add_dns_schedule.request', array((isset($measurement['measurement_id']) ? intval($measurement['measurement_id']) : 0), intval($id), intval($measurement['source_id']), intval($measurement['source_type']), $measurement['domain_name'], $measurement['server'], $seconds, $delay_seconds));
            }else{
                echo("Invalid measurement type " . $measurement['method']);
                exit;
            }

            $response = do_call($request);
            $responde = (substr($response, strpos($response, "\r\n\r\n")+4));

            if(is_numeric(xmlrpc_decode($response)))
                $result += 1;

            array_push($new_mes, intval(xmlrpc_decode($response)));
        }

        //if schedule already exists - remove existing measurements and params that do not appear in the new set
        if(intval($data['schedule_id']) != 0){
            $query = "SELECT * FROM schedule_measurements WHERE schedule_id = " . $data['schedule_id'];
            $results = mysqli_query($con, $query);
            if (!$results) {
                die('Invalid query: ' . mysqli_error($con));
            }
            while($row = @mysqli_fetch_assoc($results)){
                if(!in_array(intval($row['measurement_id']), $new_mes)){
                    $dquery = "DELETE FROM schedule_params WHERE measurement_id = " . $row['measurement_id'];
                    $dresults = mysqli_query($con, $dquery);
                    if (!$dresults) {
                        die('Invalid query: ' . mysqli_error($con));
                    }
                    $dquery = "DELETE FROM schedule_measurements WHERE measurement_id = " . $row['measurement_id'];
                    $dresults = mysqli_query($con, $dquery);
                    if (!$dresults) {
                        die('Invalid query: ' . mysqli_error($con));
                    }
                }
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
                $request = xmlrpc_encode_request("stop_schedule.request", array(intval($measurement['measurement_id']), $pid));

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
                $request = xmlrpc_encode_request('add_dns_schedule.request', array(intval($measurement['measurement_id']), intval($measurement['schedule_id']), intval($measurement['source_id']), intval($measurement['source_type']), $params['domain_name'], $params['server'], intval($schedule['period']), intval($measurement['delay'])));
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