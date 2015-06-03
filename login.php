<?php
	include('login_check.php'); // Includes Login Script

	if(isset($_SESSION['login_user'])){
		header("location: index.php");
	}
?>
<!DOCTYPE html>
<html>
<head>
<title>Sensor Management Login</title>
	<!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
	<link href="css/signin.css" rel="stylesheet">
</head>
<body>
		
	<div class="container">

		<h1>Sensor Management</h1>

      <form class="form-signin" action="" method="post">

        <h3 class="form-signin-heading">Please sign in</h3>

        <label for="name" class="sr-only">Username</label>
        <input type="text" id="name" name="username" class="form-control" placeholder="Username" required="" autofocus="">

        <label for="password" class="sr-only">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required="">
        
        <button class="btn btn-lg btn-primary btn-block" name="submit" type="submit">Sign in</button>

        <span class="error" ><?php echo $error; ?></span>
      </form>

      <p>For account management please contact the system administator. <a href="mailto:j.bird1@lancaster.ac.uk?Subject=Network%20sensor%20management%20account" target="_top">j.bird1@lancaster.ac.uk</a></p> 



    </div>

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>

</body>
</html>