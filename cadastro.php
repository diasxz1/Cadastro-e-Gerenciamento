<?php
include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);
    $endereco = $_POST['endereco'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $cep = $_POST['cep'];
    $cpf = $_POST['cpf'];

    $sql = "INSERT INTO clientes (nome, email, senha, endereco, cidade, estado, cep, cpf) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssss', $nome, $email, $senha, $endereco, $cidade, $estado, $cep, $cpf);

    if ($stmt->execute()) {
        echo "Cadastro realizado com sucesso!";
    } else {
        echo "Erro ao cadastrar: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cliente</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <form method="POST" action="cadastro.php">
        <h2>Cadastro</h2>
        <input type="text" name="nome" placeholder="Nome" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <input type="text" name="endereco" placeholder="Endereço" required>
        <input type="text" name="cidade" placeholder="Cidade" required>
        <input type="text" name="estado" placeholder="Estado" required>
        <input type="text" name="cep" placeholder="CEP" required>
        <input type="text" name="cpf" placeholder="CPF" required>
        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>
