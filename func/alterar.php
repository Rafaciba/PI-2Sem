<?php
$codQuestao = preg_replace("/[^0-9]/", "", $_GET['cq']);

if (isset($_POST['cadastrar'])){
	//DEIXA OS DADOS NO FORMATO CORRETO PARA O BD
	$_POST = array_map('utf8_decode', $_POST);
	//BEGIN TRANSACTION
	$transaction = odbc_exec($conn, "BEGIN TRANSACTION UPDTQ");
	//IMAGEM
	if(isset($_FILES['imagemQuestao'])&&$_FILES['imagemQuestao']['size'] > 0){
		if(	substr($_FILES['imagemQuestao']['type'], 0, 5) == 'image' &&
			$_FILES['imagemQuestao']['error'] == 0 &&
			($_FILES['imagemQuestao']['size'] > 0 && $_FILES['imagemQuestao']['size'] < 9000000)){
			
			$file = fopen($_FILES['imagemQuestao']['tmp_name'],'rb');
			$fileParaDB = fread($file, filesize($_FILES['imagemQuestao']['tmp_name']));
			fclose($file);
			
			$stmt = odbc_prepare($conn,'INSERT INTO imagem (tituloImagem,bitmapImagem) OUTPUT INSERTED.codImagem VALUES (?,?)');
			$resultI = odbc_execute($stmt,array($_POST['titImagem'], $fileParaDB));
			
			if ($resultI) {
				//QUESTAO
				$insertImg = odbc_fetch_array($stmt);
				$stmtQ = odbc_prepare($conn,"UPDATE questao SET textoQuestao=?, codAssunto=?, codImagem=?, codTipoQuestao=?, dificuldade=? WHERE codQuestao = ?");
				$resultQ = odbc_execute($stmtQ,array($_POST['textoQuestao'],$_POST['assunto'],$insertImg['codImagem'],$_POST['tipoQuestao'],$_POST['dificuldade'],$codQuestao));
			}
		}else{
			if($_FILES['imagemQuestao']['size'] > 9000000){
				$base = log($_FILES['imagemQuestao']['size']) / log(1024);
				$sufixo = array("", "K", "M", "G", "T");
				$tam_em_mb = round(pow(1024, $base - floor($base)),2).$sufixo[floor($base)];
				echo 'Tamanho m&aacute;ximo de imagem 9 Mb. Tamanho da imagem enviada: '.$tam_em_mb;
			}else{
				echo 'S&oacute; s&atilde;o aceitos arquivos de imagem. Tamanho da imagem: '.$_FILES['imagemQuestao']['size'];
			}
		}
	}else{
		//QUESTAO
		$stmtQ = odbc_prepare($conn,"UPDATE questao SET textoQuestao=?, codAssunto=?, codTipoQuestao=?, dificuldade=? WHERE codQuestao = ?");
		$resultQ = odbc_execute($stmtQ,array($_POST['textoQuestao'],$_POST['assunto'],$_POST['tipoQuestao'],$_POST['dificuldade'],$codQuestao));
	}
	//ALTERNATIVAS
	if ($resultQ) {
		$stmtA = odbc_prepare($conn, "SELECT codAlternativa FROM alternativa WHERE codQuestao = ?");
		$resultA = odbc_execute($stmtA, array($codQuestao));
		
		$qtdAlt = odbc_num_rows($stmtA);
		
		if ($qtdAlt <= $_POST['qtdAlternativas']) {
			for ($i=1;$i<=$qtdAlt;$i++) {
				$stmt = odbc_prepare($conn,"UPDATE alternativa SET textoAlternativa = ?, correta = ? WHERE codQuestao = ? AND codAlternativa = ?");
				if ($_POST['correta']==$i){$correta=1;}else{$correta=0;}
				switch($_POST['tipoQuestao']){
					case "A": 
						$arr = array($_POST['alternativa_'.$i],$correta,$codQuestao,$i);
						break;
					case "T": 
						$arr = array($_POST['resposta_'.$i],1,$codQuestao,$i);
						break;
					case "V": 
						$arr = array($_POST['respostaVF'],$_POST['correta'],$codQuestao,$i);
						break;
				}
				$result = odbc_execute($stmt, $arr);
			}
			for ($i=$qtdAlt+1;$i<=$_POST['qtdAlternativas'];$i++) {
				$stmt = odbc_prepare($conn,"INSERT INTO alternativa (codQuestao, codAlternativa,textoAlternativa,correta)
											VALUES (?,?,?,?)");
				if ($_POST['correta']==$i){$correta=1;}else{$correta=0;}
				switch($_POST['tipoQuestao']){
					case "A": 
						$arr = array($codQuestao,$i,$_POST['alternativa_'.$i],$correta);
						break;
					case "T": 
						$arr = array($codQuestao,$i,$_POST['resposta_'.$i],1);
						break;
					case "V": 
						$arr = array($codQuestao,$i,$_POST['respostaVF'],$_POST['correta']);
						break;
				}
				$result = odbc_execute($stmt, $arr);
			}
			
		}else {
			for ($i=1;$i<=$_POST['qtdAlternativas'];$i++) {
				$stmt = odbc_prepare($conn,"UPDATE alternativa SET textoAlternativa = ?, correta = ? WHERE codQuestao = ? AND codAlternativa = ?");
				if ($_POST['correta']==$i){$correta=1;}else{$correta=0;}
				switch($_POST['tipoQuestao']){
					case "A": 
						$arr = array($_POST['alternativa_'.$i],$correta,$codQuestao,$i);
						break;
					case "T": 
						$arr = array($_POST['resposta_'.$i],1,$codQuestao,$i);
						break;
					case "V": 
						$arr = array($_POST['respostaVF'],$_POST['correta'],$codQuestao,$i);
						break;
				}
				$result = odbc_execute($stmt, $arr);
			}
			for ($i=$_POST['qtdAlternativas']+1;$i<=$qtdAlt;$i++) {
				$stmt = odbc_prepare($conn,"DELETE FROM alternativa WHERE codQuestao = ? AND codAlternativa = ?");
				$result = odbc_execute($stmt, array($codQuestao,$i));
			}
		}
		//COMMIT TRANSACTION
		$commit = odbc_exec($conn,"COMMIT TRANSACTION UPDTQ");
		if ($commit) {
			header("Location: questao.php?s=4");
			exit;
		}
	}
}

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