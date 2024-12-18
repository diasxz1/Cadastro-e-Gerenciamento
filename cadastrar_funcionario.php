<?php
include('conexao.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $nome = $_POST['nome'];
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha']; 

    
    $sql_check_user = "SELECT * FROM usuarios WHERE usuario = ?";
    $stmt_check_user = $conn->prepare($sql_check_user);
    $stmt_check_user->bind_param('s', $usuario);
    $stmt_check_user->execute();
    $result = $stmt_check_user->get_result();

    
    if ($result->num_rows > 0) {
        $error_message = "Já existe um usuário com esse nome de usuário.";
    } else {
        
        $sql = "INSERT INTO usuarios (nome, usuario, senha, cargo) VALUES (?, ?, ?, 'funcionario')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $nome, $usuario, $senha);

        
        if ($stmt->execute()) {
            $success_message = "Funcionário cadastrado com sucesso!";
        } else {
            $error_message = "Erro ao cadastrar funcionário: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Funcionário</title>
    <link rel="stylesheet" href="cadastro_funcionario.css">
</head>
<body>
    <div class="form-container">
        <h2>Cadastrar Funcionário</h2>
        <form method="POST" action="cadastrar_funcionario.php">
            <input type="text" name="nome" placeholder="Nome Completo" required>
            <input type="text" name="usuario" placeholder="Usuário (primeiro e último nome)" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Cadastrar</button>
        </form>

        
        <?php if (isset($success_message)): ?>
            <div class="message success">
                <?= $success_message; ?>
            </div>
            <a href="dashboard.php" class="btn-return">Voltar ao Painel de Controle</a>
        <?php elseif (isset($error_message)): ?>
            <div class="message error">
                <?= $error_message; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
