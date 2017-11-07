<?php
global $dbConnection;
$host = "localhost";
$database = "accounts";
$user = "root";
$pass = "";

require 'functions.php';

$dbConnection  = mysqli_connect($host,$user,$pass,$database);
$error= mysqli_connect_error();

//quits the connection if it cant connect
if($error != null)
{
	$output = "<p class='messagecontent'>Unable to connect to DB<p>".$error;
	echo $output;
	die();
}

//starts a session if a session has not been started
if(session_status()==PHP_SESSION_NONE) {
	session_start();
}
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
	
	<!-- various functions -->
	<script src="script.js"></script>
</head>
<?php
//if user is not logged in, they should not have access to this page
if(!isset($_SESSION['name'])) { 
	header("location: login.html");
}
//get the user id if they are logged in
if(!isset($_SESSION['id'])) {
	getID($_SESSION['name'], $_SESSION['password']);
}
?>
<body>
	<!-- hidden div that contains the message data from the database -->
	<!-- used so that we can seamlessly copy it into the visible messagebox div when the database changes -->
	<div id="buffer" style="display: none;"></div>
	<div id="content">
		<div id="message-container">
			<div id="messagebox">
				<!-- We need this script in the HTML file so we can use the set interval function -->
				<!-- If we get the socket to work with the project then we can get rid of this -->
				<script>
					//gets the messages once upon page load
					getMessagesOnLoad();
					//checks for new messages every 1 second
					window.setInterval(function(){
						getMessages();
					}, 1000); //1000 milliseconds = 1 second
	 		</script>
			</div>
			<div id="settings" style="display: none; opacity: 0;">
				<?php getColors(); ?>
				<?php getAvatars(); ?>
				<!-- getBackgrounds(); goes here -->
			</div>
			<div id="inputarea">
				<!-- this form calls the sendMessage function  -->
				<form action="" method="post" id="textbox" onsubmit="return sendMessage()" >
					<div id="emoticon" onclick="toggleSettings()">&#x2699;</div>
					<div id="emoticon">&#x1f914;</div>
					<input type="text" name="textmessage" class="textbox" id="textinput" />
					<input type="submit" id="submit" value="Send" />
				</form>
			</div>
		</div>
	</div>
	<!--Background-->
	<div id="blur">
	</div>	
	
	<!-- we also need this script on the HTML page -->
	<script type="text/javascript">
		//converts all emoji on page into image form rather than text form
		twemoji.parse(document.body);
		
		//stores the messagebox div into a variable
		var messageBody = document.querySelector('#messagebox');
		
		//supposed to make the scrollbar be at the bottom of the div by default.
		//this emphasizes the newer messages over the older messages.
		//this is not working perfectly at the moment.
		messageBody.scrollTop = messageBody.scrollHeight;
	</script>
</body>
</html>
