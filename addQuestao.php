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
			  if(isset($commit)) {
				  if (!$commit) {
				  	echo '<p class="error">Ocorreu um erro ao cadastrar a quest&atilde;o. Tente novamente.</p>';
				  }
			  } 
			  ?>
	          <span class="title-cadastro">Cadastro de Quest&otilde;es</span> 

	          <br><br>
	          
				<form name="frmQuestao" id="frmQuestao" method="post" enctype="multipart/form-data">
					<div class="field-wrap">
						  <span>
						  		<textarea  class="campoForm" maxlength="300" name="textoQuestao" id="textoQuestao" required></textarea>  
						  		<label class="enunciado" for="Enunciado">Enunciado</label> 
						  </span> 	  
					</div>
					<div class="field-wrap">
						<span> 						
							<select class="campoForm" name="assunto">
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
							<select class="campoForm" name="tipoQuestao" id="tipoQuestao">
								<?php
									$query = "SELECT codTipoQuestao, descricao FROM tipoquestao ORDER BY descricao";
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
							<select class="campoForm" name="dificuldade">
								<option value="F">F&aacute;cil</option>
								<option value="M">M&eacute;dio</option>
								<option value="D">Dif&iacute;cil</option>
							</select>
							<label for="dificuldade">Dificuldade:</label>
						</span>	
					</div>	
					<div class="field-wrap">
						<span> 
							<input class="campoForm" type="file" name="imagemQuestao">
							<label for="imagem">Imagem:</label> 
						</span> 
					</div>
					<div class="field-wrap">
						<span> 
							<input class="campoForm" type="text" name="titImagem" maxlength="50" size="50">
							<label class="pad" for="titulo da imagem"><p>T&iacute;tulo da imagem:</p></label>
						</span> 
					</div>	
					<input type="hidden" name="qtdAlternativas" id="qtdAlternativas" value="1">
					<div id="alternativas">
						<h4>Alternativas</h4>
                        <div id="altRows">
                            <div>
                                <div class="field-wrap">
                                    <span>  
                                        <input type="text" class="campoForm small" name="alternativa_1" maxlength="250" size="80" required>
                                        <label class="pad"><p>Texto da alternativa</p></label>  
                                    </span> 
                                </div>	
                                <div class="field-wrap right"> 
                                    <label><h3>Correta</h3></label>
                                    <div><input type="radio" name="correta" id="correta_1" value="1" required><label for="correta_1"><span><span></span></span></label></div>
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
						<div id="txtRows">
							<div class="field-wrap">
								<span>  
									<input type="text" class="campoForm" name="resposta_1" maxlength="250" size="80">
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
								<input type="text" class="campoForm small" name="respostaVF" maxlength="250" size="80">
								<label class="pad"><p>Texto da resposta</p></label>  
							</span> 
						</div>  
						<div class="field-wrap true"> 
							<label><h3>Verdadeira:</h3></label> 
                            <div><input type="radio" id="verdadeira" name="correta"><label for="verdadeira"><span><span></span></span></label></div>
						</div> 
						<div class="field-wrap false"> 
							<label><h3>Falsa:</h3></label> 
                            <div><input type="radio" id="falsa" name="correta"><label for="falsa"><span><span></span></span></label></div>
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