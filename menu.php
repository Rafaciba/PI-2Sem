<?php
	//INTEGRAÇÃO
	include('integracao/loginFunc.php');
	lidaBasicAuthentication ('../../portal/naoautorizado.php'); 
	
	if(!isset($_SESSION["codProfessor"])){
		//se o usuário chegou a essa página e não está logado, volta para a index
		header("Location: index.php"); exit;	
	}
	
	if (isset($_SESSION["showMenu"])&&$_SESSION["showMenu"]) { 
		
?>
	<header>
		<div class="content white">
        	<div class="topLogo">
	        	<div class="logo">
					<img src="img/logo.png" alt="Logo Senaquiz" border="0">
				</div>
				<div class="help">
					<a href="documentacao.php">
						<img src="img/information.png" alt="Informações" border="0">
						<p>Documenta&ccedil;&atilde;o</p>
					</a>	
				</div>
			</div>
			<div class="menu">
				<nav>
				  <ul>
				    <li id="question"><a href="questao.php" title="Home" >Quest&otilde;es</a></li>
				    <li id="welcome">Bem Vindo, <?=$_SESSION["nomeProfessor"]?></li> 
				    <li id="logout"><a href="index.php?logout=1" title="Logout">Logout</a></li>
				  </ul>
				</nav>
			</div>
		</div>	
	</header>
<?php } ?>