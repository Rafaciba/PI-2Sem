<?php

include("config/database.php");
include("config/session.php");
include("config/func.php");

if(!isset($_SESSION["codProfessor"])){
	//caso o usuário chegou a essa página e não está logado, volta para a index
	header("Location: index.php"); exit;	
}

if (isset($_GET[p])) {
	$p = $_GET[p];
}else {
	$p = 1;	
}

if (isset($_GET[pp])) {
	$pp = $_GET[pp];
}else{
	$pp = 20;
}

if (isset($_GET[ordem])) {
	$ordem = $_GET[ordem];
}else{
	$ordem = "codQuestao";
}

if (isset($_GET[busca])) {
	$busca = $_GET[busca];
	$buscaQuery = "WHERE textoQuestao LIKE '%$busca%' OR assunto LIKE '%$busca%' OR tipoquestao LIKE '%$busca%' OR nome LIKE '%$busca%' OR dificuldade LIKE '%$busca%'";
}else{
	$buscaQuery = "";
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
<section>
	<h1>Questões</h1>
</section>
<section>
	<?php 
		$query = "SELECT q.codQuestao, q.textoQuestao, q.ativo, q.dificuldade, a.descricao AS assunto, tq.descricao AS tipoquestao, p.nome
		FROM questao q 
		INNER JOIN assunto a ON q.codAssunto = a.codAssunto
		JOIN tipoquestao tq ON q.codTipoQuestao = tq.codTipoQuestao
		JOIN professor p ON q.codProfessor = p.codProfessor 
		$buscaQuery 
		ORDER BY $ordem OFFSET ($p-1) ROWS FETCH NEXT ($p * $pp) ROWS ONLY";
		//$query = "SELECT * FROM questao WHERE ativo = '1' ORDER BY codQuestao OFFSET 0 ROWS FETCH NEXT 20 ROWS ONLY";
		//SELECT q.codQuestao, q.textoQuestao, q.ativo, q.dificuldade, a.descricao, tq.descricao FROM questao q INNER JOIN assunto a ON q.codAssunto = a.codAssunto
		//JOIN tipoquestao tq ON q.codTipoQuestao = tq.codTipoQuestao 
		//executa a requisição no banco de dados
		$result = odbc_exec($conn,$query);
		if(odbc_num_rows($result)>0){
			while($area = odbc_fetch_array($result)){
	?>
	<div><?=$area["codQuestao"]?> | <?=$area["textoQuestao"]?> | <?=$area["assunto"]?> | <?=$area["tipoquestao"]?> | <?=$area["nome"]?> | <?=$area["dificuldade"]?> | <?=$area["ativo"]?></div>
	<?php
			}
		} else {
			echo "Nenhuma questão foi encontrada!";
		}
	?>
</section>
</body>

</html>