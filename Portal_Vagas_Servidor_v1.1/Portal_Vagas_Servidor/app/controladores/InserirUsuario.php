<?php

// inserirUsuario.php

require_once __DIR__ . '/../../configuracao/banco_dados.php';// Inclui o arquivo de conexão com o banco de dados

class InserirUsuario {
    public static function inserirCandidato($nome, $email, $senha) {
        global $conexao;
        // Prepara a query
        $sql = "INSERT INTO candidatos (nome, email, senha) VALUES (?, ?, ?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("sss", $nome, $email, $senha);

        // Executa a query
        $resultado = $stmt->execute();

        // Verifica se a inserção foi bem-sucedida
        if ($resultado) {
            echo "Candidato cadastrado com sucesso!";
            header("location: ControleLogin.php");
        } else {
            echo "Erro ao cadastrar candidato: " . $conexao->error;
        }

        // Fecha a conexão
        $conexao->close();
    }

    public static function inserirEmpresa($nome, $email, $senha) {
        global $conexao;
        // Prepara a query
        $sql = "INSERT INTO empresas (nome, email, senha) VALUES (?, ?, ?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("sss", $nome, $email, $senha);

        // Executa a query
        $resultado = $stmt->execute();

        // Verifica se a inserção foi bem-sucedida
        if ($resultado) {
            echo "Empresa cadastrada com sucesso!";
            header("location: ControleLogin.php");
        } else {
            echo "Erro ao cadastrar empresa: " . $conexao->error;
        }

        // Fecha a conexão
        $conexao->close();
    }
}
