
<?php

include("config/database.php");
include("config/session.php");
include("config/func.php");

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
		<div class="documentacao">      
	        <h2>Documenta&ccedil;&atilde;o do usu&aacute;rio</h2>
			
			<h3>
			Como adicionar quest&otilde;es? </h3>	
			<p>Na p&aacute;gina quest&otilde;es, no lado superior direito, existe um bot&atilde;o  <strong>"+ Adicionar"</strong> como o exemplo abaixo. Clique nesse bot&atilde;o e ent&atilde;o voc&ecirc; ser&aacute; redirecionado para p&aacute;gina Cadastro de quest&otilde;es.</p>
			<img src="img/adicionar-documentacao.png" align="Adicionar Questões" border="0">
			
		  <p>Dentro da p&aacute;gina voc&ecirc; ter&aacute; um formul&aacute;rio com diversos campos como: enunciado, assunto, tipo e etc. Preencha os campos de acordo com a sua quest&atilde;o, e ent&atilde;o clique no bot&atilde;o cadastrar.</p>
		  <p>Pronto! Quest&atilde;o cadastrada com sucesso.</p> 
		  <br>
			
			<h3>
			Como excluir uma quest&atilde;o? </h3>
			<p>Dentro da p&aacute;gina quest&otilde;es voc&ecirc; ter&aacute; a listagem de todas as quest&otilde;es, contendo informa&ccedil;&otilde;es sobre cada uma. No lado esquerdo da tabela existe um &iacute;cone vermelho de um lixo, clique nele e confirme se voc&ecirc; realmente deseja excluir essa quest&atilde;o. Quest&otilde;es que j&aacute; foram usadas em um evento n&atilde;o ser&atilde;o exclu&iacute;das, mas desativadas.</p>
            <p> Pronto! Quest&atilde;o exclu&iacute;da ou desativada com sucesso.</p>
            <p>Lembrando que s&oacute; &eacute; permitido a exclus&atilde;o de quest&otilde;es cadastradas por voc&ecirc; mesmo. Apenas um <strong>Professor Administrador</strong> pode excluir ou desativar qualquer quest&atilde;o.</p>
			<img src="img/excluir-documentacao.png" align="Excluir Questões" border="0" align="">	
			<br><br>
			<h3>
			Como editar uma quest&atilde;o? </h3>
			<p>Dentro da p&aacute;gina quest&otilde;es voc&ecirc; ter&aacute; a listagem das quest&otilde;es. No lado esquerdo da tabela existe um &iacute;cone azul de um l&aacute;pis, clique nele e ent&atilde;o 
			voc&ecirc; ser&aacute; redirecionado para p&aacute;gina <strong>"Alterar Quest&atilde;o",</strong> onde h&aacute; um formul&aacute;rio para voc&ecirc; alterar os dados da quest&atilde;o. </p>
          <p>Ap&oacute;s ter editado os campos desejados clique no bot&atilde;o <strong>"Salvar"</strong> e ent&atilde;o a sua quest&atilde;o foi editada com sucesso.</p>
			<img src="img/editar-documentacao.png" align="Excluir Questões" border="0" align="">
			<p> Lembrando que s&oacute; &eacute; permitido a edi&ccedil;&atilde;o de quest&otilde;es cadastrada por voc&ecirc; mesmo. Apenas um <strong>Professor Administrador</strong> pode editar qualquer quest&atilde;o.</p>	
	     </div>	      
	</div>
</section>
</body>

</html>