<?php

include("config/session.php");

if (isset($_GET["i"])&&($_GET["i"]==1)) {
	$msg = "Usuário ou senha inválidos!";
}else {
	$msg = "";
}

if (isset($_SESSION["codProfessor"])){
	header("Location: login.php"); exit;
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link href='https://fonts.googleapis.com/css?family=Roboto:100,400,500' rel='stylesheet' type='text/css'> 
<link rel="stylesheet" type="text/css" href="css/style.css">
<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script type="text/javascript" src="js/javascript.js"></script>
 <style>
	.wrapper { 
	background: #385965;
	background: -webkit-linear-gradient(top left, #385965 0%, #00bbd4 100%);
	background: -moz-linear-gradient(top left, #385965 0%, #00bbd4 100%);
	background: -o-linear-gradient(top left, #385965 0%, #00bbd4 100%);
	background: linear-gradient(to bottom right, #385965 0%, #00bbd4 100%);	
	position: absolute; 
	left: 0;
	top: 0;
	width: 100%;
	height: 100%;
	overflow: hidden;
}

.container { 
	width: 600px;
	padding: 20px;
	height: 400px;
	position: absolute;
	top: calc(50% - 220px); 
	left: calc(50% - 300px); 

}

.total {
  box-shadow: 0 19px 38px rgba(0,0,0,0.30), 0 8px 12px rgba(0,0,0,0.22);
}

.title {
	font-family: 'Roboto', sans-serif;
	color: #fff;	
	font-weight: 200;
	font-size: 30px;
	text-align: center;
	margin: 0 auto;
	padding-top: 10px;
	width: 300px;
}

.title span {
	font-weight: 500;
}

.errorMsg {
	font-family: 'Roboto', sans-serif;
	color: #fff;	
	font-weight: 400;
	font-size: 18px;
	text-align: center;
	margin: 0 auto;
	padding-top: 10px;
	width: 300px;
}

.form {
	width: 300px;
	margin: 0 auto; 
	padding: 10px;
}

input{
	display: block;
	appearance: none;
	border: 1px solid #fff; 
	width: 209px;
	border-radius: 3px; 
	margin: 0 auto 10px auto;
	padding: 15px 37px;
	font-family: 'Roboto', sans-serif;
	font-size: 17px;
	background-color: rgba(255,255,255, 0.4);

}	 

::-webkit-input-placeholder {
   color: white;
   font-family: 'Roboto', sans-serif;
   font-weight: 100;
}

.user {
	background-image: url(../img/social.png);
    background-position: 7px;
    background-repeat: no-repeat; 
}


.password {
	background-image: url(../img/security.png);
    background-position: 7px;
    background-repeat: no-repeat; 
}


.button {
  background: transparent; 
  cursor: pointer; 
  border: 1px solid #fff; 
  padding: 10px; 
  width: 280px;
  text-align: center;
  transition-property: background, border-radius;
  transition-duration: 1s;
  transition-timing-function: linear;
  color: #fff;
  font-family: 'Roboto', sans-serif; 
  margin: 10px;
  font-size: 17px;

}
.button:hover {
  border-bottom: 3px solid #fff;
  border-radius: 3px;
}
 </style>
<title>PI - SENAC</title>
</head>

<body>
 	<div class="wrapper">
 		<div class="container"> 
 			<div class="title">
 				Bem <span>vindo!</span>
 			</div>
			<div class="errorMsg">
				<?=$msg?>
			</div>
 			<form class="form" id="frmLogin" name="frmLogin" action="questao.php" method="post">  
				<input type="text" placeholder="Login" class="user" id="user" name="user" required> 
				<input type="password" placeholder="Senha" class="password" id="pass" name="pass" required>
				<button type="submit" id="loginButton" name="loginButton" class="button">Login</button>
			</form>
 		</div>  
 	</div>
</body>

</html>



