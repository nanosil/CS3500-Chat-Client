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

//connect to db
global $dbConnection;
//this page was called with the textmessage post data set to the value as the innerhtml of the text input from message.php
$content = $_POST["textmessage"];

//gets current time and stores it as the datetime used by sql
$time = date("Y-m-d H:i:s");
//inserts the message into the database
$sql = "INSERT INTO messages (uid, content, time) VALUES ('".$_SESSION['id']."', '".$content."', '{$time}')";
$result = mysqli_query($dbConnection, $sql);
if(!$result)
{
	echo "SQL error: ".mysqli_error ($dbConnection);
	return false;
} else {
	
}
