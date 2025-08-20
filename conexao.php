<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sismed";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

date_default_timezone_set('America/Fortaleza');
setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
setlocale(LC_TIME, 'portuguese'); 
mysqli_set_charset($conn, 'utf8');
?>
