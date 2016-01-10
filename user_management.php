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

	<title>weperf - User Management</title>

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
	<link href="/css/index.css" rel="stylesheet">

	<!-- jquery -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

	<!-- Datatables -->
	<script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>

	

</head>

<body>

	<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		
		<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
			<a class="navbar-brand" href="#">weperf - User Management</a>
		</div>

        <!-- Nav bar body -->
		<div id="navbar" class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<li class="active"><a href="#users-con">Users</a></li>
			</ul>

			<b id="logout" class="navbar-right navbar-text"><a href="logout.php" >Log Out</a></b>
			<b id="sensor-management" class="navbar-right navbar-text"><a href="index.php" >Sensor Management</a></b>
		</div>
        
      </div>
    </nav>

    <div class="container main-body">

    	<!-- User Panel-->
		<div class="container panel panel-default panel-body" id="users-con">
			<h1>Users</h1>

			<!-- Users table-->
			<table class="table table-hover" id="users">
			<thead>
				<tr><th>Name</th><th>Email</th><th>Admin</th><th>Edit</th><th>Delete</th></tr>
			</thead>
			</table>

			<form class="form-inline" role="form">
				<button type="button" class="btn btn-success create-btn" data-toggle="modal" data-target="#add-user">Add User</button>
			</form>

		</div>

		<!-- Add User Modal -->
		<div class="modal fade" id="add-user" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">

					<!-- Modal Header-->
			        <div class="modal-header">
			        	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        	<h4 class="modal-title">Add user</h4>
			        </div>

			        <!-- Modal body-->
			        <form role="form" id="add-user-form">
            		<div class="modal-body">

            			<input value='0' id='user-id' name='user-id' style='display: none;' class='form-control' type='hidden'></input>

            			<div class="form-group">
			            	<label for="user-name">Username: </label>
			            	<input id="user-name" name="user-name" class="form-control" placeholder="Username">
						</div>

						<div class="form-group">
			            	<label for="user-email">Email: </label>
			            	<input id="user-email" name="user-email" class="form-control" placeholder="Email">
						</div>

						<div class="form-group">
			            	<label for="user-pass">Password: </label>
			            	<input type="password" id="user-pass" name="user-pass" class="form-control" placeholder="Password">
						</div>

						<div class="form-group">
			            	<label for="user-pass-cnf">Confirm Password: </label>
			            	<input type="password" id="user-pass-cnf" name="user-pass-cnf" class="form-control" placeholder="Confirm Password">
						</div>

						<div class="form-group checkbox">
  							<label><input id="user-admin" name="user-admin" type="checkbox" value="">Admin</label>
						</div>

            		</div>

            		<!-- Modal Footer-->
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<button id="user-submit" type="submit" class="btn btn-success">Create User</button>
					</div>
					</form>

				</div>
			</div>
		</div>

    </div>

    <script type="text/javascript">

    	var user_data;
    	load_users(1);

    	function load_users(auto){
    		$.getJSON("users.php", null, function(data){
    			if(JSON.stringify(user_data) != JSON.stringify(data)){
    				user_data = data;

    				$("#users").find("tr:gt(0)").remove();

    				for(x in user_data){
    					$("#users tr:last").after(	"<tr><td>" + user_data[x]['username'] + "</td>" + 
    												"<td>" + user_data[x]['email'] + "</td>" + 
    												"<td>" + user_data[x]['admin'] + "</td>" + 
    												"<td><span aria-hidden='true' class='table-button glyphicon glyphicon-cog edit-button' data-toggle='modal' data-target='#add-user' data-id='" + user_data[x]['id'] + "'></span></td>" + 
    												"<td><span class='glyphicon glyphicon-remove remove-button table-button' aria-hidden='true' onclick='delete_user(" + user_data[x]['id'] + ")'></span></td></tr>");
    				}
    			}
    		});

    		if(auto)
    			setTimeout(load_users, 5000);
    	}

    	function clear_add_user(){
    		$("#user-id").val("");
    		$("#user-name").val("");
    		$("#user-email").val("");
    		$("#user-pass").val("");
    		$("#user-pass-cnf").val("");
    		$("#user-admin").prop("checked", false);
    	}

    	function delete_user(id){
    		if(confirm("Delete user?")){
    			console.log(id);
    			$.post("user_management_.php", {Function: "deleteUser", Data: {id:id}}, function(data){
    				load_users();
    				console.log(data);
    			});
    		}
    	}

    	$('#add-user').on('shown.bs.modal', function(event){

    		clear_add_user();

    		if($(event.relatedTarget).attr('data-id')){
    			var uid = $(event.relatedTarget).data('id');
    			var user = find_user(uid);

    			$("#user-id").val(uid);
    			$("#user-name").val(user['username']);
    			$("#user-email").val(user['email']);
    			$("#user-pass").attr("placeholder", "New password");
    			$("#user-pass-cnf").attr("placeholder", "Confirm new password");

    			if(user['admin'] == "1"){
    				$("#user-admin").prop("checked", true);
    			}else{
    				$("#user-admin").prop("checked", false);
    			}

    			$("#group-title").html("Edit User");
    			$("#user-submit").html("Edit User");
    		}else{
    			clear_add_user();

    			$("#user-pass").attr("placeholder", "Password");
    			$("#user-pass-cnf").attr("placeholder", "Confirm password");

    			$("#group-title").html("Create User");
    			$("#user-submit").html("Create User");
    		}
    		$("#add-user").data("bs.modal").handleUpdate();
    	});

    	$("#add-user-form").submit(function(event){
			event.preventDefault();

			var submit_data = $(this).serializeObject();

			//local checks
			//new user
			if(!submit_data['user-id']){

				//all required data 
				if(!submit_data['user-name'] || !submit_data['user-email'] || !submit_data['user-pass'] || !submit_data['user-pass-cnf']){
					alert("Missing values");
					return;
				}

				//passwords match
				if(submit_data['user-pass'] != submit_data['user-pass-cnf']){
					alert("Passwords do not match");
					return;
				}

			//update user password
			}else if(submit_data['user-pass'] && (submit_data['user-pass'] != submit_data['user-pass-cnf'])){
				alert("Passwords do not match");
				return;
			}

			console.log(submit_data);

			$.post("user_management_.php", {Function: "addUser", Data:submit_data}, function(data){

				if(!isNaN(data)){
					alert((!submit_data['user-id'] ? "Created " : "Updated ") + "user ID: " + data);
					$("#add-user").modal("hide");
					clear_add_user();
					load_users();
				}else{
					alert("Failed to add user." + data);
				}
			});
    	});

    	function find_user(id){
    		for(u in user_data){
    			if(user_data[u]['id'] == id){
    				return user_data[u];
    			}
    		}
    		return false;
    	}

		$.fn.serializeObject = function(){
			var o = {};
			var a = this.serializeArray();
			$.each(a, function(){
				if(o[this.name] !== undefined){
					if(!o[this.name].push){
						o[this.name] = [o[this.name]];
					}
					o[this.name].push(this.value || '');
				}else{
					o[this.name] = this.value || '';
				}
			});
			return o;
		};

    </script>

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>

</body>

</html>

