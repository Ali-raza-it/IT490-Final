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
      include "nav.php";
      
  }
  
  if(isset($_SESSION['userSearched'])){
      $sruser = $_SESSION['userSearched'];
      
      $sruname = $sruser[0][0];
      $srfname = $sruser[0][1];
      $srlname = $sruser[0][2];
      $sremail = $sruser[0][3];
      
  }
  
       if(!isset($_SESSION['friendlist'])){
     header("location: ../getFriendsClient.php?username=".$uname);
  }
    if(isset($_SESSION['friendlist'])){
      $FL = $_SESSION['friendlist'];
      
$friends = false;
	
	foreach ($FL[0] as $user) {
	  
		if($sruname == $user){
		$friends = true;
		}
	}
  }
  
  
  include("notifs/connect.php");
?> <html lang="en">
  <head>
    <style>
      body {
        margin: 0;
        padding: 0;
        font-family: sans-serif;
        background: linear-gradient(120deg, #9370DB, #E6E6FA);
        height: 100vh;
        overflow: hidden;
      }

      .center {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 400px;
        background: white;
        border-radius: 10px;
        box-shadow: 20px 20px 50px grey;
      }

      .center h1 {
        text-align: center;
        color: #9370DB;
        padding: 0 0 20px 0;
        border-bottom: 1px #E6E6FA;
      }

      .center form {
        padding: 0 40px;
        box-sizing: border-box;
      }

      form .txt_field {
        position: relative;
        border-bottom: 2px solid #E6E6FA;
        margin: 30px 0;
      }

      .txt_field input {
        width: 100%;
        padding 0 5px;
        height: 40px;
        font-size: 16px;
        border: none;
        background: none;
        outline: none;
      }

      .txt_field label {
        position: absolute;
        top: 50%;
        left: 5px;
        color: #9370DB;
        transform: translateY(-50%);
        font-size: 16px;
        pointer-events: none;
        transition: .5s;
      }

      .txt_field span::before {
        content: ' ';
        position: absolute;
        top: 40px;
        left: 0;
        width: 0%;
        height: 2px;
        background: #9370DB;
        transition: .5s;
      }

      .txt_field input:focus~label,
      .txt_field input:valid~label {
        top: -5px;
        color: #9370DB;
      }

      .txt_field input:focus~span::before,
      .txt_field input:focus~span::before {
        width: 100px;
      }

      .pass {
        margin: -5px 0 20px 5px;
        color: #a6a6a6;
        cursor: pointer;
      }

      .pass:hover {
        text-decoration: underline;
      }

      input[type="Submit"] {
        width: 100%;
        height: 50px;
        border: 1px solid;
        background: #9370DB;
        font-size: 18px;
        color: #e9f4fb;
        font-weight: 700;
        cursor: pointer;
        outline: none;
      }

      input[type="submit"]:hover {
        border-color: #9370DB;
        transition: .5s;
      }

      .center a {
        color: #9370DB;
        font-size: 16px;
        text-decoration: none;
        color: #9370DB;
      }

      <link rel="stylesheet"href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"><script src="https://cdn.jsdelivr.net/npm/jquery@3.6.1/dist/jquery.slim.min.js"></script><script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script><script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    </style>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css'>
    <link rel=”stylesheet” href=”https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css” />
  </head>
  <body>
    <section>
      <div class="rt-container">
        <div class="col-rt-12">
          <div class="Scriptcontent">
            <!-- Student Profile -->
            <div class="student-profile py-4">
              <div class="container">
                <div class="row">
                  <div class="col-lg-4">
                    <div class="card shadow-sm">
                      <div class="card-header bg-transparent text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="300" height="300" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                          <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z" />
                        </svg>
                        <h3> <?php echo $sruname; ?> </h3>
                        <div class="card-body"> <?php
	$query = "select * from comments where userR='".$uname."' and userS='".$sruname."'";
	$result = mysqli_query($con, $query);
	if(mysqli_num_rows($result) > 0)
	{
	?> <form action="accept.php" method="post">
            <input type="hidden" name="accept" value=true>
            <input type="hidden" name="friendusername" value=<?php echo $sruname; ?>>
            <button type="submit">Accept</button>
            <input type="hidden" name="delete" value=true>
            <button type="submit" formaction="reject.php">Reject</button> 
        <?php } 
	else
	 { 
	$query2 = "select * from comments where userR='".$sruname."' and userS='".$uname."'";
	$result2 = mysqli_query($con, $query2);
	if(mysqli_num_rows($result2) > 0) { 
	
	?> <h1>request sent</h1> 
	
	<?php }elseif($friends==true){ ?> 
	
	<form action="../removeFriendClient.php" method="post">
        <input type="hidden" name="username" value=<?php echo $uname; ?>>
        <input type="hidden" name="friendusername" value=<?php echo $sruname; ?>>
        <button type="submit">Remove Friend</button>
        
             <?php }  else { 
	 ?> <form action="sendReq.php" method="post">
                              <input type="hidden" name="sendReq" value=true>
                              <button type="submit">Add Friend</button> 
        <?php }}
                             
                               ?> </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-8">
                    <div class="card shadow-sm">
                      <div class="card-header bg-transparent border-0">
                        <h3 class="mb-0">
                          <i class="far fa-clone pr-1"></i>General Information
                        </h3>
                      </div>
                      <div class="card-body pt-0">
                        <table class="table table-bordered">
                          <tr>
                            <th width="30%">First Name</th>
                            <td width="2%">:</td>
                            <td> <?php echo $srfname; ?> </td>
                          </tr>
                          <tr>
                            <th width="30%">Last Name</th>
                            <td width="2%">:</td>
                            <td> <?php echo $srlname; ?> </td>
                          </tr>
                          <tr>
                            <th width="30%">Email</th>
                            <td width="2%">:</td>
                            <td> <?php echo $sremail; ?> </td>
                          </tr>
                        </table>
                      </div>
                    </div>
                    <div style="height: 26px"></div>
                    <div class="card shadow-sm">
                      <div class="card-header bg-transparent border-0">
                        <h3 class="mb-0">
                          <i class="far fa-clone pr-1"></i>Other Information
                        </h3>
                      </div>
                      <div class="card-body pt-0">
                        <p>High Scores or Music likes could go here!</p>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- partial -->
          </div>
        </div>
      </div>
    </section>
    <!-- Analytics -->
  </body>
</html>
