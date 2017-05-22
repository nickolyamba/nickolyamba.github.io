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
	
	//-------------- Add to Address------------//
	if(!($stmt = $mysqli->prepare("INSERT INTO `address`
		(`street1`, `street2`, `city`, `stateID`, `zip`, `countryID`)
		VALUES
		(?, ?, ?, ?, ?, ?);"))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	
	if(!($stmt->bind_param("sssisi",$_POST['s_street1'],$_POST['s_street2'],
		$_POST['s_city'],$_POST['s_state'], $_POST['s_zip'], $_POST['s_country']))){
		echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
	
	if(!$stmt->execute()){
		echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
	}
	
	else {
		echo "<br>Added: " . $stmt->affected_rows . " rows from shipping address to [address] table.";
	}
	
	$stmt->close();
	
	// Check if Billing Address is different from Shipping 
	if(($_POST['s_street1'] !== $_POST['b_street1']) || 
		($_POST['s_street2'] !== $_POST['b_street2']) ||
		($_POST['s_city'] !== $_POST['b_city']) ||
		($_POST['s_state'] !== $_POST['b_state']) ||
		($_POST['s_zip'] !== $_POST['b_zip']) ||
		($_POST['s_country'] !== $_POST['b_country'])){
		
		////-------------- Add to Address------------////
		if(!($stmt = $mysqli->prepare("INSERT INTO `address`
			(`street1`, `street2`, `city`, `stateID`, `zip`, `countryID`)
			VALUES
			(?, ?, ?, ?, ?, ?);"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		
		if(!($stmt->bind_param("sssisi",$_POST['b_street1'],$_POST['b_street2'],
			$_POST['b_city'],$_POST['b_state'], $_POST['b_zip'], $_POST['b_country']))){
			echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
		}
		
		if(!$stmt->execute()){
			echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
		}
		
		else {
			echo "<br>Added: " . $stmt->affected_rows . " rows from billing address to [address] table.";
		}
		
		$stmt->close();	
	}//if
	
	
	////------------------------- Add to shipAddr------------------------////
	if(!($stmt = $mysqli->prepare("INSERT INTO `shipAddr` (addressID) values 
	((SELECT `addressID` FROM `address`
		WHERE (`street1` = ? AND `street2` = ? AND 
		`city` = ? AND `stateID` = ? AND `zip` = ? AND `countryID` = ?) LIMIT 1));"))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	
	if(!($stmt->bind_param("sssisi",$_POST['s_street1'],$_POST['s_street2'],
		$_POST['s_city'],$_POST['s_state'], $_POST['s_zip'], $_POST['s_country']))){
		echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
	
	if(!$stmt->execute()){
		echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
	}	
	else {
		echo "<br>Added: " . $stmt->affected_rows . " rows to [shipAddr] table.";
	}
	
	$stmt->close();
	
	
	////------------------------- Add to billAddr------------------------////
	if(!($stmt = $mysqli->prepare("INSERT INTO `billAddr` (addressID) values 
	((SELECT `addressID` FROM `address`
		WHERE (`street1` = ? AND `street2` = ? AND 
		`city` = ? AND `stateID` = ? AND `zip` = ? AND `countryID` = ?) LIMIT 1));"))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	
	if(!($stmt->bind_param("sssisi",$_POST['b_street1'],$_POST['b_street2'],
		$_POST['b_city'],$_POST['b_state'], $_POST['b_zip'], $_POST['b_country']))){
		echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
	
	if(!$stmt->execute()){
		echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
	}	
	else {
		echo "<br>Added: " . $stmt->affected_rows . " rows to [billAddr] table.";
	}
	
	$stmt->close();
	
	
	////------------------------- Add to customer------------------------////
	if(!($stmt = $mysqli->prepare("INSERT INTO `customer`
  (`firstName`, `lastName`, `userName`, `password`, `phone`, `email`, `billAddrID`, `shipAddrID`)
VALUES
  (?, ?, ?, ?, ?, ?, 
  (SELECT billAddrID FROM billAddr BA
  	INNER JOIN address A ON A.addressID = BA.addressID
  		WHERE (street1 = ? AND street2 = ? AND 
  		city = ? AND stateID = ? AND zip = ? AND 
      countryID = ?)LIMIT 1),
  (SELECT shipAddrID FROM shipAddr SA
	INNER JOIN address A ON A.addressID = SA.addressID
	  WHERE (street1 = ? AND street2 = ? AND 
  		city = ? AND stateID = ? AND zip = ? AND countryID = ?)LIMIT 1));"))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	
	if(!($stmt->bind_param("sssssssssisisssisi",
		$_POST['firstName'],$_POST['lastName'],
		$_POST['password'],$_POST['userName'],
		$_POST['phone'],$_POST['email'],
		$_POST['b_street1'],$_POST['b_street2'],
		$_POST['b_city'],$_POST['b_state'], 
		$_POST['b_zip'], $_POST['b_country'],
		$_POST['s_street1'],$_POST['s_street2'],
		$_POST['s_city'],$_POST['s_state'], 
		$_POST['s_zip'], $_POST['s_country']))){
		echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
	
	if(!$stmt->execute()){
		echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
	}	
	else {
		echo "<br>Added: " . $stmt->affected_rows . " rows to [customer] table.";
	}
	
	$stmt->close();
	
	//close connection
	$mysqli->close();
	
	echo "<br><br><a href='../index.php'>Back to Database</a>";
	//header('Location: ../index.php');
?>