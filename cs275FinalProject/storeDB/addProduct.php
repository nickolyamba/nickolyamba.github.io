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
	
	//Check if this product already exist. if it's, just UPDATE Stock
	// prepare statement
	if(!($stmt = $mysqli->prepare("SELECT `productName`, `retailPrice`, `wholePrice`,
	`quantStock` FROM `product`;"))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	
	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	
	if(!$stmt->bind_result($productName, $retailPrice, $wholePrice, $quantStock)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	
	$isUpdate = false;
	// fetch the rows and print the table
	while($stmt->fetch()){
		if ($productName == $_REQUEST['productName'] &&
			$retailPrice == $_REQUEST['retailPrice'] &&
			$wholePrice == $_REQUEST['wholePrice']){
			
			$isUpdate = true;
			break;
		}
			 
	}// while
	$stmt->close();
	
	//---------- Update Quantatity in Stock in Products-----------//
	// if the same product, add to it's stock quantity
	if ($isUpdate){
		//echo 'ISupdate true';
		// Update Quantatity in Stock in Products			
		if (!($stmt = $mysqli->prepare("UPDATE product SET quantStock = (quantStock + ?) 
		WHERE productName = ? AND retailPrice = ? AND wholePrice = ?;"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}

		if (!($stmt->bind_param("isdd", $_REQUEST['quantStock'], $_REQUEST['productName'], 
					$_REQUEST['retailPrice'], $_REQUEST['wholePrice']))){
			echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
		}

		if (!$stmt->execute()){
			echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
		} 

		else{
			echo "Updated " . $stmt->affected_rows . " rows to products. ".
			$_REQUEST['quantStock']." added to quantStock";
		}
		
		$stmt->close();
	}
	
	// if the product is unique, add new product to product table
	else{
	
		//-------------- Add Product------------//
		if(!($stmt = $mysqli->prepare("INSERT INTO `product`
			(`productName`, `retailPrice`, `wholePrice`, `quantStock`)
			VALUES(?, ?, ?, ?);"))){
			echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		}
		
		if(!($stmt->bind_param("sddi",$_REQUEST['productName'], $_REQUEST['retailPrice'],
								$_REQUEST['wholePrice'], $_REQUEST['quantStock']))){
			echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
		}
		
		if(!$stmt->execute()){
			echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
		}
		
		else {
			echo "<br>Added: " . $stmt->affected_rows . " rows to [product] table.";
		}
		
		$stmt->close();
	}
		//close connection
		$mysqli->close();
	
	echo "<br><br><a href='../index.php'>Back to Database</a>";
?>