<?php 
  session_start();
  if(!isset($_SESSION['valid']) OR $_SESSION['valid'] !== true){
      header("location: login.php");
      exit;
  }
  if(isset($_SESSION['response'])){
      $response = $_SESSION['response'];
      
      $uname = $response[0];
      $fname = $response[1];
      $lname = $response[2];
      $email = $response[3];
      
  }
?>
  
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>
<?php $username = $_SESSION['username']; ?>
<nav class="navbar navbar-inverse">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="/Frontend/landing.php">CKNAZ Spotify</a>
    </div>
    <ul class="nav navbar-nav">
      <li><a href="/Frontend/Game/index.php">Game</a></li>
      <li><a href="/Frontend/searchConcert.php">Search Concerts</a></li>
      <li><a href="/Frontend/searchSong.php">Search Songs</a></li>
      <li><a href="/Frontend/searchArtist.php">Search Artists</a></li>
      <li><a href="/Frontend/concertVideo.php">Concert Videos</a></li>
      <li><a href="/Frontend/userRec.php">Recommendations</a></li>
      <li><a href="/Frontend/forum/index.php">Discussion Forum</a></li>
      
    </ul>
    <form class="navbar-form navbar-left" action="../searchUserClient.php">
      <div class="input-group">
        <input type="text" class="form-control" placeholder="Search User" name="user">
        <div class="input-group-btn">
          <button class="btn btn-default" type="submit">
            <i class="glyphicon glyphicon-search"></i>
          </button>
        </div>
      </div>
    </form>
        <ul class="nav navbar-nav navbar-right">
        <html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<body>

<div class="w3-container">
  
  <div class="w3-dropdown-click">
    <button onclick="myFunction()" class="w3-button w3-black">Click Me!</button>
    <div id="Demo" class="w3-dropdown-content w3-bar-block w3-border">
      <a href="#" class="w3-bar-item w3-button">Link 1</a>
      <a href="#" class="w3-bar-item w3-button">Link 2</a>
      <a href="#" class="w3-bar-item w3-button">Link 3</a>
    </div>
  </div>
</div>

<script>
function myFunction() {
  var x = document.getElementById("Demo");
  if (x.className.indexOf("w3-show") == -1) {
    x.className += " w3-show";
  } else { 
    x.className = x.className.replace(" w3-show", "");
  }
}
</script>

</body>
</html>

          <li><a href="/Frontend/Profile.php"><span class="glyphicon glyphicon-user"></span>  <?php echo $uname; ?> </a></li>
      <li><a href="/Frontend/logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
    </ul>
  </div>
</nav>

</body>
</html>
