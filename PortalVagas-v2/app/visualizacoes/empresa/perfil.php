<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil da Empresa - Portal de Vagas</title>
    <?php include('../../visualizacoes/partes/cabecalhoEmpresa.php'); ?>
    <link rel="stylesheet" type="text/css" href="../../estilos/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
<div class="container"><br>
    <?php
    session_start();
    if (!isset($_SESSION['email'])) {
        header("Location: login.html");
        exit();
    }

    $email = $_SESSION['email'];
    include('/xampp/htdocs/PortalVagas-v2/configuracao/banco_dados.php');

    try {
        // Prepara a query SQL
        $query = "SELECT * FROM empresas WHERE email = :email";
        $stmt = $conexao->prepare($query);

        // Binde os parâmetros
        $stmt->bindParam(':email', $email);

        // Executa a query
        $stmt->execute();

        // Verifica se há resultados
        if ($stmt->rowCount() == 1) {
            // Recupera os dados da empresa
            $empresa = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            // Redireciona para a página de login se a empresa não for encontrada
            header("Location: login.php");
            exit();
        }
    } catch (PDOException $e) {
        // Em caso de erro, mostra a mensagem de erro
        echo "Erro ao buscar perfil da empresa: " . $e->getMessage();
    }
    ?>

    <!-- Conteúdo do perfil da empresa -->
    <?php include('empresa_dados.php'); ?>

</div>
<?php include('../../visualizacoes/partes/rodape.php'); 
include '../../../vendor/autoload.php';
?>
</body>

</html>
