<?php

include("config/database.php");
include("config/session.php");
include("config/func.php");
include("func/alterar.php");

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
<?php include("menu.php"); ?>
<section>
	<div class="content white">
		<div class="cadastro">
	      <div class="tab-content">
	        <div id="signup">
	          <span class="title-cadastro">Alterar Quest&atilde;o</span> 

	          <br><br>
              <?php
              	/*$query = "SELECT q.codQuestao, q.textoQuestao, q.dificuldade, q.codAssunto, q.codTipoQuestao, 
					a.descricao AS assunto, 
					tq.descricao AS tipoquestao, 
					i.tituloImagem, i.bitmapImagem
					FROM questao q 
					INNER JOIN assunto a ON q.codAssunto = a.codAssunto
					JOIN tipoquestao tq ON q.codTipoQuestao = tq.codTipoQuestao
					LEFT JOIN imagem i ON q.codImagem = i.codImagem
					WHERE q.codQuestao = $codQuestao";
				$resultado = odbc_exec($conn, $query);*/
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
	          
				<form name="frmQuestao" id="frmQuestao" method="post" enctype="multipart/form-data">
					<div class="field-wrap">
					  <span>
						  <input class="basic-slide" type="text" name="textoQuestao" id="textoQuestao" maxlength="300" size="90" value="<?=utf8_encode($questao['textoQuestao'])?>">
						  <label class="enunciado" for="Enunciado">Enunciado</label>
						</span>
					</div>	 
					
					<div class="field-wrap">
						<span>
							<select class="basic-slide" name="assunto">
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
							<select class="basic-slide" name="tipoQuestao" id="tipoQuestao">
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
							<select class="basic-slide" name="dificuldade">
								<option value="F" <?=($questao['dificuldade']=='F')?"selected":""?>>Fácil</option>
								<option value="M" <?=($questao['dificuldade']=='M')?"selected":""?>>Médio</option>
								<option value="D" <?=($questao['dificuldade']=='D')?"selected":""?>>Difícil</option>
							</select>							
							<label for="dificuldade">Dificuldade:</label>
						</span>	
					</div>
					<div class="field-wrap">
						<span>							
							<input class="basic-slide" type="text" name="titImagem" maxlength="50" size="50" value="<?=$questao['tituloImagem']?>">
							<label for="Titulo da Imagem">T&iacute;tulo da imagem:</label>
						</span>	
					</div>
					<div class="field-wrap">						
						<div>
							<img class="img-up" src="data:image/jpeg;base64,<?=base64_encode($questao['bitmapImagem'])?>">
						</div>
					</div>
					<div class="field-wrap">	
						<span>
							<input class="basic-slide" type="file" name="imagemQuestao">
							<label for="alterar imagem">Alterar Imagem:</label> 							
						</span>	
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
								<td><input type="text" name="alternativa_<?=$j?>" maxlength="250" size="80" value="<?=$alternativa['textoAlternativa']?>"></td>
								<td><input type="checkbox" name="correta_<?=$j?>" value="1" <?=($alternativa['correta']==1)?"checked":""?>></td>
							</tr>
							<?php } ?>
						</table>
						<div>
							<button id="addAlternativa" class="addAlternativa" name="addAlternativa">Adicionar alternativa</button>
							<button id="rmvAlternativa" class="rmvAlternativa" name="rmvAlternativa">Remover alternativa</button>
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
								<td><input type="text" name="resposta_<?=$j?>" maxlength="250" size="80" value="<?=$alternativa['textoAlternativa']?>"></td>
							</tr>
							<?php } ?>
						</table>
						<div>
							<button id="addResposta" name="addResposta">Adicionar resposta</button>
							<button id="rmvResposta" name="rmvResposta">Remover resposta</button>
						</div>
					</div>
					<?php }else if($questao['codTipoQuestao']=='V') { 
						$alternativa = odbc_fetch_array($resultA);
					?>
					<div id="verdadeiroFalso">
						<h4>Resposta</h4>
						Texto da resposta: <input type="text" name="respostaVF" maxlength="250" size="80" value="<?=$alternativa['textoAlternativa']?>"><br>
						Verdadeira: <input type="radio" name="correta" value="1" <?=($alternativa['correta']==1)?"checked":""?>>
						Falsa: <input type="radio" name="correta" value="0" <?=($alternativa['correta']==0)?"checked":""?>>
					</div>
					<?php } ?>
					<br><br>
					<input type="submit" value="Salvar" name="cadastrar" class="button-cadastrar button-block">
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