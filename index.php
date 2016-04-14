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
 			<form class="form" id="frmLogin" name="frmLogin" action="login.php" method="post">  
				<input type="text" placeholder="Login" class="user" id="user" name="user" required> 
				<input type="password" placeholder="Senha" class="password" id="pass" name="pass" required>
				<button type="submit" id="loginButton" name="loginButton" class="button">Login</button>
			</form>
 		</div>  
 	</div>
</body>

</html>



