<?php
session_start();
include 'includes/conexao.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Verifica se o ID do post foi passado pela URL
if (!isset($_GET['id'])) {
    echo "Post não encontrado.";
    exit();
}

$id = $_GET['id'];

// Consulta o post a ser editado
$sql = "SELECT * FROM posts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $post = $result->fetch_assoc();
} else {
    echo "Post não encontrado.";
    exit();
}

// Se o formulário for enviado, atualiza o post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $conteudo = $_POST['conteudo'];

    // Atualiza o post no banco de dados
    $sql_update = "UPDATE posts SET titulo = ?, conteudo = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssi", $titulo, $conteudo, $id);

    if ($stmt_update->execute()) {
        echo "<p>Post atualizado com sucesso!</p>";
    } else {
        echo "<p>Erro ao atualizar o post: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Post</title>
</head>
<body>
    <h2>Editar Post</h2>
    <form method="POST">
        <label for="titulo">Título:</label><br>
        <input type="text" name="titulo" value="<?php echo htmlspecialchars($post['titulo']); ?>" required><br><br>

        <label for="conteudo">Conteúdo:</label><br>
        <textarea name="conteudo" rows="5" required><?php echo htmlspecialchars($post['conteudo']); ?></textarea><br><br>

        <button type="submit">Atualizar Post</button>
    </form>
    <a href="index.php">Voltar ao Painel</a>
</body>
</html>
