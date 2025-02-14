<?php
// Ativa a exibição de erros para depuração
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../vendor/autoload.php'; // Caminho correto para carregar o Composer
require __DIR__ . '/includes/conexao.php'; // Ajuste o caminho conforme a estrutura do seu projeto

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

    // Verifica se o e-mail já está cadastrado
    $sql = "SELECT id FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $mensagem = "E-mail já cadastrado!";
    } else {
        // Insere o usuário no banco de dados
        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nome, $email, $senha);

        if ($stmt->execute()) {
            $mensagem = "Cadastro realizado com sucesso!";
            
            // Configuração do PHPMailer
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'seuemail@gmail.com';
                $mail->Password = 'suasenha'; // Substituir por uma Senha de App
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('seuemail@gmail.com', 'Meu Blog');
                $mail->addAddress($email, $nome);

                $mail->isHTML(true);
                $mail->Subject = 'Confirmação de Cadastro';
                $mail->Body = "Olá, $nome! <br> Seu cadastro foi realizado com sucesso. <br> <a href='http://localhost/meu_blog/admin/login.php'>Clique aqui para fazer login</a>";

                $mail->send();
            } catch (Exception $e) {
                $mensagem .= "<br>Erro ao enviar e-mail: {$mail->ErrorInfo}";
            }
        } else {
            $mensagem = "Erro ao cadastrar: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
</head>
<body>
    <h2>Cadastro de Usuário</h2>
    <?php if (isset($mensagem)) echo "<p>$mensagem</p>"; ?>
    <form method="POST">
        <label for="nome">Nome:</label><br>
        <input type="text" name="nome" required><br><br>

        <label for="email">E-mail:</label><br>
        <input type="email" name="email" required><br><br>

        <label for="senha">Senha:</label><br>
        <input type="password" name="senha" required><br><br>

        <button type="submit">Cadastrar</button>
    </form>
</body>
</html>
