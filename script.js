$(document).ready(function()
{
	//sets sendMessageForm onsubmit listener
	$("#sendMessageForm").on("onsubmit",sendMessage);
	
	//sets emoticon click toggleSettings
	$("#settingicon").on("onclick",toggleSettings);
	
	//user clicks message send button	
	$('#send-btn').click(sendMessage);
	
	
	//from websockets example------------
	//create a new WebSocket object.
	var wsUri = "ws://localhost:9000/CS3500/CS3500-Chate-Client/server.php"; 	
	websocket = new WebSocket(wsUri);
	websocket.onopen = function(ev){$('#messagebox').append("<div class=\"system_msg\">Connected!</div>");};
	websocket.onmessage = messagedRecieved;
	websocket.onerror	= function(ev){$('#messagebox').append("<div class=\"system_error\">Server Connection error Occurred - "+ev.data+"</div>");}; 
	websocket.onclose 	= function(ev){$('#messagebox').append("<div class=\"system_msg\">Server Connection Closed</div>");}; 
	//end of websocket example-------------
});

//Toggles the settings box as visible or not
function toggleSettings()
{
	//if the box is visible, then hide it
	if(document.getElementById("settings").style.display == 'block')
	{
		document.getElementById("settings").style.opacity = '0';
		document.getElementById("settings").style.display = 'none';
	}
	else //if the box is invisible, then show it
	{
		document.getElementById("settings").style.opacity = '1';
		document.getElementById("settings").style.display = 'block';
	}
}

//Sends a message into the database
function sendMessage()
{
	var mymessage = $('#textmessage').val(); //get message text
	if(mymessage == "")//emtpy message?
	{
		alert("Enter Some message Please!");
		return;
	}
	
	var objDiv = document.getElementById("messagebox");
	objDiv.scrollTop = objDiv.scrollHeight;
	
	//prepare json data
	var msg =
	{
		type: "usermsg",
		message: mymessage,
		uid: "<?php echo $_SESSION['id']; ?>"
	};
	//convert and send data to server
	websocket.send(JSON.stringify(msg));
}

//Message received from server
function messagedRecieved(ev)
{
	var msg = JSON.parse(ev.data); 	//PHP sends Json data
	var type = msg.type; 			//message type
	
	if(type == 'usermsg') 			//message received
	{
		var umsg = msg.message; 	//message text
		var uid = msg.uid;			//user id
		var time = msg.time;		//message time stamp
		$('#messagebox').append("<div>User id is"+uid+"<br>user message is "+umsg+"</div>");
	}
	if(type == 'system')			//system level messages received
		$('#messagebox').append("<div class=\"system_msg\">"+umsg+"</div>");
	
	$('#textmessage').val(''); //reset textinput 
	
	//to scroll to the bottom
	var objDiv = document.getElementById("messagebox");
	objDiv.scrollTop = objDiv.scrollHeight;
};

//Changes the users avatar
function updateAvatar(name)
{
	//prepare json data
	var msg =
	{
		type: "avatar",
		avatar: name,
		uid: "<?php echo $_SESSION['id']; ?>"
	};
	//convert and send data to server
	websocket.send(JSON.stringify(msg));
}

//Changes the users name color
function updateColor(color)
{
	//prepare json data
	var msg =
	{
		type: "color",
		colorName: color,
		uid: "<?php echo $_SESSION['id']; ?>"
	};
	//convert and send data to server
	websocket.send(JSON.stringify(msg));
}

