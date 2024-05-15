<?php
//require_once '../../../configuracao/banco_dados.php';

function obterDadosEmpresa($email) {
    global $conexao;

    try {
        // Prepara a query SQL
        $query = "SELECT * FROM empresas WHERE email = :email";
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

function editarDescricaoE($empresa_id, $nova_descricao) {
    global $conexao;

    try {
        // Prepara a query SQL
        $stmt = $conexao->prepare("UPDATE empresas SET descricao = :nova_descricao WHERE id = :empresa_id");

        // Binde os parâmetros
        $stmt->bindParam(':nova_descricao', $nova_descricao);
        $stmt->bindParam(':empresa_id', $empresa_id);

        // Executa a query
        $stmt->execute();
    } catch(PDOException $e) {
        // Em caso de erro, mostra a mensagem de erro
        echo "Erro ao editar descrição: " . $e->getMessage();
    }
}

function atualizarFotoPerfilEmpresa($empresa_id, $foto_perfil, $foto_perfil_type) {
    global $conexao;

    try {
        // Prepara a query SQL
        $stmt = $conexao->prepare("UPDATE empresas SET foto_perfil = :foto_perfil, foto_perfil_type = :foto_perfil_type WHERE id = :empresa_id");

        // Binde os parâmetros
        $stmt->bindParam(':foto_perfil', $foto_perfil);
        $stmt->bindParam(':foto_perfil_type', $foto_perfil_type);
        $stmt->bindParam(':empresa_id', $empresa_id);

        // Executa a query
        $stmt->execute();
    } catch(PDOException $e) {
        // Em caso de erro, mostra a mensagem de erro
        echo "Erro ao atualizar foto de perfil: " . $e->getMessage();
    }
}
?>
