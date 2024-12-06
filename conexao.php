<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sistema_jogos";

// Conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
