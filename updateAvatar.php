<?php
//initialize database connection
global $dbConnection;
//server address 127.0.0.1
$host = "localhost";
//name of the database to use
$database = "accounts";
//sql username
$user = "root";
//sql password
$pass = "";
//connects to the database
$dbConnection  = mysqli_connect($host,$user,$pass,$database);

//start a session if a session has not been started
if(session_status() == PHP_SESSION_NONE) {
	session_start();
}

//updates the preferred avatar of the user
$sql = "UPDATE prefs SET avatar = '".$_POST["avatar"]."' WHERE uid = '".$_SESSION["id"]."'";
$result = mysqli_query($dbConnection, $sql);
if(!$result)
{
	echo "SQL error: ".mysqli_error ($dbConnection);
	return false;
} else {
	return true;
}	
?>
