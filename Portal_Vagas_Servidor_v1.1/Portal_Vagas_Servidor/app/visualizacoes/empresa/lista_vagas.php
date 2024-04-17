<?php include_once('../../visualizacoes/partes/cabecalhoEmpresa.php'); ?>

<h1>Lista de Vagas</h1>
<?php
// iniciar sessao
session_start();
// Verifique se a sessão está definida
if (!isset($_SESSION['email'])) {
    // Redirecione o usuário de volta para a página de login se não estiver logado
    header("Location: login.php");
    exit(); // Encerre o script após o redirecionamento
}
$email = $_SESSION['email'];
// Incluir o arquivo de conexão com o banco de dados
include('/xampp/htdocs/Portal_Vagas_Servidor_v1.1/Portal_Vagas_Servidor/configuracao/banco_dados.php');

// Consulta ao banco de dados para recuperar os dados do usuário com base no email (deve ser parametrizada para evitar injeção de SQL)
$query = "SELECT * FROM empresas WHERE email = '$email'";
$resultado = $conexao->query($query);

// Verifique se os resultados foram obtidos com sucesso
if ($resultado->num_rows == 1) {
    // Recupera os dados do usuário
    $empresa = $resultado->fetch_assoc();

} else {
    // Se o usuário não for encontrado no banco de dados, redirecione-o para a página de login
    header("Location: login.php");
    exit(); // Encerre o script após o redirecionamento
}

//setando id empresa
$empresa_id = $empresa['id'];
// Obter as vagas disponíveis da empresa
$stmt_vagas = $conexao->prepare("SELECT * FROM vagas WHERE empresa_id = ?");
$stmt_vagas->bind_param("i", $empresa_id);
$stmt_vagas->execute();
$result_vagas = $stmt_vagas->get_result();

// Exibir as vagas disponíveis
if ($result_vagas && $result_vagas->num_rows > 0) {
    while ($vaga = $result_vagas->fetch_assoc()) {
        echo "<li>";
        echo "<strong>Título:</strong> " . $vaga['titulo'] . "<br>";
        echo "<strong>Descrição:</strong> " . $vaga['descricao'] . "<br>";
        echo "<strong>Localização:</strong> " . $vaga['localizacao'] . "<br>";
        echo "<strong>Salário:</strong> " . $vaga['faixa_salarial'] . "<br>";
        echo '<form action="perfil.php" method="post" style="display:inline;">';
        echo '<input type="hidden" name="vaga_id" value="' . $vaga['id'] . '">';
        echo '<button type="submit" name="excluir_vaga"><i class="fas fa-trash-alt"></i></button>';
        echo '</form>';
        echo "</li>";
    }
} else {
    echo "<p>Nenhuma vaga disponível.</p>";
}
?>

<?php include_once('../../visualizacoes/partes/rodape.php'); ?>
