<?php

$user = "TSI";
$pass = "SistemasInternet123";
$server = "koo2dzw5dy.database.windows.net";
$db = "SenaQuiz";

$dsn = "Driver={SQL Server};Server=$server;Port=1433;Database=$db;";

$conn = odbc_connect($dsn,$user,$pass);
		

?>