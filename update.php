<?php

include("config/database.php");
include("config/session.php");
include("config/func.php");

if(!isset($_SESSION["codProfessor"])&&!isset($_GET['cq'])){
	header("Location: index.php"); exit;	
}

$codQuestao = $_GET['cq'];

if (isset($_POST['cadastrar'])){
	//IMAGEM
	if(isset($_FILES['imagemQuestao'])&&$_FILES['imagemQuestao']['size'] > 0){
		if(	substr($_FILES['imagemQuestao']['type'], 0, 5) == 'image' &&
			$_FILES['imagemQuestao']['error'] == 0 &&
			($_FILES['imagemQuestao']['size'] > 0 && $_FILES['imagemQuestao']['size'] < 9000000)){
			
			$file = fopen($_FILES['imagemQuestao']['tmp_name'],'rb');
			$fileParaDB = fread($file, filesize($_FILES['imagemQuestao']['tmp_name']));
			fclose($file);
			
			$stmt = odbc_prepare($conn,'INSERT INTO imagem (tituloImagem,bitmapImagem) VALUES (?,?)');
			$resultI = odbc_execute($stmt,array($_POST['titImagem'], $fileParaDB));
			
			if ($resultI) {
				$stmt = odbc_prepare($conn,"UPDATE questao SET textoQuestao=?, codAssunto=?, codImagem=IDENT_CURRENT('IMAGEM'), codTipoQuestao=?, dificuldade=? WHERE codQuestao = ?");
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
		$stmt = odbc_prepare($conn,"UPDATE questao SET textoQuestao=?, codAssunto=?, codTipoQuestao=?, dificuldade=? WHERE codQuestao = ?");
	}
	//QUESTAO
	$resultQ = odbc_execute($stmt,array($_POST['textoQuestao'],$_POST['assunto'],$_POST['tipoQuestao'],$_POST['dificuldade'],$codQuestao));
	
	if ($resultQ) {
		$stmt = odbc_prepare($conn, "SELECT codAlternativa FROM alternativa WHERE codQuestao = ?");
		$resultA = odbc_execute($stmt, array($codQuestao));
		
		$qtdAlt = odbc_num_rows($resultA);
		
		if ($qtdAlt <= $_POST['qtdAlternativas']) {
			for ($i=1;$i<=$qtdAlt;$i++) {
				$stmt = odbc_prepare($conn,"UPDATE alternativa SET textoAlternativa = ?, correta = ? WHERE codQuestao = ? AND codAlternativa = ?");
				if (!isset($_POST['correta_'.$i])){$correta=0;}else{$correta=$_POST['correta_'.$i];}
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
											VALUES (IDENT_CURRENT('QUESTAO'),?,?,?)");
				if (!isset($_POST['correta_'.$i])){$correta=0;}else{$correta=$_POST['correta_'.$i];}
				switch($_POST['tipoQuestao']){
					case "A": 
						$arr = array($i,$_POST['alternativa_'.$i],$correta);
						break;
					case "T": 
						$arr = array($i,$_POST['resposta_'.$i],1);
						break;
					case "V": 
						$arr = array($i,$_POST['respostaVF'],$_POST['correta']);
						break;
				}
				$result = odbc_execute($stmt, $arr);
			}
			
		}else {
			for ($i=1;$i<=$_POST['qtdAlternativas'];$i++) {
				$stmt = odbc_prepare($conn,"UPDATE alternativa SET textoAlternativa = ?, correta = ? WHERE codQuestao = ? AND codAlternativa = ?");
				if (!isset($_POST['correta_'.$i])){$correta=0;}else{$correta=$_POST['correta_'.$i];}
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
				$stmt = odbc_prepare($conn,"DELETER FROM alternativa WHERE codQuestao = ? AND codAlternativa = ?");
				$result = odbc_execute($stmt, array($codQuestao,$i));
			}
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
			  <?php //=($result)?"<p>Questão cadastrada com sucesso!</p>":"<p>Ocorreu um erro ao cadastrar a questão. Tente novamente.</p>"?>

	          <span class="title-cadastro">Cadastro de Questões</span> 

	          <br><br>
              <?php
              	$query = "SELECT q.codQuestao, q.textoQuestao, q.dificuldade, q.codAssunto, q.codTipoQuestao, a.descricao AS assunto, tq.descricao AS tipoquestao, i.tituloImagem, i.bitmapImagem
					FROM questao q 
					INNER JOIN assunto a ON q.codAssunto = a.codAssunto
					JOIN tipoquestao tq ON q.codTipoQuestao = tq.codTipoQuestao
					LEFT JOIN imagem i ON q.codImagem = i.codImagem
					WHERE q.codQuestao = $codQuestao";
				$resultado = odbc_exec($conn, $query);
				$questao = odbc_fetch_array($resultado);
			  ?>
	          
				<form name="frmQuestao" id="frmQuestao" method="post" enctype="multipart/form-data">
					<div class="field-wrap">
						  <label>Enunciado</label>
						  <input type="text" name="textoQuestao" id="textoQuestao" maxlength="300" size="90" value="<?=utf8_encode($questao['textoQuestao'])?>">
					</div>
					<div class="field-wrap">
						<label>Assunto:</label>
						<select name="assunto">
							<?php
								$query = "SELECT codAssunto, descricao FROM assunto ORDER BY descricao ASC";
								$result = odbc_exec($conn,$query);
								if(odbc_num_rows($result)>0){
									while($assunto = odbc_fetch_array($result)){
										if ($questao['codAssunto']==$assunto['codAssunto']) {
											echo '<option value="'.$assunto['codAssunto'].'" selected>'.utf8_encode($assunto['descricao']).'</option>';
										}else{
											echo '<option value="'.$assunto['codAssunto'].'">'.utf8_encode($assunto['descricao']).'</option>';
										}
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
										if ($questao['codTipoQuestao']==$tipoQuestao['codTipoQuestao']) {
											echo '<option value="'.$tipoQuestao['codTipoQuestao'].'" selected>'.utf8_encode($tipoQuestao['descricao']).'</option>';
										}else{
											echo '<option value="'.$tipoQuestao['codTipoQuestao'].'">'.utf8_encode($tipoQuestao['descricao']).'</option>';
										}
									}
								}
							?>
						</select>
					</div>
					<div class="field-wrap">
						<label>Dificuldade:</label> 
						<select name="dificuldade">
							<option value="F" <?=($questao['dificuldade']=='F')?"selected":""?>>Fácil</option>
							<option value="M" <?=($questao['dificuldade']=='M')?"selected":""?>>Médio</option>
							<option value="D" <?=($questao['dificuldade']=='D')?"selected":""?>>Difícil</option>
						</select>
					</div>
					<div class="field-wrap">
						<label>Alterar Imagem:</label> 
						<input type="file" name="imagemQuestao"><br>
						<label>T&iacute;tulo da imagem:</label>
						<input type="text" name="titImagem" maxlength="50" size="50" value="<?=$questao['tituloImagem']?>"><br>
						<div><img src="data:image/jpeg;base64,<?=base64_encode($questao['bitmapImagem'])?>"></div>
					</div>
					<?php
						$resultA = odbc_exec($conn, "SELECT codAlternativa FROM alternativa WHERE codQuestao = $codQuestao");
		
						$qtdAlt = odbc_num_rows($resultA);
					?>
					<input type="hidden" name="qtdAlternativas" id="qtdAlternativas" value="<?=$qtdAlt?>">
					<?php
						if ($questao['codTipoQuestao']=='A') {
					?>
					<div id="alternativas">
						<h4>Alternativas</h4>
						<table>
							<tr>
								<td>&nbsp;</td>
								<td>Texto da alternativa</td>
								<td>Correta</td>
							</tr>
							<?php 
								for($j=1;$j<=$qtdAlt;$j++){
									$alternativa = odbc_fetch_array($resultA); 
							?>
							<tr>
								<td><?=$j?></td>
								<td><input type="text" name="alternativa_<?=$j?>" maxlength="250" size="80"></td>
								<td><input type="checkbox" name="correta_<?=$j?>" value="1"></td>
							</tr>
							<?php } ?>
						</table>
						<div>
							<button id="addAlternativa" name="addAlternativa">Adicionar alternativa</button>
							<button id="rmvAlternativa" name="rmvAlternativa">Remover alternativa</button>
						</div>
					</div>
					<?php }else if ($questao['codTipoQuestao']=='T') { ?>
					<div id="textoObjetivo">
						<h4>Respostas</h4>
						<table>
							<tr>
								<td>&nbsp;</td>
								<td>Texto da resposta</td>
							</tr>
							<?php 
								for($j=1;$j<=$qtdAlt;$j++){
									$alternativa = odbc_fetch_array($resultA); 
							?>
							<tr>
								<td><?=$j?></td>
								<td><input type="text" name="resposta_<?=$j?>" maxlength="250" size="80"></td>
							</tr>
							<?php } ?>
						</table>
						<div>
							<button id="addResposta" name="addResposta">Adicionar resposta</button>
							<button id="rmvResposta" name="rmvResposta">Remover resposta</button>
						</div>
					</div>
					<?php }else if($questao['codTipoQuestao']=='V') { ?>
					<div id="verdadeiroFalso">
						<h4>Resposta</h4>
						Texto da resposta: <input type="text" name="respostaVF" maxlength="250" size="80"><br>
						Verdadeira: <input type="radio" name="correta" value="1">
						Falsa: <input type="radio" name="correta" value="0">
					</div>
					<?php } ?>
					<br><br>
					<input type="submit" value="Cadastrar" name="cadastrar" class="button-cadastrar button-block">
					<a href="questao.php" class="button-cancelar button-block"/>Cancelar</a>
				</form>

	        </div>
	        
	        <div id="login">   
	          
	        </div>
	        
	     </div>
	      
		</div>
	</div>
</section>
</body>

</html>