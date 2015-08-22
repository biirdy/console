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

    <!-- jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

    <!-- Bootstrap -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

    <link href="/css/index.css" rel="stylesheet" type="text/css">
    <link href="/css/d3.css" rel="stylesheet">

  </head>
  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          
          <a class="navbar-brand" href="index.php">Sensor Management</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="#rtt_container">RTT</a></li>
            <li><a href="#throughput_container">TCP Throughput</a></li>
            <li><a href="#udp_container">UDP measurments</a></li>
            <li><a href="#dns_container">DNS Status</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <div class="container">
      <div class="row container">
        <div class="col-lg-4">
          <h1 id="title"></h1>
        </div>

        <div class="col-lg-7">
          <div class="input-group sensor-desc">
            <input id="desc-input" type="text" class="form-control" placeholder="Sensor Description: Lorem Ipsum" maxlength="50">
            <span class="input-group-btn">
              <button id="desc-form" class="btn btn-default" type="button">Update</button>
            </span>
          </div>
        </div>

        <div class="col-lg-1">
          <button type="button" id="sensor-disc" class="btn btn-danger sensor-disc">Disconnect</button>
        </div>
      </div>
    </div>

    <div class="container panel panel-default panel-body" id="rtt_container">

      <h2 id="rtt_title">Round Trip Times</h2>

      <!--
      Period: 
      <div class="dropdown btn-group">
        <button class="btn btn-default dropdown-toggle" type="button" id="rtt_dropdown" data-toggle="dropdown" aria-expanded="true">
          <span data-bind="label">30 Minutes</span>&nbsp;<span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu" aria-labelledby="rtt_dropdown">
          <li role="presentation"><a onclick="change_rtt_period('rtt_line_1min'); return false;" href=""role="menuitem" tabindex="-1">1 minute</a></li>
          <li role="presentation"><a onclick="change_rtt_period('rtt_line_10min'); return false;" href=""role="menuitem" tabindex="-1">10 minutes</a></li>
          <li role="presentation"><a onclick="change_rtt_period('rtt_line_30min'); return false;" href="" role="menuitem" tabindex="-1">30 minutes</a></li>
          <li role="presentation"><a onclick="change_rtt_period('rtt_line_1hour'); return false;" href="" role="menuitem" tabindex="-1">1 hour</a></li>
        </ul>
      </div>
      -->
      <label>From: </label>
      <div class="dropdown btn-group">
        <button class="btn btn-default dropdown-toggle" type="button" id="rtt-from-dropdown" data-toggle="dropdown" aria-expanded="true">
          <span data-bind="label" id="rtt-from-span">Select start</span>&nbsp;<span class="caret"></span>
        </button>
        <ul class="dropdown-menu" id="plot-from-dropdown-list" role="menu" aria-labelledby="plot-from-dropdown">
          <li class="rtt-from-dropdown-element" role="presentation" value="1"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >1 hour ago</a></li>
          <li class="rtt-from-dropdown-element" role="presentation" value="3"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >3 hours ago</a></li>
          <li class="rtt-from-dropdown-element" role="presentation" value="6"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >6 hours ago</a></li>
          <li class="rtt-from-dropdown-element" role="presentation" value="12"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >12 hours ago</a></li>
          <li class="rtt-from-dropdown-element" role="presentation" value="24"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >1 day ago</a></li>
          <li class="rtt-from-dropdown-element" role="presentation" value="168"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >1 week ago</a></li>
          <li class="rtt-from-dropdown-element" role="presentation" value="744"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >1 month ago</a></li>
          <li class="rtt-from-dropdown-element" role="presentation" value="-1"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >Forever</a></li>
        </ul>
      </div>

      <div id="rtt_graph"></div>
      <div id="no_rtt_graph" class="no_data" style="display: none;"><font size="20">No data.</font></div>

      <div id="latest_rtt" class="panel panel-default panel-body">
          <h3>Latest</h3>
          <table id="latest_rtt_table" class="table table-condensed">
            <thead></thead>
            <tr><th>Time</th><th>Min (ms)</th><th>Max (ms)</th><th>Avg (ms)</th><th>Dev (ms)</th></tr>
          </table>
      </div>

      <div class="panel panel-default panel-body">
        <h3>Send request</h3>
        <form class="form-inline request_form" id="ping_form" onsubmit="ping_validate()">
          <div class="form-group">
            <label for="rtt_ittr">Iterations: </label>
            <input name="rtt_ittr" class="form-control" id="rtt_ittr" value="5">
          </div>
          <button class="btn btn-success">Send</button>
        </form>
      </div>

    </div>

    <div class="container panel panel-default" id="throughput_container">
     
      <h2 id="bw_title">TCP Throughput - <small>Let TCP and the OS work it out</small></h2>

      <!-- 
      Period: 
      <div class="dropdown btn-group">
        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
          <span data-bind="label">1 Hour</span>&nbsp;<span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
          <li role="presentation"><a onclick="change_bw_period('bw_line_5min'); return false;" href=""role="menuitem" tabindex="-1">5 minutes</a></li>
          <li role="presentation"><a onclick="change_bw_period('bw_line_30min'); return false;" href="" role="menuitem" tabindex="-1">30 minutes</a></li>
          <li role="presentation"><a onclick="change_bw_period('bw_line_1hour'); return false;" role="menuitem" tabindex="-1">1 hour</a></li>
        </ul>
      </div>
      -->
      <label>From: </label>
      <div class="dropdown btn-group">
        <button class="btn btn-default dropdown-toggle" type="button" id="bw-from-dropdown" data-toggle="dropdown" aria-expanded="true">
          <span data-bind="label" id="bw-from-span">Select start</span>&nbsp;<span class="caret"></span>
        </button>
        <ul class="dropdown-menu" id="plot-from-dropdown-list" role="menu" aria-labelledby="plot-from-dropdown">
          <li class="bw-from-dropdown-element" role="presentation" value="1"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >1 hour ago</a></li>
          <li class="bw-from-dropdown-element" role="presentation" value="3"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >3 hours ago</a></li>
          <li class="bw-from-dropdown-element" role="presentation" value="6"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >6 hours ago</a></li>
          <li class="bw-from-dropdown-element" role="presentation" value="12"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >12 hours ago</a></li>
          <li class="bw-from-dropdown-element" role="presentation" value="24"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >1 day ago</a></li>
          <li class="bw-from-dropdown-element" role="presentation" value="168"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >1 week ago</a></li>
          <li class="bw-from-dropdown-element" role="presentation" value="744"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >1 month ago</a></li>
          <li class="bw-from-dropdown-element" role="presentation" value="-1"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >Forever</a></li>
        </ul>
      </div>

      <div id="bw_graph"></div>
      <div id="no_bw_graph" class="no_data" style="display: none;"><font size="20">No data.</font></div>

      <div id="latest_bw" class="panel panel-default panel-body">
          <h3>Latest</h3>
          <table id="latest_bw_table" class="table table-condensed">
            <tr><th>Time</th><th>Transfered (Bytes)</th><th>Duration (Seconds)</th><th>Speed (kbps)</th></tr>
          </table>
      </div>

      <div class="panel panel-default panel-body">
        <h3>Send Request</h3>
        <form class="form-inline request_form" id="iperf_form" onsubmit="iperf_validate()">
          <div class="form-group">
            <label for="bw_time">Time (seconds):</label>
            <input name="bw_time" class="form-control" id="bw_time" value="10">
          </div>
          <button class="btn btn-success">Send</button>
        </form>
      </div>
    </div>

    <div class="container panel panel-default panel-body" id="udp_container">
      <h2>UDP measurments</h2>

      <div class="panel panel-default panel-body">
        <h3>Latest</h3>
        <table id="udp_table" class="table table-condensed">
          <tr><th>Time</th><th>Send Speed (kbps)</th><th>DSCP flag</th><th>Transfered (Bytes)</th><th>Jitter (ms)</th><th>Lost Datagrams (%)</th><th>Bandwidth (kbps)</th></tr>
        </table>
      </div>

      <div class="panel panel-default panel-body">
        <h3>Send Request</h3>
        <form class="form-inline request_form" id="udp_form" onsubmit="udp_validate()">
          <div class="form-group">
            <label for="udp_speed">Send speed (kbps):</label>
            <input name="udp_speed" class="form-control" id="udp_speed" value="1">
          </div>
          <div class="form-group">
            <label for="udp_size">Datagram size (bytes):</label>
            <input name="udp_size" class="form-control" id="udp_size" value="1470">
          </div>
          <div class="form-group">
            <label for="udp_time">Duration (seconds):</label>
            <input name="udp_time" class="form-control" id="udp_time" value="10">
          </div>
        </br>
        </br>
          <b>DSCP Flag: </b>
          <input name="udp_dscp" id="udp-dscp" value="0x00" type="hidden">
          <div class="btn-group">
              <button type="button" class="form-control btn btn-default dropdown-toggle dscp-toggle" data-toggle="dropdown">
                <span data-bind="label">None</span>&nbsp;<span class="caret"></span>
              </button>
              <ul class="dropdown-menu dscp-menu" role="menu">
                  <li><a href="" data-hex="0x00" onclick="return false;">none</a></li>
                  <li><a href="" data-hex="32" onclick="return false;">cs1</a></li>
                  <li><a href="" data-hex="64" onclick="return false;">cs2</a></li>
                  <li><a href="" data-hex="96" onclick="return false;">cs3</a></li>
                  <li><a href="" data-hex="40" onclick="return false;">af11</a></li>
                  <li><a href="" data-hex="48" onclick="return false;">af12</a></li>
                  <li><a href="" data-hex="56" onclick="return false;">af13</a></li>
                  <li><a href="" data-hex="176" onclick="return false;">voice-admit</a></li>
                  <li><a href="" data-hex="184" onclick="return false;">ef</a></li>
              </ul>
          </div>
        </br>
        </br>
          <button class="btn btn-success">UDP iperf</button>
        </form>
      </div>      

    </div>

    <div class="container panel panel-default panel-body" id="dns_container">
      <h2>DNS Status <span style="color:green" class="glyphicon glyphicon-ok" aria-hidden="true"></span> </h2>

      <div class="panel panel-default panel-body">
        <h3 id="dns_title">DNS resolution</h3>

        <!--
        Period: 
        <div class="dropdown btn-group">
          <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
            <span data-bind="label">10 Minutes</span>&nbsp;<span class="caret"></span>
          </button>
          <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
            <li role="presentation"><a onclick="change_dns_period('dns_line_1min'); return false;" href=""role="menuitem" tabindex="-1">1 minute</a></li>
            <li role="presentation"><a onclick="change_dns_period('dns_line_10min'); return false;" href=""role="menuitem" tabindex="-1">10 minutes</a></li>
            <li role="presentation"><a onclick="change_dns_period('dns_line_30min'); return false;" href="" role="menuitem" tabindex="-1">30 minutes</a></li>
            <li role="presentation"><a onclick="change_dns_period('dns_line_1hour'); return false;" role="menuitem" tabindex="-1">1 hour</a></li>
          </ul>
        </div>
        -->

        <label>From: </label>
        <div class="dropdown btn-group">
          <button class="btn btn-default dropdown-toggle" type="button" id="dns-from-dropdown" data-toggle="dropdown" aria-expanded="true">
            <span data-bind="label" id="dns-from-span">Select start</span>&nbsp;<span class="caret"></span>
          </button>
          <ul class="dropdown-menu" id="dns-from-dropdown-list" role="menu" aria-labelledby="dns-from-dropdown">
            <li class="dns-from-dropdown-element" role="presentation" value="1"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >1 hour ago</a></li>
            <li class="dns-from-dropdown-element" role="presentation" value="3"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >3 hours ago</a></li>
            <li class="dns-from-dropdown-element" role="presentation" value="6"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >6 hours ago</a></li>
            <li class="dns-from-dropdown-element" role="presentation" value="12"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >12 hours ago</a></li>
            <li class="dns-from-dropdown-element" role="presentation" value="24"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >1 day ago</a></li>
            <li class="dns-from-dropdown-element" role="presentation" value="168"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >1 week ago</a></li>
            <li class="dns-from-dropdown-element" role="presentation" value="744"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >1 month ago</a></li>
            <li class="dns-from-dropdown-element" role="presentation" value="-1"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >Forever</a></li>
          </ul>
        </div>

        <div id="dns_graph"></div>
        <div id="no_dns_graph" class="no_data" style="display: none;"><font size="20">No data.</font></div>
      </div>

      <div class="panel panel-default panel-body">
        <h3 id="dns_outages">Outages</h3>
        <table id="dns_table" class="table table-condensed">
          <tr><th>Time</th><th>Duration (min)</th></tr>
        </table>
      </div>

      <div class="panel panel-default panel-body">
        <form class="form-inline request_form" id="dns_form" onsubmit="dns_validate()">
          <h3>Send Request</h3>
          <div class="form-group">
            <label for="dns_domain_name">URL:</label>
            <input name="dns_domain_name" class="form-control" id="dns_domain_name" value="google.co.uk">
          </div>

          <div class="form-group">
            <label for="dns_server">URL:</label>
            <input name="dns_server" class="form-control" id="dns_server" value="default">
          </div>

          <button class="btn btn-success">Send</button>
        </form>
      </div>

    </div>

    <script src="http://d3js.org/d3.v3.js"></script>
    <script type="text/javascript">

        var bw_data = {};
        var rtt_data = {};
        var udp_data = {};
        var dns_data = {};
        var dns_failure_data = {};

        //graph svg's
        var bw_svg;
        var rtt_svg;
        var dns_svg;

        //var active_bw_line  = "bw_line_1hour";
        //var active_rtt_line = "rtt_line_30min";
        //var active_dns_line = "dns_line_10min";

        //change the period of bw graph
        /*function change_bw_period(val){
          active_bw_line = val;
          update_bw_graph(bw_data, 0);
        }*/

        //change the period of rtt graph
        /*function change_rtt_period(val){
          active_rtt_line = val;
          update_rtt_graph(rtt_data, 0);
        }*/

        //change the period of rtt graph
        /*function change_dns_period(val){
          active_dns_line = val;
          update_dns_graph(dns_data, 0);
        }*/

        //get sensor id from url - hopefully nothing else is passed - FIX!!
        var sensor_id = location.search.replace("?", "");
        document.getElementById("title").innerHTML = "Sensor " + sensor_id + " -> Server";

        //get sensor details
        $.getJSON("sensors.php", "sensor_id=" + sensor_id, function(data){
          var sensor_details = data[0];
          $("#desc-input").attr("placeholder", "Description: " + sensor_details.description);

          //if a disconnected sensor - disable all buttons excpet description update and graph periods
          if(sensor_details.active == 0){
            $(".btn").attr('disabled', 'disabled');
            $("#desc-form").removeAttr('disabled');
            $(".dropdown-toggle").removeAttr('disabled');
          }
        });

        //POST for updating sensor
        $("#desc-form").click(function(){
          console.log(encodeURI($("#desc-input").val()));

          $.ajax({
              url: "sensor_description_update.php",
              type: 'POST',
              data: "&sensor_id=" + sensor_id + "&description=" + encodeURI($("#desc-input").val()),
              success: function(data) {
                alert("Sensor " + sensor_id + " description updated to '" + $("#desc-input").val() + "'");
              }
          });
        });

        //POST for disconnect button
        $("#sensor-disc").click(function(){
          
          if(confirm("Disconnect sensor " + sensor_id + "?")){
            $.ajax({
              url: "disconnect_sensor.php",
              type: 'POST',
              data: "&sensor_id=" + sensor_id,
              success: function(data) {
                console.log(data);
              }
            });
          }
          
        });

        function iperf_validate(){
          console.log("iperf_validate");
        }

        function ping_validate(){
          console.log("ping_validate");
          return false;
        }

        function udp_validate(){
          console.log("udp_validate");
        }

        var dscp_flag = 0x00;
        //update dscp flag for submit
        $('.dscp-menu a').on('click', function(){    
            $('#udp-dscp').val($(this).attr('data-hex'));    
        });

        //update text on dropdowns
        $(document.body).on('click', '.dropdown-menu li', function(event){
            var $target = $(event.currentTarget);
            $target.closest('.btn-group')
            .find('[data-bind="label"]').text($target.text())
            .end()
            .children('.dropdown-toggle').dropdown( 'toggle');

            if($target.attr('class') == "rtt-from-dropdown-element"){
              if($target.val() == -1){
                rtt_from = "none";
              }else{
                rtt_from = new Date();
                rtt_from.setHours(rtt_from.getHours() - $target.val());
              }
              update_rtt_graph();
            }else if($target.attr('class') == "bw-from-dropdown-element"){
              if($target.val() == -1){
                bw_from = "none";
              }else{
                bw_from = new Date();
                bw_from.setHours(bw_from.getHours() - $target.val());
              }
              update_bw_graph();
            }else if($target.attr('class') == "dns-from-dropdown-element"){
              if($target.val() == -1){
                dns_from = "none";
              }else{
                dns_from = new Date();
                dns_from.setHours(dns_from.getHours() - $target.val());
              }
              update_dns_graph();
            }
        });

        /*
        * Send request
        */
        $(".request_form").submit(function(event){
          event.preventDefault();
          
          var request_type;
          if(this.id == "ping_form")
            request_type = "ping";
          else if(this.id == "iperf_form")
            request_type = "iperf";
          else if(this.id == "udp_form")
            request_type = "udp";
          else if(this.id == "dns_form")
            request_type = "dns";

          console.log($(this).serialize());

          $.ajax({
              url: "request.php",
              type: 'POST',
              data: $(this).serialize() + "&sensor_id=" + sensor_id + "&dst_id=1" + "&request_type=" + request_type,
              success: function(data) {
                  alert(data);
              }
          });
        });

        //set margins for graphs
        var margin = {top: 20, right: 20, bottom: 30, left: 50},
        width = $("#rtt_container").width() - margin.left - margin.right,
        height = 500 - margin.top - margin.bottom;

        var parseDate = d3.time.format("%Y-%m-%d %H:%M:%S").parse;

        /*
        * rtt graph
        */
        var rtt_from = "none";
        var rtt_x = d3.time.scale()
            .range([0, width]);

        var rtt_y = d3.scale.linear()
            .range([height, 0]);

        var rtt_xAxis = d3.svg.axis()
            .scale(rtt_x)
            .orient("bottom");

        var rtt_yAxis = d3.svg.axis()
            .scale(rtt_y)
            .orient("left");

        var rtt_line = d3.svg.line()
            .x(function(d) { return rtt_x(d.time); })
            .y(function(d) { return rtt_y(d.avg); });
 
        /* 
        * bandwidth graph
        */
        var bw_from = "none";
        var bw_x = d3.time.scale()
            .range([0, width]);

        var bw_y = d3.scale.linear()
            .range([height, 0]);

        var bw_xAxis = d3.svg.axis()
            .scale(bw_x)
            .orient("bottom");

        var bw_yAxis = d3.svg.axis()
            .scale(bw_y)
            .orient("left");

        var bw_line = d3.svg.line()
            .x(function(d) { return bw_x(d.time); })
            .y(function(d) { return bw_y(d.speed); });

        /* 
        * dns resoloution graph
        */
        var dns_from = "none";
        var dns_x = d3.time.scale()
            .range([0, width]);

        var dns_y = d3.scale.linear()
            .range([height, 0]);

        var dns_xAxis = d3.svg.axis()
            .scale(dns_x)
            .orient("bottom");

        var dns_yAxis = d3.svg.axis()
            .scale(dns_y)
            .orient("left");

        var dns_line = d3.svg.line()
            .x(function(d) { return dns_x(d.time); })
            .y(function(d) { return dns_y(d.duration); });
        
        load_bw();
        function load_bw(){
          $.getJSON("metric.php", "type=tcp&sensor_id=" + sensor_id + "&dst_id=1", function(jsonData){

            if(jsonData.length != bw_data.length){
              jsonData.forEach(function(d) {
                d.time        = parseDate(d.time);
                d.speed       = d.speed / 1024;
              });
              bw_data = jsonData
              update_bw_graph(1);
            }
          });

          setTimeout(load_bw, 5000);
        }

        load_rtt();
        function load_rtt(){
          $.getJSON("metric.php", "type=rtt&sensor_id=" + sensor_id + "&dst_id=1", function(jsonData){
            if(jsonData.length != rtt_data.length){
              jsonData.forEach(function(d) {
                d.time        = parseDate(d.time);
              });
              rtt_data = jsonData;
              update_rtt_graph(1);
            }
          });
          setTimeout(load_rtt, 5000);
        }

        load_udp();
        function load_udp(){
          $.getJSON("metric.php", "type=udp&sensor_id=" + sensor_id + "&dst_id=1", function(jsonData){

            if(jsonData.length == 0){
              var table = document.getElementById("udp_table");
              table.innerHTML = "No data";
              
            }else if(jsonData.length != udp_data.length){
              var table = document.getElementById("udp_table");
              table.innerHTML = "<tr><th>Time</th><th>Send Speed (kbps)</th><th>DSCP flag</th><th>Transfered (Bytes)</th><th>Jitter (ms)</th><th>Lost Datagrams (%)</th><th>Bandwidth (kbps)</th></tr>";

              for(var i = 0; i < jsonData.length; i++){
                var row = table.insertRow(i + 1);
                row.insertCell(0).innerHTML = jsonData[i].time;
                row.insertCell(1).innerHTML = jsonData[i].send_bw;
                row.insertCell(2).innerHTML = jsonData[i].dscp_flag;
                row.insertCell(3).innerHTML = jsonData[i].size;
                row.insertCell(4).innerHTML = jsonData[i].jitter;
                row.insertCell(5).innerHTML = jsonData[i].packet_loss;
                row.insertCell(6).innerHTML = jsonData[i].bw;    
              }
                
              udp_data = jsonData;
            }
          });
          setTimeout(load_udp, 5000);
        }

        load_dns();
        function load_dns(){
          $.getJSON("metric.php", "type=dns&sensor_id=" + sensor_id, function(jsonData){
            if(jsonData.length != dns_data.length){
              jsonData.forEach(function(d) {
                d.duration    = +d.duration;
                d.time        = parseDate(d.time);
              });
              dns_data = jsonData;
              update_dns_graph(1);
            }
          });
          setTimeout(load_dns, 5000);
        }

        load_dns_failure();
        function load_dns_failure(){
          $.getJSON("metric.php", "type=dns_failure&sensor_id=" + sensor_id, function(jsonData){

            if(jsonData.length == 0){
              var table = document.getElementById("dns_table");
              table.innerHTML = "<h3 id='dns_outages'>No data</h3>";
            }else if(jsonData.length != dns_failure_data.length){
              var table = document.getElementById("dns_table");
              table.innerHTML = "<tr><th>Time</th><th>Duration (min)</th></tr>";

              var dur = 1;
              var rows = 0;
              var i = 0;
              while(i < jsonData.length){
                if(dur == 1)
                  start = jsonData[i].time;
                     

                if(i < jsonData.length-1){
                  var current = parseInt(jsonData[i].time.substring(14,16));
                  var next    = parseInt(jsonData[i+1].time.substring(14,16));
                  var diff = next - current;
                }else{
                  var diff = 2;
                }

                if(diff > 1){
                  rows ++;

                  var row = table.insertRow(rows);
                  row.insertCell(0).innerHTML = start;
                  row.insertCell(1).innerHTML = dur;

                  dur = 1;
                }else{
                  dur++;
                }

                i++;
              }
              dns_failure_data = jsonData;
            }
          });
          setTimeout(load_dns, 5000);
        }
        
        //updates the bandwidth graph data
        function update_bw_graph(auto){

          d3.select("#bw_graph").select("svg").remove();

          bw_svg = d3.select("#bw_graph").append("svg")
            .attr("width", width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom)
            .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
            
          if(auto){
            var table = document.getElementById("latest_bw_table");
            var row = table.insertRow(1);
            row.insertCell(0).innerHTML = bw_data[bw_data.length-1].time;
            row.insertCell(1).innerHTML = bw_data[bw_data.length-1].bytes;
            row.insertCell(2).innerHTML = bw_data[bw_data.length-1].duration;
            row.insertCell(3).innerHTML = bw_data[bw_data.length-1].speed;
          }

          /*var bw_average;
          if(active_bw_line == 'bw_line_5min')
            bw_average = function(d){return d;};
          else if(active_bw_line == 'bw_line_30min')
            bw_average = simple_moving_averager(6);
          else if(active_bw_line == 'bw_line_1hour')
            bw_average = simple_moving_averager(12);*/

          var graph_data;
          if(bw_from != "none"){
            graph_data = truncate_data(bw_data, bw_from);
          }else{
            graph_data = bw_data;
          }

          if(graph_data.length == 0){
            $("#bw_graph").hide();
            $("#no_bw_graph").show();
            return;
          }else{
            $("#bw_graph").show();
            $("#no_bw_graph").hide();
          }

          bw_x.domain(d3.extent(graph_data, function(d) { return d.time; }));
          bw_y.domain(d3.extent(graph_data, function(d) { return d.speed; }));

          bw_svg.append("g")
              .attr("class", "x axis")
              .attr("transform", "translate(0," + height + ")")
              .call(bw_xAxis);

          bw_svg.append("g")
              .attr("class", "y axis")
              .call(bw_yAxis)
              .append("text")
              .attr("transform", "rotate(-90)")
              .attr("y", 6)
              .attr("dy", ".71em")
              .style("text-anchor", "end")
              .text("Throughput (kbps)");

          bw_svg.append("path")
              .datum(graph_data)
              .attr("class", "line")
              .attr("d", bw_line);
        }

        //updates the bandwidth graph data
        //should remove svg first 
        function update_rtt_graph(auto){

          d3.select("#rtt_graph").select("svg").remove();
          rtt_svg = d3.select("#rtt_graph").append("svg")
            .attr("width", width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom)
            .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

          if(auto){
            var table = document.getElementById("latest_rtt_table");
            var row = table.insertRow(1);
            row.insertCell(0).innerHTML = rtt_data[rtt_data.length-1].time;
            row.insertCell(1).innerHTML = rtt_data[rtt_data.length-1].min;
            row.insertCell(2).innerHTML = rtt_data[rtt_data.length-1].max;
            row.insertCell(3).innerHTML = rtt_data[rtt_data.length-1].avg;
            row.insertCell(4).innerHTML = rtt_data[rtt_data.length-1].dev;
          }

          /*var rtt_average;
          if(active_rtt_line == 'rtt_line_1min')
            rtt_average = function(d){return d;};
          else if(active_rtt_line == 'rtt_line_10min')
            rtt_average = simple_moving_averager(10);
          else if(active_rtt_line == 'rtt_line_30min')
            rtt_average = simple_moving_averager(30);
          else if(active_rtt_line == 'rtt_line_1hour')
            rtt_average = simple_moving_averager(60);*/

          var graph_data;
          if(rtt_from != "none"){
            graph_data = truncate_data(rtt_data, rtt_from);
          }else{
            graph_data = rtt_data;
          }

          if(graph_data.length == 0){
            $("#rtt_graph").hide();
            $("#no_rtt_graph").show();
            return;
          }else{
            $("#rtt_graph").show();
            $("#no_rtt_graph").hide();
          }

          rtt_x.domain(d3.extent(graph_data, function(d) { return d.time; }));
          rtt_y.domain(d3.extent(graph_data, function(d) { return d.avg; }));

          rtt_svg.append("g")
              .attr("class", "x axis")
              .attr("transform", "translate(0," + height + ")")
              .call(rtt_xAxis)

          rtt_svg.append("g")
              .attr("class", "y axis")
              .call(rtt_yAxis)
              .append("text")
              .attr("transform", "rotate(-90)")
              .attr("y", 6)
              .attr("dy", ".71em")
              .style("text-anchor", "end")
              .text("RTT (ms)");

          rtt_svg.append("path")
              .datum(graph_data)
              .attr("class", "line")
              .attr("d", rtt_line); 
        }

        function update_dns_graph(auto){

          d3.select("#dns_graph").select("svg").remove();

          dns_svg = d3.select("#dns_graph").append("svg")
            .attr("width", width + margin.left + margin.right)
            .attr("height", height + margin.top + margin.bottom)
            .append("g")
            .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

          /*var dns_average;
          if(active_dns_line == 'dns_line_1min')
            dns_average = function(d){return d;};
          else if(active_dns_line == 'dns_line_10min')
            dns_average = simple_moving_averager(10);
          else if(active_dns_line == 'dns_line_30min')
            dns_average = simple_moving_averager(30);
          else if(active_dns_line == 'dns_line_1hour')
            dns_average = simple_moving_averager(60);*/

          var graph_data;
          if(dns_from != "none"){
            graph_data = truncate_data(dns_data, dns_from);
          }else{
            graph_data = dns_data;
            console.log("forever");
          }

          if(graph_data.length == 0){
            $("#dns_graph").hide();
            $("#no_dns_graph").show();
            return;
          }else{
            $("#dns_graph").show();
            $("#no_dns_graph").hide();
          }

          dns_x.domain(d3.extent(graph_data, function(d) { return d.time; }));
          dns_y.domain(d3.extent(graph_data, function(d) { return d.duration; }));

          dns_svg.append("g")
              .attr("class", "x axis")
              .attr("transform", "translate(0," + height + ")")
              .call(dns_xAxis)

          dns_svg.append("g")
              .attr("class", "y axis")
              .call(dns_yAxis)
              .append("text")
              .attr("transform", "rotate(-90)")
              .attr("y", 6)
              .attr("dy", ".71em")
              .style("text-anchor", "end")
              .text("Resolution time (ms)");

          dns_svg.append("path")
              .datum(graph_data)
              .attr("class", "line")
              .attr("d", dns_line); 
        }

        function simple_moving_averager(period) {
          var nums = [];
          return function(num) {
              nums.push(num);
              if (nums.length > period)
                  nums.splice(0,1);  // remove the first element of the array
              var sum = 0;
              for (var i in nums)
                  sum += nums[i];
              var n = period;
              if (nums.length < period)
                  n = nums.length;
              return(sum/n);
          }
        }

        function truncate_data(data, start){
          var new_data = [];
          for(x in data){
            if(data[x].time > start){
              new_data.push(data[x]);
            }
          }
          return new_data;
        }

    </script>

  </body>
</html>
