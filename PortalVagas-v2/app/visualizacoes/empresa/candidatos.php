<?php
// Incluir o arquivo de conexão com o banco de dados
require_once '../../../configuracao/banco_dados.php';
require_once '../../../vendor/autoload.php';

// Verificar se o parâmetro vaga_id foi enviado via GET
if(isset($_GET['vaga_id'])) {
    $vaga_id = $_GET['vaga_id'];

    // Consulta ao banco de dados para recuperar os candidatos para a vaga especificada
    $stmt_candidatos = $conexao->prepare("SELECT * FROM candidatos WHERE id = ?");
    $stmt_candidatos->bindParam(1, $vaga_id, PDO::PARAM_INT);
    $stmt_candidatos->execute();
    $result_candidatos = $stmt_candidatos->fetchAll(PDO::FETCH_ASSOC);

    // Verificar se há candidatos
    if($result_candidatos) {
        // Loop através dos candidatos e exibir suas informações
        foreach ($result_candidatos as $candidato) {
            echo "<p><strong>Nome:</strong> " . $candidato['nome'] . "</p>";
            echo "<p><strong>Email:</strong> " . $candidato['email'] . "</p>";
            echo "<p><strong>Descrição:</strong> " . $candidato['descricao'] . "</p>";
            echo "<hr>";
        }
    } else {
        echo "<p>Nenhum candidato inscrito para esta vaga.</p>";
    }
} else {
    // Se o parâmetro vaga_id não foi fornecido, redirecionar de volta para a página de vagas disponíveis
    header("Location: vagas_disponiveis.php");
    exit(); // Encerrar o script após o redirecionamento
}
?>
