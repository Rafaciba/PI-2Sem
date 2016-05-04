<?php

include("config/database.php");
include("config/session.php");
include("config/func.php");

if(!isset($_SESSION["codProfessor"])){
	//caso o usuário chegou a essa página e não está logado, volta para a index
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
<section>
	<h1>Questões</h1>
</section>
<section>
	<?php 
		$p = "0";
		$pp = "20";
		$query = "SELECT q.codQuestao, q.textoQuestao, q.ativo, q.dificuldade, a.descricao AS assunto, tq.descricao AS tipoquestao, p.nome
		FROM questao q 
		INNER JOIN assunto a ON q.codAssunto = a.codAssunto
		JOIN tipoquestao tq ON q.codTipoQuestao = tq.codTipoQuestao
		JOIN professor p ON q.codProfessor = p.codProfessor
		ORDER BY codQuestao OFFSET $p ROWS FETCH NEXT (($p+1) *$pp) ROWS ONLY";
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
			echo "Não existem questões cadastradas!";
		}
	?>
</section>
</body>

</html>