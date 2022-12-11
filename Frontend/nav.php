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
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
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
		
          <li class="dropdown">
       <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="label label-pill label-danger count" style="border-radius:10px;"></span> <span class="glyphicon glyphicon-bell" style="font-size:18px;"></span>Notifications</a>
       <ul class="dropdown-menu"></ul>
      </li>
          <li><a href="/Frontend/Profile.php"><span class="glyphicon glyphicon-user"></span>  <?php echo $uname; ?> </a></li>
      <li><a href="/Frontend/logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
    </ul>
  </div>
</nav>
<script>
$(document).ready(function(){
 
 function load_unseen_notification(view = '')
 {
  $.ajax({
   url:"notifs/fetch.php",
   method:"POST",
   data:{view:view},
   dataType:"json",
   success:function(data)
   {
    $('.dropdown-menu').html(data.notification);
    if(data.unseen_notification > 0)
    {
     $('.count').html(data.unseen_notification);
    }
   }
  });
 }
 
 load_unseen_notification();
 
 $('#comment_form').on('submit', function(event){
  event.preventDefault();
  if($('#subject').val() != '' && $('#comment').val() != '')
  {
   var form_data = $(this).serialize();
   $.ajax({
    url:"notifs/insert.php",
    method:"POST",
    data:form_data,
    success:function(data)
    {
     $('#comment_form')[0].reset();
     load_unseen_notification();
    }
   });
  }
  else
  {
   alert("Both Fields are Required");
  }
 });
 
 $(document).on('click', '.dropdown-toggle', function(){
  $('.count').html('');
  load_unseen_notification('yes');
 });
 
 setInterval(function(){ 
  load_unseen_notification();; 
 }, 5000);
 
});
</script>


</body>
</html>
