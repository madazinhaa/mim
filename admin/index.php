<?php
session_start();
include '../includes/conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Consulta todos os posts
$sql = "SELECT * FROM posts ORDER BY data_publicacao DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Administração</title>
</head>
<body>
    <h2>Painel de Administração - Posts</h2>
    <a href="novo_post.php">Criar Novo Post</a>
    <hr>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<h3>" . $row['titulo'] . "</h3>";
            echo "<p>" . substr($row['conteudo'], 0, 100) . "...</p>";
            echo "<a href='editar_post.php?id=" . $row['id'] . "'>Editar</a> | ";
            echo "<a href='excluir_post.php?id=" . $row['id'] . "'>Excluir</a>";
            echo "</div><hr>";
        }
    } else {
        echo "<p>Nenhum post encontrado.</p>";
    }
    ?>
</body>
</html>
