<?php
global $dbConnection;
$host = "localhost";
$database = "accounts";
$user = "root";
$pass = "";
$dbConnection  = mysqli_connect($host,$user,$pass,$database);
	if(session_status()==PHP_SESSION_NONE) session_start();
	if(isset($_POST["textmessage"])) {
		sendMessage();
	}

	function isloggedin()
	{
		return isset($_SESSION['name']);
	}
	
	function login($name, $password)
	{	
		global $dbConnection;
		//sanatize input
		$username = mysqli_real_escape_string($dbConnection,$username);
		$password = mysqli_real_escape_string($dbConnection,$password);
		
		//make query
		$sql = "SELECT * FROM user WHERE name = '".$name."' AND password = '".md5($password)."'";
		$result = mysqli_query($dbConnection, $sql);
		if(!$result)
		{
			echo "SQL error: ".mysqli_error ($dbConnection);
			return false;
		}
		//check if returned empty
		if(mysqli_num_rows($result)>0)
		{
			$row = mysqli_fetch_assoc($result);		//successfull login
			$_SESSION['name'] = $row["name"];
			$_SESSION['id'] = $row["id"];
			$_SESSION['password'] = $row["password"];
			return true;
		}
		else
		{
			return false;							//failed login
		}
	}
	
	function signup($name, $password)
	{	
		global $dbConnection;
		//sanatize input
		//$name = mysqli_real_escape_string($dbConnection,$name);
		$password = mysqli_real_escape_string($dbConnection,$password);
		$password = md5($password);	//hash password
		$name = mysqli_real_escape_string($dbConnection,$name);
		
		//insert into database
		$sql = "INSERT INTO user (name, password) VALUES ('".$name."', '".$password."')";
		$result = mysqli_query($dbConnection, $sql);
		if(mysqli_affected_rows($dbConnection)>0)
		{
			$_SESSION['name'] = $name;
			$_SESSION['password'] = $password;
			return true;
		}
		else 
		{
			echo "SQL error: ".mysqli_error ($dbConnection);
			return false;
		}
	}

	function logout()
	{
		if(isloggedin())
		{
			unset($_SESSION['name']);
			unset($_SESSION['id']);
			return true;
		}
		else
			return false;
	}
	
	function getMessages() {
		global $dbConnection;
		
		//make query
		$sql = "SELECT content, time, name FROM messages, user WHERE user.id = messages.uid";
		$result = mysqli_query($dbConnection, $sql);
		if(!$result)
		{
			echo "SQL error: ".mysqli_error ($dbConnection);
			return false;
		} else {
			while ($row = $result -> fetch_assoc()) {
    			echo "<div id='' class='message'>";
					echo "<img src='http://i0.kym-cdn.com/entries/icons/facebook/000/022/022/C2AAMCLVQAESU6Z.jpg' class='messageavatar' />";
					echo "<div id='' class='messagecontentcontainer'>";
						echo "<div id='' class='username'>".$row['name']."</div>";
						echo "<small>".$row['time']."</small>";
							echo "<div id='' class='messagecontent'>";
						echo "<p>".$row['content']."</p>";			
						echo "</div>";
					echo "</div>";
				echo "</div>";
			}
		}
	}
	
	function sendMessage() {
		global $dbConnection;
		$content = $_POST["textmessage"];
		$time = date("Y-m-d H:i:s");
		//make query
		$sql = "INSERT INTO messages (uid, content, time) VALUES ('".$_SESSION['id']."', '".$content."', '{$time}')";
		$result = mysqli_query($dbConnection, $sql);
		if(!$result)
		{
			echo "SQL error: ".mysqli_error ($dbConnection);
			return false;
		} else {
			header("location: message.php");
		}
	}
	
	//to do
	function getID() {
		
	}
?>
