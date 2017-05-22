<?php
	// Request from profile.php
	//header('Content-type: text/json');
	header("Content-type: application/json");
	//start session
	session_start();
	
	ini_set('display_errors', 'On');
	include 'storedInfo.php';
	

	//echo "Hello " . $_SESSION["userName"];
	$income = $_REQUEST['income'];
	$house = $_REQUEST['house'];
	$trans = $_REQUEST['trans'];
	$insur = $_REQUEST['insur'];
	$loan = $_REQUEST['loan'];
	$food = $_REQUEST['food'];
	$cloth = $_REQUEST['cloth'];
	$gift = $_REQUEST['gift'];
	
	// database connect
	$mysqli = new mysqli("localhost", "mysour66_guest", $myPassword, "mysour66_projects");
	if ($mysqli->connect_errno) {
		echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}

	// Prepared statement, stage: prepare
	if (!($stmt = $mysqli->prepare ("UPDATE planner SET income = ?, 
	housing = ?, transport = ?, insurance = ?, loans = ?, food = ?,
	clothing = ?, gifts = ? WHERE planner.userName = ? LIMIT 1"))){
		echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
	}
	
	// Bind parameters
	if (!$stmt->bind_param("iiiiiiiis", $income, $house, $trans, $insur, $loan,
		$food, $cloth, $gift, $_SESSION['userName'])) {
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	
	// execute	
	if (!$stmt->execute()) {
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}
	
	// if success
	else{
		$stmt->close();
		// get saved data from database to update profile
		
		// Prepared statement, stage: prepare
		if (!($result = $mysqli->prepare("SELECT income, housing, 
			transport, insurance, loans, food, clothing, gifts 
			FROM planner WHERE userName = ?"))){
			echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		}
		
		// Bind parameters
		if (!$result->bind_param("s", $_SESSION['userName'])) {
			echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		// execute	
		if (!$result->execute()){
			echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		}
		
		// bind result variables
		$result->bind_result($income, $house, $trans, $insur, $loan,
		$food, $cloth, $gift);
		// fetch values and save them in array
		// that will be send to ajax 
		while ($result->fetch()) {
			$userData[] = array(
			'income' => $income,
			'house' => $house,
			'trans' => $trans,
			'insur' => $insur,
			'loan' => $loan,
			'food' => $food,
			'cloth' => $cloth,
			'gift' => $gift,
			);
		}	
		
		// send array back to Ajax
		echo json_encode($userData);
		
	}
		//close result
		$result->close();
		
		//close connection
		$mysqli->close();
		
?>	