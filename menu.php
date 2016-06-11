<?php 
	if (isset($_SESSION["showMenu"])&&$_SESSION["showMenu"]) { 
		if(!isset($_SESSION["codProfessor"])){
			//caso o usuário chegou a essa página e não está logado, volta para a index
			header("Location: index.php"); exit;	
		}
?>
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