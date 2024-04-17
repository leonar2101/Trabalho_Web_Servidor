<?php

// Configurações do banco de dados
$host = 'localhost'; // Host do banco de dados
$usuario = 'root'; // Usuário do banco de dados
$senha = ''; // Senha do banco de dados
$banco_dados = 'bd_portalvagas'; // Nome do banco de dados

// Conectar ao banco de dados
$conexao= new mysqli($host, $usuario, $senha, $banco_dados);

// Verificar conexão
if ($conexao->connect_error) {
    die("Erro de conexão: " . $conexao->connect_error);
}

// Definir o conjunto de caracteres para utf8
$conexao->set_charset("utf8");
return $conexao;

?>
