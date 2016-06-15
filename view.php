<?php

include("config/database.php");
include("config/session.php");
include("config/func.php");
include("func/view.php");

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link href='https://fonts.googleapis.com/css?family=Roboto:100,400,500' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="css/style.css">
<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script type="text/javascript" src="js/javascript.js"></script>
 
<title>PI - SENAC</title>
</head>

<body>
<?php include("menu.php"); ?>
<section>
	<div class="content white">
		<div class="cadastro">
	      <div class="tab-content">
	        <div id="signup">
	          <span class="title-cadastro">Alterar Quest&atilde;o</span>
	          <br><br>
				<form name="frmQuestao" id="frmQuestao" method="post" enctype="multipart/form-data">
                	<div class="field-wrap">
						  <span>
						  		<textarea  class="campoForm" maxlength="300" name="textoQuestao" id="textoQuestao" disabled><?=utf8_encode($questao['textoQuestao'])?></textarea>  
						  		<label class="enunciado" for="Enunciado">Enunciado</label> 
						  </span> 	  
					</div>					
					<div class="field-wrap">
						<span>
							<select class="campoForm" name="assunto" disabled>
								<?php
									$queryA = "SELECT codAssunto, descricao FROM assunto ORDER BY descricao ASC";
									$resultAs = odbc_exec($conn,$queryA);
									if(odbc_num_rows($resultAs)>0){
										while($assunto = odbc_fetch_array($resultAs)){
											if ($questao['codAssunto']==$assunto['codAssunto']) {
												echo '<option value="'.$assunto['codAssunto'].'" selected>'.utf8_encode($assunto['descricao']).'</option>';
											}else{
												echo '<option value="'.$assunto['codAssunto'].'">'.utf8_encode($assunto['descricao']).'</option>';
											}
										}
									}
								?>
							</select>
							<label for="assunto">Assunto:</label>
						</span>	
					</div>
					<div class="field-wrap">
						<span>
							<select class="campoForm" name="tipoQuestao" id="tipoQuestao" disabled>
								<?php
									$queryT = "SELECT codTipoQuestao, descricao FROM tipoquestao ORDER BY descricao ASC";
									$resultT = odbc_exec($conn,$queryT);
									if(odbc_num_rows($resultT)>0){
										while($tipoQuestao = odbc_fetch_array($resultT)){
											if ($questao['codTipoQuestao']==$tipoQuestao['codTipoQuestao']) {
												echo '<option value="'.$tipoQuestao['codTipoQuestao'].'" selected>'.utf8_encode($tipoQuestao['descricao']).'</option>';
											}else{
												echo '<option value="'.$tipoQuestao['codTipoQuestao'].'">'.utf8_encode($tipoQuestao['descricao']).'</option>';
											}
										}
									}
								?>
							</select>
							<label for="tipo">Tipo:</label>
						</span>	
					</div>
					<div class="field-wrap">
						<span> 
							<select class="campoForm" name="dificuldade" disabled>
								<option value="F" <?=($questao['dificuldade']=='F')?"selected":""?>>F&aacute;cil</option>
								<option value="M" <?=($questao['dificuldade']=='M')?"selected":""?>>M&eacute;dio</option>
								<option value="D" <?=($questao['dificuldade']=='D')?"selected":""?>>Dif&iacute;cil</option>
							</select>							
							<label for="dificuldade">Dificuldade:</label>
						</span>	
					</div>
					<div class="field-wrap">
						<span>							
							<input class="campoForm" type="text" name="titImagem" maxlength="50" size="50" value="<?=utf8_encode($questao['tituloImagem'])?>" disabled>
							<label for="Titulo da Imagem">T&iacute;tulo da imagem:</label>
						</span>	
					</div>
					<div class="field-wrap">						
						<div>
							<img class="img-up" src="data:image/jpeg;base64,<?=base64_encode($questao['bitmapImagem'])?>">
						</div>
					</div>
					<?php
						$resultA = odbc_exec($conn, "SELECT * FROM alternativa WHERE codQuestao = $codQuestao");
		
						$qtdAlt = odbc_num_rows($resultA);
					?>
					<input type="hidden" name="qtdAlternativas" id="qtdAlternativas" value="<?=$qtdAlt?>">
					<?php
						if ($questao['codTipoQuestao']=='A') {
					?>
                    <div id="alternativas">
						<h4>Alternativas</h4>
							<div id="altRows">
                            	<?php 
									for($j=1;$j<=$qtdAlt;$j++){
										$alternativa = odbc_fetch_array($resultA); 
								?>
								<div>
									<div class="field-wrap">
										<span>
                                            <input type="text"  class="campoForm small" name="alternativa_<?=$j?>" maxlength="250" size="80" value="<?=utf8_encode($alternativa['textoAlternativa'])?>" disabled>
											<label class="pad"><p>Texto da alternativa</p></label>  
										</span> 
									</div>	
									<div class="field-wrap right"> 
										<label><h3>Correta</h3></label>
										<div><input type="radio" name="correta" id="correta_<?=$j?>" value="<?=$j?>" <?=($alternativa['correta']==1)?"checked":""?> disabled><label for="correta_<?=$j?>"><span><span></span></span></label></div> 
									</div>
								</div>	
                                <?php } ?> 
						 	</div>
					</div>
					<?php }else if ($questao['codTipoQuestao']=='T') { ?>
                    <div id="textoObjetivo">
						<h4>Respostas</h4>
						<div id="txtRows">
							<div class="field-wrap">
								<span>
                                	<input type="text" class="campoForm" name="resposta_<?=$j?>" maxlength="250" size="80" value="<?=utf8_encode($alternativa['textoAlternativa'])?>" disabled>
									<label class="pad"><p>Texto da resposta</p></label>  
								</span> 
							</div> 
						</div>
					</div>
					<?php }else if($questao['codTipoQuestao']=='V') { 
						$alternativa = odbc_fetch_array($resultA);
					?>
                    <div id="verdadeiroFalso">
						<h4>Resposta</h4>
                        <div>
                            <div class="field-wrap">
                                <span>
                                    <input type="text" class="campoForm small" name="respostaVF" maxlength="250" size="80" value="<?=utf8_encode($alternativa['textoAlternativa'])?>" disabled>
                                    <label class="pad"><p>Texto da resposta</p></label>  
                                </span> 
                            </div>  
                            <div class="field-wrap true"> 
                                <label><h3>Verdadeira:</h3></label> 
                                <div><input type="radio" id="verdadeira" name="correta" value="1" <?=($alternativa['correta']==1)?"checked":""?> disabled><label for="verdadeira"><span><span></span></span></label></div>
                            </div> 
                            <div class="field-wrap false"> 
                                <label><h3>Falsa:</h3></label> 
                                <div><input type="radio" id="falsa" name="correta" value="0" <?=($alternativa['correta']==1)?"checked":""?> disabled><label for="falsa"><span><span></span></span></label></div>
                            </div>
                        </div>
					</div>
					<?php } ?>
					<br><br>
					<a href="questao.php" class="button-cancelar button-block"/>Voltar</a>
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