<?php
//initialize database connection
global $dbConnection;

//returns name of the user
function isloggedin()
{
	
	return isset($_SESSION['name'])&&isset($_SESSION['id']);
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
	if(!$result)
	{
		//return error message to user
		echo "SQL error: ".mysqli_error ($dbConnection);
		return false;
	
	} //else if any data was returned from successful query
	else if(mysqli_num_rows($result) > 0)
	{
		//store sql data into php object
		$row = mysqli_fetch_assoc($result);
		//store queried data into sessions	
		$_SESSION['name'] = $row["name"];
		$_SESSION['id'] = $row["id"];
		$_SESSION['password'] = $row["password"];
		return true;
	}
	else//if query was successful but no data was returned, nothing happens
		return false;
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

//removes session variables when user logs out
function logout()
{
	if(isloggedin())
	{
		unset($_SESSION['name']);
		unset($_SESSION['password']);
		unset($_SESSION['id']);
		return true;
	}
	else
		return false;
}

function getColors()
{
	$colors = ["navy","blue","green","teal","deepskyblue", "dodgerblue","seagreen","darkslategray"];
	$temp =["royalblue","indigo","purple","slateblue","chartreuse","maroon","red","brown","sienna"];
	$colors = array_merge($colors,$temp);
	$temp=["darkgoldenrod","chocolate","goldenrod","yellow","orange","gold","coral","hotpink"];
	$colors = array_merge($colors,$temp);
	foreach($colors as $value)
		echo "<div class='color' style='background-color: ".$value."' onclick='return updateColor(\"".$value."\")' ></div>\n";
}

function getAvatars()
{
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
		while ($row = $result -> fetch_assoc())
		{
			$name = $row["name"];
			echo "<img src='images/avatars/".$name.".png' class='thumbnail' id='".$name."' onclick='return updateAvatar(\"".$name."\")'/>\n";
		}
		return true;
	}
}

function getMessagesOnLoad()
{
	global $dbConnection;
	//gets content, time sent, username, profile picture, and username color of each message in the database
	$sql = "SELECT * FROM messages, user, prefs WHERE user.id = messages.uid AND user.id = prefs.uid";
	
	$result = mysqli_query($dbConnection, $sql);
	
	if(!$result)
	{
		echo "SQL error: ".mysqli_error ($dbConnection);
		return false;
	} 
	if(mysqli_num_rows($result) > 0)
	{
		//boolean that determines if the first message found in the query has been printed or not
		$first = true;
		//counter to determine amount of consecutive messages with the same username
		$c = 0;
		//name of the user from the previous row in sql result
		$prevUser = "";
		//loops for each row found from the sql query
		while ($row = $result -> fetch_assoc())
		{
			//if the current message was from the same user as the previous message and this is not the sixth consecutive post from that user
			if($prevUser == $row['name'] && $c < 5)
			{
				//only echo the message content itself
				echo "<p>".$row['content']."</p>\n";
				//increment the counter
				$c++;
				//else if the current message is from a different user than the previous message and this is not the first message returned from the sql query
			}
			else if($prevUser != $row['name'] && !$first)
			{
				//close the three divs that were opened from the previous user
				//since we know this is not the first message returned from the sql query, we are guaranteed to close the three divs that were opened from the previous user's messages
				echo "</div>";
				echo "</div>";
				echo "</div>";
				
				//begin echoing data of the current sql query row
				echo "<div id='' class='message'>";
				//prints user's avatar
				echo "<div class = avatar>";
				echo "<img src='images/avatars/".$row['avatar'].".png' class='avatar  ".$row['uid']."' />";
				echo "</div>";
				
				//begins the div that contains all the text data of the message i.e. not the avatar
				echo "<div id='' class='messagecontentcontainer'>";
				
				//prints user's name and gives it the appropriate color
				echo "<div id='' class='username ".$row['uid']."' style='color: ".$row['color']."'>".$row['name']."</div>";
				
				//store the name of the user so we can compare it with the next row in the sql query
				$prevUser = $row['name'];
				//set counter to zero since we know this is this user's first nonconsecutive message
				$c = 0;
				
				//prints the timestamp of the message
				echo "<small>".$row['time']."</small>";
				
				//prints the content of the message itself
				echo "<div id='' class='messagecontent'>";
				echo "<p>".$row['content']."</p>\n";			
				//else if the current user is the same as the previous user but this is their sixth consecutive message
			} 
			else if($prevUser == $row['name'] && $c >= 5)
			{
				//previous if block explains all the code below
				echo "</div>";
				echo "</div>";
				echo "</div>";
				echo "<div id='' class='message'>";
				echo "<img src='images/avatars/".$row['avatar'].".png' class='avatar' />";
				echo "<div id='' class='messagecontentcontainer'>";
				echo "<div id='' class='username' style='color: ".$row['color']."'>".$row['name']."</div>";
				$prevUser = $row['name'];
				$c = 0;
				echo "<small>".$row['time']."</small>";
				echo "<div id='' class='messagecontent'>";
				echo "<p>".$row['content']."</p>\n";				
				//else if this is the first message in the database	
			} 
			else if($first)
			{
				//there are no divs to close since this is the first message that is being processed and no divs have been opened yet
				//since we are processing the first message in the database, all of the following messages will not be the first
				$first = false;
				
				//all code below is explained in prior if block
				echo "<div id='' class='message'>";
				echo "<img src='images/avatars/".$row['avatar'].".png' class='avatar' />";
				echo "<div id='' class='messagecontentcontainer'>";
				echo "<div id='' class='username' style='color: ".$row['color']."'>".$row['name']."</div>";
				$prevUser = $row['name'];
				$c = 0;
				echo "<small>".$row['time']."</small>";
				echo "<div id='' class='messagecontent'>";
				echo "<p>".$row['content']."</p>\n";
			}
		}
		//closes the tags that were opened in the very last message block returned from the whole sql query
		echo "</div>";
		echo "</div>";
		echo "</div>";
		//if no messages exist in the db
	} 
	else 
		echo "No messages yet.";
}

function getBgID()
{
	global $dbConnection;
	$uid = $_SESSION['id'];
	$sql = "SELECT * FROM backgrounds JOIN prefs on prefs.uid = $uid AND prefs.bg = backgrounds.bgID";
	$result = mysqli_query($dbConnection, $sql);
	if($error = mysqli_error($dbConnection)=="")
	{
		$row = $result -> fetch_assoc();
		echo $row["bgID"];
		return true;
	}
	else
	{
		echo "SQL error: ".mysqli_error ($dbConnection);
		return false;
	}
}
function getBgName()
{
	global $dbConnection;
	$uid = $_SESSION['id'];
	$sql = "SELECT * FROM backgrounds JOIN prefs on prefs.uid = $uid AND prefs.bg = backgrounds.bgID";
	$result = mysqli_query($dbConnection, $sql);
	if($error = mysqli_error($dbConnection)=="")
	{
		$row = $result -> fetch_assoc();
		echo $row["filename"];
		return true;
	}
	else
	{
		echo "SQL error: ".mysqli_error ($dbConnection);
		return false;
	}
}

function getBackgrounds()
{
	global $dbConnection;
	$sql = "SELECT * FROM backgrounds";
	$result = mysqli_query($dbConnection, $sql);
	if(!$result)
	{
		echo "SQL error: ".mysqli_error ($dbConnection);
		return false;
	}
	else
	{
		while ($row = $result -> fetch_assoc())
		{
			echo "<img class = 'backgrounds' src = 'images/backgrounds/".$row["filename"]." '";
			echo " onclick = changeBG(\"".$row["filename"]."\",".$row["bgID"].")>\n";
		}
		return true;
	}
}
function getTheme()
{
	global $dbConnection;
	$sql = "SELECT theme FROM prefs WHERE uid = '".$_SESSION["id"]."'";
	$result = mysqli_query($dbConnection, $sql);
	if(!$result)
	{
		echo "light";
		return false;
	}
	else
	{
		$row = mysqli_fetch_assoc($result);
		echo $row["theme"];
		return true;
	}
}

?>
