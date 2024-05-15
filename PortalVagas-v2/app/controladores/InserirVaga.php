<?php

require_once __DIR__ . '/../../configuracao/banco_dados.php';
require_once  __DIR__. '/../../vendor/autoload.php';

class InserirVaga {
    public static function inserirVaga($titulo_vaga, $descricao_vaga, $localizacao_vaga, $salario_vaga, $empresa_id) {
        global $conexao;

        try {
            // Prepara a query SQL
            $sql = "INSERT INTO vagas (titulo, descricao, localizacao, faixa_salarial, empresa_id) VALUES (:titulo, :descricao, :localizacao, :faixa_salarial, :empresa_id)";
            $stmt = $conexao->prepare($sql);

            // Binde os parâmetros
            $stmt->bindParam(':titulo', $titulo_vaga);
            $stmt->bindParam(':descricao', $descricao_vaga);
            $stmt->bindParam(':localizacao', $localizacao_vaga);
            $stmt->bindParam(':faixa_salarial', $salario_vaga);
            $stmt->bindParam(':empresa_id', $empresa_id);

            // Executa a query
            $stmt->execute();

            echo "Vaga cadastrada com sucesso!";
            header("location: ../../app/visualizacoes/empresa/perfil.php");
        } catch(PDOException $e) {
            // Em caso de erro, mostra a mensagem de erro
            echo "Erro ao cadastrar vaga: " . $e->getMessage();
        }
    }
    public static function excluirVaga($vaga_id) {
        global $conexao;

        try {
            // Prepara a query SQL para excluir uma vaga
            $sql = "DELETE FROM vagas WHERE id = :vaga_id";
            $stmt = $conexao->prepare($sql);

            // Binde o parâmetro
            $stmt->bindParam(':vaga_id', $vaga_id);

            // Executa a query
            $stmt->execute();

            echo "Vaga excluída com sucesso!";
            header("location: ../../app/visualizacoes/empresa/perfil.php");
        } catch(PDOException $e) {
            // Em caso de erro, mostra a mensagem de erro
            echo "Erro ao excluir vaga: " . $e->getMessage();
        }
    }
}