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
        // check username
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
        // check email
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
        //hash password
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
	//creates friends table for the user that was just signed up
	$usertable = "create table ? (friendUsername varchar(255) not null, friendFirstname varchar (255) not null, FriendLastname varchar(255) not null, primary key (friendUsername);";
	$createstmt = mysqli_stmt_init($mydb);
        if (!mysqli_stmt_prepare($createstmt, $usertable))
        {
                return false;
                exit();
        }
        mysqli_stmt_bind_param($createstmt, "s", $username);
        mysqli_stmt_execute($createstmt);
        mysqli_stmt_close($createstmt);
        return true;
}


function doLogin($username,$password)
{
	global $mydb;
	// lookup username in database
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
	// check password for that user
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
		list($songID, $title, $artist, $genre) = $response;
		$insert = "insert into music (songID, songTitle, artist, genre) values(?,?,?,?);";
        	$insertstmt = mysqli_stmt_init($mydb);
        	if(!mysqli_stmt_prepare($insertstmt, $insert))
        	{
                	return false;
                	exit();
        	}
        	mysqli_stmt_bind_param($insertstmt, "ssss", $songID, $title, $artist, $genre);
        	mysqli_stmt_execute($insertstmt);
		mysqli_stmt_close($insertstmt);
	}
	$songdata = "select * from music where songID  = ?;";
        $sdquery = mysqli_stmt_init($mydb);
        if(!mysqli_stmt_prepare($sdquery, $songdata))
        {
        	return false;
                exit();
        }
        mysqli_stmt_bind_param($sdquery, "s", $songID);
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

function genreRecommendation($genre)
{
	global $mydb;
        $g = "select title, service from moviesAndEpisodes where genre = ?;";
        $gquery = mysqli_stmt_init($mydb);
        if(!mysqli_stmt_prepare($gquery, $g))
        {
                return false;
                exit();
        }
        mysqli_stmt_bind_param($gquery, "s", $genre);
        mysqli_stmt_execute($gquery);
	$genreresult = mysqli_stmt_get_result($gquery);
	$genreassoc = mysqli_fetch_assoc($genreresult);
	mysqli_stmt_close($gquery);
        if ($genreassoc == Null)
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
                $request['type'] = "genre";
                $request['title'] = $genre;
                $request['message'] = $msg;
                $response = $client->send_request($request);
                if($response == false)
                {
                        return false;
                        exit();
                }

	}

}

function addFriend($username, $friendusername, $firstname, $lastname)
{
	global $mydb;
	//checks to see if the user is already friends with the other user
	$select = "select friendUsername from ? where friendUsername = ?;";
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
	if($selectassoc == Null)
	{
		//adds friend to the users friend list
		$insert = "insert into ? (friendUsernname, friendFirstname, friendLastname) values(?, ?, ?);";
		$insertstmt = mysqli_stmt_init($mydb);
		if(!mysqli_stmt_prepare($insertstmt, $insert))
        	{
                	return false;
                	exit();
        	}
        	mysqli_stmt_bind_param($insertstmt, "ssss", $username, $friendusername, $firstname, $lastname);
        	mysqli_stmt_execute($insertstmt);
        	mysqli_stmt_close($insertstmt);
		return true;
	}
	//returns false if user is already friends with the user.
	return false;

}

function removeFriend($username, $friendusername)
{
	global $mydb;
	//searches for friend in that user's friend table.
	$select = "select friendUsername from ? where friendUsername = ?;";
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
	//return false if the user is not friends with the user it is defreinding
        if($selectassoc == Null)
        {
		return false;
		exit();
	}
	//removes friend from user's freind list
	$delete = "delete from ? where friendUsername = ?;";
        $deletestmt = mysqli_stmt_init($mydb);
        if(!mysqli_stmt_prepare($deletestmt, $delete))
        {
        	return false;
                exit();
        }
        mysqli_stmt_bind_param($deletestmt, "ss", $username, $friendusername);
        mysqli_stmt_execute($deletestmt);
        mysqli_stmt_close($deletestmt);
        return true;

}

function getConcert($concertTitle)
{
	global $mydb;
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
                if($response == false)
                {
                        return false;
                        exit();
                }
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
    case "validate_session":
      return doValidate($request['sessionId']);
    case "song":
      return getSong($request['title']);
    case "genre":
      return genreRecommendation($request['genre']);
    case "add friend":
      return addFriend($request['username'],$request['friendusername'],  $request['firstname'], $request['lastname']);
    case "remove friend":
      return removeFriend($request['username'], $request['friendusername']);
    case "concert":
      return getConcert($request['title']);
  }
}

$server = new rabbitMQServer("testRabbitMQ.ini","testServer");

$server->process_requests('requestProcessor');
exit();
?>

