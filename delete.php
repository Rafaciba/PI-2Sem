<?php

include("config/database.php");
include("config/session.php");
include("config/func.php");

if(!isset($_SESSION['codProfessor'])&&!isset($_GET['cq'])){
	header("Location: index.php"); exit;	
}

$codQuestao = preg_replace("/[^0-9]/", "", $_GET['cq']);

$integridade = odbc_exec($conn,"SELECT codEvento FROM QuestaoEvento WHERE codQuestao = $codQuestao");

if (odbc_num_rows($integridade) > 0){
	$stmt = odbc_prepare($conn,"UPDATE questao SET ativo = 0 WHERE codQuestao = ?");
}else{
	$stmtAlt = odbc_prepare($conn,"DELETE FROM alternativa WHERE codQuestao = ?");
	$del = odbc_execute($stmtAlt, array($codQuestao));
	
	if($del){
		$stmt = odbc_prepare($conn,"DELETE FROM questao WHERE codQuestao = ?");
	}
}

$result = odbc_execute($stmt, array($codQuestao));

header("Location: questao.php?d=$result");
exit;

?>