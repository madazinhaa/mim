<?php
session_start();
require './../includes/conexao_usuarios.php';
require './../includes/conexao_admin.php';
require './../includes/verifica_login.php';
require './../plataforma/verifica_login.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    // Verifica se é um administrador
    $sql = "SELECT id, nome, senha FROM administradores WHERE email = ?";
    $stmt = $conn_admin->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $nome, $senha_hash);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($senha, $senha_hash)) {
            $_SESSION['admin_id'] = $id;
            $_SESSION['admin_nome'] = $nome;
            header("Location: admin/index.php");
            exit();
        }
    }

    // Verifica se é um usuário comum
    $sql = "SELECT id, nome, senha FROM usuarios WHERE email = ?";
    $stmt = $conn_usuarios->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $nome, $senha_hash);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($senha, $senha_hash)) {
            $_SESSION['usuario_id'] = $id;
            $_SESSION['usuario_nome'] = $nome;
            header("Location: plataforma/index.php"); // Direciona para a plataforma de estudos
            exit();
        }
    }

    $erro = "E-mail ou senha incorretos!";
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php if (isset($erro)) echo "<p style='color:red;'>$erro</p>"; ?>
    <form method="POST">
        <label for="email">E-mail:</label><br>
        <input type="email" name="email" required><br><br>

        <label for="senha">Senha:</label><br>
        <input type="password" name="senha" required><br><br>

        <button type="submit">Entrar</button>
    </form>
</body>
</html>
