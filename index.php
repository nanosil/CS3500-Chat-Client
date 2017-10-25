<?php
	//define varables
	define('MESSAGING_PAGE', 'message.php');	
	define('LOGIN_HTML', 'login.html');
	global $dbConnection;
	$host = "localhost";
	$database = "accounts";
	$user = "root";
	$pass = "";
	require 'functions.php';
	
	//establish database connection
	$dbConnection  = mysqli_connect($host,$user,$pass,$database);
	$error= mysqli_connect_error();
	if($error !=null)
	{
		$output = "<p class = \"messagecontent\">Unable to connect to DB <p> ".$error;
		echo $output;
		die();
	}
	
	//start a session
	if(session_status()==PHP_SESSION_NONE) session_start();
	
	//if logged in
	if(isloggedin()and !isset($_POST['logout']))	//check if already logged in and not loggin out
	{
		echo "Welcome ".$_SESSION['name']."<br/> Redirecting...<br/>";
		sleep (2);
		$_POST = array();							//clear any post input
		header("location: ".MESSAGING_PAGE);		//redirect to messagingPage
	}
	//if logging in
	 else if(isset($_POST['name']) and isset($_POST['password']))
	{	
		//login
		if (login($_POST['name'],$_POST['password']))
		{
			$_POST = array();
			echo "Welcome ".$_SESSION['name']."<br/> Redirecting...<br/>";
			sleep (2);
			header("location: ".MESSAGING_PAGE);	//redirect to messagingPage
		}
		else
		{
			$_POST = array();						//clear any post input
			echo "Login failed. ";
			include LOGIN_HTML;
		}
	}
	//if signing up
	 else if(isset($_POST['newName']) and isset($_POST['newPass']))
	{	
		//signup
		if (signup($_POST['newName'], $_POST['newPass']))
		{
			$_POST = array();
			echo "Welcome ".$_SESSION['name']."<br/> Redirecting...<br/>";
			header("location: ".MESSAGING_PAGE);	//redirect to messagingPage
		}
		else
		{
			$_POST = array();						//clear any post input
			echo "Signup failed. ";
		}
	}
	//if logging out
	else if(isset($_POST['logout']))
	{
		$_POST = array();							//clear any post input
		if(logout())
			echo "Logout Succesful<br>";
		else
			echo "Logout Unsussefull<br>";
		include LOGIN_HTML;
	}
	else
	{
		include LOGIN_HTML;
	}	
?>

