<?php
$host = "localhost"; // Servidor do banco de dados
$user = "root"; // Usuário do banco
$password = ""; // Senha do banco (deixe vazio se estiver no XAMPP)
$database = "mim"; // Nome do banco de usuários

$conn_usuarios = new mysqli($host, $user, $password, $database);

// Verifica a conexão
if ($conn_usuarios->connect_error) {
    die("Erro de conexão com o banco de usuários: " . $conn_usuarios->connect_error);
}
?>
