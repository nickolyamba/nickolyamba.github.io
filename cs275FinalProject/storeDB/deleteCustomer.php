<?php
	//Turn on error reporting
	ini_set('display_errors', 'On');
	//include password to database
	include 'storedInfo.php';
	

	//Connects to the database
	$mysqli = new mysqli("localhost", "mysour66_guest", $myPassword, "mysour66_projects");
	if ($mysqli->connect_errno) {
		echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}
	
	//-------------- Delete Customer------------//
	if(!($stmt = $mysqli->prepare("DELETE FROM customer WHERE customerID = ? LIMIT 1"))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	
	if(!($stmt->bind_param("i",$_REQUEST['customerID']))){
		echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
	
	if(!$stmt->execute()){
		echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
	}
	
	else {
		echo "<br>Deleted: " . $stmt->affected_rows . " rows in [customer] table. customerID: ".$_REQUEST['customerID'];
	}
	
	$stmt->close();
	
		
	//close connection
	$mysqli->close();
	
	echo "<br><br><a href='../index.php'>Back to the database to see changes</a>";
?>