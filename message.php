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
		
		<!-- Googles jQuery library -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script type="text/javascript">
			window.jQuery||
			document.write('<script src="/jQuery.min.js"><\/script>');
		</script>
		
		<!-- various functions Note this must be placed AFTER functions.php -->
		<script src="script.js"></script>
		
	</head>
	<body>
		<?php
			//starts a session if a session has not been started
			if(session_status()==PHP_SESSION_NONE)
				session_start();
			//establish database connection
			$dbConnection  = mysqli_connect($host,$user,$pass,$database);
			$error= mysqli_connect_error();
			if($error != null)
			{
				$output = "<p class='messagecontent'>Unable to connect to DB<p>".$error;
				echo $output;
				die();
			}
			//if user is not logged in, they should not have access to this page
			if(!isloggedin())
				header("location: login.html");
			
			//this will be the header----
			echo "<div id = 'uid' style = 'display: none'>".$_SESSION["id"]."</div";
		?>
		<div id= "message-container">
			<header>
				<p>Welcome, <?php $_SESSION["name"]?>!!</p>
				<div id="content" class="<?php getTheme();?>">
				</div>
				<form action = "index.php" method = "post">
					<input type = "submit" name = "logout" value = "logout">
				</form>
			</header>			
			<div id="messagebox">
				<!-- all messages will go here -->
				<?php getMessagesOnLoad();?>
			</div>
			<div id="settings" style="display: none; opacity: 0;">
				<?php 
					getColors();
					getAvatars();
					getBackgrounds();
				?>
				<div id="switch">
					<div id="themeToggle" style="background: #909090; position: relative; left: 0px;" onclick="toggleTheme()"></div>
					<script>getTheme();</script>
				</div>
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
		
		<!--Background-->
		<div id="blur">
		</div>
		
		<!-- We need this script on the HTML page -->
		<script>
			//converts all emoji on page into image form rather than text form
			twemoji.parse(document.body);
		</script>
		<?php
			mysqli_close($dbConnection);
		?>
	</body>
</html>					