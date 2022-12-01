#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function searchSong($songTitle)
{
	$client = new http\Client;
        $request = new http\Client\Request;
        $request->setRequestUrl('https://spotify-scraper.p.rapidapi.com/v1/track/search');
        $request->setRequestMethod('GET');
        $request->setQuery(new http\QueryString(['name' => $songTitle]));
	$request->setHeaders(['X-RapidAPI-Key' => '147e6c149emsha489899b80761adp17daebjsne9c296bf864a','X-RapidAPI-Host' => 'spotify-scraper.p.rapidapi.com']);
        $client->enqueue($request)->send();
        $response = $client->getResponse();
}

function searchArtist($artist)
{
	$client = new http\Client;
	$request = new http\Client\Request;
	$request->setRequestUrl('https://spotify-scraper.p.rapidapi.com/v1/artist/search%27');
	$request->setRequestMethod('GET');
	$request->setQuery(new http\QueryString(['name' => $artist]));
  $request->setHeaders([
    'X-RapidAPI-Key' => '147e6c149emsha489899b80761adp17daebjsne9c296bf864a',
    'X-RapidAPI-Host' => 'spotify-scraper.p.rapidapi.com']);
	$client->enqueue($request)->send();
	$response = $client->getResponse();


	$client2 = new http\Client;
        $request2 = new http\Client\Request;
        $request2->setRequestUrl('https://spotify-scraper.p.rapidapi.com/v1/artist/overview');
        $request2->setRequestMethod('GET');
        $request2->setQuery(new http\QueryString(['artistId' => $response;]));
        $request2->setHeaders(['X-RapidAPI-Key' => '147e6c149emsha489899b80761adp17daebjsne9c296bf864a','X-RapidAPI-Host' => 'spotify-scraper.p.rapidapi.com']);
        $client2->enqueue($request2)->send();
        $response2 = $client2->getResponse()

}

function searchConcert($artist)
{
	$client = new http\Client;
	$request = new http\Client\Request;
	$request->setRequestUrl('https://spotify-scraper.p.rapidapi.com/v1/artist/search%27');
	$request->setRequestMethod('GET');
	$request->setQuery(new http\QueryString(['name' => $artist]));
	$request->setHeaders([
        'X-RapidAPI-Key' => '147e6c149emsha489899b80761adp17daebjsne9c296bf864a','X-RapidAPI-Host' => 'spotify-scraper.p.rapidapi.com']);
	$client->enqueue($request)->send();
	$response = $client->getResponse();


	$client2 = new http\Client;
	$request2 = new http\Client\Request;
	$request2->setRequestUrl('https://spotify-scraper.p.rapidapi.com/v1/artist/concerts%27');
	$request2->setRequestMethod('GET');
	$request2->setQuery(new http\QueryString(['artistId' => $response;]));
	$request2->setHeaders(['X-RapidAPI-Key' => '147e6c149emsha489899b80761adp17daebjsne9c296bf864a','X-RapidAPI-Host' => 'spotify-scraper.p.rapidapi.com']);
	$client2->enqueue($request2)->send();
	$response2 = $client2->getResponse()

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
    case "songapi":
      return searchSong($request['songTitle']);
    case "artistapi":
      return searchArtist($request['artist']);
    case "concertapi":
      return searchConcet($request['title']);
  }
}

$server = new rabbitMQServer("testRabbitMQ.ini","testServer");

$server->process_requests('requestProcessor');
exit();
?>

