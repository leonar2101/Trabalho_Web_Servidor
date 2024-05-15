<?php
session_start();

require_once __DIR__ . '/../../../configuracao/banco_dados.php';
require_once __DIR__ . '/../../controladores/ControlePerfilCandidato.php';
require_once '../../../vendor/autoload.php';


// Verificar se o usuário está logado
if (!isset($_SESSION['email'])) {
    header("Location: ../../login.php");
    exit();
}

$email = $_SESSION['email'];

// Obter dados do candidato
$candidato = obterDadosCandidato($email);

if (!$candidato) {
    echo "Erro ao obter dados do candidato.";
    exit();
}

// Verificar se o formulário foi submetido para adicionar nova experiência
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nova_experiencia'])) {
    $nova_experiencia = $_POST['nova_experiencia'];
    adicionarExperiencia($candidato['id'], $nova_experiencia);
}

// Verificar se o formulário foi submetido para editar a descrição
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nova_descricao'])) {
    $nova_descricao = $_POST['nova_descricao'];
    editarDescricao($candidato['id'], $nova_descricao);
}

// Verificar se o formulário foi submetido para excluir uma experiência
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_experiencia'])) {
    $experiencia_id = $_POST['experiencia_id'];
    excluirExperiencia($experiencia_id);
}

// Verificar se o formulário foi submetido para atualizar a foto de perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['nova_foto_perfil'])) {
    $foto_perfil = file_get_contents($_FILES['nova_foto_perfil']['tmp_name']);
    $foto_perfil_type = $_FILES['nova_foto_perfil']['type'];
    atualizarFotoPerfil($candidato['id'], $foto_perfil, $foto_perfil_type);
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Candidato - Portal de Vagas</title>
    <?php include('../../visualizacoes/partes/cabecalho.php'); ?>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
<div class="container"><br>
    <?php if (isset($candidato)) : ?>
        <?php
        if (!empty($candidato['foto_perfil']) && !empty($candidato['foto_perfil_type'])) {
            $foto_perfil_src = 'data:' . $candidato['foto_perfil_type'] . ';base64,' . base64_encode($candidato['foto_perfil']);
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
            <p><strong></strong> <?php echo $candidato['nome']; ?></p>
        </div>
        <div class="email">
            <p><strong>Email:</strong> <?php echo $candidato['email']; ?></p>
        </div>
        <div class="descricao">
            <strong>Descrição:</strong> <?php echo $candidato['descricao']; ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="display: inline;">
                <input type="hidden" name="editar_descricao" value="true">
                <button type="submit" class="edit"><i class="fas fa-pencil-alt"></i></button>
            </form>

        </div>

        <?php if (isset($_POST['editar_descricao'])) : ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <textarea name="nova_descricao" rows="4" cols="50"></textarea><br>
                <button type="submit" class="save" name="salvar_descricao">Salvar</button>
                <button type="submit" class="cancelar" name="cancelar_edicao">Cancelar</button>
            </form>
        <?php endif; ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="text" name="nova_experiencia" placeholder="Nova Experiência" required>
            <button type="submit" class="xp" name="adicionar_experiencia"><i class="fas fa-plus"></i> Adicionar Experiência</button>
        </form>
        <h2>Experiências de Trabalho</h2>
        <ul>
            <?php
            $stmt = $conexao->prepare("SELECT * FROM experiencias_trabalho WHERE candidato_id = ?");
            $stmt->bindParam(1, $candidato['id']);
            $stmt->execute();
            $result_experiencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($result_experiencias as $row) {
                $experiencia_id = $row["id"];
                $experiencia_descricao = $row["experiencia"];
                echo "<li>";
                echo "<form action='" . htmlspecialchars($_SERVER["PHP_SELF"]) . "' method='post' style='display:inline;'>";
                echo "<input type='text' name='nova_experiencia_" . $experiencia_id . "' value='" . $experiencia_descricao . "' required style='display: none;'>";
                echo "<input type='hidden' name='experiencia_id' value='" . $experiencia_id . "'>";
                echo "<span>" . $experiencia_descricao . "</span>";
                echo "<button type='submit' class='delete' name='excluir_experiencia'><i class='fas fa-trash-alt'></i></button>";
                echo "</form>";
                echo "</li>";
            }
            ?>
        </ul>
    <?php endif; ?>

</div>
<?php include('../../visualizacoes/partes/rodape.php'); ?>
</body>

</html>
