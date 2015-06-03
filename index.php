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
  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
          </button>
          <a class="navbar-brand" href="#">Sensor Management</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#about">About</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>

            <b id="logout" class="navbar-right navbar-text"><a href="logout.php">Log Out</a></b>
   
        </div><!--/.nav-collapse -->
        
      </div>
    </nav>

    <div class="container main-body">

      <div class="panel panel-default panel-body">

          <h1 id="server_status">Server <span style="color:green" class="glyphicon glyphicon-ok" aria-hidden="true"><small>Running</small></span></h1>
          
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
              <button type="button" class="btn btn-success" id="server_start" value="server_start">Start</button>
              <button type="button" class="btn btn-warning" id="server_update" value="server_update">Update</button>
              <button type="button" class="btn btn-warning" id="server_restart" value="server_restart">Restart</button>
              <button type="button" class="btn btn-danger" id="server_stop" value="server_stop">Stop</button>
          </form>

      </div>

      <div class="container panel panel-default panel-body">

        <h1>Sensors</h1>
        <div class="sensor-tables">
          <h2>Connected Sensors</h2>
          <table class="table table-hover rowlink" data-link="row" id="connected_sensors">
  		      <tr><th>ID</th><th>IP</th><th>Connect Time</th><th>Description</th><th>Disconnect</th></tr>
          </table>
          <h2>Previously Connected Sensors</h2>
          <table class="table table-hover rowlink" data-link="row" id="disconnected_sensors">
            <tr><th>ID</th><th>IP</th><th>Connect Time</th><th>Disconnect Time</th><th>Description</th></tr>
          </table>
        </div>

      </div><!-- /.container -->
      <div class="container panel panel-default panel-body">
        <h1>Topology</h1>
      </div>
    </div>

    <script type="text/javascript">

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

      $(document).ready(function(){
        $('.btn').click(function(){
          var clickBtnValue = $(this).val();
          var ajaxurl = 'server.php',
          data =  {'action': clickBtnValue};
          $.post(ajaxurl, data, function (response) {
            // Response div goes here.
            server_status();
            console.log(response);
          });
        });
      });

      server_status();

      //auto refresh server log
      load_log();
      function load_log(){
        $('#server_log').load('logs/server.log', function(){
          var server_log = document.getElementById("server_log");
          document.getElementById("server_log").innerHTML = document.getElementById("server_log").innerHTML.replace(/\n/g, "<br />"); 
          document.getElementById("server_log").scrollTop = document.getElementById("server_log").scrollHeight; 
        });
        setTimeout(load_log, 5000);
      }

      var sensors_xml = "";
      load_sensors();
      function load_sensors(auto){
        downloadUrl("sensors.php", function(data){
          //check for updates
          if(data.responseXML != sensors_xml){
            sensors_xml = data.responseXML;   

            var sensors = sensors_xml.documentElement.getElementsByTagName("dsensor");

            var table = document.getElementById("disconnected_sensors");
            table.innerHTML = "<tr><th>ID</th><th>IP</th><th>Connect Time</th><th>Disconnect Time</th><th>Description</th></tr>";
            for(var i = 1; i < sensors.length + 1; i++){
              var row = table.insertRow(i);
              row.insertCell(0).innerHTML = "<a href='sensor.html?" + sensors[i-1].getAttribute("id") + "'>" + sensors[i-1].getAttribute("id") + "</a>";
              row.insertCell(1).innerHTML = sensors[i-1].getAttribute("ip");
              row.insertCell(2).innerHTML = sensors[i-1].getAttribute("start");
              row.insertCell(3).innerHTML = sensors[i-1].getAttribute("end");
              row.insertCell(4).innerHTML = sensors[i-1].getAttribute("description");
            }

            var sensors = sensors_xml.documentElement.getElementsByTagName("sensor");
            var table = document.getElementById("connected_sensors");
            table.innerHTML = "<tr><th>ID</th><th>IP</th><th>Connect Time</th><th>Description</th><th></th></tr>";
            for(var i = 1; i < sensors.length + 1; i++){
              var row = table.insertRow(i);
              row.insertCell(0).innerHTML = "<a href='sensor.html?" + sensors[i-1].getAttribute("id") + "'>" + sensors[i-1].getAttribute("id") + "</a>";
              row.insertCell(1).innerHTML = sensors[i-1].getAttribute("ip");
              row.insertCell(2).innerHTML = sensors[i-1].getAttribute("start");
              row.insertCell(3).innerHTML = sensors[i-1].getAttribute("description");
              row.insertCell(4).innerHTML = "<span class='glyphicon glyphicon-remove disconnect-button rowlink-skip' aria-hidden='true' onclick='sensor_disconnect(" + sensors[i-1].getAttribute("id") + "); return false;'></span>";
            }

          }
        });
        if(!auto)
          setTimeout(load_sensors, 5000);
      }

      function sensor_disconnect(id){
        $.ajax({
              url: "disconnect_sensor.php",
              type: 'POST',
              data: "&sensor_id=" + id,
              success: function(data) {
                console.log(data);
                load_sensors(1);
              }
        });
        console.log("Disconnect " + id);
      }

      function downloadUrl(url, callback) {
        var request = window.ActiveXObject ?
          new ActiveXObject('Microsoft.XMLHTTP') :
          new XMLHttpRequest;

        request.onreadystatechange = function() {
          if (request.readyState == 4) {
            request.onreadystatechange = doNothing;
            callback(request, request.status);
          }
        };

        request.open('GET', url, true);
        request.send(null);
      }

    function doNothing() {}
      

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
