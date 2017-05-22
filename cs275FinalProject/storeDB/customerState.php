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
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<body>
<div>
	<table>
		<th>
			<td>Customers who address is out of chosen state</td>
		</th>
		<tr>
			<td>Username</td>
			<td>State</td>
			<td>Country</td>
		</tr>
<?php
	if(!($stmt = $mysqli->prepare("SELECT tb1.userName AS userName, abbrev, countryName
									FROM (
									SELECT firstName, lastName, userName, shipAddrID
									FROM customer C
									WHERE C.customerID NOT 
									IN (
									SELECT customerID
									FROM customer C
									INNER JOIN shipAddr SA ON C.shipAddrID = SA.shipAddrID
									INNER JOIN address A ON A.addressID = SA.addressID
									INNER JOIN states S ON S.stateID = A.stateID
									WHERE S.stateID =  ?
									)) AS tb1
									INNER JOIN shipAddr SA ON tb1.shipAddrID = SA.shipAddrID
									INNER JOIN address A ON A.addressID = SA.addressID
									INNER JOIN states S ON S.stateID = A.stateID
									INNER JOIN countries CO ON A.countryID = CO.countryID;"))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}

	if(!($stmt->bind_param("i",$_POST['s_state']))){
		echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
	}

	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	if(!$stmt->bind_result($userName, $abbrev, $countryName)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
	while($stmt->fetch()){
	 echo "<tr>\n<td>\n" . $userName . "\n</td>\n<td>\n" . $abbrev . "\n</td>\n<td>\n" . $countryName . "\n</td>\n</tr>";
	}
	$stmt->close();
	
	//close connection
	$mysqli->close();
?>
	</table>
</div>

</body>
	
	<!------------------Back to Database ---------------->
	<a href="../index.php">Back to Database</a><br>
	In 5 sec it will come back to database automatically...
	<meta http-equiv="Refresh" content="5; ../index.php">

</html>