<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Nova Vaga</title>
    <link rel="stylesheet" href="../../estilos/estilos.css">
</head>

<body>
<div class="container">
    <h2>Cadastrar Nova Vaga</h2>
    <form action="../../controladores/ControleVagas.php" method="post" class="form-cadastro">
        <label for="titulo_vaga">Título da Vaga:</label><br>
        <input type="text" id="titulo_vaga" name="titulo_vaga" required><br>

        <label for="descricao_vaga">Descrição da Vaga:</label><br>
        <textarea id="descricao_vaga" name="descricao_vaga" rows="4" required></textarea><br>

        <label for="localizacao_vaga">Localização:</label><br>
        <input type="text" id="localizacao_vaga" name="localizacao_vaga" required><br>

        <label for="salario_vaga">Salário:</label><br>
        <input type="number" id="salario_vaga" name="salario_vaga" required><br>

        <br><input type="submit" class="cadastrar-vaga" name="cadastrar_vaga" value="Cadastrar Vaga">
    </form>
</div><?php
if (isset($empresa)) : echo $empresa['nome']; ?>
</body>

</html>
<?php
require_once '../../../vendor/autoload.php';

endif;
?>