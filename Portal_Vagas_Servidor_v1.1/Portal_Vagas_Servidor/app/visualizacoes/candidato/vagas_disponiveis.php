<?php include('../../visualizacoes/partes/cabecalho.php');?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vagas Disponiveis - Portal de Vagas</title>
    <link rel="stylesheet" href="../estilos/estilos.css">
</head>
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
$query = "SELECT * FROM vagas";
$resultado = $conexao->query($query);

    if($resultado->num_rows > 0){
        while ($vagas = $resultado->fetch_assoc()) {
            echo "<li>";
            echo "<strong>Título:</strong> " . $vagas['titulo'] . "<br>";
            echo "<strong>Descrição:</strong> " . $vagas['descricao'] . "<br>";
            echo "<strong>Localização:</strong> " . $vagas['localizacao'] . "<br>";
            echo "<strong>Salário:</strong> " . $vagas['faixa_salarial'] . "<br>";
            echo '</form>';
            echo "</li>";
        }
    }else{
        echo "<h1> Não há vagas </h1>";
    }

    include_once('../../visualizacoes/partes/rodape.php');

?>