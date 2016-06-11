<?php

include("config/database.php");
include("config/session.php");
include("config/func.php");

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
		$_SESSION["showMenu"] = TRUE;
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

if (isset($_GET['p'])) {
	$p = $_GET['p'];
}else {
	$p = 1;	
}

if (isset($_GET['pp'])) {
	$pp = $_GET['pp'];
}else{
	$pp = 20;
}

$totalQuery = odbc_exec($conn,"SELECT count(*) as total FROM questao q 
			INNER JOIN assunto a ON q.codAssunto = a.codAssunto
			JOIN tipoquestao tq ON q.codTipoQuestao = tq.codTipoQuestao
			JOIN professor p ON q.codProfessor = p.codProfessor");
$totalQst = odbc_fetch_array($totalQuery);

$tt = $totalQst['total'];

if (isset($_GET['ordem'])) {
	$ordem = $_GET['ordem'];
}else{
	$ordem = "codQuestao";
}

if (isset($_GET['busca'])) {
	$busca = $_GET['busca'];
	$buscaQuery = "WHERE textoQuestao LIKE '%$busca%' OR assunto LIKE '%$busca%' OR tipoquestao LIKE '%$busca%' OR nome LIKE '%$busca%' OR dificuldade LIKE '%$busca%'";
}else{
	$buscaQuery = "";
}

$query = "SELECT q.codQuestao, q.textoQuestao, q.ativo, q.dificuldade, q.codProfessor, a.descricao AS assunto, tq.descricao AS tipoquestao, p.nome
			FROM questao q 
			INNER JOIN assunto a ON q.codAssunto = a.codAssunto
			JOIN tipoquestao tq ON q.codTipoQuestao = tq.codTipoQuestao
			JOIN professor p ON q.codProfessor = p.codProfessor 
			$buscaQuery 
			ORDER BY q.codQuestao DESC OFFSET (".(($pp * $p) - $pp).") ROWS FETCH NEXT (".$pp.") ROWS ONLY";
$result = odbc_exec($conn,$query);

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="ISO-8859-1">
<link href='https://fonts.googleapis.com/css?family=Roboto:100,400,500' rel='stylesheet' type='text/css'> 
<link rel="stylesheet" type="text/css" href="css/style.css">
<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script type="text/javascript" src="js/javascript.js"></script>
 
<title>PI - SENAC</title>
</head>

<body style="background-color: #385965;">
<?php include("menu.php"); ?>
<div class="content total">
	<div class="content white">
		<section> 
			<div class="adicionar">
				<a href="addQuestao.php" class="button-cadastrar button-block"/>Adicionar +</a>
			</div>
			<div class="listagem-question">
				Listagem das Quest&otilde;es
			</div>
			<table class="responsive-table">  
				<?php 
					if(odbc_num_rows($result)>0){
				?>
			    <thead>
				  
					<?php
					if (isset($_GET['d'])){
						echo '<tr><th colspan="7">';
						if($_GET['d']==1){
							echo "Quest&atilde;o deletada/desativada com sucesso!";
						}else{
							echo "Erro ao tentar deletar/desativar a quest&atilde;o.";
						}
						echo '</th></tr>';
					}
					?>
			      <tr> 
			        <th scope="col">Editar Quest&atilde;o</th>
			        <th scope="col">Enunciado</th>
			        <th scope="col">Assunto</th>
			        <th scope="col">Tipo</th>
			        <th scope="col">Professor/Autor</th>
			        <th scope="col">Dificuldade</th>
			        <th scope="col">Ativo</th>

			      </tr>
			    </thead>
			     
			    <tbody>
				  <?php
						while($area = odbc_fetch_array($result)){
				  ?>
			      <tr> 
			        <td data-title="">
						<a href="update.php?cq=<?=$area["codQuestao"]?>" class="edit"></a>
						<a href="delete.php?cq=<?=$area["codQuestao"]?>" class="delete"></a>
			        </td>
			        <td data-title=""><?=$area["textoQuestao"]?></td>
			        <td data-title=""><?=$area["assunto"]?></td>
			        <td data-title="" data-type=""><?=$area["tipoquestao"]?></td>
			        <td data-title="" data-type=""><?=$area["nome"]?></td>
			        <td data-title="" data-type=""><?=$area["dificuldade"]?></td>
			        <td data-title="" data-type=""><?=($area["ativo"])?'<div class="ativo"></div>':'<div class="desativo"></div>'?>
			        </td>
			      </tr>
				<?php } ?>
				</tbody>
				<?php
					} else {
						echo "Nenhuma questão foi encontrada!";
					}
				?>
	  		</table>
		</section>
		<section>
			<div>
			<?php
				for($j=1;$j<=($tt/$pp);$j++){
					echo '<a href="questao.php?p='.$j.'">'.$j.'</a>';
				}
			?>
			</div>
		</section>
	</div>
</div>
</body>

</html>