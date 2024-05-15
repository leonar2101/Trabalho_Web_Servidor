<?php include_once('../../visualizacoes/partes/cabecalhoEmpresa.php'); ?>

<h1>Lista de Vagas</h1>

<?php
// Iniciar sessão
session_start();

// Verificar se a sessão está definida
if (!isset($_SESSION['email'])) {
    // Redirecionar o usuário de volta para a página de login se não estiver logado
    header("Location: login.html");
    exit(); // Encerrar o script após o redirecionamento
}

$email = $_SESSION['email'];

// Incluir o arquivo de conexão com o banco de dados
require_once '../../../configuracao/banco_dados.php';
require_once '../../../vendor/autoload.php';


// Consulta ao banco de dados para recuperar os dados do usuário com base no email
$stmt = $conexao->prepare("SELECT * FROM empresas WHERE email = ?");
$stmt->execute([$email]);
$resultado = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar se os resultados foram obtidos com sucesso
if ($resultado) {
    // Obter o ID da empresa
    $empresa_id = $resultado['id'];

    // Consulta ao banco de dados para recuperar as vagas da empresa
    $stmt_vagas = $conexao->prepare("SELECT * FROM vagas WHERE empresa_id = ?");
    $stmt_vagas->execute([$empresa_id]);
    $result_vagas = $stmt_vagas->fetchAll(PDO::FETCH_ASSOC);

    // Exibir as vagas disponíveis
    if ($result_vagas && count($result_vagas) > 0) {
        foreach ($result_vagas as $vaga) {
            echo "<li>";
            echo "<strong>Título:</strong> " . htmlspecialchars($vaga['titulo']) . "<br>";
            echo "<strong>Descrição:</strong> " . htmlspecialchars($vaga['descricao']) . "<br>";
            echo "<strong>Localização:</strong> " . htmlspecialchars($vaga['localizacao']) . "<br>";
            echo "<strong>Salário:</strong> " . htmlspecialchars($vaga['faixa_salarial']) . "<br>";
            echo '<form action="candidatos.php" method="get" style="display:inline;">';
            echo '<input type="hidden" name="vaga_id" value="' . $vaga['id'] . '">';
            echo '<button type="submit" name="visualizar_candidatos">Visualizar Candidatos</button>';
            echo '</form>';
            echo '<form action="../../controladores/ControleVagas.php" method="post" style="display:inline;">';
            echo '<input type="hidden" name="acao" value="excluir_vaga">';
            echo '<input type="hidden" name="vaga_id" value="' . $vaga['id'] . '">';
            echo '<button type="submit">Excluir</button>';
            echo '</form>';
            //echo '<form action="../../controladores/InserirVaga.php@excluirVaga" method="post" style="display:inline;">';
            //echo '<input type="hidden" name="vaga_id" value="' . $vaga['id'] . '">';
            //echo '<button type="submit" name="excluir_vaga">Excluir</button>';
            //echo '</form>';
            echo "</li>";
        }
    } else {
        echo "<p>Nenhuma vaga disponível.</p>";
    }
} else {
    // Se o usuário não for encontrado no banco de dados, redirecionar para a página de login
    header("Location: login.php");
    exit(); // Encerrar o script após o redirecionamento
}
?>

<?php include_once('../../visualizacoes/partes/rodape.php'); ?>

