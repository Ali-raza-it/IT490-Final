<html>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<head>
    <meta charset="utf-8">
    <title>Registration Page</title>
    <style>
    body{
      margin: 0;
      padding: 0;
      font-family: sans-serif;
      background: linear-gradient(120deg, #9370DB,#E6E6FA);
      height: 100vh;
      overflow: hidden;
    }
    .center{
      position: absolute;
      top: 50%;
      left:50%;
      transform: translate(-50%, -50%);
      width: 600px;
      background: white;
      border-radius: 10px;
      box-shadow: 20px 20px 50px grey;
    }
    .center h1{
      text-align: center;
      color: #9370DB;
      padding: 0 0 20px 0;
      border-bottom: 1px solid #9370DB;
    }
    .center form{
      padding: 0 40px;
      box-sizing: border-box;
    }
    form .txt_field{
      position: relative;
      border-bottom: 2px solid #E6E6FA;
      margin: 30px 0;
    }
    .txt_field input{
      width: 100%;
      paddingL 0 5px;
      height: 40px;
      font-size: 16px;
      border: none;
      background: none;
      outline: none;
    }
    .txt_field label{
      position: absolute;
      top:50%;
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
    .txt_field input:focus ~ label,
    .txt_field input:valid ~ label{
      top: -5px;
      color:#9370DB;
    }
    .txt_field input:focus ~ span::before,
    .txt_field input:focus ~ span::before{
      width: 100px;   
    }
    .pass{
      margin: -5px 0 20px 5px;
      color: #a6a6a6;
      cursor: pointer;
    }
    .pass:hover{
      text-decoration: underline;
    }
    input[type="Submit"]{
      width: 100%;
      height: 50px;
      border: 1px solid;
      background: #9370DB;
      border-radius: 25px;
      font-size:18px;
      color: #e9f4fb;
      font-weight: 700;
      cursor: pointer;
      outline:none;
    }
    input[type="submit"]:hover{
      border-color: #9370DB;
      transition: .5s;
    }
    .center a{
      color: #9370DB;
      font-size: 16px;
      text-decoration: none;
      color:#9370DB;
    }
    </style>
	<script>
	 var check = function() {
      if (document.getElementById('Password').value ==
          document.getElementById('confirm_Password').value) {
          document.getElementById('message').style.color = 'green';
          document.getElementById('message').innerHTML = 'matching';
		  document.getElementById('sumbit').enabled = true
      } else {
      		document.getElementById('message').style.color = 'red';
          document.getElementById('message').innerHTML = 'not matching';
		  ocument.getElementById('submit').enabled = false
      }
  }
	</script>
</head> 
<body>
    <div class="center">
        <h1>Create a new account</h1>
		<!--- need to ask for the following items: Username	Email	Password	First Name	Last Name --->
        <form method="post" action= "../registerClient.php">
                <div class="txt_field">
                <input type="text" name="firstname" id="firstname" required>
                <label>Please enter your first name.</label>
                </div>
				<div class="txt_field">
                <input type="text" name="lastname" id="lastname" required>
                <label>Please enter your last name.</label>
                </div>
				<div class="txt_field">
                <input type="text" name="username" id="username" required>
                <label>Please choose your username.</label>
                </div>
                <div class="txt_field">
                <input type="text" name="email" id="email" required>
                <label>Please enter a valid email.</label>
                </div>
			    
                <div class="txt_field">
                <input type="password" name="password" id="password" onkeyup='check();' required>
                <label>Enter a new password.</label>
                </div>
				<div class="txt_field">
                <input type="password" name="confirm_Password" id="confirm_Password" onkeyup='check();' required>
                <label>Re-enter your new password.</label>
                </div>
				<span id='message'></span>
				</br>
          	<input type="submit" value="Submit" name="register">
			
        </form>
		<span id='message'></span>
        <h1><a href="login.php">Sign in</a></h1>
    </div>
</body>
</html>
