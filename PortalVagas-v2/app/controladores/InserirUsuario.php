<?php

require_once __DIR__ . '/../../configuracao/banco_dados.php';
require_once  __DIR__. '/../../vendor/autoload.php';

class InserirUsuario {
    public static function inserirCandidato($nome, $email, $senha) {
        global $conexao;

        try {
            // Prepara a query SQL
            $sql = "INSERT INTO candidatos (nome, email, senha) VALUES (:nome, :email, :senha)";
            $stmt = $conexao->prepare($sql);

            // Binde os parâmetros
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $senha);

            // Executa a query
            $stmt->execute();

            echo "Candidato cadastrado com sucesso!";
            header("location: ControleLogin.php");
        } catch(PDOException $e) {
            // Em caso de erro, mostra a mensagem de erro
            echo "Erro ao cadastrar candidato: " . $e->getMessage();
        }
    }

    public static function inserirEmpresa($nome, $email, $senha) {
        global $conexao;

        try {
            // Prepara a query SQL
            $sql = "INSERT INTO empresas (nome, email, senha) VALUES (:nome, :email, :senha)";
            $stmt = $conexao->prepare($sql);

            // Binde os parâmetros
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $senha);

            // Executa a query
            $stmt->execute();

            echo "Empresa cadastrada com sucesso!";
            header("location: ControleLogin.php");
        } catch(PDOException $e) {
            // Em caso de erro, mostra a mensagem de erro
            echo "Erro ao cadastrar empresa: " . $e->getMessage();
        }
    }
}
