
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
		<div class="documentacao">      
	        <h2>Documenta&ccedil;&atilde;o</h2>
			
			<h3>
			Como adicionar quest&otilde;es? </h3>	
			<p>Na p&aacute;gina quest&otilde;es no lado superior direito existe um bot&atilde;o chamado <strong>"+ Adicionar"</strong> como o exemplo abaixo, clique nesse bot&atilde;o e ent&atilde;o voc&ecirc; ser&aacute; redirecionado para p&aacute;gina Cadastro de quest&otilde;es.</p>
			<img src="img/adicionar-documentacao.png" align="Adicionar Questões" border="0">
			
		  <p>Dentro d&aacute; p&aacute;gina voc&ecirc; ter&aacute; um formul&aacute;rio com diversos campos como: enunciado, assunto, tipo e etc. Preencha os campos de acordo com a sua quest&atilde;o, e ent&atilde;o clique no bot&atilde;o cadastrar.</p> 
		  <br>
			
			<h3>
			Como excluir uma quest&atilde;o? </h3>
			<p>Dentro da p&aacute;gina quest&otilde;es voc&ecirc; ter&aacute; a listagem de quest&otilde;es, contendo informa&ccedil;&otilde;es sobre cada quest&atilde;o. No lado esquerdo da tabela, a primeira coluna de informa&ccedil;&otilde;es ser&aacute; <strong>"Editar",</strong> l&aacute; existira um &iacute;cone simbolizando um lixo, clique no mesmo e ent&atilde;o surgir&aacute; um pop-up com um alerta perguntando se voc&ecirc; realmente deseja excluir essa quest&atilde;o, por fim clique em <strong>"Ok"</strong> se voc&ecirc; realmente for exluir essas quest&atilde;o. </p>
            <p> Pronto quest&atilde;o exclu&iacute;da.
              Obs: Lembrando que s&oacute; &eacute; permitido a exclus&atilde;o da quest&atilde;o cadastrada por voc&ecirc;, s&oacute; ter&aacute; o previl&eacute;gio de excluir todas as quest&otilde;es <strong>Professores/Administradores</strong> </p>
			<img src="img/excluir-documentacao.png" align="Excluir Questões" border="0" align="">	
			<br><br>
			<h3>
			Como editar uma quest&atilde;o? </h3>
			<p>Dentro da p&aacute;gina quest&otilde;es voc&ecirc; ter&aacute; a listagem de quest&otilde;es, contendo informa&ccedil;&otilde;es sobre cada quest&atilde;o. No lado esquerdo da tabela, a primeira coluna de informa&ccedil;&otilde;es ser&aacute; <strong>"Editar",</strong> l&aacute; existira um &iacute;cone simbolizando um lapis, clique no mesmo e ent&atilde;o 
			voc&ecirc; ser&aacute; redirecionado para p&aacute;gina <strong>"Alterar Quest&atilde;o",</strong> onde possuir&aacute; um formul&aacute;rio onde voc&ecirc; poder&aacute; alterar as informa&ccedil;&otilde;es antigas da quest&atilde;o. </p>
          <p>Ap&oacute;s ter editado os campos desejados clique no bot&atilde;o <strong>"Salvar"</strong> e ent&atilde;o a sua quest&atilde;o foi editada com sucesso.</p>
			<img src="img/editar-documentacao.png" align="Excluir Questões" border="0" align="">
			<p>Obs: Lembrando que s&oacute; &eacute; permitido a edi&ccedil;&atilde;o da quest&atilde;o cadastrada por voc&ecirc;, s&oacute; ter&aacute; o previl&eacute;gio de excluir todas as quest&otilde;es <strong>Professores/Administradores</strong> </p>	
	     </div>	      
	</div>
</section>
</body>

</html>