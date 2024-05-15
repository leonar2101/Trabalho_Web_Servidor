<?php
require_once '../../../vendor/autoload.php';

require_once __DIR__ . '/../visualizacoes/empresa/ModeloPerfilE.php';

if (!isset($_SESSION['email'])) {
    header("Location:/../login.php");
    exit();
}

$email = $_SESSION['email'];
$empresa = obterDadosEmpresa($email);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['salvar_descricao'])) {
        $nova_descricao = $_POST['nova_descricao'];
        editarDescricaoE($empresa['id'], $nova_descricao);
        header("Location: perfil.php");
        exit();
    }


    if (isset($_FILES["foto_perfil"])) {
        $foto_perfil = file_get_contents($_FILES["foto_perfil"]["tmp_name"]);
        $foto_perfil_type = $_FILES["foto_perfil"]["type"];
        atualizarFotoPerfilEmpresa($empresa['id'], $foto_perfil, $foto_perfil_type);
        header("Location: perfil.php");
        exit();
    }
}
?>
