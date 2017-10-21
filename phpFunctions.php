<?php
	function isloggedin()
	{
		return isset($_SESSION['name']);
	}
	
	function login($username, $password)
	{	
		global $dbConnection;
		//sanatize input
		$username = mysqli_real_escape_string($dbConnection,$username);
		$password = mysqli_real_escape_string($dbConnection,$password);
		
		//make query
		$sql = "SELECT * FROM users WHERE username = '".$username."' AND pass = '".md5($password)."'";
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
			$_SESSION['name']=$row["Name"];
			$_SESSION['ID']=$row["id"];
			$_SESSION['username']=$row["username"];
			return true;
		}
		else
		{
			return false;							//failed login
		}
	}
	
	function signup($name, $username,$password)
	{	
		global $dbConnection;
		//sanatize input
		$username = mysqli_real_escape_string($dbConnection,$username);
		$password = mysqli_real_escape_string($dbConnection,$password);
		$password = md5($password);				//hash password
		$name = mysqli_real_escape_string($dbConnection,$name);
		$id = rand();
		
		//insert into database
		$sql = "INSERT INTO users (Name, id, pass, username) VALUES ('".$name."', '".$id."', '".$password."', '".$username."')";
		$result = mysqli_query($dbConnection, $sql);
		if(mysqli_affected_rows($dbConnection)>0)
		{
			echo "Here2<br>";
			$_SESSION['name']=$name;
			$_SESSION['ID']=$id;
			$_SESSION['username']=$username;
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
			unset($_SESSION['ID']);
			unset($_SESSION['username']);
			return true;
		}
		else
			return false;
	}
?>