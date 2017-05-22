<?php
	header('Content-type: text/json');
	//start session
	session_start();
	// destroy current session
	$_SESSION = array();
	session_destroy();
	//start new session
	session_start();
	
	ini_set('display_errors', 'On');
	include 'storedInfo.php';
	
	//$userName = $_POST['userName'];
	//$password = $_POST['pswd'];
	
	//echo $userName . "<br>";
	//echo $password . "<br>";
		
	if(isset($_POST["userName"]) && isset($_POST["password"]))
	{
		
		// save post parameters
		$userName = $_POST['userName'];
		$password = md5($_POST['password']);
		
		// connect db
		$mysqli = new mysqli("localhost", "mysour66_guest", $myPassword, "mysour66_projects");
		if ($mysqli->connect_errno) {
			echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		}

		// Prepared statement, stage 1: prepare
		if (!($stmt = $mysqli->prepare ("INSERT INTO planner (userName, password) VALUES(?, ?)"))){
			echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}
		
		// Bind parameters
		if (!$stmt->bind_param("ss", $userName, $password)) {
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		
		// execute
		if (!$stmt->execute()) {
			echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}

		// if success
		else {
			// Query db for the name and ID to setup a session
			if (!($stmt = $mysqli->prepare ("SELECT userID, userName, password FROM planner 
				WHERE (userName = ? and password = ?)"))){
				echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			}
			// Bind parameters
			if (!$stmt->bind_param("ss", $userName, $password)) {
				echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			// execute
			if (!$stmt->execute()) {
				echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
			}
			// bind results
			if(!$stmt->bind_result($dbUserID, $dbUserName, $dbPswd)){
				echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
			}
			
			// find number of rows in the result
			$stmt->store_result();
			$num_row = $stmt->num_rows;
			//echo "<br>" . "numRows: " . $num_row . "<br>";
			
			//echo "<br>" . "dbUserName: " . $dbUserName . "<br>";
			
			// if there is only one row
			if( $num_row == 1 ) {
				$stmt->fetch();
				$_SESSION['userID'] = $dbUserID;
				$_SESSION['userName'] = $dbUserName;
				$_SESSION['password'] = $dbPswd;
				
				echo 'true';
				//echo "<br>" . "Session UserID: " . $_SESSION["userId"];
			}
			
			else{
				echo 'false';
			}
			
		}//else: if success in saving to db
		
		$mysqli->close();
	}
?>