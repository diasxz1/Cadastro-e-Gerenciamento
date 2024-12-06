<?php
session_start();
include 'conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit;
}

// Verifica o cargo do usuário
$cargo = isset($_SESSION['cargo']) ? $_SESSION['cargo'] : null;

// Variáveis e tratamento de erros
$error = '';
$nome = $descricao = $estoque = $preco = $chave = $imagem = '';

// Processa o cadastro de produtos apenas para admins
if ($_SERVER["REQUEST_METHOD"] == "POST" && $cargo === 'admin') {
    if (isset($_POST['cadastrar'])) {
        $nome = $_POST['nome'];
        $descricao = $_POST['descricao'];
        $estoque = $_POST['estoque'];
        $preco = $_POST['preco'];
        $chave = $_POST['chave'];
        $imagem = '';

        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $imagem = $_FILES['imagem']['name'];
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($imagem);

            if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $target_file)) {
                $error = "Erro ao fazer upload da imagem.";
            }
        }

        if ($error == '') {
            $sql = "INSERT INTO produtos (nome, descricao, estoque, preco, chave_ativacao, imagem) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssidss", $nome, $descricao, $estoque, $preco, $chave, $imagem);

            if ($stmt->execute()) {
                $nome = $descricao = $estoque = $preco = $chave = $imagem = '';
            } else {
                $error = "Erro ao cadastrar produto: " . $stmt->error;
            }
        }
    }
}

// Atualiza o estoque diretamente (para todos os cargos)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['atualizar_estoque'])) {
    $id = $_POST['produto_id'];
    $estoque = $_POST['estoque'];

    $sql = "UPDATE produtos SET estoque = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $estoque, $id);

    if (!$stmt->execute()) {
        echo "<p style='color:red;'>Erro ao atualizar o estoque: " . $stmt->error . "</p>";
    }
}

// Consulta produtos cadastrados
$sql = "SELECT * FROM produtos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="gerenciar_produtos.css">
    <title>Gerenciar Produtos</title>
</head>
<body>
    <div class="container">
        <h1>Gerenciar Produtos</h1>

        <!-- Botão de Voltar para o Dashboard -->
        <div class="navigation-buttons">
            <a href="dashboard.php" class="nav-btn">Voltar ao Painel de Controle</a>
        </div>

        <!-- Formulário de Cadastro (somente para admins) -->
        <?php if ($cargo === 'admin'): ?>
            <div class="form-section">
                <h2>Cadastrar Produto</h2>
                <form method="POST" action="" enctype="multipart/form-data">
                    <input type="text" name="nome" placeholder="Nome do Produto" value="<?php echo $nome; ?>" required>
                    <textarea name="descricao" placeholder="Descrição do Produto" required><?php echo $descricao; ?></textarea>
                    <input type="number" name="estoque" placeholder="Estoque" value="<?php echo $estoque; ?>" required>
                    <input type="number" step="0.01" name="preco" placeholder="Preço" value="<?php echo $preco; ?>" required>
                    <input type="text" name="chave" placeholder="Chave de Ativação" value="<?php echo $chave; ?>" required>
                    <input type="file" name="imagem" accept="image/*">
                    <button type="submit" name="cadastrar">Cadastrar Produto</button>
                </form>
                <?php if ($error) echo "<p class='error'>$error</p>"; ?>
            </div>
        <?php endif; ?>

        <!-- Tabela de Produtos -->
        <div class="table-section">
            <h2>Produtos Cadastrados</h2>
            <table>
                <tr>
                    <th>Imagem</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Estoque</th>
                    <th>Chave de Ativação</th>
                    <th>Ações</th>
                </tr>
                <?php
                if ($result->num_rows > 0) {
                    while ($produto = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td><img src="uploads/' . $produto['imagem'] . '" alt="Imagem do Produto"></td>';
                        echo '<td>' . $produto['nome'] . '</td>';
                        echo '<td>' . $produto['descricao'] . '</td>';
                        echo '<td>R$ ' . number_format($produto['preco'], 2, ',', '.') . '</td>';
                        echo '<td>
                                <form method="POST" action="">
                                    <input type="hidden" name="produto_id" value="' . $produto['id'] . '">
                                    <input type="number" name="estoque" value="' . $produto['estoque'] . '" required>
                                    <button type="submit" name="atualizar_estoque">Atualizar Estoque</button>
                                </form>
                              </td>';
                        echo '<td>' . $produto['chave_ativacao'] . '</td>';
                        echo '<td>';
                        // Exibe o botão "Deletar" apenas para administradores
                        if ($cargo === 'admin') {
                            echo '<a class="delete-btn" href="deletar_produto.php?id=' . $produto['id'] . '" onclick="return confirm(\'Tem certeza que deseja deletar este produto?\')">Deletar</a>';
                        }
                        echo '</td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td colspan="7">Nenhum produto encontrado.</td></tr>';
                }
                ?>
            </table>
        </div>
    </div>
</body>
</html>
