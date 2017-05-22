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
	
	//-------------- Add Product_Order------------//
	if(!($stmt = $mysqli->prepare("INSERT INTO `product_order`
		(`productID`, `orderID`, `quantOrdered`) VALUES(?, ?, ?);"))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	
	if(!($stmt->bind_param("iii",$_REQUEST['productID'], $_REQUEST['orderID'],
							$_REQUEST['quantOrdered']))){
		echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}
	
	if(!$stmt->execute()){
		echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
	}
	
	else {
		echo "<br>Added: " . $stmt->affected_rows . " rows to [product_order] table.";
	}
	
	$stmt->close();
	
	
	//---------- Update Quantatity in Stock in Products-----------//
			
	// Update Quantatity in Stock in Products			
	if (!($stmt = $mysqli->prepare("UPDATE product SET quantStock = (quantStock - ?) WHERE productID = ?;"))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}

	if (!($stmt->bind_param("ii",$_REQUEST['quantOrdered'], $_REQUEST['productID']))){
		echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}

	if (!$stmt->execute()){
		echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
	} 

	else{
		echo "Updated" . $stmt->affected_rows . " rows to products";
	}
	
	$stmt->close();
	
		
	//close connection
	$mysqli->close();
	
	echo "<br><br><a href='../index.php'>Back to Database</a>";
?>