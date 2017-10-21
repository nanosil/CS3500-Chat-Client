<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width,initial-scale=1.0">
	<title>Chat Client</title>
	<link rel="stylesheet" href="messagePageASyle.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i&amp;subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese" rel="stylesheet">
	<script src="http://twemoji.maxcdn.com/2/twemoji.min.js?2.3.0"></script>

</head>
<body>
	<?php
		include "MessagePageHeader.html";
		//start a session
		session_start();
	?>
	
	<div id="content">
		<div id="messagebox">
			<div id="" class="message">
				<img src="http://i0.kym-cdn.com/entries/icons/facebook/000/022/022/C2AAMCLVQAESU6Z.jpg" class="messageavatar" />
				<div id="" class="messagecontentcontainer">
					<div id="" class="username"><?php echo $_SESSION['name']?></div>
					<div id="" class="messagecontent">
					this is the first message<br>
					this is the second message<br>
					this is the third message<br>					
					</div>
					
				</div>
			</div>
			<div id="inputarea">
				<div id="textbox">
					<input type="text" name="textmessage" /><div id="emoticon">&#x1f914;</div>
				</div>
			</div>
		</div>
	</div>
	<!--Background-->
	<div id="blur">
	</div>	
	<script type="text/javascript">twemoji.parse(document.body)</script>
</body>
</html>
