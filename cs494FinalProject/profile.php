<?php
	session_start();
	//$_SESSION = array();
	//session_destroy();
	ini_set('display_errors', 'On');
	
	// if there is no current session by authorized user
	if (!(isset($_SESSION['userName']) && isset($_SESSION['userID'])))
	{
		//echo "No session yet";
		//header('Location: http://web.engr.oregonstate.edu/~goncharn/CS494/Project/signIn.php');
		header('Location: index.php');
	}
	
	//else
		//echo "Hello " . $_SESSION["userName"];
?>

<!DOCTYPE html>
<html lang="en"> 
	<head>
		<meta charset="UTF-8">
		<title>profile</title>
		
		<!-- Bootstrap core CSS -->
		<link  rel="stylesheet" href="css/bootstrap.css"/>
		<link rel="stylesheet" href="css/bootstrapValidator.min.css"/>
		
		<!-- <script src="js/jquery-2.1.0.min.js"></script> -->
		
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		
		<!-- <script src="js/jquery.validate.min.js"></script> -->
		<script src="js/bootstrapValidator.min.js"></script>
		
		<!-- <script src="js/jquery.validate.min.js"></script> -->

		<!-- Custom style for signIn page -->
		<link href="css/profile.css" rel="stylesheet">
		
		<!-- Bootstrap core JavaScript
		================================================== -->
		<!-- Placed at the end of the document so the pages load faster -->
		<script src="js/bootstrap.min.js"></script>
		
		
		<!--couldn't make bootstrap validation working
		<script>
			$(document).ready(function(){			
				// click on table cell allows to chnage the values
				$('.table-hover td').click(function(e){
					//cell #id
					var cell_id = $(this).attr('id');
					//console.log(cell_id);
					
					//value in cell
					var cellVal = $(this).text();
					//alert(cval);
					
					$(this).empty();
					
					//$('#formStart').html("<form role='form' id='update'>")
					
					$(this).html("<div class='form-group'> <input type='number' min="1" max="5" name='cell_id' value='"+cellVal+"' required data-bv-notempty-message='The full name is required'/></div>");
					e.stopPropagation(); //id='"+cell_id+"'
					$('#updateBtn').html('<button class="btn btn-lg btn-primary" type="submit" id="update">Update</button><br>');
					
					// prevent this cell to respond on click again				
					$(this).unbind('click'); 
				
				});//$(.table-hover).click()
			});//document.ready	*/
		</script> -->
		
		<script>
			$(document).ready(function(){
				$('#form-update').bootstrapValidator({
						feedbackIcons: {
							valid: 'glyphicon glyphicon-ok',
							invalid: 'glyphicon glyphicon-remove',
							validating: 'glyphicon glyphicon-refresh'
						},
						// apply to all fields of class .dataUpdate
						fields: {
							  dataUpdate: {
								selector: '.dataUpdate',
								validators:{
									notEmpty: {
										message: 'Can\'t be empty'
									},
									regexp: {
										regexp: /^([0-9]){1,9}$/,
										message: 'Value must be in the range from 0 to 999999999'
									}
								}//validators
							}//dataUpdate
							
						},//fields
						
						// submitHandler handles button event if form is valid
						submitHandler: function(validator, form, submitButton) {
							var dataString = form.serialize();
							//console.log(dataString);
							// ajax call
							$.ajax({
								type: "GET",
								url: "update.php",
								data: dataString,
								dataType : "json",
								// if success
								success: function(data, status){    
									//console.log(data);
									// declare and init var to keep balance
									var balance = 0;
									// update data in cells 
									// data retrieved from the database
									for (var i=0;i<data.length;i++){ 
										$('td#cell1-1').text(data[i].income);
										$('td#cell1-2').text(data[i].house);
										$('td#cell1-3').text(data[i].trans);
										$('td#cell1-4').text(data[i].insur);
										$('td#cell1-5').text(data[i].loan);
										$('td#cell1-6').text(data[i].food);
										$('td#cell1-7').text(data[i].cloth);
										$('td#cell1-8').text(data[i].gift);
									
										// traverse data[i] object and sum
										 for (var key in data[i]){
											balance = balance + data[i][key];
											//console.log(balance);
											/*result += 'data[i]' + "." + prop + " = " +data[i][prop] + "\n";*/
										}//internal loop inside the row
										
										balance = 2*data[i].income - balance;
										$('td#cell1-9').text(balance);
									}//external loop rows
										
										 //balance = balance - data[i].income;
									
									//console.log(balance);
									// enable button
									$('#updateBtn').prop('disabled', false);
									//update status;
									$("#update_stat").html("Data has been updated!");
									
								},//success function
								
								// error handling
								error: function(xhr, textStatus, error){
									console.log('error: '+ error);
									if (textStatus == "error") {
										 $("#update_stat").html("The requested page: " + this.url + "<br>The error number returned: " + xhr.status + "<br>The error message: " + xhr.statusText );
									 //enable button
									$('#updateBtn').prop('disabled', false);
									}
								},
								// while waiting for response
								beforeSend:function(){
									$("#update_stat").html("<div class='alert'><img src='images/loading3.gif' height=50 width=50/> Loading...</div>")
								}								
							});// .ajax()
							
						}//submitHandler
					});//bootstrapValidator
				
				$('#signOutBtn').click(function(){
					console.log('pressed');
					// ajax call to php
					$.ajax({
						type: "GET",
						url: "logout.php",
						//data: dataString,
						
						// if success
						success: function(data, status){    
							//console.log(status);
							window.location="index.php";
						},//success function
						
						// error handling
						error: function(xhr, textStatus, error){
							console.log('error: '+ error);
							//if (textStatus == "error") {
							 $("#logout_stat").html("The requested page: " + this.url + "<br>The error number returned: " + xhr.status + "<br>The error message: " + xhr.statusText );
							//}
						}/*,
						// while waiting for response
						beforeSend:function(){
							$("#logout_stat").html("<div class='alert'><img src='images/loading3.gif' height=50 width=50/> Loading...</div>")
						}*/								
					});// .ajax()
				});
				
				$('#signOutBtn').click(function () {
						var btn = $(this)
						btn.button('loading')
						setTimeout(function () {
						btn.button('reset')
					}, 3000)
				});

			});//(document).ready()*/
			</script>
		
	</head>
	
	
	<body>
		 <div class="container" id="container">
 
			<!-- Sign Out -->
			<div class="form-group">
				<button class="btn btn-lg btn-primary" type="button" id="signOutBtn" 
				data-loading-text="Loading...">Sign Out</button><br>
			</div>
			<div id="logout_stat"></div>
			
	<?php
		//error_reporting(E_ALL);
		//ini_set('display_errors', 1);
		
		ini_set('display_errors', 'On');
		include 'storedInfo.php';
	
		
		// filter html chars for output
		$userName = htmlspecialchars($_SESSION["userName"]);
		$userID = htmlspecialchars($_SESSION["userID"]);
		
		echo "<h1><br>Welcome ".$userName.", let's check your budget </h1><br>\n";

		// connect to database
		$mysqli = new mysqli("localhost", "mysour66_guest", $myPassword, "mysour66_projects");
		if ($mysqli->connect_errno) {
			echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
		}

		// Prepared statement, stage 1: prepare
		if (!($stmt = $mysqli->prepare ("SELECT * FROM planner WHERE userName = ?"))){
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
		if(!$stmt->bind_result($dbUserID, $dbUserName, $dbPswd, $dbIncome, $dbHousing, $dbTrans, $dbInsur, $dbLoan, $dbFood, $dbCloth, $dbGift)){
			echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		
		// start form
		//echo "<form role='form' id='updateForm'>\n";
		
		// build table
		echo "<table class='table table-hover table-striped table-bordered table-condensed'>\n	
					<tr>\n	
						<th>Income, \$</th>\n
						<th>Housing, \$</th>\n
						<th>Transport, \$</th>\n
						<th>Insurance, \$</th>\n
						<th>Loans, \$</th>\n
						<th>Food, \$</th>\n
						<th>Clothes, \$</th>\n
						<th>Gifts, \$</th>\n
						<th>Balance, \$</th>\n
					</tr>\n";
				
		$balance = 0;
		$rowCounter = 1;
		$colCounter = 1;
		
		// print values from database
		while($stmt->fetch()){
			// prevent malicious code
			$dbIncome = htmlspecialchars($dbIncome);
			$dbHousing = htmlspecialchars($dbHousing);
			$dbTrans = htmlspecialchars($dbTrans);
			$dbInsur = htmlspecialchars($dbInsur);
			$dbLoan = htmlspecialchars($dbLoan);
			$dbFood = htmlspecialchars($dbFood);
			$dbCloth = htmlspecialchars($dbCloth);
			$dbGift = htmlspecialchars($dbGift);
			//calculate balance
			$balance = $dbIncome-($dbHousing+$dbTrans+$dbInsur+$dbLoan+$dbFood+$dbCloth+$dbGift);
	
			echo 
				"<tr class='form-group' id='row".$rowCounter."'>\n
					<td id='cell".$rowCounter."-".$colCounter++."'>".$dbIncome."</td>\n					
					<td id='cell".$rowCounter."-".$colCounter++."'>".$dbHousing."</td>\n
					<td id='cell".$rowCounter."-".$colCounter++."'>".$dbTrans."</td>\n
					<td id='cell".$rowCounter."-".$colCounter++."'>".$dbInsur."</td>\n
					<td id='cell".$rowCounter."-".$colCounter++."'>".$dbLoan."</td>\n
					<td id='cell".$rowCounter."-".$colCounter++."'>".$dbFood."</td>\n
					<td id='cell".$rowCounter."-".$colCounter++."'>".$dbCloth."</td>\n
					<td id='cell".$rowCounter."-".$colCounter++."'>".$dbGift."</td>\n
					<td id='cell".$rowCounter."-".$colCounter++."'>".$balance."</td>\n
				</tr>\n";
				
				$rowCounter++;
		}//while
		
		// end table
		echo "</table>\n\n";
	
		//echo "<div class='form-group' id='updateBtn'>\n</div>\n";
		
		//echo "</form>\n";
	?>
			<div id="priem"></div>
		<form class="form-inline form-update" role="form" id="form-update">
        <!-- Place to let user know that username or password are not valid -->
		<br>
			<h3 class="form-update-heading text-center">Update the budget</h3>
			
			<!-- Income -->
			<div class="form-group">
				<label>Income</label>
			<input type='text' class='form-control dataUpdate' name='income' id='income' value="<?php echo $dbIncome?>"/>
			</div> 
			
			<!-- Housing -->
			<div class="form-group">
				<label>Housing</label>
			<input type='text' class='form-control dataUpdate' name='house' id='house' value="<?php echo $dbHousing?>"/>
			</div>
			
			<!-- Transportation -->
			<div class="form-group">
				<label>Transportation</label>
			<input type='text' class='form-control dataUpdate' name='trans' id='trans' value="<?php echo $dbTrans?>"/>
			</div>
			
			<!-- Insurance -->
			<div class="form-group">
				<label>Insurance</label>
				<input type='text' class='form-control dataUpdate' name='insur' id='insur' value="<?php echo $dbInsur?>"/>
			</div>
			
			<!-- Loans -->
			<div class="form-group">
				<label>Loans</label>
				<input type='text' class='form-control dataUpdate' name='loan' id='loan' value="<?php echo $dbLoan?>"/>
			</div>
			
			<!-- Food -->
			<div class="form-group">
				<label>Food</label>
				<input type='text' class='form-control dataUpdate' name='food' id='food' value="<?php echo $dbFood?>"/>
			</div>
			
			<!-- Clothes -->
			<div class="form-group">
				<label>Clothes</label>
				<input type='text' class='form-control dataUpdate' name='cloth' id='cloth' value="<?php echo $dbCloth?>"/>
			</div>
			
			<!-- Gifts -->
			<div class="form-group">
				<label>Gifts</label>
				<input type='text' class='form-control dataUpdate' name='gift' id='gift' value="<?php echo $dbGift?>"/>
			</div>

			<!-- Button -->
			<div class="buttonUpdate">
				<button class="btn btn-lg btn-primary" type="submit" id="updateBtn">Update</button>
			</div><br>
			
			<div class="update_stat" id="update_stat"></div>	 
			
		</form>
	
		</div> <!-- end of container -->
		
		
	</body>
	
</html>

