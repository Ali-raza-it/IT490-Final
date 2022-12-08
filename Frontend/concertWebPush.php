<?php // code referenced from https://github.com/MarkJamesHoward/push  
session_start();

if(isset($_SESSION['response'])){
    $response = $_SESSION['response']; 
    
    $uname = $response[0];
    $fname = $response[1];
    $lname = $response[2];
    $email = $response[3];
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Document</title>
  </head>
  <body>
    <button onclick="subscribe()">Click to get real time notifications</button>

    <script>
      async function subscribe() {
        let sw = await navigator.serviceWorker.ready;
        let push = await sw.pushManager.subscribe({
          userVisibleOnly: true,
          applicationServerKey:
            'BBZY7Q3KEtZArAAWMLi_qzWHbH4vAoqPpIXnRhmlUaw0PVs1Kt_2fgLhuaVI5i8MWASBKx3d6W6UoH2U3qChw9U'
        });
        console.log(JSON.stringify(push));
      }

      if ('serviceWorker' in navigator) {
        addEventListener('load', async () => {
          let sw = await navigator.serviceWorker.register('./sw.js');
          console.log(sw);
        });
      }
    </script>
  </body>
</html>
