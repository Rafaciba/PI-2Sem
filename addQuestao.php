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
<link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
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
				    <li id="logout"><a href="index.php?logout=1" title="Logout">Logout</a> <i class="fa fa-sign-out" aria-hidden="true"></i></li>
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
			  <?=(isset($result)&&$result)?"<p class='sucess'>Questão cadastrada com sucesso!</p>":"<p class='error'>Ocorreu um erro ao cadastrar a questão. Tente novamente.</p>"?>

	          <span class="title-cadastro">Cadastro de Questões</span> 

	          <br><br>
	          
				<form name="frmQuestao" id="frmQuestao" method="post" enctype="multipart/form-data">
					<div class="field-wrap">
						  <span>
						  		<textarea  class="basic-slide" maxlength="300" name="textoQuestao" id="textoQuestao">						  			
						  		</textarea>  
						  		<label class="enunciado" for="Enunciado">Enunciado</label> 
						  </span> 	  
					</div>
					<div class="field-wrap">
						<span> 						
							<select class="basic-slide" name="assunto">
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
							<label for="assunto">Assunto:</label>
					</div>
					<div class="field-wrap">
						<span> 
							<select class="basic-slide" name="tipoQuestao" id="tipoQuestao">
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
							<label for="tipo">Tipo:</label>
						</span>	
					</div>
					<div class="field-wrap">
						<span> 						 
							<select class="basic-slide" name="dificuldade">
								<option value="F">Fácil</option>
								<option value="M">Médio</option>
								<option value="D">Difícil</option>
							</select>
							<label for="dificuldade">Dificuldade:</label>
						</span>	
					</div>
					<div class="field-wrap">
						<span> 
							<input class="basic-slide" type="file" name="imagemQuestao">
							<label for="imagem">Imagem:</label> 
						</span> 
					</div>
					<div class="field-wrap">
						<span> 
							<input class="basic-slide" type="text" name="titImagem" maxlength="50" size="50">
							<label class="pad" for="titulo da imagem"><p>T&iacute;tulo da imagem:</p></label>
						</span> 
					</div>	
					<input type="hidden" name="qtdAlternativas" id="qtdAlternativas" value="1">
					<div id="alternativas">
						<h4>Alternativas</h4>
						<div class="field-wrap">
							<span>  
								<input type="text" class="basic-slide small" name="alternativa_1" maxlength="250" size="80">
								<label class="pad"><p>Texto da alternativa</p></label>  
							</span> 
						</div>	
						<div class="field-wrap right"> 
							<label><h3>Correta</h3></label> 
							<div class="switch">
							  <input type="checkbox" id="c1" name="correta_1" value="1" />
							  <label for="c1"></label>
							</div>
							<!-- <input type="checkbox" class="basic-slide correta" name="correta_1" value="1"> -->	 
						</div> 
						 
						<div>
							<button id="addAlternativa" class="addAlternativa" name="addAlternativa">Adicionar alternativa</button>
							<button id="rmvAlternativa" class="rmvAlternativa" name="rmvAlternativa">Remover alternativa</button> 
						</div>
					</div>
					<div id="textoObjetivo">
						<h4>Respostas</h4>
						<div class="field-wrap">
							<span>  
								<input type="text" class="basic-slide" name="resposta_1" maxlength="250" size="80">
								<label class="pad"><p>Texto da resposta</p></label>  
							</span> 
						</div> 
						<div>
							<button id="addResposta" class="addAlternativa" name="addResposta">Adicionar resposta</button>
							<button id="rmvResposta" class="rmvAlternativa" name="rmvResposta">Remover resposta</button>
						</div>
					</div>
					<div id="verdadeiroFalso">
						<h4>Resposta</h4>
						<div class="field-wrap">
							<span>  
								<input type="text" class="basic-slide small" name="respostaVF" maxlength="250" size="80">
								<label class="pad"><p>Texto da resposta</p></label>  
							</span> 
						</div>  
						<div class="field-wrap true"> 
							<label><h3>Verdadeira:</h3></label> 
							<div class="switch">
						 		<input type="checkbox" id="c1" name="correta_1" value="1">
							  <label for="c1"></label>
							</div> 
						</div> 
						<div class="field-wrap false"> 
							<label><h3>Falsa:</h3></label> 
							<div class="switch">
						 		<input type="checkbox" name="falsa" value="0">
							  <label for="c1"></label>
							</div> 
						</div>  
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