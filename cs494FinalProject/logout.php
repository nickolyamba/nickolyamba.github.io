<?php
	//start session
	session_start();
	
	if(isset($_SESSION["userName"]) || isset($_SESSION["userID"])){
		
		// destroy current session
		$_SESSION = array();
		session_destroy();
		//start new session
	}	
	header('Location: index.php');
?>	