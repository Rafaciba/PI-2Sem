<?php

include("config/database.php");
include("config/session.php");
include("config/func.php");

/*if(!isset($_SESSION["codProfessor"])){
	//caso o usuário chegou a essa página e não está logado, volta para a index
	header("Location: index.php"); exit;	
}*/

if (isset($_POST['cadastrar'])){
	echo "Cadastrando questão!<br>";
	//IMAGEM
	if(isset($_FILES['imagemQuestao'])){
		if(	substr($_FILES['imagemQuestao']['type'], 0, 5) == 'image' &&
			$_FILES['imagemQuestao']['error'] == 0 &&
			($_FILES['imagemQuestao']['size'] > 0 && $_FILES['imagemQuestao']['size'] < 9000000)){
			//print_r($_FILES);
			echo 'Arquivo recebido com sucesso<BR>';
			
			$file = fopen($_FILES['imagemQuestao']['tmp_name'],'rb');
			$fileParaDB = fread($file, filesize($_FILES['imagemQuestao']['tmp_name']));
			fclose($file);
			
			//echo $fileParaDB;
			
			//CONVERT(varbinary(max),'$fileParaDB')
			$stmt = odbc_prepare($conn,'INSERT INTO imagem (tituloImagem,bitmapImagem) VALUES (?,?)');
			$result = odbc_execute($stmt,array('Teste Imagem 5', $fileParaDB));
			//$query = "INSERT INTO imagem (tituloImagem,bitmapImagem) VALUES ('".$_POST['titImagem']."',CONVERT(varbinary(max),'$aux'))";
			//$result = odbc_exec($conn,$query) or die(odbc_errormsg($conn));
			if ($result) {
				$stmt = odbc_prepare($conn,"INSERT INTO questao (textoQuestao,codAssunto,codImagem,codTipoQuestao,codProfessor,ativo,dificuldade) 
								VALUES (?,?,IDENT_CURRENT('IMAGEM'),?,?,1,?)");
				$result = odbc_execute($stmt,array($_POST['textoQuestao'],$_POST['assunto'],$_POST['tipoQuestao'],$_SESSION['codProfessor'],$_POST['dificuldade']));
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
			$idImagem = 'NULL';
		}
	}else{
		$stmt = odbc_prepare($conn,"INSERT INTO questao (textoQuestao,codAssunto,codImagem,codTipoQuestao,codProfessor,ativo,dificuldade) 
								VALUES (?,?,NULL,?,?,1,?)");
		$result = odbc_execute($stmt,array($_POST['textoQuestao'],$_POST['assunto'],$_POST['tipoQuestao'],$_SESSION['codProfessor'],$_POST['dificuldade']));
	}
	//QUESTAO
	/*$stmt = odbc_prepare($conn,"INSERT INTO questao (textoQuestao,codAssunto,codImagem,codTipoQuestao,codProfessor,ativo,dificuldade) 
								VALUES (?,?,IDENT_CURRENT('IMAGEM'),?,?,1,?)");
	$result = odbc_execute($stmt,array($_POST['textoQuestao'],$_POST['assunto'],$_POST['tipoQuestao'],$_SESSION['codProfessor'],$_POST['dificuldade']));
	/*$query = "INSERT INTO Questao (textoQuestao,codAssunto,codImagem,codTipoQuestao,codProfessor,ativo,dificuldade) 
	VALUES ('".$_POST['textoQuestao']."','".$_POST['assunto']."','$idImagem','".$_POST['tipoQuestao']."','".$_SESSION['codProfessor']."','".$_POST['ativo']."','".$_POST['dificuldade']."')";
	$result = odbc_exec($conn,$query);
	print_r($result);*/
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link href='https://fonts.googleapis.com/css?family=Roboto:100,400,500' rel='stylesheet' type='text/css'>
<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script type="text/javascript" src="js/javascript.js"></script>
 
<title>PI - SENAC</title>
</head>

<body>
<?php if (isset($_SESSION["showMenu"])&&$_SESSION["showMenu"]) { ?>
	<center>Bem vindo, <strong><?=$_SESSION["nomeProfessor"]?></strong>! Aqui é o menu! <a href="index.php?logout=1">Fazer Logout</a></center>
<?php } ?>
<section>
	<h1>Cadastro de Questões</h1>
</section>
<section>
	<form name="frmQuestao" id="frmQuestao" method="post" enctype="multipart/form-data">
		Enunciado: <input type="text" name="textoQuestao" id="textoQuestao" maxlength="300" size="90"><br>
		Assunto:
		<select name="assunto">
			<?php
				$query = "SELECT codAssunto, descricao FROM assunto ORDER BY descricao ASC";
				$result = odbc_exec($conn,$query);
				if(odbc_num_rows($result)>0){
					while($assunto = odbc_fetch_array($result)){
						echo '<option value="'.$assunto['codAssunto'].'">'.$assunto['descricao'].'</option>';
					}
				}
			?>
		</select><br>
		Tipo: 
		<select name="tipoQuestao">
			<?php
				$query = "SELECT codTipoQuestao, descricao FROM tipoquestao ORDER BY descricao ASC";
				$result = odbc_exec($conn,$query);
				if(odbc_num_rows($result)>0){
					while($tipoQuestao = odbc_fetch_array($result)){
						echo '<option value="'.$tipoQuestao['codTipoQuestao'].'">'.$tipoQuestao['descricao'].'</option>';
					}
				}
			?>
		</select><br>
		Dificuldade: 
		<select name="dificuldade">
			<option value="F">Fácil</option>
			<option value="M">Médio</option>
			<option value="D">Difícil</option>
		</select><br><br>
		Imagem: <input type="file" name="imagemQuestao"><br>
		Titulo da imagem: <input type="text" name="titImagem" maxlength="50" size="50"><br><br>
		Ativo: <input type="checkbox" name="ativo" checked><br><br><br>
		<input type="submit" value="Cadastrar" name="cadastrar">
	</form>
</section>
</body>

</html>