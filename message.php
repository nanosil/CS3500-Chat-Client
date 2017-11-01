<?php
global $dbConnection;
$host = "localhost";
$database = "accounts";
$user = "root";
$pass = "";
require 'functions.php';
$dbConnection  = mysqli_connect($host,$user,$pass,$database);
$error= mysqli_connect_error();
if($error !=null)
{
	$output = "<p class = \"messagecontent\">Unable to connect to DB <p> ".$error;
	echo $output;
	die();
}
if(session_status()==PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset='utf=8'>
	<title>Chat Client</title>
	<link rel="stylesheet" href="style.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i&amp;subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese" rel="stylesheet">
	<script src="http://twemoji.maxcdn.com/2/twemoji.min.js?2.3.0"></script>
	<script type="text/javascript">
		function toggleSettings() {
			document.getElementById("settings").style.position = 'static';
			document.getElementById("settings").style.opacity = '1';
		}
	</script>
</head>
<?php
if(!isset($_SESSION['name'])) { 
	header("location: login.html");
}
if(!isset($_SESSION['id'])) {
	//getID($_SESSION['name'], $_SESSION['password']);
}
?>
<body>
	<div id="content">
		<div id="message-container">
			<div id="messagebox">
				<?php getMessages();
				getID($_SESSION['name'], $_SESSION['password']); ?>
			</div>
			<div id="inputarea">
				<div id="settings" style="position: absolute; opacity: 0;">
					text lol
				</div>
				<form method="post" action="functions.php" id="textbox">
					<div id="emoticon" onclick="toggleSettings()">&#x2699;</div>
					<div id="emoticon">&#x1f914;</div>
					<input type="text" name="textmessage" class="textbox" />
					<input type="submit" id="submit" value="Send" />
				</form>
			</div>
		</div>
	</div>
	<!--Background-->
	<div id="blur">
	</div>	
	<script type="text/javascript">twemoji.parse(document.body)
	var messageBody = document.querySelector('#messagebox');
	var inputBody = document.querySelector('#inputarea');
		messageBody.scrollTop = messageBody.scrollHeight;</script>
</body>
</html>
