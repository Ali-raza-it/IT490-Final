<?php
$results = shell_exec('GET https://creative-gelato-acc5e4.netlify.app/spotify%20api%20automatic/how-to-use-spotify-s-api-with-javascript-main/');
$arrayCode = json_decode($results);
var_dump($arrayCode);
?>


<?php

$curl = curl_init();

curl_setopt_array($curl, [
	CURLOPT_URL => "https://streaming-availability.p.rapidapi.com/search/basic?country=us&service=netflix&type=movie&genre=18&page=1&output_language=en&language=en",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => [
		"X-RapidAPI-Host: streaming-availability.p.rapidapi.com",
		"X-RapidAPI-Key: bca2b3d4f8mshe05faec97005306p10a74cjsnbfcb7909004f"
	],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
	echo "cURL Error #:" . $err;
} else {
	echo $response;
}
