//Toggles the settings box as visible or not
function toggleSettings() {
	//if the box is visible, then hide it
	if(document.getElementById("settings").style.display == 'block') {
		document.getElementById("settings").style.opacity = '0';
		document.getElementById("settings").style.display = 'none';
	//if the box is invisible, then show it
	} else {
		document.getElementById("settings").style.opacity = '1';
		document.getElementById("settings").style.display = 'block';
	}
}

//Sends a message into the database
function sendMessage() {
	var text = document.getElementById("textinput").value.trim();
	$.ajax ({
		type: "POST",
		url:"send.php",
	   	data: { "textmessage" : text.trim() }
	});
	return true;
}

//Changes the users avatar
function updateAvatar(name) {
	$.ajax({
		url: 'updateAvatar.php',
		type: 'POST',
		data: { 'avatar': name }
	})
}

//Changes the users name color
function updateColor(color) {
	$.ajax({
		url: 'updateColor.php',
		type: 'POST',
		data: { 'color': color }
	})
}

function getMessages() {
	//store buffer element into one variable
	var buffer = document.getElementById("buffer");
	
	//data echoed from retrieve.php is stored into the div with id of buffer
	$(buffer).load('retrieve.php');
	
	//converts any emoji text strings into emoji images
	twemoji.parse(buffer);
	
	//if the data in the buffer div is different than the data in the visible messagebox
	if(document.getElementById("messagebox").innerHTML != buffer.innerHTML) {
		//update the visible messagebox
		document.getElementById("messagebox").innerHTML = buffer.innerHTML;
	}
}

function getMessagesOnLoad() {
	//store buffer element into one variable
	var message = document.getElementById("messagebox");
	
	var buffer = document.getElementById("buffer");
	
	//data echoed from retrieve.php is stored into the div with id of buffer
	$(message).load('retrieve.php');
	buffer.innerHTML = message.innerHTML;
	
	//converts any emoji text strings into emoji images
	twemoji.parse(buffer);
}
