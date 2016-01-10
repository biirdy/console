<?php
  include('session.php'); 
?>

<html lang="en"><head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Web management console for weperf sensors">
    <meta name="author" content="Jamie Bird">
    <link rel="icon" href="res/favicon.ico">

    <title>weperf - Sensor Management</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <link href="/css/index.css" rel="stylesheet">

    <!-- single table row links -->
    <link href="/css/jasny-bootstrap.min.css" rel="stylesheet">
  
    <!-- jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

    <!-- Datatables -->
    <script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>

    <!-- vis.js -->
    <script type="text/javascript" src="/js/vis.js"></script>
    <link href="/css/vis.css" rel="stylesheet" type="text/css"/>

    <!-- d3 -->
    <script src="http://d3js.org/d3.v3.js"></script>
    <link href="/css/d3.css" rel="stylesheet" type="text/css"/>

  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">

        <!-- Nav bar header -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
          <a class="navbar-brand" href="#">weperf - Sensor Management</a>
          
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
            <b id="logout" class="navbar-right navbar-text"><a href="logout.php" >Log Out</a></b>
            <b id="usr-management" class="navbar-right navbar-text"><a href="user_management.php" >User Management</a></b>
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

        <div class="row container">
          <div class="col-lg-10">
            <h1>Sensors</h1>
          </div>
          <div class="col-lg-2 legend-row">
            <ul class="legend">
                <li><span class="green-legend"></span> Active</li>
                <li><span class="red-legend"></span> Inactive</li>
            </ul>
          </div>
        </div>

        <table class="table table-hover rowlink" data-link="row" id="sensors">
          <tr><th>ID</th><th>Ethernet</th><th>IP</th><th>Local IP</th><th>Connect Time</th><th>Disconnect Time</th><th>Description</th></tr>
        </table>
     

      </div>

      <!-- Topology Panel-->
      <div class="container panel panel-default panel-body" id="topology-con">
        <h1>Topology</h1>

        <div class="panel-body container panel panel-default topo-radio" id="topo-filter">
          <p><b>Filter</b></p>
          <div class="radio">
            <label><input data-filter-type="none" data-filter-id="0" type="radio" name="filter-radio" checked>None</label>
          </div>
        </div>

        <div id="topo-div"></div>
        <div id="no-topo-div" class="no_data" style="display: none;"><font size="20">No data.</font></div>
        
        <div class="panel-body container panel panel-default topo-radio" id="topo-feature">
          <p><b>Link feature</b></p>
          <div class="radio">
            <label><input type="radio" name="feature-radio" id="feature-rtt">Round Trip Time</label>
          </div>
          <div class="radio">
            <label><input type="radio" name="feature-radio" id="feature-bw" checked>TCP Bandwidth</label>
          </div>
          <div class="radio">
            <label><input type="radio" name="feature-radio" id="feature-jitter">Jitter</label>
          </div>
          <div class="radio">
            <label><input type="radio" name="feature-radio" id="feature-packetloss">Packet Loss</label>
          </div>
        </div>

        <div class="panel-body container panel panel-default topo-radio" id="topo-label">
          <p><b>Sensor Label</b></p>
          <div class="radio">
            <label><input type="radio" name="label-radio" id="mac">MAC address</label>
          </div>
          <div class="radio">
            <label><input type="radio" name="label-radio" id="ip">IP address</label>
          </div>
          <div class="radio">
            <label><input type="radio" name="label-radio" id="id">Sensor ID</label>
          </div>
          <div class="radio">
            <label><input type="radio" name="label-radio" id="description" checked>Description</label>
          </div>
        </div>

      </div>

      <!-- Schedule Panel -->
      <div class="container panel panel-default panel-body" id="schedules-con">
        
        <div class="row container">
          <div class="col-lg-10">
            <h1>Schedules</h1>
          </div>
          <div class="col-lg-2 legend-row">
            <ul class="legend">
                <li><span class="green-legend"></span> Active</li>
                <li><span class="orange-legend"></span> Fault</li>
                <li><span class="red-legend"></span> Inactive</li>
            </ul>
          </div>
        </div>
        
        
        <!-- Schedule Table-->
        <table class="table table-hover" id="schedules">
          <thead>
            <tr><th></th><th>Name</th><th>Description</th><th>Interval</th><th>Start/Suspend</th><th>Edit</th><th>Remove</th></tr>
          </thead>
        </table>

        <form class="form-inline" role="form">
          <button type="button" class="btn btn-success create-btn" data-toggle="modal" data-target="#create-schedule">Create Schedule</button>
        </form>  
      </div>

      <!-- Create Schedule Modal -->
      <div class="modal fade" id="create-schedule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">

            <!-- Modal Header-->
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="schedule-title">Create Schedule</h4>
            </div>

            <form role="form" id="create-schedule-form">
            <!-- Modal body-->
            <div class="modal-body">

              <input value='0' id='schedule-id' name='schedule_id' style='display: none;' class="form-control" type="hidden"></input>

              <div class="form-group">
                <label for="name">Name: </label>
                <input id="schedule-name" name="name" class="form-control" placeholder="Name:">
              </div>

              <div class="form-group">
                <label for="description">Description: </label>
                <input id="schedule-description" name="description" class="form-control" placeholder="Description:">
              </div>

              <div class="form-group form-inline">
                <label >Add measurement: </label>
                <button type="button" data-toggle="modal" class="btn btn-primary" id="add-schedule-measurement-btn" data-target="#add-measurement">+</button> 
              </div>

              <table class="table table-hover" id="create-schedule-table">
                <tr><th>Source</th><th>Destination</th><th>Type</th><th>Params</th><th>Delay</th><th>Remove</th></tr>
                <input id="create-schedule-index" value="0" style="display: none;" type="hidded">
              </table>

              <div class="form-group">
                <label for="interval">Interval: </label>
                <table>
                  <tr><td>
                    <div class="input-group">
                      <input id="schedule-hours" name="hours" type="number" class="form-control" value="1" aria-describedby="basic-addon2">
                      <span class="input-group-addon" id="basic-addon2">Hours</span>
                    </div>
                  </td><td>
                    <div class="input-group">
                      <input id="schedule-minutes" name="minutes" type="number" class="form-control" value="0" aria-describedby="basic-addon2">
                      <span class="input-group-addon" id="basic-addon2">Minues</span>
                    </div>
                  </td><td>
                    <div class="input-group">
                      <input id="schedule-seconds" name="seconds" type="number" class="form-control" value="0" aria-describedby="basic-addon2">
                      <span class="input-group-addon" id="basic-addon2">Seconds</span>
                    </div>
                  </td></tr>
                </table>
              </div>
              
            </div>

            <!-- Modal Footer-->
            <div class="modal-footer">
              <button type="button" class="btn btn-primary clear-btn" id="create-schedule-clear">Clear</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              <button disabled id="schedule-submit" type="submit" class="btn btn-success">Create Schedule</button>
            </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Create Schedule Measument Modal-->
      <div class="modal fade" id="add-measurement" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">

            <!-- Modal Header-->
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Add Measurement</h4>
            </div>


            <form role="form" id="add-measurement-form">
            <!-- Modal body-->
            <div class="modal-body">

                <div id="ip-warning" style="display: none;" class="alert alert-warning" role="alert"><b>Measurement might not work as expected!</b><br> Recipient does not have a public facing IP address.</div>
                <div id="same-sensor-warning" style="display: none;" class="alert alert-danger" role="alert"><b><i>To</i> and <i>from</i> cannot be the same sensor!</b></div>
                <div id="no-sensor-warning" style="display: none;" class="alert alert-danger" role="alert"><b>Select both <i>to</i> and <i>from</i> sensor!</b></div>
              
                <div class="form-group">

                  <div class="form-group">
                    <label for="source">Source: </label><br>
                    <div class="dropdown btn-group">
                      <button style="display: block; width: 100%;" class="btn btn-default dropdown-toggle" type="button" id="source-dropdown" data-toggle="dropdown" aria-expanded="true">
                        <span data-bind="label">Select source</span>&nbsp;<span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu" id="source-dropdown-list" role="menu" aria-labelledby="source-dropdown">
                      </ul>
                      <input id="source-input" class="dropdown-input" value="0" style="display: none;" name="source_id" class="form-control" type="hidded">
                      <input id="source-type" class="dropdown-type" value="0" style="display: none;" name="source_type" class="form-control" type="hidded">
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="destination">Destination: </label><br>
                    <div class="dropdown btn-group">
                      <button class="btn btn-default dropdown-toggle" type="button" id="destination-dropdown" data-toggle="dropdown" aria-expanded="true">
                        <span data-bind="label">Select destination</span>&nbsp;<span class="caret"></span>
                      </button>
                      <ul class="dropdown-menu" id="destination-dropdown-list" role="menu" aria-labelledby="destination-dropdown">
                      </ul>
                      <input id="destination-input" class="dropdown-input" value="0" style="display: none;" hidden name="destination_id" class="form-control" type="hidded">
                      <input id="destination-type" class="dropdown-type" value="0" style="display: none;" name="destination_type" class="form-control" type="hidded">
                    </div> 
                  </div>

                  <div class="form-group">
                  <label for="type">Measurement: </label>
                  <div class="radio">
                    <label><input type="radio" name="method" value="rtt" data-div="rtt-details" id="type-rtt">Round Trip Time</label>
                  </div>
                  <div class="radio">
                    <label><input type="radio" name="method" value="tcp" data-div="tcp-details" id="type-tcp">TCP throughput</label>
                  </div>
                  <div class="radio">
                    <label><input type="radio" name="method" value="udp" data-div="udp-details" id="type-udp">UDP measurements</label>
                  </div>
                  <div class="radio">
                    <label><input type="radio" name="method" value="dns" data-div="dns-details" id="type-dns">DNS resolution</label>
                  </div>
                  </div>

                  <div class="form-group" style="display: none;" id="rtt-details">
                    <label for="iterations">Iterations: </label>
                    <input type="number" name="iterations" class="form-control" id="rtt-details-itr" value="5">
                  </div>

                  <div class="form-group" style="display: none;" id="tcp-details">
                    <label for="duration">Duration (Seconds): </label>
                    <input type="number" name="duration" class="form-control" id="tcp-details-dur" value="10">
                  </div>

                  <div class="form-group" style="display: none;" id="udp-details">
                    <label for="speed">Send Speed (kbps): </label>
                    <input type="number" name="speed" class="form-control" id="udp-details-speed" value="1024">

                    <label for="size">Packet Size (bytes): </label>
                    <input type="number" name="size" class="form-control" id="udp-details-size" value="1024">

                    <label for="duration">Duration (Seconds): </label>
                    <input type="number" name="duration" class="form-control" id="udp-details-dur" value="10">

                    <label for="dscp">DSCP flag: </label>
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
                      <input style="display: none;" name="dscp" class="form-control" type="hidded">
                    </div>
                  </div>

                  <div class="form-group" style="display: none;" id="dns-details">
                    <label for="domain_name">URL: </label>
                    <input name="domain_name" class="form-control" id="dns-details-domain-name" value="google.co.uk">

                    <label for="server">Server: </label>
                    <input name="server" class="form-control" id="dns-details-server" value="8.8.8.8">
                  </div>

                  <div class="form-group">
                    <label for="interval">Delay: </label>
                    <table>
                      <tr><td>
                        <div class="input-group">
                          <input name="delay-hours" type="number" class="form-control" value="0" aria-describedby="basic-addon2">
                          <span class="input-group-addon" id="basic-addon2">Hours</span>
                        </div>
                      </td><td>
                        <div class="input-group">
                          <input name="delay-minutes" type="number" class="form-control" value="0" aria-describedby="basic-addon2">
                          <span class="input-group-addon" id="basic-addon2">Minues</span>
                        </div>
                      </td><td>
                        <div class="input-group">
                          <input name="delay-seconds" type="number" class="form-control" value="10" aria-describedby="basic-addon2">
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
              <button disabled id="measurement-submit" type="submit" class="btn btn-success">Add Measurement</button>
            </div>
            </form>
          </div>
        </div>
      </div>

      <!-- Schedule plot modal -->
      <div class="modal fade" id="schedule-plot-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" id="schedule-plot-dialog" role="document">
          <div class="modal-content">

            <!-- Modal Header-->
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="schedule-plot-title">Schedule Plot</h4>
            </div>

            <!-- Modal body-->
            <div class="modal-body">

              <table width="100%">

                <tr><td>
                  <label>Feature: </label>
                  <div class="dropdown btn-group">
                    <button class="btn btn-default dropdown-toggle" type="button" id="plot-feature-dropdown" data-toggle="dropdown" aria-expanded="true">
                      <span data-bind="label" id="plot-feature-span">Select feature</span>&nbsp;<span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" id="plot-feature-dropdown-list" role="menu" aria-labelledby="plot-feature-dropdown">
                    </ul>
                  </div>
                </td>

                <td>
                  <label>Source: </label>
                  <div class="dropdown btn-group">
                    <button class="btn btn-default dropdown-toggle" type="button" id="plot-source-dropdown" data-toggle="dropdown" aria-expanded="true">
                      <span data-bind="label" id="plot-source-span">Select source</span>&nbsp;<span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" id="plot-source-dropdown-list" role="menu" aria-labelledby="plot-source-dropdown">
                    </ul>
                  </div>
                </td>

                <td>
                  <label>Destination: </label>
                  <div class="dropdown btn-group">
                    <button class="btn btn-default dropdown-toggle" type="button" id="plot-destination-dropdown" data-toggle="dropdown" aria-expanded="true">
                      <span data-bind="label" id="plot-destination-span">Select destination</span>&nbsp;<span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" id="plot-destination-dropdown-list" role="menu" aria-labelledby="plot-destination-dropdown">
                    </ul>
                  </div>
                </td>

                <td>
                  <label>From: </label>
                  <div class="dropdown btn-group">
                    <button class="btn btn-default dropdown-toggle" type="button" id="plot-from-dropdown" data-toggle="dropdown" aria-expanded="true">
                      <span data-bind="label" id="plot-from-span">1 weekago</span>&nbsp;<span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" id="plot-from-dropdown-list" role="menu" aria-labelledby="plot-from-dropdown">
                      <li class="plot-from-dropdown-element" role="presentation" value="1"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >1 hour ago</a></li>
                      <li class="plot-from-dropdown-element" role="presentation" value="3"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >3 hours ago</a></li>
                      <li class="plot-from-dropdown-element" role="presentation" value="6"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >6 hours ago</a></li>
                      <li class="plot-from-dropdown-element" role="presentation" value="12"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >12 hours ago</a></li>
                      <li class="plot-from-dropdown-element" role="presentation" value="24"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >1 day ago</a></li>
                      <li class="plot-from-dropdown-element" role="presentation" value="168"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >1 week ago</a></li>
                      <li class="plot-from-dropdown-element" role="presentation" value="744"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >1 month ago</a></li>
                      <li class="plot-from-dropdown-element" role="presentation" value="-1"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >Forever</a></li>
                    </ul>
                  </div>
                </td>

                <td>
                  <label>Average: </label>
                  <div class="dropdown btn-group">
                    <button class="btn btn-default dropdown-toggle" type="button" id="plot-average-dropdown" data-toggle="dropdown" aria-expanded="true">
                      <span data-bind="label" id="plot-average-span">5 minutes</span>&nbsp;<span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" id="plot-average-dropdown-list" role="menu" aria-labelledby="plot-average-dropdown">
                      <li class="plot-average-dropdown-element" role="presentation" value="-1"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >None</a></li>
                      <li class="plot-average-dropdown-element" role="presentation" value="5"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >5 minutes</a></li>
                      <li class="plot-average-dropdown-element" role="presentation" value="30"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >30 minutes</a></li>
                      <li class="plot-average-dropdown-element" role="presentation" value="60"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >1 hour</a></li>
                      <li class="plot-average-dropdown-element" role="presentation" value="360"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >6 hours</a></li>
                      <li class="plot-average-dropdown-element" role="presentation" value="720"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >12 hours</a></li>
                      <li class="plot-average-dropdown-element" role="presentation" value="1440"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >1 day</a></li>
                      <li class="plot-average-dropdown-element" role="presentation" value="10080"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >1 week</a></li>
                    </ul>
                  </div>
                </td>

              <tr></table>

              <div id="schedule-plot"></div>
              <div id="no-schedule-plot" class="no_data" style="display: none;"><font size="20">No data.</font></div>

            </div>

            <!-- Modal Footer-->
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>

          </div>
        </div>
      </div>  

      <!-- Groups Panel -->
      <div class="container panel panel-default panel-body" id="groups-con">
        <div class="row container">
          <div class="col-lg-10">
            <h1>Groups</h1>
          </div>
          <div class="col-lg-2 legend-row">
            <ul class="legend">
                <li><span class="green-legend"></span> All Active</li>
                <li><span class="orange-legend"></span> Partly Active</li>
                <li><span class="red-legend"></span> All Inactive</li>
            </ul>
          </div>
        </div>

        <!-- Groups Table-->
        <table class="table table-hover" id="groups">
          <thead>
            <tr><th></th><th>Name</th><th># sensors</th><th>Description</th><th style='text-align: center;'>Edit</th><th style='text-align: center;'>Delete</th></tr>
          </thead>
        </table>

        <form class="form-inline" role="form">
          <button type="button" class="btn btn-success create-btn" data-toggle="modal" data-target="#create-group">Create Group</button>
        </form>
      </div>

      <!-- Create group modal -->
      <div class="modal fade" id="create-group" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">

            <!-- Modal Header-->
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="group-title">Create Group</h4>
            </div>


            <form role="form" id="create-group-form">
            <!-- Modal body-->
            <div class="modal-body">

              <input value='0' id='group-id' name='group_id' style='display: none;' class="form-control" type="hidden"></input>

              <div class="form-group">
                <label for="name">Name: </label>
                <input id="group-name" name="name" class="form-control" placeholder="Name:">
              </div>

              <div class="form-group">
                <label for="description">Description: </label>
                <input id="group-description" name="description" class="form-control" placeholder="Description:">
              </div>

              <div class="form-group">
                <label for="to">Add sensor: </label><br>
                <div class="dropdown btn-group">
                  <button class="btn btn-default dropdown-toggle" type="button" id="add-sensor-dropdown" data-toggle="dropdown" aria-expanded="true">
                    <span data-bind="label">Select sensor</span>&nbsp;<span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" id="add-sensor-dropdown-list" role="menu" aria-labelledby="add-sensor-dropdown">
                  </ul>
                </div>
                <button disabled type="button" class="btn btn-success" id="add-sensor-btn">Add sensor</button> 
              </div>

              <table class="table table-hover" id="create-group-table">
                <tr><th>ID</th><th>Description</th><th>Remove</th></tr>
              </table>
              
            </div>

            <!-- Modal Footer-->
            <div class="modal-footer">
              <button type="button" class="btn btn-primary clear-btn" id="create-group-clear">Clear</button>
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              <button disabled id="group-submit" type="submit" class="btn btn-success">Create Group</button>
            </div>
            </form>
          </div>
        </div>
      </div>

      <div class="container panel panel-default panel-body" id="alarms-con">
        <div class="row container">
          <div class="col-lg-10">
            <h1>Alarms</h1>
          </div>
          <div class="col-lg-2 legend-row">
            <ul class="legend">
                <li><span class="green-legend"></span> Okay </li>
                <li><span class="red-legend"></span> Alarm </li>
            </ul>
          </div>
        </div>

        <!-- Groups Table-->
        <table class="table table-hover" id="alarms">
          <thead>
            <tr><th></th><th>Name</th><th>On</th><th>Feature</th><th>Operator</th><th>Threshold</th><th>Recipient</th><th style='text-align: center;'>Delete</th></tr>
          </thead>
        </table>

        <form class="form-inline" role="form">
          <button type="button" class="btn btn-success create-btn" data-toggle="modal" data-target="#create-alarm">Create Alarm</button>
        </form>
      </div>

      <!-- Create alarm modal -->
      <div class="modal fade" id="create-alarm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">

            <!-- Modal Header-->
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Create Alarm</h4>
            </div>

            <form role="form" id="create-alarm-form">

            <!-- Modal body-->
            <div class="modal-body">

              <div id="alarm-no-data-warning" style="display: none;" class="alert alert-danger" role="alert"><b>Insufficient data to create alarm.</b></div>

              <div class="form-group">
                <label for="name">Name: </label>
                <input id="alarm-name" name="name" class="form-control" placeholder="Name:">
              </div>

              <div class="form-group">
                <label for="description">Description: </label>
                <input id="alarm-description" name="description" class="form-control" placeholder="Description:">
              </div>

              <div class="form-group">
                <label>Schedule/Measurement: </label><br>
                <div class="dropdown btn-group">
                  <button class="btn btn-default dropdown-toggle" type="button" id="alram-schedule-dropdown" data-toggle="dropdown" aria-expanded="true">
                    <span data-bind="label">Select schedule/measurement</span>&nbsp;<span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" id="alarm-schedule-dropdown-list" role="menu" aria-labelledby="alarm-schedule-dropdown">
                  </ul>
                </div>
              </div>

              <div class="form-group">
                <label>Feature: </label><br>
                <div class="dropdown btn-group">
                  <button class="btn btn-default dropdown-toggle" type="button" id="alram-feature-dropdown" data-toggle="dropdown" aria-expanded="true">
                    <span data-bind="label">Select feature</span>&nbsp;<span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" id="alarm-feature-dropdown-list" role="menu" aria-labelledby="alarm-feature-dropdown">
                  </ul>
                </div>
              </div>

              <div class="form-group">
                <label>Operator: </label><br>
                <div class="dropdown btn-group">
                  <button class="btn btn-default dropdown-toggle" type="button" id="alram-operator-dropdown" data-toggle="dropdown" aria-expanded="true">
                    <span data-bind="label">Select feature</span>&nbsp;<span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" id="alarm-operator-dropdown-list" role="menu" aria-labelledby="alarm-operator-dropdown">
                    <li role="presentation"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >Equals (=)</a></li>
                    <li role="presentation"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >Less than (&lt;)</a></li>
                    <li role="presentation"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >Greater than (&gt;)</a></li>
                    <li role="presentation"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >Status active</a></li>
                    <li role="presentation"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >Status inactive</a></li>
                  </ul>
                </div>
              </div>

              <div class="form-group">
                <label>Average: </label><br>
                <div class="dropdown btn-group">
                  <button class="btn btn-default dropdown-toggle" type="button" id="alram-average-dropdown" data-toggle="dropdown" aria-expanded="true">
                    <span data-bind="label">Select average</span>&nbsp;<span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" id="alarm-average-dropdown-list" role="menu" aria-labelledby="alarm-average-dropdown">
                    <li role="presentation"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >None</a></li>
                    <li role="presentation"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >1 hour</a></li>
                    <li role="presentation"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >6 hours</a></li>
                    <li role="presentation"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >12 hours</a></li>
                    <li role="presentation"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >1 day</a></li>
                    <li role="presentation"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >1 week</a></li>
                  </ul>
                </div>
              </div>

              <div class="form-group">
                <label for="threshold">Threshold: </label>
                <input id="alarm-threshold" name="threshold" class="form-control" type="number">
              </div>

              <div class="form-group">
                <label for="recipient">Threshold: </label>
                <input id="alarm-recipient" name="recipient" class="form-control" placeholder="Recipient:">
              </div>

            </div>

            <!-- Modal Footer-->
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              <button disabled id="alarm-submit" type="submit" class="btn btn-success">Create Alarm</button>
            </div>

            </form>
          </div>
        </div>
      </div>

    </div>

    <script type="text/javascript">
      /*
      * Get current state of server - update glyphicon and active server interaction buttons
      */
      function server_status(){
        $.post("server.php", {'action':'server_state'}, function (response) {
          server_running = response;
          if(response == 1){
             $("#server_status").html("Server <span style='color:green' class='glyphicon glyphicon-ok' aria-hidden='true'><small>Running</small></span>");
             $("#server_start").prop('disabled', true);
             $("#server_update").prop('disabled', true);
             $("#server_restart").prop('disabled', false);
             $("#server_stop").prop('disabled', false);
          }else{
            $("#server_status").html("Server <span style='color:red' class='glyphicon glyphicon-remove' aria-hidden='true'></span><small>Not Running</small>");
            $("#server_start").prop('disabled', false);
            $("#server_update").prop('disabled', false);
            $("#server_restart").prop('disabled',true);
            $("#server_stop").prop('disabled', true);
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
              server_status();
            });             
          }
        });
      });

      //inital state of server
      server_running  = 0;
      server_status();

      //auto refresh server log
      load_log();
      function load_log(){
        $('#server_log').load('server_log.php', function(){
          setTimeout(load_log, 5000);  
        });
      }
      
      //initial load of sensor data 
      var sensor_data;
      load_sensors();

      //initial load of groups
      var group_data;
      load_groups();

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
            $("#destination-dropdown-list").find('.sensor-element').remove();
            $("#source-dropdown-list").find('.sensor-element').remove();
            
            //clear group creator
            $("#add-sensor-dropdown-list").empty();

            //schedule creator headers
            $("#destination-dropdown-list").append('<li class="dropdown-header sensor-element">Sensors</li>');
            $("#source-dropdown-list").append('<li class="dropdown-header sensor-element">Sensors</li>');
            
            //repopulate
            for(x in sensor_data){
              $('#sensors tr:last').after('<tr bgcolor=' + (sensor_data[x].active == 0 ? "'#FFCCCC'" : "'#99FF99'") + '><td><a href="sensor.php?' + sensor_data[x].sensor_id + '">' + sensor_data[x].sensor_id + '</a></td>' + 
                                                    '<td>' + sensor_data[x].ether + '</td>' + 
                                                    '<td>' + sensor_data[x].ip + '</td>' + 
                                                    '<td>' + sensor_data[x].local_ip + '</td>' + 
                                                    '<td>' + sensor_data[x].start + '</td>' + 
                                                    '<td' + (sensor_data[x].active == 0 ? '>' + sensor_data[x].end : " style='text-align: center;'>-") + '</td>' + 
                                                    '<td>' + sensor_data[x].description + '</td></tr>');
                                                      
              

              //add to schedule creator dropdowns 
              $("#destination-dropdown-list").append('<li class="destination-dropdown-element sensor-element" role="presentation" value="' + sensor_data[x].sensor_id + '"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >' + sensor_data[x].description + '</a></li>');
              $("#source-dropdown-list").append('<li class="source-dropdown-element sensor-element" role="presentation" value="' + sensor_data[x].sensor_id + '"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >' + sensor_data[x].description + '</a></li>');

              //add to group creator dropdown
              $("#add-sensor-dropdown-list").append('<li class="add-sensor-dropdown-element" role="presentation" value="' + sensor_data[x].sensor_id + '"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >' + sensor_data[x].description + '</a></li>');
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

            //clear topo filter list
            $("#topo-filter").find(".schedule-radio").remove();
            $("#topo-filter").append("<p class='schedule-radio'><u>Schedules</u></p>");

            //clear create alarm list
            $("#alarm-schedule-dropdown").find('.alarm-measurement-element').remove();
            $("#alarm-schedule-dropdown").find('.alarm-schedule-element').remove();

            for(x in schedule_data){
              //topo filter list
              $("#topo-filter").append( '<div class="radio schedule-radio">' +
                                        '<label><input data-filter-type="schedule" data-filter-id="' + schedule_data[x]['schedule_id'] + '" type="radio" name="filter-radio">' + schedule_data[x]['name'] + '</label>' + 
                                        '</div>');

              //create alram list
              $("#alarm-schedule-dropdown-list").append('<li class="dropdown-header alarm-schedule-element">' + schedule_data[x]['name'] + '</li>');
              for(y in schedule_data[x]['measurements'])
                $("#alarm-schedule-dropdown-list").append('<li class="alarm-measurement-element" role="presentation" value="' + schedule_data[x]['measurements'][y]['measurement_id'] + '"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >' + schedule_data[x]['measurements'][y]['source_name'] + (schedule_data[x]['measurements'][y]['destination_name'] == null ? "" : " -> " + schedule_data[x]['measurements'][y]['destination_name']) + " (" + schedule_data[x]['measurements'][y]['method'] + ')</a></li>');
            }
            reload_filter_onclicks();

            $('#schedules tbody').off('click', 'td.details-control');
            var table = $('#schedules').DataTable({
                "destroy":  true, 
                "paging":   false,
                "ordering": false,
                "info":     false,
                "searching": false, 
                "data": schedule_data,
                "columns": [
                    { 
                      "data":null,
                      "defaultContent": "<span class='glyphicon glyphicon-chevron-right table-button' aria-hidden='true'></span>",
                      "className": 'details-control'
                    },
                    { "data": "name" },
                    { "data": "description"},
                    { "data": null},
                    {
                      "data": "null",
                      "defaultContent": "<span class='glyphicon glyphicon-remove remove-button table-button' aria-hidden='true'></span>",
                      "className": "center"
                    },
                    {
                      "data": "null",
                      "defaultContent": "<span class='glyphicon glyphicon-cog table-button' aria-hidden='true'></span>",
                      "className": "center"
                    },
                    {
                      "data": "null",
                      "defaultContent": "<span class='glyphicon glyphicon-remove remove-button table-button' aria-hidden='true'></span>",
                      "className": "center"
                    }
                ],
                //add remove button with correct group ID & colour
                "createdRow": function(row, data, index){
                  $('td', row).css('background-color', (data['active'] == 0 ? '#FFCCCC' : (data['faults'] > 0 ? '#FFCC66' : '#99FF99')));                  
                  $('td', row).eq(3).html(time_string(parseInt(data['period'])));
                  $('td', row).eq(4).html("<span aria-hidden='true' class='table-button glyphicon " + (data['active'] == 1 ? "glyphicon-pause susspend-button' onclick='schedule_susspend( " : "glyphicon-play susspend-button' onclick='schedule_start( ") + data['schedule_id'] + ")'></span>");
                  $('td', row).eq(5).html("<span aria-hidden='true' class='table-button glyphicon glyphicon-cog edit-button' data-toggle='modal' data-target='#create-schedule' data-id='" + data['schedule_id'] + "'></span>");  
                  $('td', row).eq(6).html("<span class='glyphicon glyphicon-remove remove-button table-button' aria-hidden='true' onclick='schedule_delete(" + data['schedule_id'] + ")'></span>");
                }
            });
            
            // Add event listener for opening and closing details
            $('#schedules tbody').on('click', 'td.details-control', function (){
              var tr = $(this).closest('tr');
              var row = table.row(tr);

              if (row.child.isShown()){
                  row.child.hide();
                  $(this).find('span').removeClass('glyphicon-chevron-down');
                  $(this).find('span').addClass('glyphicon-chevron-right');
              }else{
                  row.child(schedule_format(row.data())).show();
                  $(this).find('span').removeClass('glyphicon-chevron-right');
                  $(this).find('span').addClass('glyphicon-chevron-down');
              }
            });
          }
        }); 

        if(!auto)
          setTimeout(load_schedules, 5000);
      }

      function load_groups(auto){
        $.getJSON("groups.php", null, function(data){

          if(JSON.stringify(group_data) != JSON.stringify(data)){
            group_data = data;

            //clear schedule creator dropdowns of groups
            $("#destination-dropdown-list").find(".group-element").remove();
            $("#source-dropdown-list").find(".group-element").remove();

            //add dropdown headers to schedule creator dropdowns
            $("#destination-dropdown-list").append('<li class="dropdown-header group-element">Groups</li>');
            $("#source-dropdown-list").append('<li class="dropdown-header group-element">Groups</li>');            

            $("#topo-filter").find(".group-radio").remove()
            $("#topo-filter").append("<p class='group-radio'><u>Groups</u></p>");

            //repopulate
            for(x in group_data){
              $("#destination-dropdown-list").append('<li class="destination-dropdown-element group-element" role="presentation" value="' + group_data[x]['group_id'] + '"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >' + group_data[x]['name'] + '</a></li>');
              $("#source-dropdown-list").append('<li class="source-dropdown-element group-element" role="presentation" value="' + group_data[x]['group_id'] + '"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >' + group_data[x]['name'] + '</a></li>');

              //topo filtering
              $("#topo-filter").append( '<div class="radio group-radio">' +
                                        '<label><input data-filter-type="group" data-filter-id="' + group_data[x]['group_id'] + '" type="radio" name="filter-radio">' + group_data[x]['name'] + '</label>' + 
                                        '</div>');
            }
            reload_filter_onclicks();

            $('#groups tbody').off('click', 'td.details-control');
            var table = $('#groups').DataTable({
                "destroy":  true, 
                "paging":   false,
                "ordering": false,
                "info":     false,
                "searching": false, 
                "data": group_data,
                "columns": [
                    { 
                      "data":null,
                      "defaultContent": "<span class='glyphicon glyphicon-chevron-right table-button' aria-hidden='true'></span>",
                      "className": 'details-control'
                    },
                    { "data": "name" },
                    { "data": "num_sensors"},
                    { "data": "description" },
                    {
                      "data": null,
                      "defaultContent": "<span aria-hidden='true' class='table-button glyphicon glyphicon-cog edit-button' data-toggle='modal' data-target='#create-group'></span>",
                      "className": "center"
                    },
                    {
                      "data": null,
                      "defaultContent": "<span class='delete-group glyphicon glyphicon-remove remove-button table-button' aria-hidden='true'></span>",
                      "className": "center"
                    }
                ],
                //add remove button with correct group ID & colour
                "createdRow": function(row, data, index){
                  $('td', row).css('background-color', (data['status'] == 0 ? '#99FF99'  : (data['status'] == 1 ? '#FFCC66' : '#FFCCCC')));
                  $('td', row).eq(4).html("<span aria-hidden='true' class='table-button glyphicon glyphicon-cog edit-button' data-toggle='modal' data-target='#create-group' data-id='" + data['group_id'] + "'></span>");
                  $('td', row).eq(5).html("<span class='delete-group glyphicon glyphicon-remove remove-button table-button' aria-hidden='true' onclick='group_delete(" + data['group_id'] + ")'></span>");
                }
            });

            // Add event listener for opening and closing details
            $('#groups tbody').on('click', 'td.details-control', function (){
              var tr = $(this).closest('tr');
              var row = table.row(tr);

              if (row.child.isShown()){
                  row.child.hide();
                  $(this).find('span').removeClass('glyphicon-chevron-down');
                  $(this).find('span').addClass('glyphicon-chevron-right');
              }else{
                  row.child(groups_format(row.data())).show();
                  $(this).find('span').removeClass('glyphicon-chevron-right');
                  $(this).find('span').addClass('glyphicon-chevron-down');
              }
            });
          }
        });

        if(!auto)
          setTimeout(load_groups, 5000);
      }

      /*
      * Format for group child table
      */
      function groups_format(d){
          var child_table = '<table class="table child-table">';
          child_table = child_table + "<tr><th>ID</th><th>Ethernet</th><th>IP</th><th>Description</th><th>Remove</th></tr>";
          for(var i = 0; i < d['sensors'].length; i++){
            child_table = child_table + '<tr bgcolor=' + (d['sensors'][i]['active'] == 0 ? "'#FFCCCC'" : "'#99FF99'") + '>'+
                                        '<td>'+ d['sensors'][i]['sensor_id'] +'</td>'+
                                        '<td>'+ d['sensors'][i]['ether'] +'</td>'+
                                        '<td>'+ d['sensors'][i]['ip'] +'</td>'+
                                        '<td>'+ d['sensors'][i]['description'] +'</td>'+
                                        '<td><span class="delete-group glyphicon glyphicon-remove remove-button table-button" aria-hidden="true" onclick="group_remove(' + d['group_id'] + ", " + d['sensors'][i]['sensor_id'] + ')"></span></td>'+
                                        '</tr>';
          }
          child_table = child_table + '</table>';
          return child_table;
      }

      /*
      * Propulate the create schedule modal if editing
      */
      $('#create-group').on('shown.bs.modal', function(event) {

        clear_create_group();

        if($(event.relatedTarget).attr('data-id')){
          //get group data
          var gid = $(event.relatedTarget).data('id');
          var group = jQuery.extend(true, {}, find_group(gid));

          //fill group data
          $("#group-id").val(gid);
          $("#group-name").val(group['name']);
          $("#group-description").val(group['description']);

          for(s in group['sensors']){
            add_group_sensor(group['sensors'][s]);
          }

          $("#group-submit").html("Edit group");
          $("#group-title").html("Edit group");

        }else{
          $("#group-id").val(0);
          $("#group-submit").html("Create group");
          $("#group-title").html("Create group");
          
        }
        $("#create-group").data("bs.modal").handleUpdate();
      });

      function schedule_format(d){
        var child_table = '<table class="table child-table table-hover rowlink" data-link="row">';
        child_table = child_table + "<tr><th>Source</th><th>Destination</th><th>Type</th><th>Params</th><th>Delay</th><th style='text-align: center;'>Add alarm</th></tr>";
        for(var i = 0; i < d['measurements'].length; i++){
          child_table = child_table + '<tr bgcolor=' + (d['measurements'][i]['active'] == 0 ? "'#FFCCCC'" : (d['measurements'][i]['status'] == 0 ? "'#FFCCCC'" : "'#99FF99'")) + '>'+
                                      '<td><a data-toggle="modal" data-target="#schedule-plot-modal" data-id="' + d['measurements'][i]['measurement_id'] + '"></a>'+ d['measurements'][i]['source_name'] +'</td>'+
                                      '<td>'+ (d['measurements'][i]['destination_name'] == null ? "N/A" : d['measurements'][i]['destination_name']) +'</td>'+
                                      '<td>'+ d['measurements'][i]['method'] +'</td>'+
                                      '<td>'+ child_param_string(d['measurements'][i]) +'</td>'+
                                      '<td>'+ time_string(parseInt(d['measurements'][i]['delay'])) +'</td>'+
                                      '<td class="center"><span class="glyphicon glyphicon-bell table-button alarm-button" aria-hidden="true" data-toggle="modal" data-target="#create-alarm" data-id="' + d['measurements'][i]['measurement_id'] + '"></span></td>' + 
                                      '</tr>';
        }
        child_table = child_table + '</table>';
        return child_table;
      }

      /*
      * Propulate the create schedule modal if editing
      */
      $('#create-schedule').on('shown.bs.modal', function(event) {
        
        if($(event.relatedTarget).attr('data-id')){
          
          clear_create_schedule();

          //get schedule data
          var sid = $(event.relatedTarget).data('id');
          var schedule = jQuery.extend(true, {}, find_schedule(sid));
          
          //fill schedule data
          $("#schedule-id").val(sid);
          $("#schedule-name").val(schedule['name']);
          $("#schedule-description").val(schedule['description']);

          if(schedule["period"] >= 60*60){
            $("#schedule-hours").val(Math.floor(schedule['period']/(60*60)));  
            $("#schedule-minutes").val(Math.floor(schedule['period']%(60*60)/60));
            $("#schedule-seconds").val(Math.floor(schedule['period']%(60*60)%60));
          }else if(schedule["period"] >= 60){
            $("#schedule-hours").val(0);  
            $("#schedule-minutes").val(Math.floor(schedule['period']/60));
            $("#schedule-seconds").val(Math.floor(schedule['period']%(60*60)%60));
          }else{
            $("#schedule-hours").val(0);  
            $("#schedule-minutes").val(0);
            $("#schedule-seconds").val(Math.floor(schedule['period']%(60*60)%60));
          }

          //fill measurment data
          for(m in schedule['measurements']){

            //delay
            var new_measurement = jQuery.extend(true, {}, schedule['measurements'][m]);

            //params
            for(p in schedule['measurements'][m]['params']){
                new_measurement[schedule['measurements'][m]['params'][p]['param']] = schedule['measurements'][m]['params'][p]['value'];
            }
            delete new_measurement['params'];
            delete new_measurement['pid'];

            schedule['measurements'][m] = new_measurement;

            add_schedule_measurement(new_measurement);
          }

          $("#schedule-submit").html("Edit Schedule");
          $("#schedule-title").html("Edit Schedule");
        }else{
          $("#schedule-id").val(0);
          $("#schedule-submit").html("Create Schedule");
          $("#schedule-title").html("Create Schedule");
          clear_create_schedule();
        }
        $("#create-schedule").data("bs.modal").handleUpdate();
      });

      var plot_data;
      var plot_source;
      var plot_destination;
      var plot_feature;
      var plot_from = 168;
      var plot_average = 5;

      $('#schedule-plot-modal').on('shown.bs.modal', function(event) {
        
        //clear lists and svg 
        $("#plot-feature-dropdown-list").find(".feature-dropdown-element").remove();
        $("#plot-source-dropdown-list").find(".plot-source-dropdown-element").remove();
        $("#plot-destination-dropdown-list").find(".plot-destination-dropdown-element").remove();

        var mid = $(event.relatedTarget).data('id');
        plot_measurement = mid;
        $(this).find('#schedule-plot-title').html($('<b> Schedule plot - measurement ID ' + mid  + '</b>'));

        var measurement = find_measurement(mid);

        //build source list
        if(measurement['source_type'] == 1){
          //group
          var group = find_group(measurement['source_id']);
          for(x in group['sensors'])
            $("#plot-source-dropdown-list").append('<li class="plot-source-dropdown-element" role="presentation" value="' + group['sensors'][x]['sensor_id'] + '"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >' + group['sensors'][x]['description'] + '</a></li>');
          plot_source = group['sensors'][0]['sensor_id'];
          $("#plot-source-span").html(group['sensors'][0]['description']);
        }else{
          //single sensor
          var sensor = find_sensor(measurement['source_id']);
          $("#plot-source-dropdown-list").append('<li class="plot-source-dropdown-element" role="presentation" value="' + sensor['sensor_id'] + '"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >' + sensor['description'] + '</a></li>');
          plot_source = sensor['sensor_id'];
          $("#plot-source-span").html(sensor['description']);
        }

        //build destination list
        if(parseInt(measurement['destination_id']) == 0){
          //dns schedule - no destination needed
          plot_destination = 0;
          $("#plot-destination-span").html("N/A");
          $("#plot-destination-dropdown").prop("disabled", true);
        }else{
          $("#plot-destination-dropdown").prop("disabled", false);
          if(measurement['destination_type'] == 1){
            //group
            var group = find_group(measurement['destination_id']);
            for(x in group['sensors'])
              $("#plot-destination-dropdown-list").append('<li class="plot-destination-dropdown-element" role="presentation" value="' + group['sensors'][x]['sensor_id'] + '"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >' + group['sensors'][x]['description'] + '</a></li>');
            plot_destination = group['sensors'][0]['sensor_id'];
            $("#plot-destination-span").html(group['sensors'][0]['description']);
          }else{
            //single sensor
            var sensor = find_sensor(measurement['destination_id']);
            $("#plot-destination-dropdown-list").append('<li class="plot-destination-dropdown-element" role="presentation" value="' + sensor['sensor_id'] + '"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >' + sensor['description'] + '</a></li>');
            plot_destination = sensor['sensor_id'];
            $("#plot-destination-span").html(sensor['description']);
          }
        }

        update_plot_data();
      });

      var parseDate = d3.time.format("%Y-%m-%d %H:%M:%S").parse;
      var bisectDate = d3.bisector(function(d) { return d.time; }).left;

      function update_plot_data(){

        $.getJSON("metric.php", "&measurement_id=" + plot_measurement + "&sensor_id=" + plot_source + "&dst_id=" + plot_destination, function(graph_data){

          if(graph_data.length == 0){
            $("#schedule-plot").hide();
            $("#no-schedule-plot").show();

            $("#plot-feature-dropdown").prop("disabled", true);
            $("#plot-feature-span").html("No data");

            return;
          }else{
            $("#schedule-plot").show();
            $("#no-schedule-plot").hide();

            $("#plot-feature-dropdown").prop("false", true);
          }

          //build features list
          var keys        = Object.keys(graph_data[0]);
          var features    = [];   
          $("#plot-feature-dropdown-list").find(".feature-dropdown-element").remove();
          for(x in keys){
            if(is_feature(keys[x])){
              features.push(keys[x]);
              $("#plot-feature-dropdown-list").append('<li class="feature-dropdown-element" role="presentation"  data-feature="' + keys[x] + '"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >' + keys[x] + '</a></li>');
            }
          }

          //parse time
          graph_data.forEach(function(d) {
            d.time        = parseDate(d.time);
          });

          //should already be in order
          graph_data.sort(function(a, b){
            return a.time - b.time;
          });

          //set feature to first in list
          plot_feature  = features[0];
          $("#plot-feature-span").html(plot_feature);

          plot_data     = graph_data;

          update_plot();
        });
      }
      
      function update_plot(){
        //remove svg
        d3.select("#schedule-plot").select("svg").remove();

        var graph_data;
        if(plot_from != "none"){
          graph_data = plot_data.filter(function(d){
            return d.time >= plot_from;
          });
        }else{
          graph_data = plot_data;
        }

        if(plot_average != "none")
          graph_data = average_data(graph_data, plot_average);

        if(graph_data.length == 0){
          $("#schedule-plot").hide();
          $("#no-schedule-plot").show();
          return;
        }else{
          $("#schedule-plot").show();
          $("#no-schedule-plot").hide();
        }

        //set margins for graphs - use modal width
        var margin = {top: 20, right: 70, bottom: 50, left: 50},
        width = $("#schedule-plot-dialog").width() - margin.left - margin.right,
        height = 500 - margin.top - margin.bottom;        

        var x = d3.time.scale().range([0, width]);
        var y = d3.scale.linear().range([height, 0]);

        var customTimeFormat = d3.time.format.multi([
          [".%L", function(d) { return d.getMilliseconds(); }],
          [":%S", function(d) { return d.getSeconds(); }],
          ["%b %d %I:%M", function(d) { if(x(d) >= (width/2)*0.9 && x(d) <= (width/2)*1.1 && d.getMinutes()) return true; }],
          ["%I:%M", function(d) { return d.getMinutes(); }],
          ["%b %d - %I %p", function(d) { if(x(d) >= (width/2)*0.9 && x(d) <= (width/2)*1.1 && d.getHours()) return true; }],
          ["%I %p", function(d) { return d.getHours(); }],
          ["%a %d", function(d) { return d.getDay() && d.getDate() != 1; }],
          ["%b %d", function(d) { return d.getDate() != 1; }],
          ["%B", function(d) { return d.getMonth(); }],
          ["%Y", function() { return true; }]
        ]);

        var xAxis = d3.svg.axis()
            .scale(x)
            .orient("bottom")
            .tickFormat(customTimeFormat);

        var yAxis = d3.svg.axis()
            .scale(y)
            .orient("left");

        var line = d3.svg.line()
            .defined(function(d) { return !isNaN(d['defined']); })
            .x(function(d) { return x(d.time); })
            .y(function(d) { return y(d[plot_feature]); });

        svg = d3.select("#schedule-plot").append("svg")
          .attr("width", width + margin.left + margin.right)
          .attr("height", height + margin.top + margin.bottom)
          .append("g")
          .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

        //convert to numeric is exten, min and max work
        graph_data.forEach(function(d) {
          d[plot_feature] = +d[plot_feature];
        });

        //if only a single point - center
        if(graph_data.length == 1)
          x.domain([new Date(graph_data[0].time.getTime() - 30*60000), new Date(graph_data[0].time.getTime() + 30*60000)]);
        else
          x.domain(d3.extent(graph_data, function(d) { return d.time; }));
        y.domain([d3.min(graph_data, function(d) { return d[plot_feature]; })*0.9, d3.max(graph_data, function(d) { return d[plot_feature]; }) * 1.1]);

        svg.append("g")
            .attr("class", "x axis")
            .attr("transform", "translate(0," + height + ")")
            .call(xAxis);

        svg.append("g")
            .attr("class", "y axis")
            .call(yAxis)
            .append("text")
            .attr("transform", "rotate(-90)")
            .attr("y", 6)
            .attr("dy", ".71em")
            .style("text-anchor", "end")
            .text("RTT (ms)");

        svg.append("path")
            .datum(graph_data)
            .attr("class", "line")
            .attr("d", line);

        var focus_bg = svg.append("rect")
            .attr("fill", "white")
            .attr("width", 50)
            .attr("height", 50);

        var focus = svg.append("g")
            .attr("class", "focus")
            .style("display", "none");

        focus.append("circle")
            .attr("r", 4.5);

        focus.append("text")
            .attr("x", 9)
            .attr("dy", ".35em");

        svg.append("rect")
            .attr("class", "overlay")
            .attr("width", width)
            .attr("height", height)
            .on("mouseover", function() { focus.style("display", null); focus_bg.style("display", null);})
            .on("mouseout", function() { focus.style("display", "none"); focus_bg.style("display", "none"); })
            .on("mousemove", mousemove);

        svg.selectAll(".point")
            .data(graph_data)
            .enter().append("svg:circle")
            .attr("stroke", "steelblue")
            .attr("fill", function(d, i) { return "steelblue" })
            .attr("cx", function(d, i) { return x(d.time) })
            .attr("cy", function(d, i) { return y(d[plot_feature]) })
            .attr("r", function(d, i){ 
              if(isNaN(d['defined'])) 
                return 0;
              return 1;  
            });

        /*
        * Format and position hover label for extra freatures of a point
        */
        function mousemove() {
          //choose which point the mouse is closest too
          var x0 = x.invert(d3.mouse(this)[0]);
              i = bisectDate(graph_data, x0, 1);
          if(graph_data.length == 1){   //if only a single point
            d = graph_data[0];
          }else{
            d0 = find_left_point(i-1);
            d1 = find_right_point(i);
            d = (x0 - d0.time > d1.time - x0 ? d1 : d0);
          }

          focus.attr("transform", "translate(" + x(d.time) + "," + y(d[plot_feature]) + ")");
          var bg_size = format_info(d, focus.select("text")); 
          focus_bg.attr("transform", "translate(" + x(d.time) + "," + y(d[plot_feature]) + ")");
          focus_bg.attr("height", bg_size[0] + "em");
          focus_bg.attr("width", bg_size[1]);
        }

        function find_left_point(index){
            if(isNaN(graph_data[index]['defined']))
              return find_left_point(index - 1);
            return graph_data[index];
        }

        function find_right_point(index){
            if(isNaN(graph_data[index]['defined']))
              return find_right_point(index + 1);
            return graph_data[index];
        }

        $("#schedule-plot-modal").data("bs.modal").handleUpdate();
      }

      /*
      * Format and append the text for extra features.
      * d     -  
      * text  - d3 svg text elemtent
      */
      function format_info(d, text){
        text.text("");
        var max_len = 0;
        var count = 0;
        for(x in d){
          if(is_feature(x)){
            var tspan = text.append("tspan")
              .attr("dy", "1.2em")
              .attr("x",0)
              .text(x + ":" + parseFloat(d[x]).toFixed(2));
            if(tspan.node().getComputedTextLength() > max_len)
              max_len = tspan.node().getComputedTextLength();
            count++; 
          }
        }
        return [1.2 * count, max_len];
      }

      /*
      * Creates a new data set of averages
      * data    - original data
      * period  - period of seconds to group samples 
      */
      function average_data(data, period){

        var new_data = [];
        var start = null;
        var count = 0;
        var sum   = {};

        for(x in data){
          if(!isNaN(data[x]['defined'])){
            if(start == null){
              start = data[x].time;
              for(y in data[x]) sum[y] = 0;
            }else if(Math.floor((data[x].time - start) / (1000*60)) >= period || x == data.length - 1){
              var average = {};
              for(y in sum){
                average[y] = parseFloat(sum[y] / count);
                sum[y] = 0;
              }
              count = 0;
              average.time = data[x].time;
              new_data.push(average);
              start = data[x].time;
            }
            for(y in data[x]) sum[y] += parseFloat(data[x][y]);
            count ++;
          }else{
            //only push if a period has passed since last - no data inside an average period should be okay?
            if(x!= 0 && Math.floor((data[x].time - data[x-1].time) / (1000*60)) >= period)
              new_data.push(data[x]);
          }
        }
        return new_data;
      }

      /*
      *
      */
      function is_feature(key){
        var not_feature = ["time", "defined", "server", "address"];
        if(!(key.indexOf("id") > -1) && !(not_feature.indexOf(key) > -1))
          return true;
        return false;
      }

      /*
      * Update text on all dropdowns 
      */
      $(document.body).on('click', '.dropdown-menu li', function(event){
          var $target = $(event.currentTarget);
          $target.closest('.btn-group')
          .find('[data-bind="label"]').text($target.text())
          .end()
          .children('.dropdown-toggle').dropdown( 'toggle');

      });

      /*
      * Dropdown handlers 
      */
      $(document.body).on('click', '.dropdown-menu .destination-dropdown-element, .dropdown-menu .source-dropdown-element', function(event){
        var $target = $(event.currentTarget);
        //update hidden input
        $target.closest('.btn-group')
        .find('.dropdown-input').val($target.val())
        .end();

        //update hidden input type
        //0 - single sensor
        //1 - group
        if($.inArray('group-element', this.classList) == 1)
          $target.closest('.btn-group').find('.dropdown-type').val("1").end();
        else
          $target.closest('.btn-group').find('.dropdown-type').val("0").end();

        //checks
        if($("#destination-input").val() == 0 || $("#source-input").val() == 0){
          $("#same-sensor-warning").hide();
          $("#no-sensor-warning").show();
          $("#measurement-submit").attr('disabled', 'disabled');
        }else if($("#destination-input").val() == $("#source-input").val()){
          $("#no-sensor-warning").hide();
          $("#same-sensor-warning").show();
          $("#measurement-submit").attr('disabled', 'disabled');
        }else{
          $("#same-sensor-warning").hide();
          $("#no-sensor-warning").hide();
          $("#measurement-submit").removeAttr('disabled');
        }

        //need some fix for groups
        /*if(find_sensor($("#destination-input").val()).local_ip != find_sensor($("#destination-input").val()).ip){
          $("#ip-warning").show();
        }else{
          $("#ip-warning").hide();
        }*/

        $("#add-measurement").data("bs.modal").handleUpdate();
      });

      /*
      * Create group dropdown listeners
      */
      $(document.body).on('click', '.dropdown-menu .add-sensor-dropdown-element', function(event){
        var $target = $(event.currentTarget);
        to_add = find_sensor($target.val());
        $("#add-sensor-btn").removeAttr('disabled');
        $("#create-group").data("bs.modal").handleUpdate();
      });

      /*
      * Schedule plot dropdown listeners
      */    
      $(document.body).on('click', '.dropdown-menu .plot-destination-dropdown-element', function(event){
        var $target = $(event.currentTarget);
        plot_destination = $target.val();
        update_plot_data(); 
      });   

      $(document.body).on('click', '.dropdown-menu .plot-source-dropdown-element', function(event){
        var $target = $(event.currentTarget);
        plot_source     = $target.val();
        update_plot_data();
      });
      
      $(document.body).on('click', '.dropdown-menu .feature-dropdown-element', function(event){
        var $target = $(event.currentTarget);
        plot_feature    = $target.attr("data-feature");
        update_plot();  
      });
            
      $(document.body).on('click', '.dropdown-menu .plot-from-dropdown-element', function(event){
        var $target = $(event.currentTarget);
        if($target.val() == -1){
          plot_from = "none";
          update_plot();
        }else{
          plot_from = new Date();
          plot_from.setHours(plot_from.getHours() - $target.val());
          update_plot();
        }
      });

      $(document.body).on('click', '.dropdown-menu .plot-average-dropdown-element', function(event){
        var $target = $(event.currentTarget);
        if($target.val() == -1){
          plot_average = "none";
        }else{
          plot_average = $target.val();  
        }
        update_plot();
      });

      /*
      * Create alarm dropdown listener
      */
      $(document.body).on('click', '.dropdown-menu .alarm-measurement-element', function(event){
        
        //get measurement
        var $target = $(event.currentTarget);
        var measurement = find_measurement($target.val());

        console.log(measurement);

        $.getJSON("metric.php", "measurement_id=" + measurement['measurement_id'], function(graph_data){

          console.log(graph_data.length);

          if(graph_data.length == 0){
            
            $("#alarm-submit").prop("disabled", true);
            $("#alarm-no-data-warning").show();

          }else{

            $("#alarm-submit").prop("disabled", false);
            $("#alarm-no-data-warning").hide();

            var keys        = Object.keys(graph_data[0]);
            var features    = [];   
            $("#alarm-feature-dropdown-list").find(".alarm-feature-dropdown-element").remove();
            for(x in keys){
              if(is_feature(keys[x])){
                features.push(keys[x]);
                $("#alarm-feature-dropdown-list").append('<li class="alarm-feature-dropdown-element" role="presentation"  data-feature="' + keys[x] + '"><a onclick="return false;" href=""role="menuitem" tabindex="-1" >' + keys[x] + '</a></li>');
              }
            }

          }

        });

        //fill features
      });

      /*
      * Sensor to be added to the group being created
      */
      var to_add = null;
      $('#add-sensor-btn').click(function(){
        add_group_sensor(to_add);
      });

      function add_group_sensor(sensor){
        //check if table already contains sensor
        if($('#create-group-table tr > td:contains(' + sensor['sensor_id'] + ') + td:contains(' + sensor['description'] + ')').length){
          alert("Sensor already added to group");
        }else{
          $('#create-group-table tr:last').after( "<tr><td>" + sensor['sensor_id'] + "</td>" + 
                                                  "<td>" + sensor['description'] + "</td>" + 
                                                  "<td style='text-align: center;'><span class='glyphicon glyphicon-remove remove-button table-button' aria-hidden='true' onclick='create_group_remove_sensor(" + sensor['sensor_id'] + ")'></span></td>" +
                                                  "</tr>");
          $('#create-group-table tr:last').after('<input type="hidden" name="sensor" value="' + sensor['sensor_id'] + '"/>');
          $("#group-submit").removeAttr('disabled');
        }
      }

      /*
      * Remove row containing sensor from the create group modal
      */
      function create_group_remove_sensor(id){
        $('#create-group-table tr > td:contains(' + id + ') + td:contains(' + find_sensor(id)['description'] + ')').closest('tr').remove();
        $('#create-group-table input[value=' + id + ']').remove();

        if($('#create-group-table tr').length == 1){
          $("#group-submit").attr('disabled', 'disabled');
        }
      }

      /*
      *
      */
      function create_schedule_remove_measurement(index){
        $('#create-schedule-table tr[data-index="' + index + '"]').remove();
        $('#create-schedule-table input[data-index="' + index + '"]').remove();

        if($('#create-schedule-table tr').length == 1){
          $("#schedule-submit").attr('disabled', 'disabled');
        }
      }

      /*
      * Clear the create group modal
      */
      function clear_create_group(){
        $("#create-group-table").find("tr:gt(0)").remove();
        $("#create-group-table").find("input").remove();
        $("#group-name").val("");
        $("#group-description").val("");
        $("#group-submit").attr('disabled', 'disabled');
      }
      $('#create-group-clear').click(clear_create_group);

      /*
      *
      */
      function clear_create_schedule(){
        $("#create-schedule-table").find("tr:gt(0)").remove();
        $("#create-schedule-table").find("input").remove();
        $("#schedule-name").val("");
        $("#schedule-description").val("");
        $("#schedule-submit").attr('disabled', 'disabled');
      }
      $('#create-schedule-clear').click(clear_create_schedule);

      function clear_create_alarm(){
        $("#alarm-name").val("");
        $("#alarm-description").val("");
        $("#alarm-recipient").val();

        //reset selected measurement

        //clear features list

        $("#alarm-submit").prop("disbaled", true);
      }

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
                  load_sensors(1);
                }
          });
        }
      }

      /*
      *
      */
      function schedule_susspend(sid){
        $.post("scheduler.php", {Function: "stopSchedule", Data: {sid: sid}}, function(data){
          if(data == 0)
            alert("Susspended schedule " + sid);
          else
            alert("Failed to susspend schedule " + sid + ". Check scheduler log.");
          load_schedules(1);
        });
      }

      function schedule_start(sid){
        if(server_running == 1){
          $.post("scheduler.php", {Function: "startSchedule", Data: {sid: sid}}, function(data){
            if(isNaN(data))
              alert("Failed to start schedule. Check scheduler log.");
            else
              alert("Started schedule with ID " + data);
            load_schedules(1);
          })
        }else{
          alert("Cannont start schedule when server is not runnning!");
        }
      }

      function schedule_delete(sid){
        if(confirm("Stop and delete schedule " + sid + "?")){
          $.post("scheduler.php", {Function: "deleteSchedule", Data: {sid: sid}}, function(data){
            load_schedules(1);
          });
        }
      }

      function group_delete(gid){
        if(confirm("Delete group " + gid + ". All schedules using the group will become faulty.")){
          $.post("group_management.php", {Function: "deleteGroup", Data: {gid: gid}}, function(data){
            load_groups(1);
          });
        }
      } 

      function group_remove(gid, sid){
        if(confirm("Remove sensor " + sid + " from group " + gid + ". Schedules using the group may become faulty.")){
          $.post("group_management.php", {Function: "removeGroup", Data: {gid: gid, sid: sid}}, function(data){
            load_groups(1);
          });
        }
      }

      /*
      * Updates the topology view.
      * feature_url   - url of JSON file (PHP) to label the links
      * sensor_label  - string describing how to label the sesnors (MAC, IP, ID or Description)
      */
      function load_topo(sensor_label, feature, filter_type, filter_id){
        $.getJSON("sensors.php", (filter_type == "none" ? "" : "filter_type=" + filter_type + "&filter_id=" + filter_id), function(json_sensor){
    
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

          $.getJSON("topo.php", "feature=" + feature + (filter_type == "none" ? "" : "&filter_type=" + filter_type + "&filter_id=" + filter_id), function(json_feature){

            for(x in json_feature){
              json_feature[x].from = json_feature[x].sensor_id;
              json_feature[x].to = json_feature[x].dst_id;
              json_feature[x].label = ((feature == "feature-bw") ? rate_string(json_feature[x].feature) : (feature == "feature-packetloss") ? json_feature[x].feature + "%" : json_feature[x].feature + "ms");
            }

            topo_data = {
                nodes: new vis.DataSet(json_sensor),
                edges: new vis.DataSet(json_feature)
            };

            update_topo();
          });           
        
        });
      }

      /*
      * For when new topo_data is available. Re-simulates using physics before stablising
      * and saving posiitions.
      */
      function update_topo(){

        if(topo_data['edges'].length == 0){
          $("#topo-div").hide();
          $("#no-topo-div").show();
        }else{

          $("#topo-div").show();
          $("#no-topo-div").hide();

          network.setOptions(topo_options);

          // initialize your network!
          network.setData(topo_data);

          //freeze network once organised - 100ms
          setTimeout(function(){
            network.setOptions(no_sim_options);
            network.storePositions();
          }, 100);

          //onclick to redirect when clicked on a sensor - not edges or sensor
          /*network.on( 'click', function(properties) {
            if(properties.nodes.length == 1){
              if(properties.nodes != 1)
                window.location.href = "sensor.html?" + properties.nodes;
            }
          });*/

          network.on("dragEnd", function(properties){
            network.storePositions();
          }); 
        }  
      }

      /*
      * Updates sensor label - sensors are repositioned using their previous positions.
      */
      function update_topo_label(){
        for(x in topo_data['nodes']['_data']){
          if(topo_data['nodes']['_data'][x]['id'] != "1"){
            if(sensor_label == "mac")
              topo_data['nodes']['_data'][x].label = topo_data['nodes']['_data'][x].ether;
            else if(sensor_label == "id")
              topo_data['nodes']['_data'][x].label = topo_data['nodes']['_data'][x].sensor_id;
            else if(sensor_label == "ip")
              topo_data['nodes']['_data'][x].label = topo_data['nodes']['_data'][x].ip;
            else if(sensor_label == "description")
              topo_data['nodes']['_data'][x].label = topo_data['nodes']['_data'][x].description;
          }
        }
        network.setData(topo_data);
      }

      // Create initial topology
      var topo_container = document.getElementById('topo-div');

      var topo_data = {nodes: null, edges: null};
      var topo_options = {
        autoResize: true,
        height: '100%',
        width: '100%',
        layout: {randomSeed: 0},
        edges: {length: 200, smooth: {enabled: false}},
        physics: {enabled: true, barnesHut: {avoidOverlap: 1}, repulsion: {nodeDistance: 1}},
        nodes: {scaling: {min: 30,max: 30,}}
      };

      var no_sim_options = {
        autoResize: true,
        height: '100%',
        width: '100%',
        clickToUse: true,
        edges: {length: 200, smooth: {enabled: false}},
        physics: {enabled: false},
        interaction: {hover: true, hoverConnectedEdges: false, zoomView: true}
      }

      var network       = new vis.Network(topo_container, topo_data, topo_options);
      var feature       = "feature-bw";
      var sensor_label  = "description";
      var filter_id     = "0";
      var filter_type   = "none";
      load_topo(sensor_label, feature, filter_type, filter_id); 

      /*
      * Change topology link feature according to radio buttons
      */
      var feature_radios = document.getElementsByName("feature-radio");
      for (x in feature_radios){
        feature_radios[x].onclick = function(){
          feature = this.id;
          load_topo(sensor_label, feature, filter_type, filter_id);
        }
      }

      /*
      * Change topology sensor label accoring to radio buttons
      */
      var label_radios = document.getElementsByName("label-radio");
      for (x in label_radios){
        label_radios[x].onclick = function(){
          sensor_label = this.id;
          update_topo_label();
        }
      }

      function reload_filter_onclicks(){
        $("[name='filter-radio']").click(function(data){
          filter_type  = $(this).attr("data-filter-type");
          filter_id    = $(this).attr("data-filter-id");
          load_topo(sensor_label, feature, filter_type, filter_id);
        });  
      }

      /*
      * Change create schdule form for the type of measurement selected
      */
      var type_radios = document.getElementsByName("method");
      for (x in type_radios){
        type_radios[x].onclick = function(){
          document.getElementById('rtt-details').style.display = 'none';
          document.getElementById('tcp-details').style.display = 'none';
          document.getElementById('udp-details').style.display = 'none';
          document.getElementById('dns-details').style.display = 'none';        

          document.getElementById(this.getAttribute("data-div")).style.display = 'block';

          //stop duplicate 'duration' input being submitted
          if(this.getAttribute("data-div") != "tcp-details"){
            $("#tcp-details-dur").prop("disabled", true);
          }else{
            $("#tcp-details-dur").prop("disabled", false);
          }
          if(this.getAttribute("data-div") != "udp-details"){
            $("#udp-details-dur").prop("disabled", true);
          }else{
            $("#udp-details-dur").prop("disabled", false);
          }

          $("#add-measurement").data("bs.modal").handleUpdate();
        }
      }

      /*
      * Add measurement schedule
      */
      $("#add-measurement-form").submit(function(event){
        event.preventDefault();

        var measurement_json = $(this).serializeObject();
        add_schedule_measurement(measurement_json);

        $("#add-measurement").modal('hide');
        $("#create-schedule").data("bs.modal").handleUpdate();        
      });

      function add_schedule_measurement(json){

        if(!json.hasOwnProperty('delay'))
          json['delay'] = (parseInt(json['delay-hours']) * 60 * 60) + (parseInt(json['delay-minutes']) * 60) + parseInt(json['delay-seconds']);

        if(!json.hasOwnProperty('source_name')){
          if(json['source_type'] == 1)
            json['source_name'] = find_group(json['source_id'])['name']
          else 
            json['source_name'] = find_sensor(json['source_id'])['description']
        }

        if(!json.hasOwnProperty('destination_name')){
          if(json['destination_type'] == 1)
            json['destination_name'] = find_group(json['destination_id'])['name']
          else 
            json['destination_name'] = find_sensor(json['destination_id'])['description']
        }

        console.log(json);

        $('#create-schedule-table tr:last').after( "<tr data-index='" + $('#create-schedule-index').val() + "'>" + 
                                                  "<td>" + json['source_name'] + "</td>" + 
                                                  "<td>" + (json['destination_name'] == null ? "N/A" : json['destination_name']) + "</td>" + 
                                                  "<td>" + json['method'] + "</td>" + 
                                                  "<td>" + param_string(json) + "</td>" + 
                                                  "<td>" + time_string(json['delay']) + "</td>" + 
                                                  "<td style='text-align: center;'><span class='glyphicon glyphicon-remove remove-button table-button' aria-hidden='true' onclick='create_schedule_remove_measurement(" + $('#create-schedule-index').val() + ")'></span></td>" +
                                                  "</tr>");
        $('#create-schedule-table tr:last').after("<input type='hidden' data-index='" + $('#create-schedule-index').val() +"'' name='measurement' value='" + JSON.stringify(json) + "'/>");
        $('#create-schedule-index').val(parseInt($('#create-schedule-index').val()) + 1);
        $("#schedule-submit").removeAttr('disabled');
      }

      /*
      * Create schedule POST
      */
      $("#create-schedule-form").submit(function(event){
        event.preventDefault();

        var submit_data = $(this).serializeObject();

        if(submit_data['measurement'] instanceof Array){
          for(x in submit_data['measurement']){
            submit_data['measurement'][x] = jQuery.parseJSON(submit_data['measurement'][x]);
          }
        }else{
          submit_data['measurement'] = Array(jQuery.parseJSON(submit_data['measurement']));
        }

        $.post("scheduler.php", {Function: "createSchedule", Data:submit_data}, function(data){

          if(!isNaN(data)){
            if(parseInt(data) == submit_data['measurement'].length)
              alert("Created shcedule.");
            else
              alert("Created schedule with " + parseInt(data) + " out of " + submit_data['measurement'].length + "successful measurements. Check scheduler logs");
            $('#create-schedule').modal('hide');
            clear_create_schedule();
            load_schedules(1);
          }else{
            alert("Failed to create schedule. Check shecduler logs.");
          }
        });
      });

      /*
      * POST create group
      */
      $("#create-group-form").submit(function(event){
        event.preventDefault();

        $.post("group_management.php", {Function: "createGroup", Data:$(this).serializeObject()}, function(data){

          if(!isNaN(data)){
            alert("Created group with ID " + data);
            $("#create-group").modal('hide');
            clear_create_group();
            load_groups(1);
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

      function find_sensor(id){
        for(s in sensor_data){
          if(sensor_data[s].sensor_id == id)
            return sensor_data[s];
        }
        return false;
      }

      function find_group(gid){
        for(g in group_data){
          if(group_data[g].group_id == gid){
            return group_data[g];
          }   
        }
        return false;
      }

      function find_schedule(sid){
        for(x in schedule_data){
          if(schedule_data[x].schedule_id == sid)
            return schedule_data[x];
        }
        return false;
      }

      function find_measurement(mid){
        for(m in schedule_data){
          for(n in schedule_data[m]['measurements']){
            if(schedule_data[m]['measurements'][n]['measurement_id'] == mid)
              return schedule_data[m]['measurements'][n];
          }
        }
        return false;
      }

      function rate_string(bps){
        if(bps > 1024*1024*1024)
          return  parseFloat(bps/(1024*1024*1024)).toFixed(2) + "gbps";
        else if(bps > 1024*1024)
          return parseFloat(bps/(1024*1024)).toFixed(2) + "mbps";
        else if(bps > 1024)
          return  parseFloat(bps/(1024)).toFixed(2) + "kbps";
        else 
          return bps + "bps";
      }

      function time_string(seconds){
        if(seconds >= 60*60)
          return Math.floor(seconds/(60*60)) + " hour(s) " + (Math.floor((seconds%(60*60)/60)) > 0 ? Math.floor((seconds%(60*60)/60)) + " minutes(s) " : "") + (Math.floor(seconds%(60*60)%60) > 0 ? Math.floor(seconds%(60*60)%60) + " seconds(s)" : "") ;
        else if(seconds >= 60)
          return Math.floor(seconds/60) + " minute(s) " + (Math.floor(seconds%(60*60)%60) > 0 ? Math.floor(seconds%(60*60)%60) + " seconds(s)" : "");
        else
          return seconds + " seconds(s)"; 
      }

      var flags = { 0   : "none", 32  : "cs1", 64  : "cs2", 96  : "cs3", 40  : "af11", 48  : "af12", 56  : "af13", 176 : "voice-admit", 184 : "ef"}
      function param_string(measurement){
        if(measurement['method'] == 'rtt'){
          return "Iterations: " + measurement['iterations'];
        }else if(measurement['method'] == 'tcp'){
          return "Duration: " + measurement['duration'];
        }else if(measurement['method'] == 'udp'){
          return  "Duration: " + measurement['duration'] + "<br/ >" +
                  "Size: " + measurement['packet_size'] + "<br />" + 
                  "Speed: " + measurement['send_speed'] + "<br />" + 
                  "DSCP: " + (measurement['dscp_flag'] == "" ? "None" : flags[parseInt(measurement['dscp_flag'])]); 
        }else if(measurement['method'] == 'dns'){
          return  "Address: " + measurement['domain_name'] + "<br />" + 
                  "Server: " + measurement['server'];
        }else{
          return "Invalid measurement type";
        }
      }

      function child_param_string(measurement){
        var string = "";
        for(x in measurement['params']){
          if(measurement['params'][x]['param'] == "iterations"){
            string += "Iterations: " + measurement['params'][x]['value'] + "<br />";
          }else if(measurement['params'][x]['param'] == "duration"){
            string += "Duration: " + measurement['params'][x]['value'] + "<br />";
          }else if(measurement['params'][x]['param'] == "send_speed"){
            string += "Send Speed: " + measurement['params'][x]['value'] + "<br />";
          }else if(measurement['params'][x]['param'] == "packet_size"){
            string += "Packet Size: " + measurement['params'][x]['value'] + "<br />";
          }else if(measurement['params'][x]['param'] == "dscp_flag"){
            string += "DSCP: " + flags[parseInt(measurement['params'][x]['value'])] + "<br />";
          }else if(measurement['params'][x]['param'] == "server"){
            string += "Server: " + measurement['params'][x]['value'] + "<br />";
          }else if(measurement['params'][x]['param'] == "domain_name"){
            string += "Address: " + measurement['params'][x]['value'] + "<br />";
          }else{
            string += "Unrecognised param <br />";
          }
        }
        return string;
      }
  
    </script>
    
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <!-- Used for the single line links -->
    <script src="/js/jasny-bootstrap.min.js"></script>

</body></html>
