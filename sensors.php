<?php
	function parseToXML($htmlStr)
	{
		$xmlStr=str_replace('<','&lt;',$htmlStr);
		$xmlStr=str_replace('>','&gt;',$xmlStr);
		$xmlStr=str_replace('"','&quot;',$xmlStr);
		$xmlStr=str_replace("'",'&#39;',$xmlStr);
		$xmlStr=str_replace("&",'&amp;',$xmlStr);
		return $xmlStr;
	}

	echo '<doc>';

	// Opens a connection to a MySQL server
	$con = mysqli_connect("localhost","root","root","tnp");
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}

	//query
	$sensor_query = "SELECT * FROM sensors WHERE active = 1";
	$sensor_results = mysqli_query($con, $sensor_query);
	if (!$sensor_results) {
	  die('Invalid query: ' . mysqli_error());
	}
	
	header("Content-type: text/xml");	

	// ADD FACILITIES
	while ($row = @mysqli_fetch_assoc($sensor_results)){
		echo '<sensor ';
			echo 'id="' . parseToXML($row['sensor_id']) . '" ';
			echo 'ip="' . parseToXML($row['ip']) . '" ';
			echo 'start="' . parseToXML($row['start']) . '" ';
		echo '/>';
	}

	// Select all the rows in the markers table
	$sensor_query = "SELECT * FROM sensors WHERE active = 0";
	$sensor_results = mysqli_query($con, $sensor_query);
	if (!$sensor_results) {
	  die('Invalid query: ' . mysqli_error());
	}
	
	header("Content-type: text/xml");	

	// ADD FACILITIES
	while ($row = @mysqli_fetch_assoc($sensor_results)){
		echo '<dsensor ';
			echo 'id="' . parseToXML($row['sensor_id']) . '" ';
			echo 'ip="' . parseToXML($row['ip']) . '" ';
			echo 'start="' . parseToXML($row['start']) . '" ';
			echo 'end="' . parseToXML($row['end']) . '" ';
		echo '/>';
	}
	
	echo '</doc>';
?>