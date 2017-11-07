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

//returns name of the user
function isloggedin()
{
	return isset($_SESSION['name']);
}

//checks if the name and password entered by the user are in the database
function login($name, $password)
{	
	//connect to database
	global $dbConnection;
	
	//stores username and password into the proper charset used by the database
	//essentially does nothing but ensures the strings are in the proper format
	$username = mysqli_real_escape_string($dbConnection,$name);
	$password = mysqli_real_escape_string($dbConnection,$password);
	
	//gets all users that have the name and password as entered by the user
	//all passwords in the database are hashed with md5 algorithm
	$sql = "SELECT * FROM user WHERE name = '".$name."' AND password = '".md5($password)."'";
	//returns sql data
	$result = mysqli_query($dbConnection, $sql);
	//if query returned an error
	if(!$result) {
		//return error message to user
		echo "SQL error: ".mysqli_error ($dbConnection);
		return false;
	//else if any data was returned from successful query
	} else if(mysqli_num_rows($result) > 0) {
		//store sql data into php object
		$row = mysqli_fetch_assoc($result);
		//store queried data into sessions	
		$_SESSION['name'] = $row["name"];
		$_SESSION['id'] = $row["id"];
		$_SESSION['password'] = $row["password"];
		return true;
	//if query was successful but no data was returned
	} else {
		//nothing happens
		return false;
	}
}

//stores user input data into the database
function signup($name, $password)
{	
	//connect to db
	global $dbConnection;
	//convert charsets
	$password = mysqli_real_escape_string($dbConnection,$password);
	$password = md5($password);
	$name = mysqli_real_escape_string($dbConnection,$name);
	//no id has been made for the user yet, so initialize as empty
	$id = "";
	
	//insert new user into database with the input credentials
	$sql = "INSERT INTO user (name, password) VALUES ('".$name."', '".$password."')";
	$result = mysqli_query($dbConnection, $sql);
	
	//gets the id of the new user and stores it into the session
	$sql = "SELECT id FROM user WHERE name = '".$name."' AND password = '".$password."'";
	$result = mysqli_query($dbConnection, $sql);
	$row = mysqli_fetch_assoc($result);
	$_SESSION['id'] = $row["id"];
	
	//stores the new user into the prefs table too
	$sql = "INSERT INTO prefs (uid) VALUES (".$_SESSION['id'].")";
	$result = mysqli_query($dbConnection, $sql);
	
	//store the input into a session
	if(mysqli_affected_rows($dbConnection)>0) {
		$_SESSION['name'] = $name;
		$_SESSION['password'] = $password;
		return true;
	//else return error
	} else {
		echo "SQL error: ".mysqli_error ($dbConnection);
		return false;
	}
}

//removes session variables when user logs out
function logout()
{
	if(isloggedin()) {
		unset($_SESSION['name']);
		unset($_SESSION['password']);
		unset($_SESSION['id']);
		return true;
	} else {
		return false;
	}
}

function getID($name, $password) {
	global $dbConnection;
	$sql = "SELECT id FROM user WHERE name = '".$name."' AND password = '".$password."'";
	$result = mysqli_query($dbConnection, $sql);
	if(!$result)
	{
		echo "SQL error: ".mysqli_error ($dbConnection);
		return false;
	}
	else
	{
		$row = mysqli_fetch_assoc($result);
		$_SESSION['id'] = $row['id'];
		return true;
	}
}

function getColors() {
	global $dbConnection;
	$sql = "SELECT * FROM colors";
	$result = mysqli_query($dbConnection, $sql);
	if(!$result)
	{
		echo "SQL error: ".mysqli_error ($dbConnection);
		return false;
	}
	else
	{
		while ($row = $result -> fetch_assoc()) {
			echo "<div class='color' style='background-color: ".$row['color']."' onclick='return updateColor(\"".$row['color']."\")' ></div>";
		}
		return true;
	}
}

function getAvatars() {
	global $dbConnection;
	$sql = "SELECT * FROM avatars";
	$result = mysqli_query($dbConnection, $sql);
	if(!$result)
	{
		echo "SQL error: ".mysqli_error ($dbConnection);
		return false;
	}
	else
	{
		while ($row = $result -> fetch_assoc()) {
			$name = $row["name"];
			echo "<img src='images/avatars/".$name.".png' class='thumbnail' id='".$name."'  onclick='return updateAvatar(\"".$name."\")'/>";
		}
		return true;
	}
}

//put getBackgrounds() here
?>
