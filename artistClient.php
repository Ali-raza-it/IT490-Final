#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$artist = $_POST['artist'];

$client = new rabbitMQClient("testRabbitMQ.ini","testServer");
if (isset($argv[1]))
{
  $msg = $argv[1];
}
else
{
  $msg = "test message";
}

$request = array();
$request['type'] = "artist";
$request['artist'] = $artist;
$request['message'] = $msg;
$response = $client->send_request($request);
//$response = $client->publish($request);

echo "client received response: ".PHP_EOL;
echo "\n\n";

if ($response!==0) 
{
	echo "<table style='width:100%'>";
	echo " <tr>";
	echo "  <th>Artist Name</th>";
	echo "  <th>Followers</th>";
	echo "  <th>Montly Listeners</th>";
	echo "  <th>World Rank</th>";
	echo " </tr>";
	echo " <tr>";
	echo "  <td>$response[0]</td>";
	echo "  <td>$response[1]</td>";
	echo "  <td>$response[2]</td>";
	echo "  <td>$response[3]</td>";
	echo " </tr>";
	echo "</table>";
}
echo $argv[0]." END".PHP_EOL;
