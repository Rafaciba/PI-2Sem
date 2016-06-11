<?php

include("config/database.php");
include("config/session.php");
include("config/func.php");
include("func/adicionar.php");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="ISO-8859-1">
<link href='https://fonts.googleapis.com/css?family=Roboto:100,400,500' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script type="text/javascript" src="js/javascript.js"></script>
<script type="text/javascript" src="js/form.js"></script>
 
<title>PI - SENAC</title>
</head>

<body>
<?php include("menu.php"); ?>
<section>
	<div class="content white">
		<div class="cadastro">
	      <div class="tab-content">
	        <div id="signup">   
			  <?php 
			  if(isset($result)) {
				  if ($result) {
				  	echo "<p class='sucess'>Questão cadastrada com sucesso!</p>";
				  }else{
				  	echo "<p class='error'>Ocorreu um erro ao cadastrar a questão. Tente novamente.</p>";
				  }
			  } 
			  ?>

	          <span class="title-cadastro">Cadastro de Quest&otilde;es</span> 

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
											echo '<option value="'.$assunto['codAssunto'].'">'.$assunto['descricao'].'</option>';
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
									$query = "SELECT codTipoQuestao, descricao FROM tipoquestao ORDER BY descricao DESC";
									$result = odbc_exec($conn,$query);
									if(odbc_num_rows($result)>0){
										while($tipoQuestao = odbc_fetch_array($result)){
											echo '<option value="'.$tipoQuestao['codTipoQuestao'].'">'.$tipoQuestao['descricao'].'</option>';
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
								<option value="F">F&aacute;cil</option>
								<option value="M">M&eacute;dio</option>
								<option value="D">Dif&iacute;cil</option>
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
							<div id="AltRows">
								<div>
									<div class="field-wrap">
										<span>  
											<input type="text" class="basic-slide small" name="alternativa_1" maxlength="250" size="80">
											<label class="pad"><p>Texto da alternativa</p></label>  
										</span> 
									</div>	
									<div class="field-wrap right"> 
										<label><h3>Correta</h3></label> 
										<div class="switch">
										  <input type="checkbox" id="c1" name="correta_1" value="1" class="checkbox" />
										  <label for="c1"></label>
										</div>
										<!-- <input type="checkbox" class="basic-slide correta" name="correta_1" value="1"> -->	 
									</div>
								</div>	 
						 	</div>
						<div>
							<button id="addAlternativa" class="addAlternativa" name="addAlternativa">Adicionar alternativa</button>
							<button id="rmvAlternativa" class="rmvAlternativa" name="rmvAlternativa">Remover alternativa</button> 
						</div>
					</div>
					<div id="textoObjetivo">
						<h4>Respostas</h4>
						<div id="AltRows">
							<div class="field-wrap">
								<span>  
									<input type="text" class="basic-slide" name="resposta_1" maxlength="250" size="80">
									<label class="pad"><p>Texto da resposta</p></label>  
								</span> 
							</div> 
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
							<div class="btnRadio">
						 		<input type="radio" id="true-radio" name="verdadeira" value="1" class="checkbox">
							  <label for="true-radio"></label>
							</div> 
						</div> 
						<div class="field-wrap false"> 
							<label><h3>Falsa:</h3></label> 
							<div class="btnRadio">
						 		<input type="checkbox" id="false-radio" name="falsa" value="0" class="checkbox">
							  <label for="false-radio"></label>
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