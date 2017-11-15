<?php
	global $dbConnection;
	$host = "localhost";
	$database = "accounts";
	$user = "root";
	$pass = "ag081493";
	//establish database connection
	$dbConnection  = mysqli_connect($host,$user,$pass,$database);
	$error= mysqli_connect_error();
	if($error != null)
	{
		$output = "<p class='messagecontent'>Unable to connect to DB<p>".$error;
		echo $output;
		die();
	}
	
	$host = 'localhost'; //host
	$port = '9000'; //port
	$null = NULL; //null var
	
	//Create TCP/IP sream socket
	$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	//reuseable port
	socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);
	
	//bind socket to specified host
	socket_bind($socket, 0, $port);
	
	//listen to port
	socket_listen($socket);
	
	//create & add listning socket to the list
	$clients = array($socket);
	
	//start endless loop, so that our script doesn't stop
	while (true)
	{
		//manage multipal connections
		$changed = $clients;
		//returns the socket resources in $changed array
		socket_select($changed, $null, $null, 0, 10);
		
		//check for new socket
		if (in_array($socket, $changed))
		{
			$socket_new = socket_accept($socket); //accpet new socket
			$clients[] = $socket_new; //add socket to client array
			
			$header = socket_read($socket_new, 1024); //read data sent by the socket
			perform_handshaking($header, $socket_new, $host, $port); //perform websocket handshake
			
			//broadcast ip address of this new client
			socket_getpeername($socket_new, $ip); //get ip address of connected socket
			$response = mask(json_encode(array('type'=>'system', 'message'=>$ip.' connected'))); //prepare json data
			send_message($response); //notify all users about new connection
			printf("New connection on ip address: %s", $ip);
			
			//make room for new socket
			$found_socket = array_search($socket, $changed);
			unset($changed[$found_socket]);
		}
		
		//loop through all connected sockets
		foreach ($changed as $changed_socket)
		{	
			//for any incomming data
			while(socket_recv($changed_socket, $buf, 1024, 0) >= 1)
			{
				$received_text = unmask($buf); 				//unmask data
				$tst_msg = json_decode($received_text); 	//json decode 
				$type = $tst_msg->type;
				$uid = $tst_msg->uid;

				if($type == "avatar")						//if updating the avatar
				{
					$error=updateAvatarDB($tst_msg->avatar,$uid);
					if($error)
						sendErr("Updating Avatar DB Error");
				}
				else if($type =="color")					//if updating the color
				{
					$error = updateColorDB($tst_msg->colorName,$uid);
					if($error)
						sendErr("Updating Color DB Error");
				}
				else if($type =="usermsg")									//if sending a message
				{
					$user_message = $tst_msg->message; 		//message text
					$time = date("Y-m-d H:i:s");			//server time
					
					//write to DB
					$error = writeToDB($uid, $user_message, $time);
					if($error)
						sendErr("Writting Message to DB Error");
					else 
						sendErr("succesfully wrote user mess to DB");
				
					//prepare data to be sent to client
					$response_text = mask(json_encode(array('type'=>'usermsg', 'uid'=>$uid, 'message'=>$user_message,'time'=>$time)));
					send_message($response_text); 				//send data
				}
				break 2; 									//exist this loop
			}
			
			$buf = @socket_read($changed_socket, 1024, PHP_NORMAL_READ);
			if ($buf === false)
			{ 	// check disconnected client
				// remove client for $clients array
				$found_socket = array_search($changed_socket, $clients);
				socket_getpeername($changed_socket, $ip);
				unset($clients[$found_socket]);
				
				//notify all users about disconnected connection
				$response = mask(json_encode(array('type'=>'system', 'message'=>$ip.' disconnected')));
				send_message($response);
			}
		}
	}
	// close the listening socket
	socket_close($socket);
	
	function sendErr($errMsg)
	{
		$response = mask(json_encode(array('type'=>'system', 'message'=>$errMsg))); //prepare json data
		send_message($response); 				//notify all users about error
	}
	
	function updateColorDB($color,$uid)
	{
		global $dbConnection;
		//updates the preferred color of the user's name
		$sql = "UPDATE prefs SET color = '".$color."' WHERE uid = '".$uid."'";
		$result = mysqli_query($dbConnection, $sql);
		if(!$result)
			return false;
		else 
			return true;
	}
	
	function updateAvatarDB($name,$uid)
	{
		global $dbConnection;
		//updates the preferred avatar of the user
		$sql = "UPDATE prefs SET avatar = '".$name."' WHERE uid = '".$uid."'";
		$result = mysqli_query($dbConnection, $sql);
		if(!$result)
			return false;
		else
			return true;
	}
	
	function send_message($msg)
	{
		global $clients;
		foreach($clients as $changed_socket)		//send message to all clients
			@socket_write($changed_socket,$msg,strlen($msg));
		return true;
	}
	//Unmask incoming framed message
	function unmask($text)
	{
		$length = ord($text[1]) & 127;
		if($length == 126)
		{
			$masks = substr($text, 4, 4);
			$data = substr($text, 8);
		}
		else if($length == 127)
		{
			$masks = substr($text, 10, 4);
			$data = substr($text, 14);
		}
		else
		{
			$masks = substr($text, 2, 4);
			$data = substr($text, 6);
		}
		$text = "";
		for ($i = 0; $i < strlen($data); ++$i)
			$text .= $data[$i] ^ $masks[$i%4];
		return $text;
	}
	
	//Encode message for transfer to client.
	function mask($text)
	{
		$b1 = 0x80 | (0x1 & 0x0f);
		$length = strlen($text);
	
		if($length <= 125)
			$header = pack('CC', $b1, $length);
		else if($length > 125 && $length < 65536)
			$header = pack('CCn', $b1, 126, $length);
		elseif($length >= 65536)
			$header = pack('CCNN', $b1, 127, $length);
		return $header.$text;
	}
	
	//handshake new client.
	function perform_handshaking($receved_header,$client_conn, $host, $port)
	{
		$headers = array();
		$lines = preg_split("/\r\n/", $receved_header);
		foreach($lines as $line)
		{
			$line = chop($line);
			if(preg_match('/\A(\S+): (.*)\z/', $line, $matches))
				$headers[$matches[1]] = $matches[2];
		}
	
		$secKey = $headers['Sec-WebSocket-Key'];
		$secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
		//hand shaking header
		$upgrade  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
		"Upgrade: websocket\r\n" .
		"Connection: Upgrade\r\n" .
		"WebSocket-Origin: $host\r\n" .
		"WebSocket-Location: ws://$host:$port/demo/shout.php\r\n".
		"Sec-WebSocket-Accept:$secAccept\r\n\r\n";
		socket_write($client_conn,$upgrade,strlen($upgrade));
	}

	function writeToDB($uid, $content, $time)
	{
		global $dbConnection;
		//make query
		$sql = "INSERT INTO messages (uid, content, time) VALUES ('{$uid}', '{$content}', '{$time}')";
		printf($sql."\n");
		$result = mysqli_query($dbConnection, $sql);
		$affRows =mysqli_affected_rows($dbConnection);
		printf("Affected rows is: ".$affRows);
		if($affRows>0)
		{
	
			return false;
		}
		else
		{
			return true;
		}
	}
?>
