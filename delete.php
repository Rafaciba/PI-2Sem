<?php

include("config/database.php");
include("config/session.php");
include("config/func.php");

if(!isset($_SESSION['codProfessor'])&&!isset($_GET['cq'])){
	header("Location: index.php"); exit;	
}

$codQuestao = preg_replace("/[^0-9]/", "", $_GET['cq']);

$integridade = odbc_exec($conn,"SELECT codEvento FROM QuestaoEvento WHERE codQuestao = $codQuestao");

$imagem = odbc_exec($conn,"SELECT questao.codImagem FROM questao INNER JOIN imagem ON questao.codImagem = imagem.codImagem WHERE codQuestao = $codQuestao");

$del = 0;

if (odbc_num_rows($integridade) > 0){
	$stmt = odbc_prepare($conn,"UPDATE questao SET ativo = 0 WHERE codQuestao = ?");
}else{
	$stmtAlt = odbc_prepare($conn,"DELETE FROM alternativa WHERE codQuestao = ?");
	$del = odbc_execute($stmtAlt, array($codQuestao));
	
	if($del){
		$stmt = odbc_prepare($conn,"DELETE FROM questao WHERE codQuestao = ?");
	}
}

if ($del){
	$result = odbc_execute($stmt, array($codQuestao));
}else{
	$result = 0;
}

if(odbc_num_rows($imagem)>0&&$del){
	$codImg = odbc_fetch_array($imagem);
	$delImg = odbc_exec($conn,"DELETE FROM imagem WHERE codImagem = ".$codImg['codImagem']."");
}

header("Location: questao.php?d=$result");
exit;

?>