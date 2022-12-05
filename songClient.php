#!/usr/bin/php
<?php
@ob_end_clean();
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$song = $_POST['song'];

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
$request['type'] = "song";
$request['title'] = $song;
$request['message'] = $msg;
$response = $client->send_request($request);
//$response = $client->publish($request);


if ($response!==0) 
{
	echo "<table style='width:100%'>";
        echo " <tr>";
        echo "  <th>Song Name</th>";
        echo "  <th>Artist Name</th>";
	echo "  <th>Album Name</th>";
	echo "  <th>Like Song</th>";
	echo "  <th>Dislike Song</th>";
        echo " </tr>";
        echo " <tr>";
        echo "  <td>".$response[0]."</td>";
        echo "  <td>".$response[1]."</td>";
	echo "  <td>".$response[2]."</td>";
	echo "  <td> </td>";
	echo "  <td> </td>";
        echo " </tr>";
        echo "</table>";

}
