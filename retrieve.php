<?php
global $dbConnection;
$host = "localhost";
$database = "accounts";
$user = "root";
$pass = "";
$dbConnection  = mysqli_connect($host,$user,$pass,$database);
if(session_status()==PHP_SESSION_NONE) {
	session_start();
}

//gets content, time sent, username, profile picture, and username color of each message in the database
$sql = "SELECT content, time, name, avatar, color FROM messages, user, prefs WHERE user.id = messages.uid AND user.id = prefs.uid";

$result = mysqli_query($dbConnection, $sql);

if(!$result) {
	echo "SQL error: ".mysqli_error ($dbConnection);
	return false;
} else if(mysqli_num_rows($result) > 0) {
	//boolean that determines if the first message found in the query has been printed or not
	$first = true;
	//counter to determine amount of consecutive messages with the same username
	$c = 0;
	//name of the user from the previous row in sql result
	$prevUser = "";
	//loops for each row found from the sql query
	while ($row = $result -> fetch_assoc()) {
		//if the current message was from the same user as the previous message and this is not the sixth consecutive post from that user
		if($prevUser == $row['name'] && $c < 5) {
			//only echo the message content itself
			echo "<p>".$row['content']."</p>";
			//increment the counter
			$c++;
		//else if the current message is from a different user than the previous message and this is not the first message returned from the sql query
		} else if($prevUser != $row['name'] && !$first) {
			//close the three divs that were opened from the previous user
			//since we know this is not the first message returned from the sql query, we are guaranteed to close the three divs that were opened from the previous user's messages
			echo "</div>";
			echo "</div>";
			echo "</div>";
			
			//begin echoing data of the current sql query row
			echo "<div id='' class='message'>";
			//prints user's avatar
			echo "<img src='images/avatars/".$row['avatar'].".png' class='avatar' />";

			//begins the div that contains all the text data of the message i.e. not the avatar
			echo "<div id='' class='messagecontentcontainer'>";
			
			//prints user's name and gives it the appropriate color
			echo "<div id='' class='username' style='color: ".$row['color']."'>".$row['name']."</div>";
			
			//store the name of the user so we can compare it with the next row in the sql query
			$prevUser = $row['name'];
			//set counter to zero since we know this is this user's first nonconsecutive message
			$c = 0;
			
			//prints the timestamp of the message
			echo "<small>".$row['time']."</small>";
			
			//prints the content of the message itself
			echo "<div id='' class='messagecontent'>";
			echo "<p>".$row['content']."</p>";			
		//else if the current user is the same as the previous user but this is their sixth consecutive message
		} else if($prevUser == $row['name'] && $c >= 5) {
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
			echo "<p>".$row['content']."</p>";				
		//else if this is the first message in the database	
		} else if($first) {
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
			echo "<p>".$row['content']."</p>";
		}
	}
	//closes the tags that were opened in the very last message block returned from the whole sql query
	echo "</div>";
	echo "</div>";
	echo "</div>";
//if no messages exist in the db
} else {
	echo "No messages yet.";
}
