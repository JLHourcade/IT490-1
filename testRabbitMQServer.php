#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function doLogin($username,$password)
{
	$db = mysqli_connect("localhost","admin","password","test");
	mysqli_select_db($db, "test");
	$s = "SELECT * FROM students WHERE username = '$username' AND password = '$password'";
	$q = mysqli_query($db,$s);
	echo $s;
	$rowCount = mysqli_num_rows($q);
	
	if($rowCount == 1){
		return "Congratz";
	}
	else
	{
		echo "FAILURE";
		return "No no";
	}
	 
	// check password
	return "Congratz";
    	return true;
    	//return false if not valid
}

function requestProcessor($request)
{
  echo "received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
  {
    return "ERROR: unsupported message type";
  }
  switch ($request['type'])
  {
    case "login":
      return doLogin($request['username'],$request['password']);
    case "validate_session":
      return doValidate($request['sessionId']);
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

$server = new rabbitMQServer("testRabbitMQ.ini","testServer");

$server->process_requests('requestProcessor');
exit();
?>

