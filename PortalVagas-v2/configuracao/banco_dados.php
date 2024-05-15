<?php


// Configurações do banco de dados
$host = 'localhost'; // Host do banco de dados
$dbname = 'bd_portalvagas'; // Nome do banco de dados
$username = 'root'; // Nome de usuário do banco de dados
$password = ''; // Senha do banco de dados

try {
    // Criar uma nova conexão PDO
    $conexao = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    // Definir o modo de erro do PDO para exceção
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Em caso de erro, mostrar mensagem de erro
    echo 'Erro de conexão: ' . $e->getMessage();
    exit(); // Encerrar o script em caso de erro
}
?>

