
var thisUid = 0;
var bgID =0;
var bgName=0;

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
	var wsUri = "ws://"+location.host+":9000/CS3500/CS3500-Chate-Client/server.php"; 	
	websocket = new WebSocket(wsUri);
	websocket.onopen = function(ev){$('#leftRail').append("<div class=\"system_msg\">Connected to server</div>");};
	websocket.onmessage = messagedRecieved;
	websocket.onerror	= function(ev){$('#leftRail').append("<div class=\"system_error\">Server Connection error Occurred - "+ev.data+"</div>");}; 
	websocket.onclose 	= function(ev){$('#leftRail').append("<div class=\"system_msg\">Server Connection Closed</div>");}; 
	//end of websocket example-------------
	
	//get this uid from html
	thisUid = $("#uid").html();
	
	//get this background name from html
	bgID = $("#bgID").html();
	bgName = $("#bgName").html();
	
	bg = document.getElementById("blur");
	var path = "images/backgrounds/"+bgName;
	bg.style.background = "url("+path+")";
	
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
		uid: thisUid
	};
	//convert and send data to server
	websocket.send(JSON.stringify(msg));
}

//Message received from server
function messagedRecieved(ev)
{
	var msg = JSON.parse(ev.data); 	//PHP sends Json data
	var type = msg.type; 			//message type
	
	if(type =="uid")				//occures when first connected
	{
		thisUid= msg.message;
	}
	
	if(type == 'usermsg') 			//message received
	{
		var uid = msg.uid;			//user id
		var time = msg.time;		//message time stamp
		var umsg = msg.message; 	//message text
		$('#messagebox').append("<div>User id is"+uid+"<br>user message is "+umsg+"</div>");
	}
	if(type == 'system')			//system level messages received
	{
		var umsg = msg.message; 	//message text
		$('#leftRail').append("<div class=\"system_msg\">"+umsg+"</div>");
	}
	
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
		uid: thisUid
	};
	//convert and send data to server
	websocket.send(JSON.stringify(msg));
	var clasname = "avatar "+thisUid;
	var names = document.getElementsByClassName(clasname);
	for (var i = 0; i < names.length; i++)
		names[i].setAttribute("src", "images/avatars/"+name+".png");
}
function changeBG(name,id)
{
	//prepare json data
	var msg =
	{
		type: "bg",
		bgID: id,
		uid: thisUid
	};
	//convert and send data to server
	websocket.send(JSON.stringify(msg));
	
	bg = document.getElementById("blur");
	var path = "images/backgrounds/"+name;
	bg.style.background = "url("+path+")";
}

//Changes the users name color
function updateColor(color)
{
	//prepare json data
	var msg =
	{
		type: "color",
		colorName: color,
		uid: thisUid
	};
	//convert and send data to server
	websocket.send(JSON.stringify(msg));
	
	//change on page
	var clasname = "username "+thisUid;
	var names = document.getElementsByClassName(clasname);
	for (var i = 0; i < names.length; i++)
		names[i].style.color = color;
}
function toggleTheme() 
{
	if(document.getElementById("themeToggle").style.left == '0px') {
		document.getElementById("themeToggle").style.left = '50px';
		document.getElementById("themeToggle").style.background = '#2be25f';
		document.getElementById("content").classList.add('dark');
		document.getElementById("content").classList.remove('light');

		$.ajax ({
			type: "POST",
			url:"theme.php",
		   	data: { "theme" : "dark" }
		});
	
	}
	else 
	{
		document.getElementById("themeToggle").style.left = '0px';
		document.getElementById("themeToggle").style.background = '#909090';
		document.getElementById("content").classList.add('light');
		document.getElementById("content").classList.remove('dark');
		$.ajax ({
			type: "POST",
			url:"theme.php",
		   	data: { "theme" : "light" }
		});
	}
}

function getTheme()
{
	if(document.getElementById("content").classList.contains('dark'))
	{
		document.getElementById("themeToggle").style.left = '50px';
		document.getElementById("themeToggle").style.background = '#2be25f';
	}
}


