<?php

$email = $_POST['user'];
$senha = $_POST['pass'];
$user = "TSI";
$pass = "SistemasInternet123";
$server = "koo2dzw5dy.database.windows.net";
$db = "SenaQuiz";

$dsn = "Driver={SQL Server};Server=$server;Port=1433;Database=$db;";

$conn = odbc_connect($dsn,$user,$pass);


if ($conn) {
    echo "Connection established.";
} else{
    die("Connection could not be established.");
}		

?>

<!-- insert into professor(nome, email, senha) values ('teste', 'teste@gmail.com', HASHBYTES('SHA1','teste')); -->