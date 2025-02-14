<?php
session_start();
include '../includes/conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $conteudo = $_POST['conteudo'];
    $data_publicacao = date('Y-m-d H:i:s');  // Data e hora atual

    // Insere o post no banco de dados
    $sql = "INSERT INTO posts (titulo, conteudo, data_publicacao) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $titulo, $conteudo, $data_publicacao);

    if ($stmt->execute()) {
        echo "<p>Post criado com sucesso!</p>";
    } else {
        echo "<p>Erro ao criar post: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Novo Post</title>
</head>
<body>
    <h2>Criar Novo Post</h2>
    <form method="POST">
        <label for="titulo">Título:</label><br>
        <input type="text" name="titulo" required><br><br>

        <label for="conteudo">Conteúdo:</label><br>
        <textarea name="conteudo" rows="5" required></textarea><br><br>

        <button type="submit">Criar Post</button>
    </form>
    <a href="index.php">Voltar ao Painel</a>
</body>
</html>
