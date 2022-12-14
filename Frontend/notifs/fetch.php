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

include('connect.php');


if(isset($_POST['view'])){

// $con = mysqli_connect("localhost", "root", "", "notif");

if($_POST["view"] != '')
{
    $update_query = "UPDATE comments SET comment_status = 1 WHERE comment_status=0 and userR = '$uname' AND `dt` < NOW() + INTERVAL 2 MONTH;";
    mysqli_query($con, $update_query);
}
$query = "SELECT * FROM comments WHERE userR = '$uname' AND `dt` < NOW() + INTERVAL 2 MONTH ORDER BY comment_id DESC LIMIT 10 ";
$result = mysqli_query($con, $query);
$output = '';
if(mysqli_num_rows($result) > 0)
{
 while($row = mysqli_fetch_array($result))
 {
 $lnk = $row["lnk"];
 
   $output .= '
   <li>
   <a href="'.$lnk.'">
   <strong>'.$row["comment_subject"].'</strong><br />
   <small><em>'.$row["comment_text"].'</em></small>
   </a>
   </li>
   ';

 }
}
else{
     $output .= '
     <li><a href="#" class="text-bold text-italic">No Noti Found</a></li>';
}



$status_query = "SELECT * FROM comments WHERE comment_status=0 and userR = '$uname' AND `dt` < NOW() + INTERVAL 2 MONTH;";
$result_query = mysqli_query($con, $status_query);
$count = mysqli_num_rows($result_query);
$data = array(
    'notification' => $output,
    'unseen_notification'  => $count
);

echo json_encode($data);

}

?>
