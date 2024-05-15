<?php
require_once __DIR__ . '/../../../configuracao/banco_dados.php';
require_once __DIR__ . '/../../controladores/ControlePerfilEmpresa.php';
require_once '../../../vendor/autoload.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['email'])) {
    header("Location: ../../login.php");
    exit();
}

$email = $_SESSION['email'];

// Obter dados da empresa
$empresa = obterDadosEmpresa($email);

if (!$empresa) {
    echo "Erro ao obter dados da empresa.";
    exit();
}


// Verificar se o formulário foi submetido para editar a descrição
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nova_descricao'])) {
    $nova_descricao = $_POST['nova_descricao'];
    editarDescricaoE($empresa['id'], $nova_descricao);
}

// Verificar se o formulário foi submetido para atualizar a foto de perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['nova_foto_perfil'])) {
    $foto_perfil = file_get_contents($_FILES['nova_foto_perfil']['tmp_name']);
    $foto_perfil_type = $_FILES['nova_foto_perfil']['type'];
    atualizarFotoPerfilEmpresa($empresa['id'], $foto_perfil, $foto_perfil_type);
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil da Empresa - Portal de Vagas</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
<div class="container"><br>
    <?php if (isset($empresa)) : ?>
        <?php
        if (!empty($empresa['foto_perfil']) && !empty($empresa['foto_perfil_type'])) {
            $foto_perfil_src = 'data:' . $empresa['foto_perfil_type'] . ';base64,' . base64_encode($empresa['foto_perfil']);
            echo '<img src="' . $foto_perfil_src . '" alt="Foto de Perfil" class="foto-perfil">';
        } else {
            echo '<img src="caminho_para_imagem_padrao" alt="Foto de Perfil" class="foto-perfil">';
        }
        ?>

        <?php if (!isset($_POST['trocar_foto'])) : ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="trocar_foto" value="true">
                <button type="submit" class="trocar-foto-button" name="trocar_foto_btn"><i class="fas fa-camera"></i></button>
            </form>
        <?php endif; ?>

        <?php if (isset($_POST['trocar_foto'])) : ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <input type="file" class="selecionar" name="nova_foto_perfil" accept="image/*" required>
                <button type="submit" class="alterar-foto">Trocar Foto</button>
                <button type="button" class="cancelar" onclick="window.location.href='perfil.php'">Cancelar</button>
            </form>
        <?php endif; ?>

        <div class="user">
            <p><strong>Nome:</strong> <?php echo $empresa['nome']; ?></p>
        </div>
        <div class="email">
            <p><strong>Email:</strong> <?php echo $empresa['email']; ?></p>
        </div>
        <div class="descricao">
            <p><strong>Descrição:</strong> <?php echo $empresa['descricao']; ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="display: inline;">
                <input type="hidden" name="editar_descricao" value="true">
                <button type="submit" class="edit"><i class="fas fa-pencil-alt"></i></button>
            </form>
            </p>
        </div>

        <?php if (isset($_POST['editar_descricao'])) : ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <textarea name="nova_descricao" rows="4" cols="50"></textarea><br>
                <button type="submit" class="save" name="salvar_descricao">Salvar</button>
                <button type="button" class="cancelar" onclick="window.location.href='perfil.php'">Cancelar</button>
            </form>
        <?php endif; ?>

    <?php endif; ?>

</div>
</body>

</html>
