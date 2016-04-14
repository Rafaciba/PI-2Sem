<?php

include("config/database.php");
include("config/session.php");



if (isset($_POST["loginButton"])){

	$email = $_POST['user'];
	$senha = $_POST['pass'];
	//requisição para verificar se o email e senha existem no banco de dados e coleta suas informações se o mesmo existir
	$query = "  SELECT *
				FROM
				professor
				WHERE email = '$email' AND senha = HASHBYTES('SHA1', '$senha')";


	//executa a requisição no banco de dados
	$result = odbc_exec($conn,$query);
	//recebe o número de linhas de resultado retornadas
	$login = odbc_num_rows($result);
	
	if($login>0){//se existem resultados é TRUE
		$credenciais = odbc_fetch_array($result);//passa o resultado encontrado para um array
		
		//passa os valores necessários para as variáveis globais
		$_SESSION["showMenu"] = FALSE;
		$_SESSION["codProfessor"]=$credenciais["codProfessor"];
		$_SESSION["nomeProfessor"]=$credenciais["nome"];
		$_SESSION["tipoProfessor"]=$credenciais["tipo"];
	}else{
		//caso não tenha resultados volta para a index informando que houve erro no login
		header("Location: index.php?i=1"); exit;
	}
	
} else if(!isset($_SESSION["codProfessor"])){
	//caso o usuário chegou a essa página sem ser pelo formulário e não está logado, volta para a index
	header("Location: index.php"); exit;	
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
<?php if (isset($_SESSION["showMenu"])&&$_SESSION["showMenu"]) { ?>
	<center>Bem vindo, <strong><?=$_SESSION["nomeProfessor"]?></strong>! Aqui é o menu! <a href="index.php?logout=1">Fazer Logout</a></center>
<?php } ?>
</body>

</html>