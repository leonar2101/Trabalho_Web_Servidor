<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil do Candidato- Portal de Vagas</title>
    <?php include('../../visualizacoes/partes/cabecalho.php'); ?>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>

<body>
<div class="container">
    <h1>Meu Perfil</h1>
    <?php
    // iniciar sessao
    session_start();
    // Verifique se a sessão está definida
    if (!isset($_SESSION['email'])) {
        // Redirecione o usuário de volta para a página de login se não estiver logado
        header("Location: login.php");
        exit(); // Encerre o script após o redirecionamento
    }

    // Recupere o email da sessão
    $email = $_SESSION['email'];
    // Incluir o arquivo de conexão com o banco de dados
    include('/xampp/htdocs/Portal_Vagas_Servidor_v1.1/Portal_Vagas_Servidor/configuracao/banco_dados.php');

    // Consulta ao banco de dados para recuperar os dados do usuário com base no email (deve ser parametrizada para evitar injeção de SQL)
    $query = "SELECT * FROM candidatos WHERE email = '$email'";
    $resultado = $conexao->query($query);

    // Verifique se os resultados foram obtidos com sucesso
    if ($resultado->num_rows == 1) {
        // Recupera os dados do usuário
        $candidato = $resultado->fetch_assoc();
        //     echo "id do candidato: " . $candidato['id'];
        //     echo "Nome do candidato: " . $candidato['nome'];
        //    echo "Email do candidato: " . $candidato['email'];
        //    echo "Descricao do candidato: " . $candidato['descricao'];
        //    echo "foto do candidato: " . $candidato['foto_perfil'];
        //    echo "foto tipo do candidato: " . $candidato['foto_perfil_type'];

    } else {
        // Se o usuário não for encontrado no banco de dados, redirecione-o para a página de login
        header("Location: login.php");
        exit(); // Encerre o script após o redirecionamento
    }

//setando id candidato
    $candidato_id = $candidato['id'];
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Adicionar uma nova experiência de trabalho
        if (isset($_POST['adicionar_experiencia'])) {
            $nova_experiencia = $_POST['nova_experiencia'];

            // Inserir a nova experiência de trabalho no banco de dados
            $stmt = $conexao->prepare("INSERT INTO experiencias_trabalho (candidato_id, experiencia) VALUES (?, ?)");
            $stmt->bind_param("is", $candidato_id, $nova_experiencia);
            $stmt->execute();
            $stmt->close();

            // Redirecionar de volta para a página de perfil após a inserção
            header("Location: perfil.php");
            exit();
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Verificar se a edição da descrição foi solicitada
        if (isset($_POST['editar_descricao'])) {
            // Exibir o formulário para editar a descrição
            $editar_descricao = true;
        }
        // Verificar se a descrição foi editada e está sendo salva
        elseif (isset($_POST['salvar_descricao'])) {
            // Processar o formulário de edição da descrição
            $nova_descricao = $_POST['nova_descricao'];

            // Atualizar a descrição no banco de dados
            $stmt = $conexao->prepare("UPDATE candidatos SET descricao = ? WHERE id = ?");
            $stmt->bind_param("si", $nova_descricao, $candidato_id);
            $stmt->execute();
            $stmt->close();

            // Redirecionar de volta para a página de perfil após a atualização
            header("Location: perfil.php");
            exit();
        }
    }

    // Obter as experiências de trabalho do candidato do banco de dados
    $stmt = $conexao->prepare("SELECT * FROM experiencias_trabalho WHERE candidato_id = ?");
    $stmt->bind_param("i", $candidato_id);
    $stmt->execute();
    $result_experiencias = $stmt->get_result();


    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["foto_perfil"])) {
        $foto_perfil = file_get_contents($_FILES["foto_perfil"]["tmp_name"]);
        $foto_perfil_type = $_FILES["foto_perfil"]["type"];

        // Salvar a imagem no banco de dados
        $stmt = $conexao->prepare("UPDATE candidatos SET foto_perfil = ?, foto_perfil_type = ? WHERE id = ?");
        $stmt->bind_param("ssi", $foto_perfil, $foto_perfil_type, $candidato_id);
        $stmt->execute();
        $stmt->close();

        // Redirecionar de volta para a página de perfil após o upload
        header("Location: perfil.php");
        exit();
    }

    // Obter as informações do candidato do banco de dados
    $stmt = $conexao->prepare("SELECT * FROM candidatos WHERE id = ?");
    $stmt->bind_param("i", $candidato_id);
    $stmt->execute();
    $result_candidato = $stmt->get_result();


    // Verificar se os resultados foram obtidos com sucesso
    if ($result_candidato && $result_candidato->num_rows > 0) {
        $candidato = $result_candidato->fetch_assoc();
    }



    // Verificar se o formulário de upload foi submetido
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['editar_descricao'])) {
            // Exibir o formulário para editar a descrição
            $editar_descricao = true;
        } elseif (isset($_POST['salvar_descricao'])) {
            // Processar o formulário de edição da descrição
            $nova_descricao = $_POST['nova_descricao'];

            // Atualizar a descrição no banco de dados
            $stmt = $conexao->prepare("UPDATE candidatos SET descricao = ? WHERE id = ?");
            $stmt->bind_param("si", $nova_descricao, $candidato_id);
            $stmt->execute();
            $stmt->close();

            // Redirecionar de volta para a página de perfil após a atualização
            header("Location: perfil.php");
            exit();
        } elseif (isset($_POST['cancelar_edicao'])) {
            // Cancelar a edição da descrição
            header("Location: perfil.php");
            exit();
        }
    }

    // Obter as informações do candidato do banco de dados
    $stmt = $conexao->prepare("SELECT * FROM candidatos WHERE id = ?");
    $stmt->bind_param("i", $candidato_id);
    $stmt->execute();
    $result_candidato = $stmt->get_result();

    // Verificar se os resultados foram obtidos com sucesso
    if ($result_candidato && $result_candidato->num_rows > 0) {
        $candidato = $result_candidato->fetch_assoc();
    }

    // Excluir uma experiência de trabalho
    if (isset($_POST['excluir_experiencia'])) {
        $experiencia_id = $_POST['experiencia_id'];

        // Excluir a experiência de trabalho do banco de dados
        $stmt = $conexao->prepare("DELETE FROM experiencias_trabalho WHERE id = ?");
        $stmt->bind_param("i", $experiencia_id);
        $stmt->execute();
        $stmt->close();

        header("Location: perfil.php");
        exit();
    }

    ?>
    <?php if (isset($candidato)) : ?>
    <?php
    // Se a foto de perfil existir, exiba-a
    if (!empty($candidato['foto_perfil']) && !empty($candidato['foto_perfil_type'])) {
        $foto_perfil_src = 'data:' . $candidato['foto_perfil_type'] . ';base64,' . base64_encode($candidato['foto_perfil']);
        // Exibir a imagem de perfil em um tamanho reduzido e formato redondo
        echo '<img src="' . $foto_perfil_src . '" alt="Foto de Perfil" class="foto-perfil">';
    } else {
        // Se não houver foto de perfil, exiba uma imagem padrão
        echo '<img src="caminho_para_imagem_padrao" alt="Foto de Perfil" class="foto-perfil">';
    }
    ?>
    <?php if (!isset($_POST['trocar_foto'])) : ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="trocar_foto" value="true">
            <button type="submit" class="trocar-foto-button" name="trocar_foto_btn"><i class="fas fa-camera"></i></button>
        </form>
    <?php endif; ?>
    <!-- Formulário para trocar a foto de perfil -->
    <?php if (isset($_POST['trocar_foto'])) : ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <input type="file" class="selecionar" name="foto_perfil" accept="image/*" required>
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
        <p><strong>Descrição:</strong> <?php echo $candidato['descricao']; ?>
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
        // Exibe as experiências de trabalho do candidato
        while ($row = $result_experiencias->fetch_assoc()) {
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
        endif;
        ?>
    </ul>

<?php include('../../visualizacoes/partes/rodape.php'); ?>

</body>

</html>