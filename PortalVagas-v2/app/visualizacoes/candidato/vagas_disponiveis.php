<?php include('../../visualizacoes/partes/cabecalho.php'); ?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vagas Disponiveis - Portal de Vagas</title>
    <link rel="stylesheet" href="../estilos/estilos.css">
</head>

<?php
// Iniciar sessão
session_start();

// Verificar se a sessão está definida
if (!isset($_SESSION['email'])) {
    // Redirecionar o usuário de volta para a página de login se não estiver logado
    header("location:../login.html");
    exit(); // Encerrar o script após o redirecionamento
}

// Incluir o arquivo de conexão com o banco de dados
require_once '../../../configuracao/banco_dados.php';
require_once '../../../vendor/autoload.php';

try {
    // Consulta ao banco de dados para recuperar os dados das vagas
    $query = "SELECT * FROM vagas";
    $stmt = $conexao->prepare($query);
    $stmt->execute();
    $result_vagas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($result_vagas) {
        foreach ($result_vagas as $vaga) {
            echo "<ul>";
            echo "<strong>Título:</strong> " . $vaga['titulo'] . "<br>";
            echo "<strong>Descrição:</strong> " . $vaga['descricao'] . "<br>";
            echo "<strong>Localização:</strong> " . $vaga['localizacao'] . "<br>";
            echo "<strong>Salário:</strong> " . $vaga['faixa_salarial'] . "<br>";
            // Botão para candidatar-se
            echo '<form action="" method="post">';
            echo '<input type="hidden" name="vaga_id" value="' . $vaga['id'] . '">';
            echo '<button type="submit">Candidatar-se</button>';
            echo '</form>';
            echo "</ul>";
        }
    } else {
        echo "<h1>Não há vagas disponíveis</h1>";
    }
} catch (PDOException $e) {
    // Em caso de erro, mostra a mensagem de erro
    echo "Erro ao executar a consulta: " . $e->getMessage();
}

include_once('../../visualizacoes/partes/rodape.php');
?>
