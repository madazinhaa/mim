<?php
include 'includes/conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Blog</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Bem-vindo ao Meu Blog</h1>

    <?php
    $sql = "SELECT * FROM posts ORDER BY data_publicacao DESC";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div>";
            echo "<h2><a href='post.php?id=" . $row['id'] . "'>" . $row['titulo'] . "</a></h2>";
            echo "<p>" . substr($row['conteudo'], 0, 200) . "...</p>";
            echo "<hr>";
            echo "</div>";
        }
    } else {
        echo "<p>Nenhum post encontrado.</p>";
    }

    $conn->close();
    ?>

</body>
</html>
