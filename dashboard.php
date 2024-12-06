<?php
session_start();


if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}


$cargo = isset($_SESSION['cargo']) ? $_SESSION['cargo'] : null;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel - <?= ucfirst($cargo); ?></title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <header class="header">
            <h1>Painel de Controle</h1>
            <div class="user-info">
                <span>Bem-vindo, <?= ucfirst($cargo); ?>!</span>
                <a href="logout.php" class="logout">Sair</a>
            </div>
        </header>

        <main class="main-content">
            <div class="button-container">
                
                <a href="gerenciar_produtos.php" class="action-button">Gerenciar Produtos</a>

                <?php if ($cargo === 'admin'): ?>
                    
                    <a href="cadastrar_funcionario.php" class="action-button">Cadastrar Funcionário</a>
                <?php endif; ?>

                <?php if ($cargo === 'funcionario'): ?>
                   
                    <p style="color: #6A0DAD;">Você tem acesso restrito. Apenas gerenciar produtos.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>
</body>
</html>
