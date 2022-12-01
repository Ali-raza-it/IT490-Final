#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function searchSong($songTitle)
{
	$newSongTitle = str_replace(" ", "%20", $songTitle);
	$curl = curl_init();
	curl_setopt_array($curl, [CURLOPT_URL => "https://spotify-scraper.p.rapidapi.com/v1/track/search?name=$newSongTitle", CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true, CURLOPT_ENCODING => "", CURLOPT_MAXREDIRS => 10, CURLOPT_TIMEOUT => 30, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => "GET", CURLOPT_HTTPHEADER => ["X-RapidAPI-Host: spotify-scraper.p.rapidapi.com","X-RapidAPI-Key: 147e6c149emsha489899b80761adp17daebjsne9c296bf864a"],]);

	$response = curl_exec($curl);
	$err = curl_error($curl);

	curl_close($curl);

	if ($err) 
	{
		echo "cURL Error #:" . $err;
	} 	

	else 
	{
		$decoded = json_decode($response, true);
		$songName = $decoded['name'];
		$songArtist = $decoded['artists'][0]['name'];
		$songAlbum = $decoded['album']['name'];
		$songArray = array();
		array_push($songArray, $songName);
		array_push($songArray, $songArtist);
		array_push($songArray, $songAlbum);
		return $songArray;
	}
}


function searchArtist($artist)
{
	$newArtist = str_replace(" ", "%20", $artist); 
	$curl = curl_init();
        curl_setopt_array($curl, [CURLOPT_URL => "https://spotify-scraper.p.rapidapi.com/v1/artist/search?name=$newArtist", CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true, CURLOPT_ENCODING => "", CURLOPT_MAXREDIRS => 10, CURLOPT_TIMEOUT => 30, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => "GET", CURLOPT_HTTPHEADER => ["X-RapidAPI-Host: spotify-scraper.p.rapidapi.com","X-RapidAPI-Key: 147e6c149emsha489899b80761adp17daebjsne9c296bf864a"],]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

	if ($err) 
	{
                echo "cURL Error #:" . $err;
        }

	else 
	{
		$decoded = json_decode($response, true);
		$artistID = $decoded['id'];
		$curl2 = curl_init();
        	curl_setopt_array($curl2, [CURLOPT_URL => "https://spotify-scraper.p.rapidapi.com/v1/artist/overview?artistId=$artistID", CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true, CURLOPT_ENCODING => "", CURLOPT_MAXREDIRS => 10, CURLOPT_TIMEOUT => 30, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => "GET", CURLOPT_HTTPHEADER => ["X-RapidAPI-Host: spotify-scraper.p.rapidapi.com","X-RapidAPI-Key: 147e6c149emsha489899b80761adp17daebjsne9c296bf864a"],]);

        	$response2 = curl_exec($curl2);
        	$err2 = curl_error($curl2);

        	curl_close($curl2);

		if ($err2)
       		{
                	echo "cURL Error #:" . $err2;
		}
		else
		{
			$decoded2 = json_decode($response2, true);
			$artistName = $decoded2['name'];
			$followers = $decoded2['stats']['followers'];
			$monthlyListeners = $decoded2['stats']['monthlyListeners'];
			$worldRank = $decoded2['stats']['worldRank'];
			$artistArray = array();
			array_push($artistArray, $artistName);
			array_push($artistArray, $followers);
			array_push($artistArray, $monthlyListeners);
			array_push($artistArray, $worldRank);
                	return $artistArray;
        	}
	}
}


function searchConcerts($artist)
{
	$newArtist = str_replace(" ", "%20", $artist);
	$curl = curl_init();
        curl_setopt_array($curl, [CURLOPT_URL => "https://spotify-scraper.p.rapidapi.com/v1/artist/search?name=$newArtist", CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true, CURLOPT_ENCODING => "", CURLOPT_MAXREDIRS => 10, CURLOPT_TIMEOUT => 30, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => "GET", CURLOPT_HTTPHEADER => ["X-RapidAPI-Host: spotify-scraper.p.rapidapi.com","X-RapidAPI-Key: 147e6c149emsha489899b80761adp17daebjsne9c296bf864a"],]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

	if ($err) 
	{
                echo "cURL Error #:" . $err;
        }

	else 
	{
		$decoded = json_decode($response, true);
		$artistID = $decoded['id'];
        	$curl2 = curl_init();
                curl_setopt_array($curl2, [CURLOPT_URL => "https://spotify-scraper.p.rapidapi.com/v1/artist/concerts?artistId=$artistID", CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true, CURLOPT_ENCODING => "", CURLOPT_MAXREDIRS => 10, CURLOPT_TIMEOUT => 30, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => "GET", CURLOPT_HTTPHEADER => ["X-RapidAPI-Host: spotify-scraper.p.rapidapi.com","X-RapidAPI-Key: 147e6c149emsha489899b80761adp17daebjsne9c296bf864a"],]);

                $response2 = curl_exec($curl2);
                $err2 = curl_error($curl2);

                curl_close($curl2);

                if ($err2)
                {
                        echo "cURL Error #:" . $err2;
                }
                else
                {
			$decoded2 = json_decode($response2, true);
			$concerts = $decoded2['concerts'];
			$concertArray = array();
			foreach($concerts as $concert)
			{
				$carray = array();
				$concertTitle = $concert['title'];
				$concertLocation = $concert['location'];
				$concertDate = $concert['date'];
				$concertVenue = $concert['venue'];
				array_push($carray, $concertTitle);
				array_push($carray, $artist);
				array_push($carray, $concertLocation);
				array_push($carray, $concertDate);
				array_push($carray, $concertVenue);
				array_push($concertArray, $carray);
			}	
                        return $concertArray;
                }
        
        }
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

