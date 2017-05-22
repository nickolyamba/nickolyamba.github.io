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


<!DOCTYPE html>
<html lang="en"> 
	<head>
		<meta charset="UTF-8">
		<title>Store Database</title>
		<!-- <link rel="stylesheet" type="text/css" href="database.css"> -->
		
		<!-- Bootstrap core CSS -->
		<link  rel="stylesheet" href="css/bootstrap.css"/>
		<link rel="stylesheet" href="css/bootstrapValidator.min.css"/>
		
		<!-- <script src="js/jquery-2.1.0.min.js"></script> -->
		
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		
		<!-- <script src="js/jquery.validate.min.js"></script> -->
		<script src="js/bootstrapValidator.min.js"></script>
		
		<!-- <script src="js/jquery.validate.min.js"></script> -->

		<!-- Custom style for signIn page -->
		<link href="css/interface.css" rel="stylesheet">
		
		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="js/bootstrap.min.js"></script>
		
		<script>
			// http://stackoverflow.com/questions/12285495/
			// billing-address-same-as-shipping-address-jquery
			$(document).ready(function(){ 
				$('#sameAddress').click(function(){
					// if checked
					if($(this).is(':checked')){
						$('#b_street1').val($('#s_street1').val());
						$('#b_street2').val($('#s_street2').val());
						$('#b_city').val($('#s_city').val());
						//copy select state
						var state = $('#s_state option:selected').val();
						$("#b_state option[value='"+state+"']").attr('selected', 'selected');
						
						$('#b_zip').val($('#s_zip').val());
						//copy select country
						var country = $('#s_country option:selected').val();
						$("#b_country option[value='"+country+"']").attr('selected', 'selected');
					}
					else {
						var unselect = "1";
						//Clear on uncheck
						$('#b_street1').val("");
						$('#b_street2').val("");
						$('#b_city').val("");
						$("#b_state option:selected").attr('selected', '');						
						//$("#b_state option[value='"+1+"']").attr('selected','selected');
						//$('#b_country option[value=Nothing]').attr('disabled','selected');
						$('#b_zip').val("");
					}
				}); //checkbox
				
				/*
				// validation
				$('#form_customer').bootstrapValidator({
						feedbackIcons: {
							valid: 'glyphicon glyphicon-ok',
							invalid: 'glyphicon glyphicon-remove',
							validating: 'glyphicon glyphicon-refresh'
						},
						// apply to all fields of class .dataUpdate
						fields: {
							  validate: {
								selector: '.validate',
								validators:{
									notEmpty: {
										message: 'Can\'t be empty'
									}//,
									//regexp: {
									//	regexp: /^([0-9]){1,9}$/,
									//	message: 'Value must be in the range from 0 to 999999999'
									//}
								}//validators
							}//validate
							
						}//fields
				});//bootstrapValidator */
			});//document
		</script>
		
		
	</head> 
	
	<body>
	<div class="container" id="container">
		<h2>Welcome To Online Store Database</h2>
		<div class="tableContainer">
			<h4 class="customerHead">Customer + billAddr + shipAddr + address + state + country joined Table</h4>
			<table class='table table-hover table-condensed success'>
				<tr class="info">
					<th>First Name</th>
					<th>Last Name</th>
					<th>Username</th>
				<!-- <th>Password</th> -->
					<th>Phone</th>
					<th>Email</th>
					<th>Billing Adrress</th>
					<th>Shipping Adrress</th>
				</tr>

		<!-------------------- Fill the Customer Table -------------------->
		<?php
			// prepare statement
			if(!($stmt = $mysqli->prepare("SELECT firstName, lastName, userName, `password`, phone, email,
					b_street1, b_street2, b_city, b_state, b_zip, b_country,
					s_street1, s_street2, s_city, s_state, s_zip, s_country FROM 
						(SELECT customerID AS b_cid, firstName, lastName, userName, `password`, 
						phone, email, street1 AS b_street1, street2 AS b_street2, city AS b_city, 
						abbrev AS b_state, zip AS b_zip, countryName AS b_country
						FROM customer C
						INNER JOIN billAddr AS BA ON C.billAddrID = BA.billAddrID
						INNER JOIN address A ON A.addressID = BA.addressID
						INNER JOIN states S ON A.stateID = S.stateID
						INNER JOIN countries CO ON A.countryID = CO.countryID) AS tb1
					INNER JOIN 
						(SELECT customerID AS s_cid, street1 AS s_street1, 
						street2 AS s_street2, city AS s_city, abbrev AS s_state, 
						zip AS s_zip, countryName AS s_country
						FROM customer C
						INNER JOIN shipAddr AS SA ON C.shipAddrID = SA.shipAddrID
						INNER JOIN address A ON A.addressID = SA.addressID
						INNER JOIN states S ON A.stateID = S.stateID
						INNER JOIN countries CO ON A.countryID = CO.countryID)	AS tb2	
					ON tb1.b_cid = tb2.s_cid;"))){
				echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
			}
			
			if(!$stmt->execute()){
				echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
			}
			
			if(!$stmt->bind_result($firstName, $lastName, $userName, 
				$password, $phone, $email, 
				$b_street1, $b_street2, $b_city, $b_state, $b_zip, $b_country,
				$s_street1, $s_street2, $s_city, $s_state, $s_zip, $s_country)){
				echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
			}
			
			// fetch the rows and print the table
			while($stmt->fetch()){
				// if billing street2 is NULL and ship street2 is NULL
				if (($b_street2 == "") && ($s_street2 == ""))
					echo "<tr class='info'>\n
							<td>".$firstName."</td>\n					
							<td>".$lastName."</td>\n
							<td>".$userName."</td>\n
					   <!-- <td>".$password."</td>\n -->
							<td>".$phone."</td>\n
							<td>".$email."</td>\n
							<td>".$b_street1.", ";
							// check for empty fieleds
							// billAddr
							if ($b_street2 != "")
								echo $b_street2.",";	
							
							echo "<br>".$b_city.", ";
							
							if ($b_state != "")
								echo $b_state.", ";
							
							echo $b_zip.",<br>";
							
							if ($b_country != "")
								echo $b_country."</td>\n";
							//------------------------\\
							// shipAddr
							echo	
							"<td>".$s_street1.", ";
							// check for empty fieleds
							if ($s_street2 != "")
								echo $s_street2.",";
								
							echo "<br>".$s_city.", ";
							
							if ($s_state != "")
								echo $s_state.", ";
							
							echo $s_zip.",<br>";
							
							if ($s_country != "")
								echo $s_country."</td>\n";
							
			}
			
			$stmt->close();
		?>
				</table>
		</div> <!---end table container ----->


		<!--------------------------------------------       --------------------------------------->
		<!-----------------------------------------  ADD CUSTOMER ---------------------------------->
		<!--------------------------------------------       --------------------------------------->
		<form id="form_customer" role="form" action="storeDB/addCustomer.php" method="POST">
		<h3>Add Customer</h3>
		<div class="leftAlign">
			<h4>Customer Account Details</h4>
				<div class="form-group"> 
					<input type="text" class="form-control validate" name="firstName" id="firstName" placeholder="First Name" autofocus required/>
				</div> 
				
				<div class="form-group"> 
					<input type="text" class="form-control validate" name="lastName" id="lastName" placeholder="Last Name" required/>
				</div> 
				
				<div class="form-group"> 
					<input type="text" class="form-control validate" name="userName" id="userName" placeholder="Username" required />
				</div> 
				
				<div class="form-group">
					<input type="password" class="form-control validate" name="password" id="password" placeholder="Password" required/>
				</div>
				
				<div class="form-group"> 
					<input type="text" class="form-control validate" name="email" id="" placeholder="Email" required/>
				</div>

				<div class="form-group"> 
					<input type="text" class="form-control validate" name="phone" id="" placeholder="Phone"/>
				</div>
			</div>
		
			<div class="centerAlign">
				<!------------------------- Shipping Address ----------------------------->
				<h4 class="tableHead">Shipping Adrress</h4>
				
				<div class="form-group"> 
					<input type="text" class="form-control validate" name="s_street1" id="s_street1" placeholder="Street 1" required/>
				</div> 
				
				<div class="form-group"> 
					<input type="text" class="form-control" name="s_street2" id="s_street2" placeholder="Street 2 Optional"/>
				</div>
				
				<div class="form-group"> 
					<input type="text" class="form-control validate" name="s_city" id="s_city" placeholder="City" required/>
				</div>
				
				<!----------------  Select State --------------->
				<div class="form-group"> 
					<select class="form-control" name="s_state" id="s_state">
					<!--  Load Choose Menu -->
						<?php
						if(!($stmt = $mysqli->prepare("SELECT stateID, abbrev FROM states"))){
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}

						if(!$stmt->execute()){
							echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						
						if(!$stmt->bind_result($s_stateID, $s_abbrev)){
							echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
							echo "<option value='' disabled selected>Select State Optional</option>";
							echo "<option value='66'>Not United States</option>\n";
						while($stmt->fetch()){
							// $id of a palnet is value of submit, label showed - planet name
							echo '<option value="'.$s_stateID.'">'.$s_abbrev.'</option>\n';
						}
						$stmt->close();			
						?>
					</select>
				</div>
				
				<div class="form-group"> 
					<input type="text" class="form-control validate" name="s_zip" id="s_zip" placeholder="Zip Code"/>
				</div>
				
				<!--  Select Country -->
				<div class="form-group"> 
				<select class="form-control" name="s_country" id="s_country">
						<!--  Load Choose Menu -->
					<?php
					if(!($stmt = $mysqli->prepare("SELECT countryID, countryName FROM countries"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}

					if(!$stmt->execute()){
						echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					
					if(!$stmt->bind_result($s_countryID, $s_countryName)){
						echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
						//echo "<option value='' disabled selected>Select Country</option>";
						//echo '<option value=""></option>\n';
					while($stmt->fetch()){
						// $id of a palnet is value of submit, label showed - planet name
						echo '<option value="'.$s_countryID.'">'.$s_countryName.'</option>\n';
					}
					$stmt->close();
					?>
					</select>
				</div>
				
				<label class="checkbox">
				  <input type="checkbox" id="sameAddress"> Check if Billing Address is the same
				</label>
			</div>
		
			<div class="rightAlign">
				<!------------------------- Billing Address ----------------------------->
				<h4 class="tableHead">Billing Adrress</h4>
				
				<div class="form-group"> 
					<input type="text" class="form-control" name="b_street1" id="b_street1" placeholder="Street 1" required/>
				</div> 
				
				<div class="form-group"> 
					<input type="text" class="form-control" name="b_street2" id="b_street2" placeholder="Street 2 Optional"/>
				</div>
				
				<div class="form-group"> 
					<input type="text" class="form-control" name="b_city" id="b_city" placeholder="City" required/>
				</div>
				
				<!----------------  Select State --------------->
				<div class="form-group"> 
					<select class="form-control" name="b_state" id="b_state">
						<!--  Load Choose Menu -->
						<?php
						if(!($stmt = $mysqli->prepare("SELECT stateID, abbrev FROM states"))){
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}

						if(!$stmt->execute()){
							echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						
						if(!$stmt->bind_result($b_stateID, $b_abbrev)){
							echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
							echo "<option value='' disabled selected>Select State Optional</option>";
							echo "<option value='66'>Not United States</option>\n";
						while($stmt->fetch()){
							// $id of a palnet is value of submit, label showed - planet name
							echo '<option value="'.$b_stateID.'">'.$b_abbrev.'</option>\n';
						}
						$stmt->close();			
						?>
					</select>
				</div>
				
				<div class="form-group"> 
					<input type="text" class="form-control" name="b_zip" id="b_zip" placeholder="Zip Code" required/>
				</div>
				
				<!----------------  Select Country --------------->
				<div class="form-group"> 
					<select class="form-control" name="b_country" id="b_country">
						<!--  Load Choose Menu -->
						<?php
						if(!($stmt = $mysqli->prepare("SELECT countryID, countryName FROM countries"))){
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}

						if(!$stmt->execute()){
							echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						
						if(!$stmt->bind_result($b_countryID, $b_countryName)){
							echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
							//echo "<option value='' disabled selected>Select Country</option>";
							//echo '<option value=""></option>\n';
						while($stmt->fetch()){
							// $id of a palnet is value of submit, label showed - planet name
							echo '<option value="'.$b_countryID.'">'.$b_countryName.'</option>\n';
						}
						$stmt->close();
						?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<button class="btn btn-lg btn-primary btn-block" type="submit" id="costumBtn">Add Customer</button>
			</div>
			
		</form><br>
		
		
		<!----------------------------------------------      -------------------------------------------->
		<!----------------------------------------- Filter Customer -------------------------------------->
		<!---------------------------------------------       -------------------------------------------->
		<!----------------  Filter Customers: Find customers who shipping address isn't in chosen state--->
		
		<!----------------  Select State --------------->
			<h3>Filter Customers: Find customers who shipping address isn't in chosen state</h3>
			<form id="form_customerFilter" role="form" action="storeDB/customerState.php" method="POST">
				<div class="form-group">
					<p class="help-block">
						Note that result state might contain NULL values since state is an optional attribute.
					</p>
					<select class="form-control" name="s_state" id="s_state" required>
					<!--  Load Choose Menu -->
						<?php
						if(!($stmt = $mysqli->prepare("SELECT stateID, abbrev FROM states"))){
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}

						if(!$stmt->execute()){
							echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						
						if(!$stmt->bind_result($s_stateID, $s_abbrev)){
							echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
							echo "<option value='' disabled selected>Select State</option>";
						while($stmt->fetch()){
							// $id of a palnet is value of submit, label showed - planet name
							echo '<option value="'.$s_stateID.'">'.$s_abbrev.'</option>\n';
						}
						$stmt->close();			
						?>
					</select>					
				</div>
				<div class="form-group">
						<button class="btn btn-primary" type="submit">Filter Costumer</button>
				</div>
			</form>
			
			
		<div class="" id="">	
		<!----------------------------------------------      -------------------------------------------->
		<!----------------------------------------- Delete Customer -------------------------------------->
		<!---------------------------------------------       -------------------------------------------->
		<h3 id="delCustomer">Delete Customer</h3>
			<form id="form_customerFilter" role="form" action="storeDB/deleteCustomer.php" method="POST">
				<div class="form-group">
					<select class="form-control" name="customerID" required>
					<!--  Load Choose Menu -->
						<?php
						if(!($stmt = $mysqli->prepare("SELECT customerID, userName FROM customer"))){
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}

						if(!$stmt->execute()){
							echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						
						if(!$stmt->bind_result($customerID, $userName)){
							echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
							echo "<option value='' disabled selected>Select Customer</option>";
						while($stmt->fetch()){
							// $id of a palnet is value of submit, label showed - planet name
							echo '<option value="'.$customerID.'">'.$userName.'</option>\n';
						}
						$stmt->close();			
						?>
					</select>					
				</div>
				<div class="form-group">
						<button class="btn btn-primary" type="submit">Delete Costumer</button>
				</div>
			</form>
		</div>
		
		
		<!--------------------------------------------       --------------------------------------->
		<!-----------------------------------------  ADD ORDER ------------------------------------->
		<!--------------------------------------------       --------------------------------------->
		<div class="leftAlign">
		<form id="form_addOrder" role="form" action="storeDB/addOrder.php" method="POST">
		<h3>Add to Order</h3>
			<h4>Order Details</h4>
				<!----------------  Select userName --------------->
				<div class="form-group"> 
					<select class="form-control" name="userName">
						<!-----  Load Choose Menu ----->
						<?php
						if(!($stmt = $mysqli->prepare("SELECT userName FROM customer"))){
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}

						if(!$stmt->execute()){
							echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						
						if(!$stmt->bind_result($userName)){
							echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
							echo "<option value='' disabled selected>Select Username</option>";
						while($stmt->fetch()){
							
							echo '<option value="'.$userName.'">'.$userName.'</option>\n';
						}
						$stmt->close();
						?>
					</select>
					<div class="form-group">
							<button class="btn btn-primary" type="submit" id="signButton">Add to Order</button>
					</div>
				</div>
			</form>

			
		<!-------------------- Fill the Order Table -------------------->
		<div class="">
			<h4>Order Table</h4>
			<table class='table table-hover table-condensed' id="orderTable">
				<tr class="info">
					<th>Order ID</th>
					<th>Username</th>
					<th>Order Timestamp</th>
				</tr>

			<?php
				// prepare statement
				if(!($stmt = $mysqli->prepare("SELECT userName, orderID, orderDate FROM `order`
						LEFT JOIN customer C ON C.customerID = order.customerID;"))){
								echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
				}
				
				if(!$stmt->execute()){
					echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
				}
				
				if(!$stmt->bind_result($userName, $orderID, $orderDate)){
					echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
				}
				
				// fetch the rows and print the table
				while($stmt->fetch()){
					// if billing street2 is NULL and ship street2 is NULL
					
						echo "<tr class='info'>\n
								<td>".$orderID."</td>\n
								<td>".$userName."</td>\n					
								<td>".$orderDate."</td>\n
							</tr>\n";
									
				}// while
				$stmt->close();
			?>
		
			</table>
		</div><br> <!-- end div for table -->
		</div>
		
		<!--------------------------------------------       --------------------------------------->
		<!-----------------------------------------  ADD PRODUCT ------------------------------------->
		<!--------------------------------------------       --------------------------------------->
		<div class="centerAlign" id="addProductForm">
		<form id="form_addOrder" role="form" action="storeDB/addProduct.php" method="POST">
		<h3>Add Product or Add to Stock</h3>
				<h4>Product Details</h4>
				
				<div class="form-group"> 
					<input type="text" class="form-control validate" name="productName" placeholder="Product Name" required/>
				</div> 
				
				<div class="form-group"> 
					<input type="number" step="0.01" class="form-control validate" name="retailPrice" placeholder="Retail Price" required number/>
				</div> 
				
				<div class="form-group"> 
					<input type="number" step="0.01" class="form-control validate" name="wholePrice" placeholder="Wholesale Price" required number/>
				</div> 
				
				<div class="form-group">
					<input type="number" class="form-control validate" name="quantStock" placeholder="Quantity in Stock" required />
				</div>
				
				<div class="form-group">
						<button class="btn btn-primary" type="submit">Add to Order</button>
				</div>
				<p class="help-block">
					To UPDATE product in stock, enter its unique Name, Retail Price, and Wholesale Price
					and then add amount of items to add to stock in Quantity in Stock.
				</p>
			</form><br>
		</div>
		
		
		<!-------------------- Fill the Product Table -------------------->
		<div class="rightAlign" id="prodTable">
			<h4>Product Table</h4>
			<table class='table table-hover table-condensed' id="orderTable">
				<tr class="info">
					<th>Product Name</th>
					<th>Retail Price</th>
					<th>Wholesale Price</th>
					<th>Stock Quantity</th>
				</tr>

			<?php
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
				
				// fetch the rows and print the table
				while($stmt->fetch()){
						echo "<tr class='info'>\n
								<td>".$productName."</td>\n					
								<td>".$retailPrice."</td>\n
								<td>".$wholePrice."</td>\n
								<td>".$quantStock."</td>\n
							</tr>\n";
									
				}// while
				$stmt->close();
			?>
		
			</table>
		</div><br> <!-- end div for table -->
		
		
		
		
		<!--------------------------------------------       --------------------------------------->
		<!----------------------------------    ADD PRODUCT TO ORDER  ------------------------------>
		<!--------------------------------------------       --------------------------------------->
		<div class="clearLeft"></div>
		
		<form role="form" action="storeDB/addProductOrder.php" method="POST">	
			<div class="floatLeft">
				<h3>Add Product to Order and Update Product Stock</h3>
				<!----------------  Select Product --------------->
				<div class="form-group"> 
					<select class="form-control form-inline" name="productID" required>
						<!-----  Load Choose Menu ----->
						<?php
						// prepare statement
						if(!($stmt = $mysqli->prepare("SELECT `productID`, `productName` FROM `product`
							ORDER BY `productName`;"))){
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}

						if(!$stmt->execute()){
							echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						
						if(!$stmt->bind_result($productID, $productName)){
							echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						
							echo "<option value='' disabled selected>Select Product</option>";
						while($stmt->fetch()){
							
							echo '<option value="'.$productID.'">'.$productName.'</option>\n';
						}
						$stmt->close();
						?>
					</select>
				</div>
			
		
				
				<!----------------  Select Order --------------->
				<div class="form-group"> 
					<select class="form-control form-inline" name="orderID" required>
						<!-----  Load Choose Menu ----->
						<?php
						// prepare statement
						if(!($stmt = $mysqli->prepare("SELECT userName, orderID FROM `order`
						INNER JOIN customer C ON C.customerID = order.customerID ORDER BY orderID;"))){
								echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}

						if(!$stmt->execute()){
							echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						
						if(!$stmt->bind_result($userName, $orderID)){
							echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						
							echo "<option value='' disabled selected>Select Order</option>";
						while($stmt->fetch()){
							
							echo '<option value="'.$orderID.'">'.'ID# '.$orderID.' '.$userName.'</option>\n';
						}
						$stmt->close();
						?>
					</select>
				</div>
				
				<div class="form-group">
					<input type="number" class="form-control validate" name="quantOrdered" placeholder="Product Quantity" required/>
					<p class="help-block">
							Note Product Quanitity will be subtracted from <br> Quantity in Stock in Products.
					</p>
				</div>
				
				<div class="form-group">
						<button class="btn btn-primary" type="submit">Add Product to Order</button>
				</div>
			</div>
		</form>
	
	
	
			<!--------------------------------------------       --------------------------------------->
			<!----------------------------------    UPDATE PRODUCT_ORDER  ------------------------------>
			<!--------------------------------------------       --------------------------------------->
		
		<div class="clearLeft"></div>
		<form role="form" action="storeDB/updateProductOrder.php" method="POST">	
			<div class="floatLeft">
				<h3>Update Product_Order</h3>
				<!----------------  Select Product_Order Pair--------------->
				<div class="form-group"> 
					<select class="form-control form-inline" name="P_O" required>
						<!-----  Load Choose Menu ----->
						<?php
						// prepare statement
						if(!($stmt = $mysqli->prepare("SELECT PO.orderID, PO.productID, productName FROM
														product_order PO
														INNER JOIN product P ON P.productID = PO.productID  
														ORDER BY `orderID`;"))){
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}

						if(!$stmt->execute()){
							echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						
						if(!$stmt->bind_result($orderID, $productID, $productName)){
							echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						
							echo "<option value='' disabled selected>Select Product-Order Pair</option>";
						while($stmt->fetch()){
							
							echo '<option value="'.$productID."|".$orderID.'">'.$orderID." ".$productName.'</option>\n';
						}
						$stmt->close();
						?>
					</select>
				</div>
				
				
				<!----------------  Select Product --------------->
				<div class="form-group"> 
					<select class="form-control form-inline" name="productID" required>
						<!-----  Load Choose Menu ----->
						<?php
						// prepare statement
						if(!($stmt = $mysqli->prepare("SELECT `productID`, `productName` FROM `product`
							ORDER BY `productName`;"))){
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}

						if(!$stmt->execute()){
							echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						
						if(!$stmt->bind_result($productID, $productName)){
							echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						
							echo "<option value='' disabled selected>Select Product</option>";
						while($stmt->fetch()){
							
							echo '<option value="'.$productID.'">'.$productName.'</option>\n';
						}
						$stmt->close();
						?>
					</select>
				</div>
			
				
				<!----------------  Select Order --------------->
				<div class="form-group"> 
					<select class="form-control form-inline" name="orderID" required>
						<!-----  Load Choose Menu ----->
						<?php
						// prepare statement
						if(!($stmt = $mysqli->prepare("SELECT userName, orderID FROM `order`
						INNER JOIN customer C ON C.customerID = order.customerID ORDER BY orderID;"))){
								echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}

						if(!$stmt->execute()){
							echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						
						if(!$stmt->bind_result($userName, $orderID)){
							echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						
							echo "<option value='' disabled selected>Select Order</option>";
						while($stmt->fetch()){
							
							echo '<option value="'.$orderID.'">'.'ID# '.$orderID.' '.$userName.'</option>\n';
						}
						$stmt->close();
						?>
					</select>
				</div>
				
				<div class="form-group">
					<input type="number" class="form-control validate" name="quantOrdered" placeholder="Product Quantity" required/>
				</div>
				
				<div class="form-group">
						<button class="btn btn-primary" type="submit">Update Product-Order</button>
				</div>
			</div>
		</form>
			
			
			
			<!--------------------------------------------       --------------------------------------->
			<!----------------------------------    Delete PRODUCT_ORDER  ------------------------------>
			<!--------------------------------------------       --------------------------------------->
		
		<div class="floatLeft" id="delPO">
		<form role="form" action="storeDB/deleteProductOrder.php" method="POST">	
			<div class="">
				<h3>Delete Product_Order</h3>
				<!----------------  Select Product_Order Pair--------------->
				<div class="form-group"> 
					<select class="form-control form-inline" name="P_O" required>
						<!-----  Load Choose Menu ----->
						<?php
						// prepare statement
						if(!($stmt = $mysqli->prepare("SELECT PO.orderID, PO.productID, productName FROM
														product_order PO
														INNER JOIN product P ON P.productID = PO.productID  
														ORDER BY `orderID`;"))){
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						}

						if(!$stmt->execute()){
							echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						
						if(!$stmt->bind_result($orderID, $productID, $productName)){
							echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						}
						
							echo "<option value='' disabled selected>Select Product-Order Pair</option>";
						while($stmt->fetch()){
							
							echo '<option value="'.$productID."|".$orderID.'">'.$orderID." ".$productName.'</option>\n';
						}
						$stmt->close();
						?>
					</select>
				</div>
				<div class="form-group">
						<button class="btn btn-primary" type="submit">Delete Product_Order</button>
				</div>
			</div>
		</div><!-- end delete -->
		
		
		<!-------------------- Fill the Product_Order Table -------------------->
		<div class="floatRight" id="prodOrderTable">
			<h4>Product_Order Table + UserName</h4>
			<table class='table table-hover table-condensed' id="orderTable">
				<tr class="info">
					<th>OrderID</th>
					<th>PsroductName</th>
					<th>UserName</th>
					<th>Quantity Ordered</th>
				</tr>

			<?php
				// prepare statement
				if(!($stmt = $mysqli->prepare("SELECT PO.orderID, productName, userName, quantOrdered FROM  `order` 
												INNER JOIN customer C ON C.customerID = order.customerID
												INNER JOIN product_order PO ON order.orderID = PO.orderID
												INNER JOIN product P ON P.productID = PO.productID
												ORDER BY orderID;"))){
					echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
				}
				
				if(!$stmt->execute()){
					echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
				}
				
				if(!$stmt->bind_result($orderID, $productName, $userName, $quantOrdered)){
					echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
				}
				
				// fetch the rows and print the table
				while($stmt->fetch()){
						echo "<tr class='info'>\n
								<td>".$orderID."</td>\n					
								<td>".$productName."</td>\n
								<td>".$userName."</td>\n
								<td>".$quantOrdered."</td>\n
							</tr>\n";
									
				}// while
				$stmt->close();
			?>
		
			</table>
		</div><br> <!-- end div for table -->
		
		
		
	</div> <!---end of .container ----->
	</body>
</html>