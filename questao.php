<?php

include("config/database.php");
include("config/session.php");
include("config/func.php");
include("func/listar.php");

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="iso-8859-1">
<link href='https://fonts.googleapis.com/css?family=Roboto:100,400,500' rel='stylesheet' type='text/css'> 
<link rel="stylesheet" type="text/css" href="css/style.css">
<script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script type="text/javascript" src="js/javascript.js"></script>
 
<title>PI - SENAC</title>
</head>

<body>
<?php include("menu.php"); ?>
<div class="content total">
	<div class="content white">
		<section> 
			<div class="adicionar">
				<a href="addQuestao.php" class="button-cadastrar button-block"/>Adicionar +</a>
			</div>
			<div class="listagem-question">
				Listagem das Quest&otilde;es
			</div>
            <div>
            	<?php
				  if (isset($_GET['s'])){
					  switch($_GET['s']){
						  case 0: echo '<div class="error">Erro ao tentar deletar/desativar a quest&atilde;o.</div>'; break;
						  case 1: echo '<div class="success">Quest&atilde;o desativada com sucesso!</div>'; break;
						  case 2: echo '<div class="success">Quest&atilde;o deletada com sucesso!</div>'; break;
						  case 3: echo '<div class="success">Quest&atilde;o cadastrada com sucesso!</div>'; break;
						  case 4: echo '<div class="success">Quest&atilde;o alterada com sucesso!</div>'; break;
					  }
				  }
				?>
            </div>
            <div class="frmFiltro">
            	<div>
                	<label for="ordem">Ordenar por:</label>
                	<select name="ordem" id="ordem">
                    	<option value="maisRecente" <?=(isset($_GET['ordem'])&&$_GET['ordem']=="maisRecente")?"selected":""?>>Mais recente</option>
                        <option value="maisAntigo" <?=(isset($_GET['ordem'])&&$_GET['ordem']=="maisAntigo")?"selected":""?>>Mais antigo</option>
                        <option value="alfabetico" <?=(isset($_GET['ordem'])&&$_GET['ordem']=="alfabetico")?"selected":""?>>A - Z</option>
                        <option value="alfabetico2" <?=(isset($_GET['ordem'])&&$_GET['ordem']=="alfabetico2")?"selected":""?>>Z - A</option>
                        <option value="assunto" <?=(isset($_GET['ordem'])&&$_GET['ordem']=="assunto")?"selected":""?>>Assunto</option>
                        <option value="tipoQuestao" <?=(isset($_GET['ordem'])&&$_GET['ordem']=="tipoQuestao")?"selected":""?>>Tipo da quest&atilde;o</option>
                        <option value="professor" <?=(isset($_GET['ordem'])&&$_GET['ordem']=="professor")?"selected":""?>>Professor</option>
                        <option value="dificuldade" <?=(isset($_GET['ordem'])&&$_GET['ordem']=="dificuldade")?"selected":""?>>Dificuldade</option>
                        <option value="ativo" <?=(isset($_GET['ordem'])&&$_GET['ordem']=="ativo")?"selected":""?>>Ativo</option>
                    </select>
                    <label for="pp">Quest&otilde;es p/ P&aacute;g.:</label>
                    <select name="pp" id="pp">
                    	<option value="10" <?=($pp=="10")?"selected":""?>>10</option>
                        <option value="20" <?=($pp=="20")?"selected":""?>>20</option>
                        <option value="30" <?=($pp=="30")?"selected":""?>>30</option>
                        <option value="40" <?=($pp=="40")?"selected":""?>>40</option>
                        <option value="50" <?=($pp=="50")?"selected":""?>>50</option>
                        <option value="60" <?=($pp=="60")?"selected":""?>>60</option>
                        <option value="70" <?=($pp=="70")?"selected":""?>>70</option>
                        <option value="80" <?=($pp=="80")?"selected":""?>>80</option>
                        <option value="90" <?=($pp=="90")?"selected":""?>>90</option>
                        <option value="100" <?=($pp=="100")?"selected":""?>>100</option>
                    </select>
                </div>
            </div>
			<table class="responsive-table">  
				<?php 
					if(odbc_num_rows($result)>0){
				?>
			    <thead>
			      <tr> 
			        <th scope="col">Editar Quest&atilde;o</th>
			        <th scope="col">Enunciado</th>
			        <th scope="col">Assunto</th>
			        <th scope="col">Tipo</th>
			        <th scope="col">Professor/Autor</th>
			        <th scope="col">Dificuldade</th>
			        <th scope="col">Ativo</th>

			      </tr>
			    </thead>
			     
			    <tbody>
				  <?php
						while($area = odbc_fetch_array($result)){
				  ?>
			      <tr> 
			        <td data-title="">
                    	<?php if($_SESSION["tipoProfessor"]=="A"||$_SESSION["codProfessor"]==$area["codProfessor"]){ ?>
						<a href="update.php?cq=<?=$area["codQuestao"]?>" class="edit"></a>
						<a href="delete.php?cq=<?=$area["codQuestao"]?>" class="delete"></a>
                        <?php }else{ ?>
                        <a href="view.php?cq=<?=$area["codQuestao"]?>" class="view"></a>
                        <?php } ?>
			        </td>
			        <td data-title=""><?=$area["textoQuestao"]?></td>
			        <td data-title=""><?=$area["assunto"]?></td>
			        <td data-title="" data-type=""><?=$area["tipoquestao"]?></td>
			        <td data-title="" data-type=""><?=$area["nome"]?></td>
			        <td data-title="" data-type=""><?=$area["dificuldade"]?></td>
			        <td data-title="" data-type=""><?=($area["ativo"])?'<div class="ativo"></div>':'<div class="desativo"></div>'?>
			        </td>
			      </tr>
				<?php } ?>
				</tbody>
				<?php
					} else {
						echo "<div>Nenhuma quest&atilde;o foi encontrada!</div>";
					}
				?>
	  		</table>
		</section>
		<section>
			<div class="pagination" align="center">
				<div>
					<?php
						for($j=1;$j<=($tt/$pp);$j++){
							if ($j%21==0) {
								echo "</div><div>";
							}
							echo '<div><a href="questao.php?p='.$j.'&pp='.$pp.'">'.$j.'</a></div>';
						}
					?>
				</div>
			</div>
		</section>
	</div>
</div>
</body>

</html>