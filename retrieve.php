<?php
global $dbConnection;
$host = "localhost";
$database = "accounts";
$user = "root";
$pass = "";
$dbConnection  = mysqli_connect($host,$user,$pass,$database);
if(session_status()==PHP_SESSION_NONE) session_start();
		
		//make query
		$sql = "SELECT content, time, name, bg, color FROM messages, user, prefs WHERE user.id = messages.uid AND user.id = prefs.uid";
		$result = mysqli_query($dbConnection, $sql);
		if(!$result)
		{
			echo "SQL error: ".mysqli_error ($dbConnection);
			return false;
		} else {
			$first = true;
			$c = 0;
			$prevUser = "";
			while ($row = $result -> fetch_assoc()) {
				if($prevUser == $row['name'] && $c < 5) {
					$first = false;
					echo "<p>".$row['content']."</p>";
					$c++;
				} else if($prevUser != $row['name'] && !$first) {
					echo "</div>";
					echo "</div>";
					echo "</div>";
					echo "<div id='' class='message'>";
					echo "<img src='images/avatars/".$row['bg'].".png' class='avatar' />";
					echo "<div id='' class='messagecontentcontainer'>";
					echo "<div id='' class='username' style='color: ".$row['color']."'>".$row['name']."</div>";
					$prevUser = $row['name'];
					$c = 0;
					echo "<small>".$row['time']."</small>";
					echo "<div id='' class='messagecontent'>";
					echo "<p>".$row['content']."</p>";					
				} else if($prevUser == $row['name'] && $c >= 5) {
				$first = false;
					echo "</div>";
					echo "</div>";
					echo "</div>";
					echo "<div id='' class='message'>";
					echo "<img src='images/avatars/".$row['bg'].".png' class='avatar' />";
					echo "<div id='' class='messagecontentcontainer'>";
					echo "<div id='' class='username' style='color: ".$row['color']."'>".$row['name']."</div>";
					$prevUser = $row['name'];
					$c = 0;
					echo "<small>".$row['time']."</small>";
					echo "<div id='' class='messagecontent'>";
					echo "<p>".$row['content']."</p>";					
				} else if($first) {
					$first = false;
					echo "<div id='' class='message'>";
					echo "<img src='images/avatars/".$row['bg'].".png' class='avatar' />";
					echo "<div id='' class='messagecontentcontainer'>";
					echo "<div id='' class='username' style='color: ".$row['color']."'>".$row['name']."</div>";
					$prevUser = $row['name'];
					$c = 0;
					echo "<small>".$row['time']."</small>";
					echo "<div id='' class='messagecontent'>";
					echo "<p>".$row['content']."</p>";
				}
				/*
    			echo "<div id='' class='message'>";
				echo "<img src='images/avatars/".$row['bg'].".png' class='avatar' />";
					echo "<div id='' class='messagecontentcontainer'>";
					echo "<div id='' class='username' style='color: ".$row['color']."'>".$row['name']."</div>";
					$prevName = $row['name'];
					$c = 0;
					echo "<small>".$row['time']."</small>";
						echo "<div id='' class='messagecontent'>";
							echo "<p>".$row['content']."</p>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
				*/
			}
			echo "</div>";
			echo "</div>";
			echo "</div>";
		}
