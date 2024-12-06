<?php
include 'conexao.php'; // Inclui a conexão com o banco de dados

// Verifica se o administrador já existe no banco
$sql = "SELECT * FROM usuarios WHERE username = 'admin'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "O usuário administrador já existe.";
} else {
    // Insere o administrador no banco com uma senha simples
    $sql = "INSERT INTO usuarios (username, password, cargo) VALUES ('admin', 'admin@123123', 'admin')";

    if ($conn->query($sql) === TRUE) {
        echo "Usuário administrador criado com sucesso.";
    } else {
        echo "Erro ao criar administrador: " . $conn->error;
    }
}

$conn->close();
?>
