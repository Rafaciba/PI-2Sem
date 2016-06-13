<?php
$codQuestao = preg_replace("/[^0-9]/", "", $_GET['cq']);

$stmt = odbc_prepare($conn,"SELECT q.codQuestao, q.textoQuestao, q.dificuldade, q.codAssunto, q.codTipoQuestao, 
					a.descricao AS assunto, 
					tq.descricao AS tipoquestao, 
					i.tituloImagem, i.bitmapImagem
					FROM questao q 
					INNER JOIN assunto a ON q.codAssunto = a.codAssunto
					JOIN tipoquestao tq ON q.codTipoQuestao = tq.codTipoQuestao
					LEFT JOIN imagem i ON q.codImagem = i.codImagem
					WHERE q.codQuestao = ?");
$resultado = odbc_execute($stmt, array($codQuestao));
odbc_binmode ($stmt, ODBC_BINMODE_RETURN);
odbc_longreadlen ($stmt, 9000000);//ini_set ('odbc.defaultlrl', 9000000);
$questao = odbc_fetch_array($stmt);
?>