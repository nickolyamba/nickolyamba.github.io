<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
   
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../assets/ico/favicon.ico">

    <title>Sign-Up Page</title>

    <!-- Bootstrap core CSS -->
    <link  rel="stylesheet" href="css/bootstrap.css"/>
	<link rel="stylesheet" href="css/bootstrapValidator.min.css"/>
	
	<script src="js/jquery-2.1.0.min.js"></script> 
	
	<!-- <script src="js/jquery.validate.min.js"></script> -->
	<script src="js/bootstrapValidator.min.js"></script>

    <!-- Custom styles for this template -->
    <link href="css/signUp.css" rel="stylesheet">
	
	      <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/bootstrap.min.js"></script>

    <script>
		$(document).ready(function(){
			// http://www.sanwebe.com/2013/04/username-live-check-using-ajax-php
			// while user types userName
			$("#userName").focusin(function(){
				// clear error message
			   $('#error').empty();
			});
			
			$("#userName").keyup(function(e){
			   // get the curernt value of userName
			   var userName = $(this).val();
			   // make ajax call
			   $.post('nameCheck.php', {'userName': userName}, function(data){ 
					//console.log(data);
					//$(this).data( "test", true);
						//console.log($( "#userName" ).data("test"));
					// if userName is available
					if (!data)
					{
						$("#isAvailable").html("<div class='avail'><img src='images/available24.png'/>"+ 
						"Username is available </div>");
						// store availability  state to retrieve it
						// when an user tries to submit the form
						// with already reserved username
						$('#userName').data('available', true);
						//console.log($('#userName').data('available'));
					}
					// if userName isn't available
					if(data)
					{
						// disable button
						$('#signUpButton').prop('disabled', true);
						$("#isAvailable").html("<div class='notAvail'><img src='images/not-availx24.png' height=16 width=16/>"
						+" Username is not available </div>");
						// store key and value to retrieve it
						// when an user tries to submit the form
						// with already reserved username
						$('#userName').data('available', false);
						//console.log($('#userName').data('available'));
					}
			   });//post
			});//keyup
			
			// validation
			$('#form_signUp').bootstrapValidator({
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
							},
							stringLength: {
								min: 4,
								max: 30,
								message: 'The username must be from 4 to 30 characters'
							},
							regexp: {
								regexp: /^[a-zA-Z0-9_\.#]+$/,
								message: 'The username can only consist of alphanumerical, dot, and underscore'
							}
						}
					},
					
					password: {
						validators: {
							notEmpty: {
								message: 'The password is required and can\'t be empty'
							},
							stringLength: {
								min: 8,
								max: 30,
								message: 'The username must be from 8 to 30 characters'
							},
						}
					},
					confirmPassword: {
						validators: {
							notEmpty: {
								message: 'The password confirmation is required'
							},
							identical: {
								field: 'password',
								message: 'The password and its confirm are not the same'
							}//identical
						}//validators
					}//confirmPassword
				},//fields
				
				submitHandler: function(validator, form, submitButton) {
						// check if name isn't avaialable
						if(!($('#userName').data('available'))) {
							// print error msg
							$("#error").html("<div class='alert'><img src='images/alert1.png' height=50 width=50/><br>The username isn't available. Choose available username and continue to sign up.</div>");
							
							return;
						} 
						var dataString = form.serialize();
						//console.log(dataString);
						
						$.ajax({
							type: "POST",
							url: "createUser.php",
							data: dataString,
							
							success: function(data){    
								//console.log(data);
								if(data==true){
									//$("#error").html("correct username or password!");
									// go to profile
									window.location="profile.php";
								}
								else{
									$("#error").html("<div class='alert'><img src='images/alert1.png' height=50 width=50/><br>Couldn't create account.<br>Unexpected error. Contact developer to resolve the issue.</div>");
									
									//reset form: http://stackoverflow.com/questions/680241
									function resetForm($form){
										$form.find('input:text, input:password').val('');
									}
									resetForm($('#form-signin'));
									
								}//else
							}, //success
							
							// error handling
							error: function(xhr, textStatus, error){
								console.log('error '+ error);
								//if (textStatus == "error") {
									$("#error").html("The requested page: " + this.url + "<br>The error number returned: " + xhr.status + "<br>The error message: " + xhr.statusText );
								//}//if
							},//error
							
							// while waiting for response
							beforeSend:function(){
								//$("#error").css('display', 'inline', 'important');
								$("#error").html("<div class='alert'><img src='images/loading3.gif' height=50 width=50/> Loading...</div>")
							}
							
						});// .ajax()
					}//submitHandler	
			});//bootsrapValidator*/
       
		});//document.ready


    </script>

  </head>
  
  <body>
  <video autoplay preload="auto" loop 
	poster="video/sf_snapshot.jpg" id="bgvid">
			<source src="video/sf_1280x720.webm" type="video/webm; codecs=&quot;vp8, vorbis&quot;">
  </video>
	 <div class="container" id="container">
		
			
			<form class="form_signUp" role="form" id="form_signUp">
			<h4 class="form-signUp-heading" id="signUp-heading">   Create Your Account</h4><br>
			<div id="isAvailable"></div>
				<div class="form-group">
					<input type="text" class="form-control" name="userName" id="userName" placeholder="Username" autofocus autocomplete="on"/>
				</div>

				<div class="form-group">
					<input type="password" class="form-control" name="password" id="password" placeholder="Password" />
				</div>

				<div class="form-group">
					<input type="password" class="form-control" name="confirmPassword" placeholder="Retype password" />
				</div>

				<div class="form-group">
					<button class="btn btn-lg btn-primary btn-block" type="submit" id="signUpButton">
						Sign Up
					</button>
				</div>
				<span>Already have an account? <a href="index.php">Sign in Here</a></span>
				<div class="error" id="error"></div>
			</form>
			 
    </div>
  </body>

</html>