<?php
include 'conexao.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Preparando e executando a exclusão do produto no banco de dados
    $sql = "DELETE FROM produtos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: gerenciar_produtos.php"); // Redireciona de volta para a página de gerenciamento de produtos
        exit();
    } else {
        echo "Erro ao deletar produto.";
    }
} else {
    echo "Produto não encontrado.";
}
?>
