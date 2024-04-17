<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil da Empresa - Portal de Vagas</title>
    <?php include('../../visualizacoes/partes/cabecalhoEmpresa.php'); ?>
    <link rel="stylesheet" type="text/css" href="../empresa/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <div class="container">
        <h1>Perfil da Empresa</h1>
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
        $query = "SELECT * FROM empresas WHERE email = '$email'";
        $resultado = $conexao->query($query);

        // Verifique se os resultados foram obtidos com sucesso
        if ($resultado->num_rows == 1) {
            // Recupera os dados do usuário
            $empresa = $resultado->fetch_assoc();
            //     echo "id do empresa: " . $empresa['id'];
            //     echo "Nome do empresa: " . $empresa['nome'];
            //    echo "Email do empresa: " . $empresa['email'];
            //     echo "Ramo do empresa: " . $empresa['ramo'];
            //    echo "Token do empresa: " . $empresa['token'];
            //    echo "Descricao do empresa: " . $empresa['descricao'];
            //    echo "foto do empresa: " . $empresa['foto_perfil'];
            //    echo "foto tipo do empresa: " . $empresa['foto_perfil_type'];

        } else {
            // Se o usuário não for encontrado no banco de dados, redirecione-o para a página de login
            header("Location: login.php");
            exit(); // Encerre o script após o redirecionamento
        }

        //setando id empresa
        $empresa_id = $empresa['id'];
        // Verifique se o formulário foi enviado para editar a descrição
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['editar_descricao'])) {
                // Exibir o formulário para editar a descrição
                $editar_descricao = true;
            } elseif (isset($_POST['salvar_descricao'])) {
                // Processar o formulário de edição da descrição
                $nova_descricao = $_POST['nova_descricao'];

                // Atualizar a descrição no banco de dados
                $stmt = $conexao->prepare("UPDATE empresas SET descricao = ? WHERE id = ?");
                $stmt->bind_param("si", $nova_descricao, $empresa_id);
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


        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['excluir_vaga'])) {
                $vaga_id = $_POST['vaga_id'];

                // Excluir a vaga do banco de dados
                $stmt = $conexao->prepare("DELETE FROM vagas WHERE id = ?");
                $stmt->bind_param("i", $vaga_id);
                $stmt->execute();
                $stmt->close();

                // Redirecionar para a página de perfil
                header("Location: perfil.php");
                exit();
            }
        }


        // Lidar com o envio do formulário de nova vaga
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['cadastrar_vaga'])) {
                // Processar o formulário de cadastro de vaga
                $titulo_vaga = $_POST['titulo_vaga'];
                $descricao_vaga = $_POST['descricao_vaga'];
                $localizacao_vaga = $_POST['localizacao_vaga'];
                $salario_vaga = $_POST['salario_vaga'];

                // Inserir a nova vaga no banco de dados
                $stmt = $conexao->prepare("INSERT INTO vagas (empresa_id, titulo, descricao, localizacao, faixa_salarial) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("issss", $empresa_id, $titulo_vaga, $descricao_vaga, $localizacao_vaga, $salario_vaga);
                $stmt->execute();
                $stmt->close();

                // Redirecionar de volta para a página de perfil após o cadastro
                header("Location: perfil.php");
                exit();
            }
        }

        // Obter as informações da empresa do banco de dados
        $stmt = $conexao->prepare("SELECT * FROM empresas WHERE id = ?");
        $stmt->bind_param("i", $empresa_id);
        $stmt->execute();
        $result_empresa = $stmt->get_result();

        // Verificar se os resultados foram obtidos com sucesso
        if ($result_empresa && $result_empresa->num_rows > 0) {
            $empresa = $result_empresa->fetch_assoc();
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["foto_perfil"])) {
            $foto_perfil = file_get_contents($_FILES["foto_perfil"]["tmp_name"]);
            $foto_perfil_type = $_FILES["foto_perfil"]["type"];
        
            // Salvar a imagem no banco de dados
            $stmt = $conexao->prepare("UPDATE empresas SET foto_perfil = ?, foto_perfil_type = ? WHERE id = ?");
            $stmt->bind_param("ssi", $foto_perfil, $foto_perfil_type, $empresa_id);
            $stmt->execute();
            $stmt->close();
        
            // Redirecionar de volta para a página de perfil após o upload
            header("Location: perfil.php");
            exit();
        }
       

        ?>
        <?php if (isset($empresa)) : ?>
            <?php
            // Verifique se a foto de perfil existe e exiba-a
            if (!empty($empresa['foto_perfil']) && !empty($empresa['foto_perfil_type'])) {
                $foto_perfil_src = 'data:' . $empresa['foto_perfil_type'] . ';base64,' . base64_encode($empresa['foto_perfil']);
                // Exibir a imagem de perfil em um tamanho reduzido e formato redondo
                echo '<img src="' . $foto_perfil_src . '" alt="Foto de Perfil" class="foto-perfil">';
            } else {
                // Se não houver foto de perfil, exibir uma imagem padrão
                echo '<img src="caminho_para_imagem_padrao.jpg" alt="Foto de Perfil" class="foto-perfil">';
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
        <?php endif; ?>



        <?php if (isset($empresa)) : ?>
            <div class="empresa-informacoes">
                <h2>Informações da Empresa</h2>
                <p><strong>Nome:</strong> <?php echo $empresa['nome']; ?></p>
                <p><strong>Email:</strong> <?php echo $empresa['email']; ?></p>
                <p><strong>Descrição:</strong> <?php echo $empresa['descricao']; ?></p>
                <!-- Botão para editar a descrição -->
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <input type="hidden" name="editar_descricao" value="true">
                    <button type="submit"><i class="fas fa-pencil-alt"></i></button>
                </form>
            </div>

            <?php if (isset($_POST['editar_descricao'])) : ?>
                <!-- Formulário para editar a descrição -->
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <textarea name="nova_descricao" rows="4" cols="50"><?php echo htmlspecialchars($empresa['descricao']); ?></textarea><br>
                    <button type="submit" name="salvar_descricao">Salvar</button>
                    <button type="submit" name="cancelar_edicao">Cancelar</button>
                </form>
            <?php endif; ?>


            <h2>Cadastrar Vaga</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <label for="titulo_vaga">Título da Vaga:</label><br>
                <input type="text" id="titulo_vaga" name="titulo_vaga" required><br>

                <label for="descricao_vaga">Descrição da Vaga:</label><br>
                <textarea id="descricao_vaga" name="descricao_vaga" rows="4" required></textarea><br>

                <label for="localizacao_vaga">Localização:</label><br>
                <input type="text" id="localizacao_vaga" name="localizacao_vaga" required><br>

                <label for="salario_vaga">Salário:</label><br>
                <input type="number" id="salario_vaga" name="salario_vaga" required><br>

                <button type="submit" class="cadastrar-vaga" name="cadastrar_vaga">Cadastrar Vaga</button>
            </form>

            <h2>Vagas Disponíveis</h2>
            <ul>
                <?php
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
            </ul>

        <?php else : ?>
            <p>Empresa não encontrada.</p>
        <?php endif; ?>
    </div>
    </div>

    <?php include('../../visualizacoes/partes/rodape.php'); ?>


</body>

</html>