<?php

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
	
}

if (isset($_GET['p'])) {
	$p = preg_replace("/[^0-9]/", "", $_GET['p']);
}else {
	$p = 1;	
}

if (isset($_GET['pp'])) {
	$pp = preg_replace("/[^0-9]/", "", $_GET['pp']);
}else{
	$pp = 20;
}

$totalQuery = odbc_exec($conn,"SELECT count(*) as total FROM questao q 
			INNER JOIN assunto a ON q.codAssunto = a.codAssunto
			JOIN tipoquestao tq ON q.codTipoQuestao = tq.codTipoQuestao
			JOIN professor p ON q.codProfessor = p.codProfessor");
$totalQst = odbc_fetch_array($totalQuery);

$tt = $totalQst['total'];

/*
option value="maisRecente">Mais recente</option>
                        <option value="maisAntigo">Mais antigo</option>
                        <option value="alfabetico">A - Z</option>
                        <option value="alfabetico2">Z - A</option>
                        <option value="assunto">Assunto</option>
                        <option value="tipoQuestao">Tipo da quest&atilde;o</option>
                        <option value="professor">Professor</option>
                        <option value="dificuldade">Dificuldade</option>
                        <option value="ativo">Ativo</option>
*/

if (isset($_GET['ordem'])) {
	switch($_GET['ordem']){
		case "maisRecente": $ordem = "q.codQuestao DESC"; break;
		case "maisAntigo": $ordem = "q.codQuestao ASC"; break;
		case "alfabetico": $ordem = "q.textoQuestao ASC"; break;
		case "alfabetico2": $ordem = "q.textoQuestao DESC"; break;
		case "assunto": $ordem = "assunto"; break;
		case "tipoQuestao": $ordem = "tipoquestao"; break;
		case "professor": $ordem = "p.nome"; break;
		case "dificuldade": $ordem = "dificuldade"; break;
		case "ativo": $ordem = "ativo"; break;
	}
}else{
	$ordem = "codQuestao DESC";
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
			ORDER BY ".$ordem." OFFSET (".(($pp * $p) - $pp).") ROWS FETCH NEXT (".$pp.") ROWS ONLY";
$result = odbc_exec($conn,$query);
?>