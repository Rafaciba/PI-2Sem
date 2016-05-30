<?php

include("config/database.php");
include("config/session.php");
include("config/func.php");

if(!isset($_SESSION["codProfessor"])){
	//caso o usuário chegou a essa página e não está logado, volta para a index
	header("Location: index.php"); exit;	
}

if (isset($_POST['cadastrar'])){
	//echo "Cadastrando questão!<br>";
	//IMAGEM
	if(isset($_FILES['imagemQuestao'])&&$_FILES['imagemQuestao']['size'] > 0){
		if(	substr($_FILES['imagemQuestao']['type'], 0, 5) == 'image' &&
			$_FILES['imagemQuestao']['error'] == 0 &&
			($_FILES['imagemQuestao']['size'] > 0 && $_FILES['imagemQuestao']['size'] < 9000000)){
			//print_r($_FILES);
			//echo 'Arquivo recebido com sucesso<BR>';
			
			$file = fopen($_FILES['imagemQuestao']['tmp_name'],'rb');
			$fileParaDB = fread($file, filesize($_FILES['imagemQuestao']['tmp_name']));
			fclose($file);
			
			//echo $fileParaDB;
			
			//CONVERT(varbinary(max),'$fileParaDB')
			$stmt = odbc_prepare($conn,'INSERT INTO imagem (tituloImagem,bitmapImagem) OUTPUT INSERTED.codImagem VALUES (?,?)');
			$resultI = odbc_execute($stmt,array($_POST['titImagem'], $fileParaDB));
			//$query = "INSERT INTO imagem (tituloImagem,bitmapImagem) VALUES ('".$_POST['titImagem']."',CONVERT(varbinary(max),'$aux'))";
			//$result = odbc_exec($conn,$query) or die(odbc_errormsg($conn));
			if ($resultI) {
				$insertImg = odbc_fetch_array($stmt);
				/*$stmtQ = odbc_prepare($conn,"INSERT INTO questao (textoQuestao,codAssunto,codImagem,codTipoQuestao,codProfessor,ativo,dificuldade) 
								VALUES (?,?,?,?,?,1,?)");*/
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
			$insertImg = array('codImagem' => 'NULL');
		}
	}else{
		$insertImg = array('codImagem' => 'NULL');
	}
	//QUESTAO
	$stmtQ = odbc_prepare($conn,"INSERT INTO questao (textoQuestao,codAssunto,codImagem,codTipoQuestao,codProfessor,ativo,dificuldade) 
								OUTPUT INSERTED.codQuestao VALUES (?,?,?,?,?,1,?)");
	$resultQ = odbc_execute($stmtQ,array($_POST['textoQuestao'],$_POST['assunto'],$insertImg['codImagem'],$_POST['tipoQuestao'],$_SESSION['codProfessor'],$_POST['dificuldade']));
	
	if ($resultQ) {
		$insertQuestao = odbc_fetch_array($stmtQ);
		for ($i=1;$i<=$_POST['qtdAlternativas'];$i++) {
			$stmt = odbc_prepare($conn,"INSERT INTO alternativa (codQuestao, codAlternativa,textoAlternativa,correta)
										VALUES (?,?,?,?)");
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
	}
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
	<header>
		<div class="content white">
			<div class="menu">
				<nav>
				  <ul>
				    <li id="question"><a href="questao.php" title="Home" >Questões</a></li>
				    <li id="welcome">Bem Vindo, <?=$_SESSION["nomeProfessor"]?></li> 
				    <li id="logout"><a href="index.php?logout=1" title="Logout">Logout</a></li>
				  </ul>
				</nav>
			</div>
		</div>	
	</header>
<?php } ?>
<section>
	<div class="content white">
		<div class="cadastro">
	      <div class="tab-content">
	        <div id="signup">   
			  <?=(isset($result)&&$result)?"<p>Questão cadastrada com sucesso!</p>":"<p>Ocorreu um erro ao cadastrar a questão. Tente novamente.</p>"?>

	          <span class="title-cadastro">Cadastro de Questões</span> 

	          <br><br>
	          
				<form name="frmQuestao" id="frmQuestao" method="post" enctype="multipart/form-data">
					<div class="field-wrap">
						  <label>Enunciado</label>
						  <input type="text" name="textoQuestao" id="textoQuestao" maxlength="300" size="90">
					</div>
					<div class="field-wrap">
						<label>Assunto:</label>
						<select name="assunto">
							<?php
								$query = "SELECT codAssunto, descricao FROM assunto ORDER BY descricao ASC";
								$result = odbc_exec($conn,$query);
								if(odbc_num_rows($result)>0){
									while($assunto = odbc_fetch_array($result)){
										echo '<option value="'.$assunto['codAssunto'].'">'.utf8_encode($assunto['descricao']).'</option>';
									}
								}
							?>
						</select>
					</div>
					<div class="field-wrap">
						<label>Tipo:</label>
						<select name="tipoQuestao" id="tipoQuestao">
							<?php
								$query = "SELECT codTipoQuestao, descricao FROM tipoquestao ORDER BY descricao ASC";
								$result = odbc_exec($conn,$query);
								if(odbc_num_rows($result)>0){
									while($tipoQuestao = odbc_fetch_array($result)){
										echo '<option value="'.$tipoQuestao['codTipoQuestao'].'">'.utf8_encode($tipoQuestao['descricao']).'</option>';
									}
								}
							?>
						</select>
					</div>
					<div class="field-wrap">
						<label>Dificuldade:</label> 
						<select name="dificuldade">
							<option value="F">Fácil</option>
							<option value="M">Médio</option>
							<option value="D">Difícil</option>
						</select>
					</div>
					<div class="field-wrap">
						<label>Imagem:</label> 
						<input type="file" name="imagemQuestao"><br>
						<label>T&iacute;tulo da imagem:</label>
						<input type="text" name="titImagem" maxlength="50" size="50">
					</div>
					<input type="hidden" name="qtdAlternativas" id="qtdAlternativas" value="1">
					<div id="alternativas">
						<h4>Alternativas</h4>
						<table>
							<tr>
								<td>&nbsp;</td>
								<td>Texto da alternativa</td>
								<td>Correta</td>
							</tr>
							<tr>
								<td>1</td>
								<td><input type="text" name="alternativa_1" maxlength="250" size="80"></td>
								<td><input type="checkbox" name="correta_1" value="1"></td>
							</tr>
						</table>
						<div>
							<button id="addAlternativa" name="addAlternativa">Adicionar alternativa</button>
							<button id="rmvAlternativa" name="rmvAlternativa">Remover alternativa</button>
						</div>
					</div>
					<div id="textoObjetivo">
						<h4>Respostas</h4>
						<table>
							<tr>
								<td>&nbsp;</td>
								<td>Texto da resposta</td>
							</tr>
							<tr>
								<td>1</td>
								<td><input type="text" name="resposta_1" maxlength="250" size="80"></td>
							</tr>
						</table>
						<div>
							<button id="addResposta" name="addResposta">Adicionar resposta</button>
							<button id="rmvResposta" name="rmvResposta">Remover resposta</button>
						</div>
					</div>
					<div id="verdadeiroFalso">
						<h4>Resposta</h4>
						Texto da resposta: <input type="text" name="respostaVF" maxlength="250" size="80"><br>
						Verdadeira: <input type="radio" name="correta" value="1">
						Falsa: <input type="radio" name="correta" value="0">
					</div>
					<br><br>
					<input type="submit" value="Cadastrar" name="cadastrar" class="button-cadastrar button-block">
					<a href="questao.php" class="button-cancelar button-block"/>Cancelar</a>
				</form>

	        </div>
	        
	        <div id="login">   
	          
	        </div>
	        
	     </div><!-- tab-content -->
	      
		</div> <!-- /form -->
	</div>
</section>
</body>

</html>