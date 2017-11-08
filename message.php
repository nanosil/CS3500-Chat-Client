<?php
	global $dbConnection;
	$host = "localhost";
	$database = "accounts";
	$user = "root";
	$pass = "ag081493";
	require 'functions.php';
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset='utf=8'>
		<title>Chat Client</title>
		<link rel="stylesheet" href="style.css">
		
		<!-- gets the Open Sans font -->
		<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i&amp;subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese" rel="stylesheet">
		
		<!-- Gets the twemoji library. Allows us to use emojis in the webpage. -->
		<script src="http://twemoji.maxcdn.com/2/twemoji.min.js?2.3.0"></script>
		
		<!-- Google's jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script type="text/javascript">
			window.jQuery||
			document.write('script src="/jQuery.min.js"><\/script>');
		</script>
			
		<!-- various functions Note this must be placed AFTER functions.php -->
		<script src="script.js"></script>
		
	</head>
	<body>
		<?php
			//establish database connection
			$dbConnection  = mysqli_connect($host,$user,$pass,$database);
			$error= mysqli_connect_error();
			if($error != null)
			{
				$output = "<p class='messagecontent'>Unable to connect to DB<p>".$error;
				echo $output;
				die();
			}
			//starts a session if a session has not been started
			if(session_status()==PHP_SESSION_NONE)
				session_start();
			//if user is not logged in, they should not have access to this page
			if(!isset($_SESSION['name']))
				header("location: login.html");
			//get the user id if they are logged in
			if(!isset($_SESSION['id']))
				getID($_SESSION['name'], $_SESSION['password']);
		?>
		
		<!-- hidden div that contains the message data from the database -->
		<!-- used so that we can seamlessly copy it into the visible messagebox div when the database changes -->
		<div id="buffer" style="display: none;">
		</div>
		<div id="content">
			<div id="message-container">
				<div id="messagebox">
					<!-- all messages will go here -->
					<?php
						getMessagesOnLoad();
					?>
				</div>
				<div id="settings" style="display: none; opacity: 0;">
					<?php 
						getColors();
						getAvatars();
						getBackgrounds();
					?>
					<!-- getBackgrounds(); goes here -->
				</div>
				<div id="inputarea">
					<!-- this form calls the sendMessage function  -->
					<div id="settingicon" onclick = "toggleSettings()">&#x2699;</div>
					<div id="emoticon">&#x1f914;</div>
					<input type="text" name="textmessage" class="textbox" id="textmessage" />
					<button id="send-btn" class=button>Send</button>
				</div>
			</div>
		</div>
		<!--Background-->
		<div id="blur">
		</div>	
		
		<!-- We need this script on the HTML page -->
		<script>
			//converts all emoji on page into image form rather than text form
			twemoji.parse(document.body);
		</script>
	</body>
</html>