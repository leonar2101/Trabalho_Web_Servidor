<?php
require_once __DIR__ . '/../../app/visualizacoes/candidato/ModeloPerfilC.php';

if (!isset($_SESSION['email'])) {
    header("Location:/../login.php");
    exit();
}

$email = $_SESSION['email'];
$candidato = obterDadosCandidato($email);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['adicionar_experiencia'])) {
        $nova_experiencia = $_POST['nova_experiencia'];
        adicionarExperiencia($candidato['id'], $nova_experiencia);
        header("Location: perfil.php");
        exit();
    }

    if (isset($_POST['salvar_descricao'])) {
        $nova_descricao = $_POST['nova_descricao'];
        editarDescricao($candidato['id'], $nova_descricao);
        header("Location: perfil.php");
        exit();
    }

    if (isset($_POST['excluir_experiencia'])) {
        $experiencia_id = $_POST['experiencia_id'];
        excluirExperiencia($experiencia_id);
        header("Location: perfil.php");
        exit();
    }

    if (isset($_FILES["foto_perfil"])) {
        $foto_perfil = file_get_contents($_FILES["foto_perfil"]["tmp_name"]);
        $foto_perfil_type = $_FILES["foto_perfil"]["type"];
        atualizarFotoPerfil($candidato['id'], $foto_perfil, $foto_perfil_type);
        header("Location: perfil.php");
        exit();
    }
}
?>
