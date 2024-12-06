<?php
session_start();

// Se o usuário já estiver logado, redireciona para o dashboard
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header("Location: dashboard.php");
    exit;
}

include('conexao.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Consulta para buscar o usuário
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica se o usuário existe
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Depuração: exibe as senhas para comparar
        echo "Senha cadastrada (do banco de dados): " . htmlspecialchars($user['senha']) . "<br>"; // Depuração
        echo "Senha informada (do formulário): " . htmlspecialchars($password) . "<br>"; // Depuração

        // Comparação direta da senha
        if ($password === $user['senha']) {
            $_SESSION['loggedin'] = true;
            $_SESSION['cargo'] = $user['cargo'];
            header("Location: dashboard.php");
            exit;
        } else {
            // Senha incorreta
            $error = "Senha incorreta!";
        }
    } else {
        // Usuário não encontrado
        $error = "Usuário não encontrado!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <form action="login.php" method="POST">
        <h2>Login</h2>
        <label for="username">Usuário:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <button type="submit">Entrar</button>
        
        <?php if (isset($error)): ?>
            <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
    </form>
</body>
</html>