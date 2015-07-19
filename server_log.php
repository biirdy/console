<?php

	$file = file("logs/server.log");

	for($x = 100; $x > 0; $x--){
		echo $file[count($file) - $x]."<br/>";
	}

?>