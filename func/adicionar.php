<?php 
if (isset($_POST['cadastrar'])){
	//BEGIN TRANSACTION
	$transaction = odbc_exec($conn,"BEGIN TRANSACTION ADDQ");
	//IMAGEM
	if(isset($_FILES['imagemQuestao'])&&$_FILES['imagemQuestao']['size'] > 0){
		if(	substr($_FILES['imagemQuestao']['type'], 0, 5) == 'image' &&
			$_FILES['imagemQuestao']['error'] == 0 &&
			($_FILES['imagemQuestao']['size'] > 0 && $_FILES['imagemQuestao']['size'] < 9000000)){
			//Arquivo recebido com sucesso
			
			$file = fopen($_FILES['imagemQuestao']['tmp_name'],'rb');
			$fileParaDB = fread($file, filesize($_FILES['imagemQuestao']['tmp_name']));
			fclose($file);
			
			$stmt = odbc_prepare($conn,'INSERT INTO imagem (tituloImagem,bitmapImagem) OUTPUT INSERTED.codImagem VALUES (?,?)');
			$resultI = odbc_execute($stmt,array($_POST['titImagem'], $fileParaDB));
			
			if ($resultI) {
				$insertImg = odbc_fetch_array($stmt);
			}
		}else{
			if($_FILES['imagemQuestao']['size'] > 9000000){
				$base = log($_FILES['imagemQuestao']['size']) / log(1024);
				$sufixo = array("", "K", "M", "G", "T");
				$tam_em_mb = round(pow(1024, $base - floor($base)),2).$sufixo[floor($base)];
				//echo 'Tamanho m&aacute;ximo de imagem 9 Mb. Tamanho da imagem enviada: '.$tam_em_mb;
			}else{
				//echo 'S&oacute; s&atilde;o aceitos arquivos de imagem. Tamanho da imagem: '.$_FILES['imagemQuestao']['size'];
			}
			$insertImg = array('codImagem' => NULL);
		}
	}else{
		$insertImg = array('codImagem' => NULL);
	}
	//QUESTAO
	$stmtQ = odbc_prepare($conn,"INSERT INTO questao (textoQuestao,codAssunto,codImagem,codTipoQuestao,codProfessor,ativo,dificuldade) OUTPUT INSERTED.codQuestao VALUES (?,?,?,?,?,1,?)");
	$resultQ = odbc_execute($stmtQ,array($_POST['textoQuestao'],$_POST['assunto'],$insertImg['codImagem'],$_POST['tipoQuestao'],$_SESSION['codProfessor'],$_POST['dificuldade']));

	if ($resultQ) {
		$insertQuestao = odbc_fetch_array($stmtQ);
		for ($i=1;$i<=$_POST['qtdAlternativas'];$i++) {
			$stmt = odbc_prepare($conn,"INSERT INTO alternativa (codQuestao, codAlternativa,textoAlternativa,correta) VALUES (?,?,?,?)");
				
			if (!isset($_POST['correta_'.$i])){$correta=0;}else{$correta=$_POST['correta_'.$i];}
			switch($_POST['tipoQuestao']){
				case "A": 
					$arr = array($insertQuestao['codQuestao'],$i,$_POST['alternativa_'.$i],$correta);
					break;
				case "T": 
					$arr = array($insertQuestao['codQuestao'],$i,$_POST['resposta_'.$i],1);
					break;
				case "V": 
					$arr = array($insertQuestao['codQuestao'],$i,$_POST['respostaVF'],$_POST['correta']);
					break;
			}
			$result = odbc_execute($stmt, $arr);
		}
		$commit = odbc_exec($conn,"COMMIT TRANSACTION ADDQ");
	}
}
?>