#!/usr/bin/php
<?php
@ob_end_clean();
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$artist = $_POST['Search Artist'];

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
$request['type'] = "concert";
$request['artist'] = $artist;
$request['message'] = $msg;
$response = $client->send_request($request);
//$response = $client->publish($request);


if ($response!==0)) 
{
	echo "<table style='width:100%'>";
        echo " <tr>";
        echo "  <th>Concert Name</th>";
        echo "  <th>Artist Name</th>";
        echo "  <th>Location</th>";
        echo "  <th>Date and Time</th>";
        echo "  <th>Venue</th>";
	echo " </tr>";
	foreach($response as $concert)
	{
        	echo " <tr>";
        	echo "  <td>".$concert[0]."</td>";
        	echo "  <td>".$concert[1]."</td>";
        	echo "  <td>".$concert[2]."</td>";
        	echo "  <td>".$concert[3]."</td>";
        	echo "  <td>".$concert[4]."</td>";
		echo " </tr>";
	}
        echo "</table>";

}

