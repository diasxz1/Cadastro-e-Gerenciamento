<?php
include 'conexao.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    
    $sql = "DELETE FROM produtos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: gerenciar_produtos.php"); 
        exit();
    } else {
        echo "Erro ao deletar produto.";
    }
} else {
    echo "Produto não encontrado.";
}
?>
