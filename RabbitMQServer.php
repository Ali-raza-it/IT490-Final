#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

//establishes connection to the database.
$mydb = new mysqli('127.0.0.1','carter','abcde','IT490');

function doSignup($username,$password,$firstname,$lastname,$email)
{
	global $mydb;
        // check username.
        $usr = "select username from users where username = ?;";
        $uquery = mysqli_stmt_init($mydb);
        if(!mysqli_stmt_prepare($uquery, $usr))
        {
                return false;
                exit();
        }
        mysqli_stmt_bind_param($uquery, "s", $username);
        mysqli_stmt_execute($uquery);
        $usrresult = mysqli_stmt_get_result($uquery);
        if (mysqli_fetch_assoc($usrresult) !== Null)
        {
                return false;
                exit();

	}
        mysqli_stmt_close($uquery);
        // check email.
        $e = "select email from users where email = ?;";
        $equery = mysqli_stmt_init($mydb);
        if(!mysqli_stmt_prepare($equery, $e))
        {
                return false;
                exit();
        }
        mysqli_stmt_bind_param($equery, "s", $email);
        mysqli_stmt_execute($equery);
        $emailresult = mysqli_stmt_get_result($equery);
        if (mysqli_fetch_assoc($emailresult) !== Null)
        {
                return false;
                exit();
        }
        mysqli_stmt_close($equery);
        //hash password.
        $hashedpassword = password_hash($password, PASSWORD_DEFAULT);
        // insert parameters into users table
        $insert = "insert into users (username, password, firstName, lastName, email) values(?,?,?,?,?);";
        $insertstmt = mysqli_stmt_init($mydb);
	if (!mysqli_stmt_prepare($insertstmt, $insert))
        {
                return false;
                exit();
        }
        mysqli_stmt_bind_param($insertstmt, "sssss", $username, $hashedpassword, $firstname, $lastname, $email);
        mysqli_stmt_execute($insertstmt);
	mysqli_stmt_close($insertstmt);
	$insert2 = "insert into userRec (username, hiphopLD, countryLD, popLD, rockLD, indieLD, lationoLD, RandBLD, edmLD, chillLD) values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";
                $insertstmt2 = mysqli_stmt_init($mydb);
                if(!mysqli_stmt_prepare($insertstmt2, $insert2))
                {
                        return false;
                        exit();
                }
                mysqli_stmt_bind_param($insertstmt2, "s", $username, 0, 0, 0, 0, 0, 0, 0, 0, 0);
                mysqli_stmt_execute($insertstmt2);
                mysqli_stmt_close($insertstmt2);
        return true;
}


function doLogin($username,$password)
{
	global $mydb;
	// lookup username in database.
	$usr = "select username from users where username = ?;";
	$uquery = mysqli_stmt_init($mydb);
        if(!mysqli_stmt_prepare($uquery, $usr))
        {
                return false;
                exit();
        }
        mysqli_stmt_bind_param($uquery, "s", $username);
        mysqli_stmt_execute($uquery);
        $usrresult = mysqli_stmt_get_result($uquery);
	if (mysqli_fetch_assoc($usrresult) == Null)
	{
		return false;
		exit();
	}
	mysqli_stmt_close($uquery);
	// check password for that user.
	$pwd = "select password from users where username = ?;";
	$pquery = mysqli_stmt_init($mydb);
        if(!mysqli_stmt_prepare($pquery, $pwd))
        {
                return false;
                exit();
        }
        mysqli_stmt_bind_param($pquery, "s", $username);
        mysqli_stmt_execute($pquery);
        $pwdresult = mysqli_stmt_get_result($pquery);
	$pwdquery = mysqli_fetch_assoc($pwdresult);
	foreach($pwdquery as $key => $value)
	{
		//compares password user enetered to the dehashed password that user has in the database.
		if (password_verify($password, $value) == false)
        	{
			return false;
			exit();
		}
	}
	mysqli_stmt_close($pquery);
        return true;
}

function getSong($songTitle)
{
	global $mydb;
	// Searches for the requested song title in the music database.
	$song = "select songTitle from music where songTitle = ?;";
        $songquery = mysqli_stmt_init($mydb);
        if(!mysqli_stmt_prepare($songquery, $song))
        {
                return false;
                exit();
        }
        mysqli_stmt_bind_param($songquery, "s", $songTitle);
        mysqli_stmt_execute($songquery);
	$songresult = mysqli_stmt_get_result($songquery);
	mysqli_stmt_close($songquery);
	// Sends client request to the dmz if song title isn't in the music database.
	if (mysqli_fetch_assoc($songresult) == Null)
	{
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
		$request['type'] = "songapi";
		$request['songTitle'] = $songTitle;
		$request['message'] = $msg;
		$response = $client->send_request($request);
		if($response == false)
		{
			return false;
			exit();
		}
		// Inserts the values that were returned from the api and the dmz into the databse.
		list($songTitle, $artist, $genre, $playlist) = $response;
		$insert = "insert into music (songTitle, artist, genre, playlist) values(?,?,?,?);";
        	$insertstmt = mysqli_stmt_init($mydb);
        	if(!mysqli_stmt_prepare($insertstmt, $insert))
        	{
                	return false;
                	exit();
        	}
        	mysqli_stmt_bind_param($insertstmt, "ssss", $songTitle, $artist, $genre, $playlist);
        	mysqli_stmt_execute($insertstmt);
		mysqli_stmt_close($insertstmt);
		$insert2 = "insert into songLikesandDislike (songTitle, likes, dislikes) values(?, ?, ?);";
                $insertstmt2 = mysqli_stmt_init($mydb);
                if(!mysqli_stmt_prepare($insertstmt2, $insert2))
                {
                        return false;
                        exit();
                }
                mysqli_stmt_bind_param($insertstmt2, "sss", $songTitle, 0, 0);
                mysqli_stmt_execute($insertstmt2);
                mysqli_stmt_close($insertstmt2);

	}
	// Returns the information of the requested song to the client in the form of an array.
	$songdata = "select songTitle, artist, genre, playlist from music where songTitle = ?;";
        $sdquery = mysqli_stmt_init($mydb);
        if(!mysqli_stmt_prepare($sdquery, $songdata))
        {
        	return false;
                exit();
        }
        mysqli_stmt_bind_param($sdquery, "s", $songTitle);
        mysqli_stmt_execute($sdquery);
        $sdresult = mysqli_stmt_get_result($sdquery);
	$sfetch = mysqli_fetch_assoc($sdresult);
	$songarray = array();
	foreach($sfetch as $key => $value)
	{
		array_push($songarray, $value);
	}
	mysqli_stmt_close($sdquery);
	return $songarray;
}

function getArtist($artist)
{
	global $mydb;
	// Searches for the requested artist in the music database.
        $a = "select artist from music where artist = ?;";
        $artistquery = mysqli_stmt_init($mydb);
        if(!mysqli_stmt_prepare($artistquery, $a))
        {
                return false;
                exit();
        }
        mysqli_stmt_bind_param($artistquery, "s", $artist);
        mysqli_stmt_execute($artistquery);
        $artistresult = mysqli_stmt_get_result($artistquery);
	mysqli_stmt_close($artistquery);
	// Sends a client request to the dmz if there are no songs by the requested artist in the music database.
        if (mysqli_fetch_assoc($artistresult) == Null)
        {
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
                $request['type'] = "artistapi";
                $request['artist'] = $artist;
                $request['message'] = $msg;
                $response = $client->send_request($request);
                if($response == false)
                {
                        return false;
                        exit();
		}
		// Inserts the values returned from the api and the dmz into the database.
                list($title, $artist, $genre, $playlist) = $response;
                $insert = "insert into music (songTitle, artist, genre, playlist) values(?,?,?,?);";
                $insertstmt = mysqli_stmt_init($mydb);
                if(!mysqli_stmt_prepare($insertstmt, $insert))
		{
			return false;
                        exit();
                }
                mysqli_stmt_bind_param($insertstmt, "ssss", $title, $artist, $genre, $playlist);
                mysqli_stmt_execute($insertstmt);
                mysqli_stmt_close($insertstmt);
	}
	// Returns all songs made by the requested artist to the client in the form of an array.
        $artistdata = "select songTitle, artist, genre, playlist from music where artist = ?;";
        $adquery = mysqli_stmt_init($mydb);
        if(!mysqli_stmt_prepare($adquery, $artistdata))
        {
                return false;
                exit();
        }
        mysqli_stmt_bind_param($adquery, "s", $artist);
        mysqli_stmt_execute($adquery);
        $adresult = mysqli_stmt_get_result($adquery);
        $afetch = mysqli_fetch_assoc($adresult);
        $artistarray = array();
        foreach($afetch as $key => $value)
	{
                array_push($artistarray, $value);
        }
        mysqli_stmt_close($adquery);
        return $artistarray;
}

function getGenre($genre)
{
	global $mydb;
	// Searches for the requested genre in the music database.
        $g = "select genre from music where genre = ?;";
        $genrequery = mysqli_stmt_init($mydb);
        if(!mysqli_stmt_prepare($genrequery, $g))
        {
                return false;
                exit();
        }
        mysqli_stmt_bind_param($genrequery, "s", $genre);
        mysqli_stmt_execute($genrequery);
        $genreresult = mysqli_stmt_get_result($genrequery);
	mysqli_stmt_close($genrequery);
	// Sends a client request to the dmz if the requested genre isn't in the music database.
        if (mysqli_fetch_assoc($genreresult) == Null)
        {
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
                $request['type'] = "genreapi";
                $request['genre'] = $genre;
                $request['message'] = $msg;
                $response = $client->send_request($request);
                if($response == false)
                {
                        return false;
                        exit();
		}
		// Inserts the values returend by the pi and the dmz into the database.
                list($title, $artist, $genre, $playlist) = $response;
                $insert = "insert into music (songTitle, artist, genre, playlist) values(?,?,?,?);";
                $insertstmt = mysqli_stmt_init($mydb);
                if(!mysqli_stmt_prepare($insertstmt, $insert))
                {
                        return false;
                        exit();
		}
		mysqli_stmt_bind_param($insertstmt, "ssss", $title, $artist, $genre, $playlist);
                mysqli_stmt_execute($insertstmt);
                mysqli_stmt_close($insertstmt);
	}
	// Returns all songs of the requested genre to the client in the form of an array. 
        $genredata = "select songTitle, artist, genre, playlist from music where genre = ?;";
        $gquery = mysqli_stmt_init($mydb);
        if(!mysqli_stmt_prepare($gquery, $genredata))
        {
                return false;
                exit();
        }
        mysqli_stmt_bind_param($gquery, "s", $genre);
        mysqli_stmt_execute($gquery);
        $gresult = mysqli_stmt_get_result($gquery);
        $gfetch = mysqli_fetch_assoc($gresult);
        $genrearray = array();
        foreach($gfetch as $key => $value)
        {
                array_push($genrearray, $value);
        }
        mysqli_stmt_close($gquery);
        return $genrearray;	
}

function addLike($username, $songTitle, $genre)
{
	global $mydb;
	// Searches for the number of likes the requested song currently has.
	$select = "select likes from songLikesAndDislike where songTitle = ?;";
	$selectstmt = mysqli_stmt_init($mydb);
        if(!mysqli_stmt_prepare($selectstmt, $select))
        {
                return false;
                exit();
        }
        mysqli_stmt_bind_param($selectstmt, "s", $songTitle);
        mysqli_stmt_execute($selectstmt);
        $selectresult = mysqli_stmt_get_result($selectstmt);
        $selectassoc = mysqli_fetch_assoc($selectresult);
        mysqli_stmt_close($selectstmt);
        if($selectassoc == Null)
        {
                return false;
                exit();
        }
        // Updates the like counter for the requested song.
        foreach($selectassoc as $key => $value)
        {
		$likesUpdated = $value+1;
		$update = "update songLikesAndDislike set likes = ? where songTitle = ?;";
        	$updatestmt = mysqli_stmt_init($mydb);
        	if(!mysqli_stmt_prepare($updatestmt, $update))
        	{
                	return false;
                	exit();
        	}
        	mysqli_stmt_bind_param($updatestmt, "is", $likesUpdated, $songTitle);
        	mysqli_stmt_execute($updatestmt);
        	mysqli_stmt_close($updatestmt);
	}
	// Selects the column to update in the userRec table based on the genre of the liked song.
	if($genre == "pop")
	{
		$column = "popLD";
	}
	if($genre == "hiphop")
        {
                $column = "hiphopLD";
        }
	if($genre == "country")
        {
		$column = "countryLD";	
	}
	if($genre == "latino")
	{
		$column = "lationLD";
	}
	if($genre == "indie")
	{
		$column = "indieLD";
	}
	if($genre == "rock")
	{
		$column = "rockLD";
	}
	if($genre == "edm")
	{
		$column = "edmLD";
	}
	if($genre == "R&B")
	{
		$column = "R&BLD";
	}
	if($genre == "chill")
	{
		$column = "chillLD";
	}
	$select2 = "select ? from userRec where username = ?;";
        $selectstmt2 = mysqli_stmt_init($mydb);
        if(!mysqli_stmt_prepare($selectstmt2, $select2))
        {
                return false;
                exit();
        }
        mysqli_stmt_bind_param($selectstmt2, "ss", $column, $username);
        mysqli_stmt_execute($selectstmt2);
       	$selectresult2 = mysqli_stmt_get_result($selectstmt2);
        $selectassoc2 = mysqli_fetch_assoc($selectresult2);
        mysqli_stmt_close($selectstmt2);
        if($selectassoc2 == Null)
        {
                return false;
                exit();
	}
	// Updates the like to dislike ratio of the genre of the song this specific user liked.
	foreach($selectassoc as $key => $value)
        {
		$ldUpdated = $value +1;
		$update2 = "update userRec set ? = ? where username = ?;";
                $updatestmt2 = mysqli_stmt_init($mydb);
                if(!mysqli_stmt_prepare($updatestmt2, $update2))
                {
                        return false;
                        exit();
                }
                mysqli_stmt_bind_param($updatestmt2, "sis", $column, $ldUpdated, $username);
                mysqli_stmt_execute($updatestmt2);
                mysqli_stmt_close($updatestmt2);
	}
	return $likesUpdated;
}

function addDislike($username, $songTitle, $genre)
{
	global $mydb;
	// Searches for the number of dislikes the requested song currently has. 
	$select = "select dislikes from songLikesAndDislike where songTitle = ?;";
        $selectstmt = mysqli_stmt_init($mydb);
        if(!mysqli_stmt_prepare($selectstmt, $select))
        {
                return false;
                exit();
        }
        mysqli_stmt_bind_param($selectstmt, "s", $songTitle);
        mysqli_stmt_execute($selectstmt);
        $selectresult = mysqli_stmt_get_result($selectstmt);
        $selectassoc = mysqli_fetch_assoc($selectresult);
        mysqli_stmt_close($selectstmt);
        if($selectassoc == Null)
        {
                return false;
                exit();
        }
        // Updates the dislike counter for the requested song.
        foreach($selectassoc as $key => $value)
        {
		$dislikesUpdated = $value + 1;
		$update = "update songLikesAndDislike set dislikes = ? where songTitle = ?;";
                $updatestmt = mysqli_stmt_init($mydb);
                if(!mysqli_stmt_prepare($updatestmt, $update))
                {
                        return false;
                        exit();
                }
                mysqli_stmt_bind_param($updatestmt, "is", $dislikesUpdated, $songTitle);
                mysqli_stmt_execute($updatestmt);
                mysqli_stmt_close($updatestmt);
	}
	// Selects the column to update in the userRec table based on the genre of the disliked song.
        if($genre == "pop")
        {
                $column = "popLD";
        }
        if($genre == "hiphop")
        {
                $column = "hiphopLD";
        }
        if($genre == "country")
        {
                $column = "countryLD";
	}
	if($genre == "latino")
        {
                $column = "lationLD";
        }
        if($genre == "indie")
        {
                $column = "indieLD";
        }
        if($genre == "rock")
        {
                $column = "rockLD";
        }
        if($genre == "edm")
        {
                $column = "edmLD";
        }
        if($genre == "R&B")
        {
                $column = "RandBLD";
	}
	if($genre == "chill")
        {
                $column = "chillLD";
        }
	$select2 = "select ? from userRec where username = ?;";
        $selectstmt2 = mysqli_stmt_init($mydb);
        if(!mysqli_stmt_prepare($selectstmt2, $select2))
        {
                return false;
                exit();
        }
        mysqli_stmt_bind_param($selectstmt2, "ss", $column, $username);
        mysqli_stmt_execute($selectstmt2);
        $selectresult2 = mysqli_stmt_get_result($selectstmt2);
        $selectassoc2 = mysqli_fetch_assoc($selectresult2);
        mysqli_stmt_close($selectstmt2);
        if($selectassoc2 == Null)
        {
                return false;
                exit();
	}
	// Updates the like to dislike ratio of the genre of the song this specific user disliked.
        foreach($selectassoc as $key => $value)
        {
                $ldUpdated = $value-1;
                $update2 = "update userRec set ? = ? where username = ?;";
                $updatestmt2 = mysqli_stmt_init($mydb);
                if(!mysqli_stmt_prepare($updatestmt2, $update2))
                {
                        return false;
		}
		mysqli_stmt_bind_param($updatestmt2, "sis", $column, $ldUpdated, $username);
                mysqli_stmt_execute($updatestmt2);
                mysqli_stmt_close($updatestmt2);
	}
	return $dislikesUpdated;
}

function genreRecommendation($username)
{
	global $mydb;
	// Selects the like to dislike ratio of all genres for the selected user.
        $select = "select * from userRec where username = ?;";
        $selectquery = mysqli_stmt_init($mydb);
        if(!mysqli_stmt_prepare($selectquery, $select))
        {
                return false;
                exit();
        }
        mysqli_stmt_bind_param($selectquery, "s", $username);
        mysqli_stmt_execute($selectquery);
	$selectresult = mysqli_stmt_get_result($selectquery);
	$selectassoc = mysqli_fetch_assoc($selectresult);
	mysqli_stmt_close($selectquery);
        if ($selectassoc == Null)
	{
		return false;
		exit();
	}
	// Loops through the user's like to dislike ratio for each genre to see hwich genre has the greatest ratio.
	$greatestRatio = -999;
	$column = "";
	foreach($selectassoc as $key => $value)
	{
		if($value > $greatestRatio)
		{
			$greatestRatio = $value;
			$column = $key;
		}
	}
	// Chooses a genre to recomend to the user based on which genre had the highest like to dislike ratio.
	if($column == "popLD")
        {
                $genre = "pop";
        }
        if($column == "hiphopLD")
        {
                $genre = "hiphop";
        }
        if($column == "countryLD")
        {
                $genre = "country";
        }
        if($column == "latinoLD")
        {
                $genre = "lation";
        }
        if($column == "indieLD")
        {
                $genre = "indie";
        }
        if($column == "rockLD")
        {
                $genre = "rock";
        }
	if($column == "edmLD")
        {
                $genre = "edm";
        }
        if($column == "RandBLD")
        {
                $genre = "R&B";
        }
        if($column == "chillLD")
        {
                $genre = "chill";
	}
	// Returns songs and song information of the picked genre. 
	$genredata = "select songTitle, artist, genre, playlist from music where genre = ?;";
        $gquery = mysqli_stmt_init($mydb);
        if(!mysqli_stmt_prepare($gquery, $genredata))
        {
                return false;
                exit();
        }
        mysqli_stmt_bind_param($gquery, "s", $recGenre);
        mysqli_stmt_execute($gquery);
        $gresult = mysqli_stmt_get_result($gquery);
        $gfetch = mysqli_fetch_assoc($gresult);
        $recarray = array();
        foreach($gfetch as $key => $value)
        {
                array_push($recarray, $value);
        }
        mysqli_stmt_close($gquery);
	return $recarray;
}

function getPlaylist($playlist)
{
	global $mydb;
	// Searches for the requested playlist in the music database.
        $p = "select playlist from music where playlist = ?;";
        $playlistquery = mysqli_stmt_init($mydb);
        if(!mysqli_stmt_prepare($playlistquery, $p))
        {
                return false;
                exit();
        }
        mysqli_stmt_bind_param($playlistquery, "s", $playlist);
        mysqli_stmt_execute($playlistquery);
        $playlistresult = mysqli_stmt_get_result($playlistquery);
	mysqli_stmt_close($playlistquery);
	// Sends a client request to the dmz if the requested playlist was't in the database.
        if (mysqli_fetch_assoc($playlistresult) == Null)
        {
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
                $request['type'] = "playlistapi";
                $request['playlist'] = $playlist;
                $request['message'] = $msg;
		$response = $client->send_request($request);
		// Returns false if the requested playlist was not found in the api.
                if($response == false)
                {
                        return false;
                        exit();
		}
		// Inserts the values returned by the api and the dmz into the database.
                list($title, $artist, $genre, $playlist) = $response;
                $insert = "insert into music (songTitle, artist, genre, playlist) values(?,?,?,?);";
                $insertstmt = mysqli_stmt_init($mydb);
                if(!mysqli_stmt_prepare($insertstmt, $insert))
                {
                        return false;
                        exit();
                }
                mysqli_stmt_bind_param($insertstmt, "ssss", $title, $artist, $genre, $playlist);
                mysqli_stmt_execute($insertstmt);
                mysqli_stmt_close($insertstmt);
	}
	// Returns all songs in the requested playlist to the client in the forms of an array.
        $playlistdata = "select songTitle, artist, genre, playlist from music where playlist = ?;";
        $pdquery = mysqli_stmt_init($mydb);
	if(!mysqli_stmt_prepare($pdquery, $playlistdata))
        {
                return false;
                exit();
        }
        mysqli_stmt_bind_param($pdquery, "s", $playlist);
        mysqli_stmt_execute($pdquery);
        $pdresult = mysqli_stmt_get_result($pdquery);
        $pdfetch = mysqli_fetch_assoc($pdresult);
        $playlistarray = array();
        foreach($pdfetch as $key => $value)
        {
                array_push($playlistarray, $value);
        }
        mysqli_stmt_close($pdquery);
        return $playlistarray;
}

function searchUser($username)
{
	global $mydb;
	// Searches for the requested username in the users table.
	$select = "select username from users where username = ?;";
        $selectstmt = mysqli_stmt_init($mydb);
        if(!mysqli_stmt_prepare($selectstmt, $select))
        {
                return false;
                exit();
        }
        mysqli_stmt_bind_param($selectstmt, "s", $username);
        mysqli_stmt_execute($selectstmt);
        $selectresult = mysqli_stmt_get_result($selectstmt);
        $selectassoc = mysqli_fetch_assoc($selectresult);
        mysqli_stmt_close($selectstmt);
        if($selectassoc == Null)
	{
		return false;
		exit();
	}
	// Returns the requested username to the client.
	foreach($selectassoc as $key => $value)
        {
                $user = $value;
	}
	return $user;

}

function searchUserAll($username)
{
        global $mydb;
        // Searches for the requested username in the users table.
        $select = "select username, firstname, lastname, email from users where username = ?;";
        $selectstmt = mysqli_stmt_init($mydb);
        if(!mysqli_stmt_prepare($selectstmt, $select))
        {
                return false;
                exit();
        }
        mysqli_stmt_bind_param($selectstmt, "s", $username);
        mysqli_stmt_execute($selectstmt);
        $selectresult = mysqli_stmt_get_result($selectstmt);
        $selectassoc = mysqli_fetch_assoc($selectresult);
        mysqli_stmt_close($selectstmt);
        if($selectassoc == Null)
        {
                return false;
                exit();
	}
	$userarray= array();
        // Returns the username, firstname, lastname, and email of the requested username to the client.
	foreach($selectassoc as $key => $value)
	{
		array_push($userarray, $value);
	}
	return $userarray;
}

function addFriend($username, $friendusername)
{
	global $mydb;
	// Checks to see if the user is already friends with the other user.
	$select = "select username1, username2 from ? friends where username1 = ? and username2 = ?;";
        $selectstmt = mysqli_stmt_init($mydb);
        if(!mysqli_stmt_prepare($selectstmt, $select))
        {
                return false;
                exit();
        }
        mysqli_stmt_bind_param($selectstmt, "ss", $username, $friendusername);
        mysqli_stmt_execute($selectstmt);
	$selectresult = mysqli_stmt_get_result($selectstmt);
	$selectassoc = mysqli_fetch_assoc($selectresult);
	mysqli_stmt_close($selectstmt);
	// Makes two insertions into the friends databse to make the two users provided by the client friends. 
	if($selectassoc == Null)
	{
		$insert1 = "insert into friends (username1, username2) values(?, ?);";
		$insertstmt1 = mysqli_stmt_init($mydb);
		if(!mysqli_stmt_prepare($insertstmt1, $insert1))
        	{
                	return false;
                	exit();
        	}
        	mysqli_stmt_bind_param($insertstmt1, "ss", $username, $friendusername);
        	mysqli_stmt_execute($insertstmt1);
        	mysqli_stmt_close($insertstmt1);
		return true;
		$insert2 = "insert into friends (username1, username2) values(?, ?);";
                $insertstmt2 = mysqli_stmt_init($mydb);
                if(!mysqli_stmt_prepare($insertstmt2, $insert2))
                {
                        return false;
                        exit();
                }
                mysqli_stmt_bind_param($insertstmt2, "ss", $friendusername, $username);
                mysqli_stmt_execute($insertstmt2);
                mysqli_stmt_close($insertstmt2);
                return true;

	}
	// Returns false if user is already friends with the other user.
	return false;
}

function removeFriend($username, $friendusername)
{
	global $mydb;
	// Checks to see if the user the other user attempting to unfriend it friends with that user.
	$select = "select username1, username2 from friends where username1 = ?
 and username 2 = ?;";
        $selectstmt = mysqli_stmt_init($mydb);
        if(!mysqli_stmt_prepare($selectstmt, $select))
        {
                return false;
                exit();
        }
        mysqli_stmt_bind_param($selectstmt, "ss", $username, $friendusername);
        mysqli_stmt_execute($selectstmt);
	$selectresult = mysqli_stmt_get_result($selectstmt);
        $selectassoc = mysqli_fetch_assoc($selectresult);
	mysqli_stmt_close($selectstmt);
	// Returns false if the user is not friends with the user it is unfriending.
        if($selectassoc == Null)
        {
		return false;
		exit();
	}
	// Unfriends the two users provided by the client.
	$delete1 = "delete from friends where username1 = ? and username2 = ?;";
        $deletestmt1 = mysqli_stmt_init($mydb);
        if(!mysqli_stmt_prepare($deletestmt1, $delete1))
        {
        	return false;
                exit();
        }
        mysqli_stmt_bind_param($deletestmt1, "ss", $username, $friendusername);
        mysqli_stmt_execute($deletestmt1);
	mysqli_stmt_close($deletestmt1);

	$delete2 = "delete from friends where username1 = ? and username2 = ?;";
        $deletestmt2 = mysqli_stmt_init($mydb);
        if(!mysqli_stmt_prepare($deletestmt2, $delete2))
        {
                return false;
                exit();
        }
        mysqli_stmt_bind_param($deletestmt2, "ss", $friendusername, $username);
        mysqli_stmt_execute($deletestmt2);
        mysqli_stmt_close($deletestmt2);
        return true;

}

function getConcert($concertTitle)
{
	global $mydb;
	// Searches for the requested concert in the concert database table.
        $concert = "select concertTitle from concerts where concertTitle = ?;";
        $concertquery = mysqli_stmt_init($mydb);
        if(!mysqli_stmt_prepare($concertquery, $concert))
        {
                return false;
                exit();
        }
        mysqli_stmt_bind_param($concertquery, "s", $concertTitle);
        mysqli_stmt_execute($concertquery);
        $concertresult = mysqli_stmt_get_result($concertquery);
        mysqli_stmt_close($concertquery);
        if (mysqli_fetch_assoc($movieresult) == Null)
	{
		// Sends a client request to the dmz if the concert was not found in the database.
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
                $request['type'] = "concertapi";
                $request['title'] = $concertTitle;
                $request['message'] = $msg;
		$response = $client->send_request($request);
		// Returns false if the concert was not found in the api.
                if($response == false)
                {
                        return false;
                        exit();
		}
		// Insert the concert and concert information into the database.
		list($concertTitle, $artist, $location, $datetime) = $response;
                $insert = "insert into concerts (concertTitle, artist, location, dateAndTime) values(?,?,?,?);";
                $insertstmt = mysqli_stmt_init($mydb);
                if(!mysqli_stmt_prepare($insertstmt, $insert))
                {
                        return false;
                        exit();
                }
                mysqli_stmt_bind_param($insertstmt, "ssss", $concertTitle, $artist, $locaton, $datetime);
                mysqli_stmt_execute($insertstmt);
                mysqli_stmt_close($insertstmt);
	}
	// Returns concert information to the client.
	$concertdata = "select * from concerts where concertTitle  = ?;";
        $cdquery = mysqli_stmt_init($mydb);
        if(!mysqli_stmt_prepare($cdquery, $concertdata))
        {
                return false;
                exit();
        }
        mysqli_stmt_bind_param($cdquery, "s", $concertTitle);
        mysqli_stmt_execute($cdquery);
        $cdresult = mysqli_stmt_get_result($cdquery);
        $cfetch = mysqli_fetch_assoc($cdresult);
        $concertarray = array();
        foreach($cfetch as $key => $value)
        {
                array_push($concertarray, $value);
        }
        mysqli_stmt_close($cdquery);
        return $concertarray;

}

//function getConcertDate()
//{
	//global $mydb;

//}

function addDiscussion($username, $content, $timestamp)
{
	global $mydb;
	// Inserts the newest discussion post into the discussion table.
        $discussion = "insert into discussion (username, content, timestamp) values(?, ?, ?);";

        $discussionquery = mysqli_stmt_init($mydb);
        if(!mysqli_stmt_prepare($discussionquery, $discussion))
        {
                return false;
                exit();
        }
        mysqli_stmt_bind_param($discussionquery, "sss", $username, $content, $timestamp);
        mysqli_stmt_execute($discussionquery);
	mysqli_stmt_close($discussionquery);
	return true;
}

function getDiscussion()
{
	global $mydb;
        // Searches for an returns all discussion posts.
        $discussion = "select * from discussion;";
        $discussionquery = mysqli_stmt_init($mydb);
        if(!mysqli_stmt_prepare($discussionquery, $discussion))
        {
                return false;
                exit();
        }
        mysqli_stmt_execute($discussionquery);
	$discussionresult = mysqli_stmt_get_result($discussionquery);
	$discussionfetch = mysqli_fetch_assoc($discussionresult);
        $discussionarray = array();
        foreach($discussionfetch as $key => $value)
        {
                array_push($discussionarray, $value);
        }
	mysqli_stmt_close($discussionquery);
	return $discussionarray;
}

//function sendNotification()
//{
	//global $mydb;
//}

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
      return doLogin($request["username"],$request["password"]);
    case "register":
      return doSignup($request["username"],$request["password"], $request["firstname"], $request["lastname"], $request["email"]);
    case "song":
      return getSong($request['title']);
    case "artist":
      return getArtist($request['artist']);
    case "genre":
      return getGenre($request['genre']);
    case "playlist":
      return getPlaylist($request['playlist']);
    case "search user":
      return searchUser($request['username']);
    case "search user all":
      return searchUserAll($request['username']);
    case "add friend":
      return addFriend($request['username'],$request['friendusername']);
    case "remove friend":
      return removeFriend($request['username'], $request['friendusername']);
    case "concert":
      return getConcert($request['title']);
    case "add discussion":
      return addDiscussion($request['username'], $request['post content'], $request['timestamp']);
    case "get discussion":
      return getDiscussion();
    case "get notification":
      return sendNotification();
    case "update notification":
      return updateNotification();
    case "get notification by user":
      return getNotificationByUSer($request['username']);
    case "like":
      return addLike($reuest['username'],$request['song'], $request['genre']);
    case "dislike":
      return addDislike($reuest['username'], $request['song'], $request['genre']);
    case "get recomendation":
      return getRecomendation($request['username']);
  }
}

$server = new rabbitMQServer("testRabbitMQ.ini","testServer");

$server->process_requests('requestProcessor');
exit();
?>

