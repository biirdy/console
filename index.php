<?php
  include('session.php'); 
?>

<html lang="en"><head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Jamie Bird">
    <link rel="icon" href="../../favicon.ico">

    <title>Sensor Management</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <!-- Custom styles for this template -->
    <link href="/css/index.css" rel="stylesheet">
    <link href="/css/jasny-bootstrap.min.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="/assets/js/ie-emulation-modes-warning.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- vis.js -->
    <script type="text/javascript" src="/js/vis.js"></script>
    <link href="/css/vis.css" rel="stylesheet" type="text/css" />

    <!-- bootstap-timepicker.js -->
    <script type="text/javascript" src="/js/bootstrap-timepicker.js"></script>
    <link type="text/css" href="/css/bootstrap-timepicker.css" />

  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">

        <!-- Nav bar header -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
          </button>
          <a class="navbar-brand" href="#">Sensor Management</a>
        </div>

        <!-- Nav bar body -->
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#server-con">Server</a></li>
            <li><a href="#sensors-con">Sensors</a></li>
            <li><a href="#topology-con">Topology</a></li>
            <li><a href="#schedules-con">Schedules</a></li>
            <li><a href="#groups-con">Groups</a></li>
            <li><a href="#alarms-con">Alarms</a></li>
          </ul>

            <!-- Notification Panel -->

            <!-- Log out button -->
            <b id="logout" class="navbar-right navbar-text"><a href="logout.php">Log Out</a></b>
        </div><!--/.nav-collapse -->
        
      </div>
    </nav>

    <div class="container main-body">
  
      <!-- Server Panel-->
      <div class="container panel panel-default panel-body" id="server-con">

          <h1 id="server_status">Server <span style="color:green" class="glyphicon glyphicon-ok" aria-hidden="true"><small>Running</small></span> </h1>

          
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a data-toggle="collapse" data-target="#collapseOne" class="collapsed">Server Log</a>
              </h4>
        

            </div>
            <div id="collapseOne" class="panel-collapse collapse">
              <div class="panel-body" id="server_log"></div>
            </div>
          </div>

          <form class="form-inline" role="form">
              <button type="button" class="btn btn-success server-btn" id="server_start" value="server_start">Start</button>
              <button type="button" class="btn btn-warning server-btn" id="server_update" value="server_update">Update</button>
              <button type="button" class="btn btn-warning server-btn" id="server_restart" value="server_restart">Restart</button>
              <button type="button" class="btn btn-danger server-btn" id="server_stop" value="server_stop">Stop</button>
          </form>

          <a href="logs/server.log" target"_self">Full server log</a>

      </div>

      <!-- Sensor Panel -->
      <div class="container panel panel-default panel-body" id="sensors-con">

        <h1>Sensors</h1>
        <div class="sensor-tables">
          <table class="table table-hover rowlink" data-link="row" id="sensors">
            <tr><th>ID</th><th>Ethernet</th><th>IP</th><th>Local IP</th><th>Connect Time</th><th>Disconnect Time</th><th>Description</th></tr>
          </table>
        </div>

      </div>

      <!-- Topology Panel-->
      <div class="container panel panel-default panel-body" id="topology-con">
        <h1>Topology</h1>
        <div id="topo-div"></div>
        
        <div class="panel-body container panel panel-default" id="topo-feature">
          <p><b>Link feature</b></p>
          <div class="radio">
            <label><input type="radio" name="feature-radio" id="feature-rtt">Round Trip Time</label>
          </div>
          <div class="radio">
            <label><input type="radio" name="feature-radio" id="feature-bw" checked>TCP Bandwidth</label>
          </div>
          <div class="radio disabled">
            <label><input type="radio" name="feature-radio" disabled>Option 3</label>
          </div>
        </div>

        <div class="panel-body container panel panel-default" id="topo-feature">
          <p><b>Sensor Label</b></p>
          <div class="radio">
            <label><input type="radio" name="label-radio" id="mac" checked>MAC address</label>
          </div>
          <div class="radio">
            <label><input type="radio" name="label-radio" id="ip">IP address</label>
          </div>
          <div class="radio">
            <label><input type="radio" name="label-radio" id="id">Sensor ID</label>
          </div>
          <div class="radio">
            <label><input type="radio" name="label-radio" id="description">Description</label>
          </div>
        </div>

      </div>

      <!-- Schedule Panel -->
      <div class="container panel panel-default panel-body" id="schedules-con">
        <h1>Schedules</h1>

        <!-- Schedule Table-->
        <table class="table table-hover rowlink" data-link="row" id="schedules">
          <tr><th>From</th><th>To</th><th>Interval (seconds)</th><th>Details</th><th>Description</th><th style='text-align: center;'>Start/Suspend</th><th style='text-align: center;'>Delete</th></tr>
        </table>

        <form class="form-inline" role="form">
          <button type="button" class="btn btn-success schedule-btn" data-toggle="modal" data-target="#create-schedule">Create Schedule</button>
        </form>  
      </div>

      <!-- Create Schedule Modal-->
      <div class="modal fade" id="create-schedule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">

            <!-- Modal Header-->
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Create Schedule</h4>
            </div>


            <form role="form" id="create-schedule-form">
            <!-- Modal body-->
            <div class="modal-body">

                <div id="ip-warning" style="display: none;" class="alert alert-danger" role="alert"><b>Measurement might not work as expected!</b><br> Recipient does not have a public facing IP address.</div>
                <div id="same-sensor-warning" style="display: none;" class="alert alert-danger" role="alert"><b><i>To</i> and <i>from</i> cannot be the same sensor!</b></div>
                <div id="no-sensor-warning" style="display: none;" class="alert alert-danger" role="alert"><b>Select both <i>To</i> and <i>from</i> sensor!</b></div>
              
                <div class="form-group">

                  <div class="form-group">
                    <label for="from">From: </label><br>
                    <div class="dropdown btn-group">
                      <button style="display: block; width: 100%;" class="btn btn-default dropdown-toggle" type="button" id="from-dropdown" data-toggle="dropdown" aria-expanded="true">
                        <span data-bind="label">Select sensor</span>&nbsp;<span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu" id="from-dropdown-list" role="menu" aria-labelledby="to-dropdown">
                      </ul>
                      <input id="from-input" value="0" style="display: none;" name="from" class="form-control" type="hidded">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="to">To: </label><br>
                    <div class="dropdown btn-group">
                      <button class="btn btn-default dropdown-toggle" type="button" id="to-dropdown" data-toggle="dropdown" aria-expanded="true">
                        <span data-bind="label">Select sensor</span>&nbsp;<span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu" id="to-dropdown-list" role="menu" aria-labelledby="to-dropdown">
                      </ul>
                      <input id="to-input" value="0" style="display: none;" hidden name="to" class="form-control" type="hidded">
                    </div> 
                  </div>

                  <div class="form-group">
                    <label for="description">Description: </label>
                    <input name="description" class="form-control" placeholder="Description:">
                  </div>

                  <div class="form-group">
                  <label for="type">Measurement: </label>
                  <div class="radio">
                    <label><input type="radio" name="type-radio" value="rtt" data-div="rtt-details" id="type-rtt">Round Trip Time</label>
                  </div>
                  <div class="radio">
                    <label><input type="radio" name="type-radio" value="tcp" data-div="tcp-details" id="type-tcp">TCP throughput</label>
                  </div>
                  <div class="radio">
                    <label><input type="radio" name="type-radio" value="udp" data-div="udp-details" id="type-udp">UDP measurements</label>
                  </div>
                  <div class="radio">
                    <label><input type="radio" name="type-radio" value="dns" data-div="dns-details" id="type-dns">DNS resolution</label>
                  </div>
                  </div>

                  <div class="form-group" style="display: none;" id="rtt-details">
                    <label for="rtt-details-itr">Itterations: </label>
                    <input name="rtt-details-itr" class="form-control" id="rtt-details-itr" value="5">
                  </div>

                  <div class="form-group" style="display: none;" id="tcp-details">
                    <label for="tcp-details-dur">Duration (Seconds): </label>
                    <input name="tcp-details-dur" class="form-control" id="tcp-details-dur" value="10">
                  </div>

                  <div class="form-group" style="display: none;" id="udp-details">
                    <label for="udp-details-speed">Send Speed (kbps): </label>
                    <input name="udp-details-speed" class="form-control" id="udp-details-speed" value="1024">

                    <label for="udp-details-size">Packet Size (bytes): </label>
                    <input name="udp-details-size" class="form-control" id="udp-details-size" value="1024">

                    <label for="udp-details-dur">Duration (Seconds): </label>
                    <input name="udp-details-dur" class="form-control" id="udp-details-dur" value="10">

                    <label for="udp-details-dscp">DSCP flag: </label>
                    <br>
                    <div class="btn-group dropdown">
                      <button type="button" class="form-control btn btn-default dropdown-toggle dscp-toggle" data-toggle="dropdown">
                        <span data-bind="label">None</span>&nbsp;<span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu" role="menu">
                          <li value="0"><a href="" value="0" onclick="return false;">none</a></li>
                          <li value="32"><a href="" value="32" onclick="return false;">cs1</a></li>
                          <li value="64"><a href="" value="64" onclick="return false;">cs2</a></li>
                          <li value="96"><a href="" vlaue="96" onclick="return false;">cs3</a></li>
                          <li value="40"><a href="" value="40" onclick="return false;">af11</a></li>
                          <li value="48"><a href="" value="48" onclick="return false;">af12</a></li>
                          <li value="56"><a href="" value="56" onclick="return false;">af13</a></li>
                          <li value="176"><a href="" onclick="return false;">voice-admit</a></li>
                          <li value="184"><a href="" onclick="return false;">ef</a></li>
                      </ul>
                      <input style="display: none;" name="udp-details-dscp" class="form-control" type="hidded">
                    </div>
                  </div>

                  <div class="form-group" style="display: none;" id="dns-details">
                    <label for="dns-details-addr">URL: </label>
                    <input class="form-control" id="dns-details-addr" value="google.co.uk">
                  </div>

                  <div class="form-group">
                  <label for="interval">Every: </label>
                  <table>
                    <tr><td>
                      <div class="input-group">
                        <input name="hours" type="text" class="form-control" value="1" aria-describedby="basic-addon2">
                        <span class="input-group-addon" id="basic-addon2">Hours</span>
                      </div>
                    </td><td>
                      <div class="input-group">
                        <input name="minutes" type="text" class="form-control" value="0" aria-describedby="basic-addon2">
                        <span class="input-group-addon" id="basic-addon2">Minues</span>
                      </div>
                    </td><td>
                      <div class="input-group">
                        <input name="seconds" type="text" class="form-control" value="0" aria-describedby="basic-addon2">
                        <span class="input-group-addon" id="basic-addon2">Seconds</span>
                      </div>
                    </td></tr>
                  </table>
                  </div>


                </div>
              
            </div>

            <!-- Modal Footer-->
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              <button disabled id="schedule-submit" type="submit" class="btn btn-success">Create Schedule</button>
            </div>
            </form>
          </div>
        </div>
      </div>  

      <div class="container panel panel-default panel-body" id="groups-con">
        <h1>Groups</h1>

        <h2>Not implemented</h2>
      </div>

      <div class="container panel panel-default panel-body" id="alarms-con">
        <h1>Alarms</h1>

        <h2>Not implemented</h2>
      </div>
    </div>

    <script type="text/javascript">

      /*
      * Get current state of server - update glyphicon and active server interaction buttons
      */
      function server_status(){
        $.post("server.php", {'action':'server_state'}, function (response) {
          // Response div goes here.
          console.log(response);
          if(response == 1){
             document.getElementById("server_status").innerHTML = "Server <span style='color:green' class='glyphicon glyphicon-ok' aria-hidden='true'><small>Running</small></span>";
             document.getElementById("server_start").disabled = true;
             document.getElementById("server_update").disabled = true;
             document.getElementById("server_restart").disabled = false;
             document.getElementById("server_stop").disabled = false;
          }else{
            document.getElementById("server_status").innerHTML = "Server <span style='color:red' class='glyphicon glyphicon-remove' aria-hidden='true'></span><small>Not Running</small>";
            document.getElementById("server_start").disabled = false;
            document.getElementById("server_update").disabled = false;
            document.getElementById("server_restart").disabled = true;
            document.getElementById("server_stop").disabled = true;
          }
        });
      }

      /*
      * Server interaction
      */
      $(document).ready(function(){
        $('.server-btn').click(function(){
          
          var clickBtnValue = $(this).val();
          var confirm_message;

          switch(clickBtnValue){
            case 'server_start':
              confirm_message = 'Start the server?';
              break;
            case 'server_stop':
              confirm_message = 'Stop the server? All current sensor connections will be lost.';
              break;
            case 'server_restart':
              confirm_message = 'Restart the server?';
              break;
            case 'server_update':
              confirm_message = 'Update the server?';
              break;  
          }

          if(confirm(confirm_message)){
            data =  {'action': clickBtnValue};
            $.post('server.php', data, function (response) {
              // Response div goes here.
              server_status();
              console.log(response);
            });             
          }else{
            console.log("Nothing");
          }
        });
      });

      //inital state of server
      server_status();

      //auto refresh server log
      load_log();
      function load_log(){
        $('#server_log').load('server_log.php', function(){
          //document.getElementById("server_log").scrollTop = document.getElementById("server_log").scrollHeight; 
          setTimeout(load_log, 5000);  
        });
      }
      
      //initial load of sensor data 
      var sensor_data;
      load_sensors();

      //initial load of schedule sata
      var schedule_data;
      load_schedules();

      /*
      * Loads in sensor data and fills out tables
      * AUTO REFRESH - once called will load and check for differences every # seconds
      */
      function load_sensors(auto){

        $.getJSON("sensors.php", null, function(data){
          
          if(JSON.stringify(sensor_data) != JSON.stringify(data)){

            sensor_data = data;

            //clear tables
            $("#sensors").find("tr:gt(0)").remove();

            //clear schdule creator dropdowns
            $("#to-dropdown-list").empty();
            $("#from-dropdown-list").empty();

            //repopulate tables
            for(x in sensor_data){
              $('#sensors tr:last').after('<tr bgcolor=' + (sensor_data[x].active == 0 ? "'#FFCCCC'" : "'#99FF99'") + '><td><a href="sensor.html?' + sensor_data[x].sensor_id + '">' + sensor_data[x].sensor_id + '</a></td>' + 
                                                    '<td>' + sensor_data[x].ether + '</td>' + 
                                                    '<td>' + sensor_data[x].ip + '</td>' + 
                                                    '<td>' + sensor_data[x].local_ip + '</td>' + 
                                                    '<td>' + sensor_data[x].start + '</td>' + 
                                                    '<td' + (sensor_data[x].active == 0 ? '>' + sensor_data[x].end : " style='text-align: center;'>-") + '</td>' + 
                                                    '<td>' + sensor_data[x].description + '</td></tr>');
                                                      
              

              //add to schedule creator dropdowns 
              $("#to-dropdown-list").append('<li role="presentation" value="' + sensor_data[x].sensor_id + '"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >' + sensor_data[x].description + '</a></li>');
              $("#from-dropdown-list").append('<li role="presentation" value="' + sensor_data[x].sensor_id + '"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >' + sensor_data[x].description + '</a></li>');
            }

          }
        });

        if(!auto)
          setTimeout(load_sensors, 5000);
      }

      function load_schedules(auto){
        $.getJSON("schedules.php", null, function(data){

          if(JSON.stringify(schedule_data) != JSON.stringify(data)){
            schedule_data = data;

            $("#schedules").find("tr:gt(0)").remove();

            for(x in schedule_data){
              $('#schedules tr:last').after("<tr bgcolor=" + (schedule_data[x].active == 0 ? "'#FFCCCC'" : "'#99FF99'") + "><td>" + schedule_data[x].sensor + "</td>" + 
                                            "<td>" + schedule_data[x].recipient + "</td>" + 
                                            "<td>" + schedule_data[x].period + "</td>" + 
                                            "<td>" + schedule_data[x].details + "</td>" + 
                                            "<td>" + (schedule_data[x].description == "" ? "-" : schedule_data[x].description) + "</td>" + 
                                            "<td style='text-align: center;' class='rowlink-skip'><span class='glyphicon " + (schedule_data[x].active == 0 ? "glyphicon-play" : "glyphicon-pause") + " susspend-button table-button' aria-hidden='true' onclick='" + (schedule_data[x].active == 0 ? "schedule_start(" : "schedule_susspend(") + schedule_data[x].schedule_id + "); return false;'></span></td>"  + 
                                            "<td style='text-align: center;'class='rowlink-skip'><span class='glyphicon glyphicon-remove remove-button table-button' aria-hidden='true' onclick='schedule_delete(" + schedule_data[x].schedule_id + ")'></span></td>" + "</tr>");
            }
          }
        }); 

        if(!auto)
          setTimeout(load_schedules, 5000);
      }

      /*
      * Update text on dropdowns
      */
      $(document.body).on('click', '.dropdown-menu li', function(event){
          var $target = $(event.currentTarget);
          $target.closest('.btn-group')
          .find('[data-bind="label"]').text($target.text())
          .end()
          .children('.dropdown-toggle').dropdown( 'toggle');

          console.log($target);
          console.log($target.val());

          $target.closest('.btn-group')
          .find('[type="hidded"]').val($target.val())
          .end()

          //checks
          if($("#to-input").val() == 0 || $("#from-input").val() == 0){
            $("#same-sensor-warning").hide();
            $("#no-sensor-warning").show();
            $("#schedule-submit").attr('disabled', 'disabled');
          }else if($("#to-input").val() == $("#from-input").val()){
            $("#no-sensor-warning").hide();
            $("#same-sensor-warning").show();
            $("#schedule-submit").attr('disabled', 'disabled');
          }else{
            $("#same-sensor-warning").hide();
            $("#no-sensor-warning").hide();
            $("#schedule-submit").removeAttr('disabled');
          }//check for non differnet IP's

          $("#create-schedule").data("bs.modal").handleUpdate();
      });

    /*
    * Disconnect sensor
    */
    function sensor_disconnect(id){
        
      if(confirm("Disconnect sensor " + id + "?")){

        $.ajax({
              url: "disconnect_sensor.php",
              type: 'POST',
              data: "&sensor_id=" + id,
              success: function(data) {
                console.log(data);
                load_sensors(1);
              }
        });
      }
    }

    /*
    *
    */
    function schedule_susspend(sid){

      console.log("Susspend schedule " + sid);

      $.post("scheduler.php", {Function: "stopSchedule", Data: {sid: sid}}, function(data){
        
        alert(data);
        load_schedules(1);
      });

    }

    function schedule_start(sid){

      console.log("Start schedule " + sid);

      $.post("scheduler.php", {Function: "startSchedule", Data: {sid: sid}}, function(data){

        alert(data);
        load_schedules(1);
      })

    }

    function schedule_delete(sid){

      console.log("Delete delete " + sid);

      if(confirm("Stop and delete schedule " + sid + "?")){
        $.post("scheduler.php", {Function: "deleteSchedule", Data: {sid: sid}}, function(data){
          load_schedules(1);
        });
      }
    } 

    /*
    * Updates the topology view.
    * feature_url   - url of JSON file (PHP) to label the links
    * sensor_label  - string describing how to label the sesnors (MAC, IP, ID or Description)
    */
    function load_topo(sensor_label, feature_url){
      $.getJSON("sensors.php", null, function(json_sensor){
      
        for(x in json_sensor){
          json_sensor[x].id = json_sensor[x].sensor_id;

          if(sensor_label == "mac")
            json_sensor[x].label = json_sensor[x].ether;
          else if(sensor_label == "id")
            json_sensor[x].label = json_sensor[x].sensor_id;
          else if(sensor_label == "ip")
            json_sensor[x].label = json_sensor[x].ip;
          else if(sensor_label == "description")
            json_sensor[x].label = json_sensor[x].description;
        }

        json_sensor.push({id: 1, label: "Server"});

        $.getJSON(feature_url, null, function(json_feature){

          for(x in json_feature){
            json_feature[x].from = json_feature[x].sensor_id;
            json_feature[x].to = json_feature[x].dst_id;
            json_feature[x].label = json_feature[x].feature + ((feature_url == "bws_topo.php") ? "kbps" : "ms");
          }

          // provide the data in the vis format
          topo_data = {
              nodes: new vis.DataSet(json_sensor),
              edges: new vis.DataSet(json_feature)
          };

          network.setOptions(topo_options);

          // initialize your network!
          network.setData(topo_data);

          console.log(network.getSeed());

          //freeze network once organised - 100ms
          setTimeout(function(){
            network.setOptions(no_sim_options);
          }, 100);

          //onclick to redirect when clicked on a sensor - not edges or sensor
          network.on( 'click', function(properties) {
            if(properties.nodes.length == 1){
              if(properties.nodes != 1)
                window.location.href = "sensor.html?" + properties.nodes;
            }
          });       

        });           
      
      });
    }

    // Create initial topology
    var topo_container = document.getElementById('topo-div');

    var topo_data = {nodes: null, edges: null};
    var topo_options = {
      autoResize: true,
      height: '100%',
      width: '100%',
      layout: {
        randomSeed: 0
      },
      edges: {
        length: 200,
        smooth: {
          enabled: false
        }
      },
      physics: {
        enabled: true,
        barnesHut: {
          avoidOverlap: 1
        }
      }
    };

    var no_sim_options = {
      autoResize: true,
      height: '100%',
      width: '100%',
      clickToUse: true,
      edges: {
        length: 200,
        smooth: {
          enabled: false
        }
      },
      physics: {
        enabled: false
      },
      interaction: {
        hover: true,
        hoverConnectedEdges: false,
        zoomView: false
      }
    }

    var network = new vis.Network(topo_container, topo_data, topo_options);
    var feature_url = "bws_topo.php";
    var sensor_label = "mac";
    load_topo(sensor_label, feature_url); 

    /*
    * Change topology link feature according to radio buttons
    */
    var feature_radios = document.getElementsByName("feature-radio");
    for (x in feature_radios){
      feature_radios[x].onclick = function(){

        if(this.id == "feature-rtt"){
          feature_url = "rtts_topo.php";
        }else if(this.id == "feature-bw"){
          feature_url = "bws_topo.php";
        }

        load_topo(sensor_label, feature_url);

      }
    }

    /*
    * Change topology sensor label accoring to radio buttons
    */
    var label_radios = document.getElementsByName("label-radio");
    for (x in label_radios){
      label_radios[x].onclick = function(){
        sensor_label = this.id;
        load_topo(sensor_label, feature_url);
      }
    }

    /*
    * Change create schdule form for the type of measurement selected
    */
    var type_radios = document.getElementsByName("type-radio");
    for (x in type_radios){
      type_radios[x].onclick = function(){
        document.getElementById('rtt-details').style.display = 'none';
        document.getElementById('tcp-details').style.display = 'none';
        document.getElementById('udp-details').style.display = 'none';
        document.getElementById('dns-details').style.display = 'none';        

        document.getElementById(this.getAttribute("data-div")).style.display = 'block';

        $("#create-schedule").data("bs.modal").handleUpdate();
      }
    }

    /*
    * POST create schedule
    */
    $("#create-schedule-form").submit(function(event){
      event.preventDefault();

      console.log($(this).serializeObject());

      $.post("scheduler.php", {Function: "createSchedule", Data:$(this).serializeObject()}, function(data){
        //check if succeeds
        if(!isNaN(data)){
          alert("Created schedule with ID "  + data);
          $("#create-schedule").modal('hide');
          load_schedules(1);
        }else{
          alert("Failed - " + data);
        }
      });


    });

    $.fn.serializeObject = function(){
        var o = {};
        var a = this.serializeArray();
        $.each(a, function() {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };
  

    </script>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="/assets/js/ie10-viewport-bug-workaround.js"></script>
    <!-- Used for the single line links -->
    <script src="/js/jasny-bootstrap.min.js"></script>

  

</body></html>
