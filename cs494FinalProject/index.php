<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../assets/ico/favicon.ico">

    <title>Sign-in Page</title>

    <!-- Bootstrap core CSS -->
    <link  rel="stylesheet" href="css/bootstrap.css"/>
	<link rel="stylesheet" href="css/bootstrapValidator.min.css"/>
	
	<script src="js/jquery-2.1.0.min.js"></script> 
	
	<!-- <script src="js/jquery.validate.min.js"></script> -->
	<script src="js/bootstrapValidator.min.js"></script>

    <!-- Custom style for signIn page -->
    <link href="css/sign-In.css" rel="stylesheet">
	
	   <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/bootstrap.min.js"></script>

    <script>
		$(document).ready(function(){
			$('#form-signin').bootstrapValidator({
					message: 'This value is not valid',
					feedbackIcons: {
						valid: 'glyphicon glyphicon-ok',
						invalid: 'glyphicon glyphicon-remove',
						validating: 'glyphicon glyphicon-refresh'
					},
					fields: {
						userName: {
							message: 'The username is not valid',
							validators: {
								notEmpty: {
									message: 'The username is required and can\'t be empty'
								}
							}
						},//username
						
						pswd: {
							validators: {
								notEmpty: {
									message: 'The password is required and can\'t be empty'
								}
							}
						}//pswd
					},//fields
					
					// submitHandler handles button event if form is valid
					submitHandler: function(validator, form, submitButton) {
						var dataString = form.serialize();
						console.log(dataString);
						
						$.ajax({
							type: "POST",
							url: "login.php",
							data: dataString,
							
							success: function(data, status){    
								console.log(data);
								if(data=='true'){
									//$("#error").html("username and password correct!");
									// go to profile page
									window.location="profile.php";
								}
								else{
									//$("#error").css('display', 'inline', 'important');
									$("#error").html("<div class='alert'><img src='images/alert1.png' height=50 width=50/><br>The username or password is incorrect.<br>Please try again or <a href='signUp.php' class='signUp'>sign up</a></div>");
									
									//reset form: http://stackoverflow.com/questions/680241
									$('#form-signin').find('input:text, input:password').val('');
									
								}//else
							},// success
							
							// error handling
							error: function(xhr, textStatus, error){
								console.log('error '+ error);
								if (textStatus == "error") {
									$("#error").html("The requested page: " + this.url + "<br>The error number returned: " + xhr.status + "<br>The error message: " + xhr.statusText );
								}
							},//error
							
							// while waiting for response
							beforeSend:function(){
								//$("#error").css('display', 'inline', 'important');
								$("#error").html("<div class='alert'><img src='images/loading3.gif' height=50 width=50/> Loading...</div>")
							}//beforeSend
							
						});// .ajax()
						
					}//submitHandler
				});//bootstrapValidator
		
		});//document.ready

    </script>

  </head>

  <body>
  
  <video autoplay preload="auto" loop 
		poster="video/adrift-4_snapshot.jpg" id="bgvid">
		<source src="video/adrift-4.webm" type="video/webm; codecs=&quot;vp8, vorbis&quot;">    
  </video>

    <div class="container" id="container">
	
      <form class="form-signin" role="form" id="form-signin" method="POST">
			<!-- Place to let user know that username or password are not valid -->
			<h3 class="form-signin-heading" id="signin-heading">Welcome To Budget Planner</h3>
			<div class="error" id="error"></div>			
			<h4>Please sign in</h4>	
			<!-- input fields: http://www.tutorialrepublic.com/twitter-bootstrap-tutorial/ -->
			<!-- <div class="input-group">
				<span class="input-group-addon">
					<span class="glyphicon glyphicon-user"></span>
				</span> -->
			
			<div class="form-group"> 
				<input type="text" class="form-control" name="userName" id="userName" placeholder="Username" autofocus/>
			</div> 
			
			<!-- </div>-->
			<div class="form-group">
				<input type="password" class="form-control" name="pswd" id="pswd" placeholder="Password" />
			</div>

		   <!--
			<label class="checkbox">
			  <input type="checkbox" value="remember-me"> Remember me
			</label> -->
			<div class="form-group">
				<button class="btn btn-lg btn-primary btn-block" type="submit" id="signButton">Sign in</button>
			</div>
			
			<!-- Sign Up -->
			<span>Don't have an account? <a href="signUp.php">Sign Up Here</a></span>
      </form>
	

    </div> <!-- /container -->



  </body>
  
</html>