<?php
require_once '../../../configuracao/banco_dados.php';
require_once '../../../vendor/autoload.php';


function obterDadosCandidato($email) {
    global $conexao;

    try {
        // Prepara a query SQL
        $query = "SELECT * FROM candidatos WHERE email = :email";
        $stmt = $conexao->prepare($query);

        // Binde os parâmetros
        $stmt->bindParam(':email', $email);

        // Executa a query
        $stmt->execute();

        // Retorna os resultados como um array associativo
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        // Em caso de erro, mostra a mensagem de erro
        echo "Erro ao obter dados do candidato: " . $e->getMessage();
        return false;
    }
}

function adicionarExperiencia($candidato_id, $nova_experiencia) {
    global $conexao;

    try {
        // Prepara a query SQL
        $stmt = $conexao->prepare("INSERT INTO experiencias_trabalho (candidato_id, experiencia) VALUES (:candidato_id, :nova_experiencia)");

        // Binde os parâmetros
        $stmt->bindParam(':candidato_id', $candidato_id);
        $stmt->bindParam(':nova_experiencia', $nova_experiencia);

        // Executa a query
        $stmt->execute();
    } catch(PDOException $e) {
        // Em caso de erro, mostra a mensagem de erro
        echo "Erro ao adicionar experiência: " . $e->getMessage();
    }
}

function editarDescricao($candidato_id, $nova_descricao) {
    global $conexao;

    try {
        // Prepara a query SQL
        $stmt = $conexao->prepare("UPDATE candidatos SET descricao = :nova_descricao WHERE id = :candidato_id");

        // Binde os parâmetros
        $stmt->bindParam(':nova_descricao', $nova_descricao);
        $stmt->bindParam(':candidato_id', $candidato_id);

        // Executa a query
        $stmt->execute();
    } catch(PDOException $e) {
        // Em caso de erro, mostra a mensagem de erro
        echo "Erro ao editar descrição: " . $e->getMessage();
    }
}

function excluirExperiencia($experiencia_id) {
    global $conexao;

    try {
        // Prepara a query SQL
        $stmt = $conexao->prepare("DELETE FROM experiencias_trabalho WHERE id = :experiencia_id");

        // Binde os parâmetros
        $stmt->bindParam(':experiencia_id', $experiencia_id);

        // Executa a query
        $stmt->execute();
    } catch(PDOException $e) {
        // Em caso de erro, mostra a mensagem de erro
        echo "Erro ao excluir experiência: " . $e->getMessage();
    }
}

function atualizarFotoPerfil($candidato_id, $foto_perfil, $foto_perfil_type) {
    global $conexao;

    try {
        // Prepara a query SQL
        $stmt = $conexao->prepare("UPDATE candidatos SET foto_perfil = :foto_perfil, foto_perfil_type = :foto_perfil_type WHERE id = :candidato_id");

        // Binde os parâmetros
        $stmt->bindParam(':foto_perfil', $foto_perfil);
        $stmt->bindParam(':foto_perfil_type', $foto_perfil_type);
        $stmt->bindParam(':candidato_id', $candidato_id);

        // Executa a query
        $stmt->execute();
    } catch(PDOException $e) {
        // Em caso de erro, mostra a mensagem de erro
        echo "Erro ao atualizar foto de perfil: " . $e->getMessage();
    }
}
?>
