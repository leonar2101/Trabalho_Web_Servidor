<?php
require "../visualizacoes/empresa/cadastrar_vaga.php";
require_once 'InserirVaga.php';
require_once '../../app/visualizacoes/empresa/ModeloPerfilE.php';


// Verificar se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar_vaga'])) {
    // Recebe os dados do formulário
    $titulo_vaga = $_POST['titulo_vaga'];
    $descricao_vaga = $_POST['descricao_vaga'];
    $localizacao_vaga = $_POST['localizacao_vaga'];
    $salario_vaga = $_POST['salario_vaga'];
    session_start();
    $empresa = obterDadosEmpresa($_SESSION['email']);

    // Validar os dados
    if (!empty($titulo_vaga) && !empty($descricao_vaga) && !empty($localizacao_vaga) && !empty($salario_vaga)) {
        InserirVaga::inserirVaga($titulo_vaga, $descricao_vaga, $localizacao_vaga, $salario_vaga, $empresa['id']);
    } else {
        echo "Todos os campos são obrigatórios.";

    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao']) && $_POST['acao'] === 'excluir_vaga') {
    if (isset($_POST['vaga_id'])) {
        $vaga_id = $_POST['vaga_id'];
        InserirVaga::excluirVaga($vaga_id);
    } else {
        echo "ID da vaga não fornecido.";
    }
}
