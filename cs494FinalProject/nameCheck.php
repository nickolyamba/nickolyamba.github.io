<?php
	header('Content-type: text/json');
	ini_set('display_errors', 'On');
	include 'storedInfo.php';
	
	if(isset($_POST["userName"]))
	{
		// save post parameters
		$userName = $_POST["userName"];
		
		// database connect
		$mysqli = new mysqli("localhost", "mysour66_guest", $myPassword, "mysour66_projects");
		if ($mysqli->connect_errno) {
			echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		}
		
		// check first if userName is already registered 
		// Prepared statement, stage 1: prepare
		if (!($stmt = $mysqli->prepare("SELECT userID, userName FROM planner 
			WHERE userName = ?"))){
			echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}
		
		// Bind parameters
		if (!$stmt->bind_param("s", $userName)) {
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		
		// execute
		if (!$stmt->execute()) {
			echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		
		// bind results
		if(!$stmt->bind_result($dbUserID, $dbUserName)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		
		// find number of rows in the result
		$stmt->store_result();
		$num_row = $stmt->num_rows;
		
		// if userName available
		if( $num_row == 1 ) {
			echo 'true';
		}
		else {
			echo 'false';
		}
		
		$mysqli->close();
	}
?>

