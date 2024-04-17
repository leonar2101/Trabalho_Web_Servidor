<?php

require_once __DIR__ . '/../../configuracao/banco_dados.php';

class ControleUsuario {
    public static function verificarCredenciais($email, $senha) {
        global $conexao;

        // Executar a consulta SQL para buscar candidatos e empresas
        $sql = "SELECT 'candidatos' AS tipo, id, nome, email 
                FROM candidatos WHERE email = ? AND senha = ? 
                UNION
                SELECT 'empresas' AS tipo, id, nome, email 
                FROM empresas WHERE email = ? AND senha = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ssss", $email, $senha, $email, $senha);

        // Executa a query
        $resultado = $stmt->execute();

        if ($resultado) {
            // Busca os resultados da consulta
            $resultados = $stmt->get_result();

            // Verifica se há resultados
            if ($resultados) {
                // Obtenha o número de linhas
                $num_rows = $resultados->num_rows;
                if ($num_rows == 1) {
                    // Existem resultados =  existe usuario
                    while ($row = $resultados->fetch_assoc()) {
                        $tipo = $row['tipo'];
                        $id = $row['id'];
                        $nome = $row['nome'];
                        $email = $row['email'];

                        if ($tipo === 'candidatos') {
                            $tipo_usuario = 'candidato';
                        } elseif ($tipo === 'empresas') {
                            $tipo_usuario = 'empresa';
                        }
                            // Redirecionar com base no tipo de usuario e atribuir sua sessao e cookie
                        if (isset($tipo_usuario)) {
                            // Armazenar o tipo de usuario e email em uma variável de sessão
                            session_start();
                            $_SESSION['tipo_usuario'] = $tipo_usuario;
                            $_SESSION['email'] = $email;

                            // Redirecionar para o perfil correspondente
                            if ($tipo_usuario === 'candidato') {
                                header("Location: ../visualizacoes/candidato/perfil.php");
                            } elseif ($tipo_usuario === 'empresa') {
                                header("Location: ../visualizacoes/empresa/perfil.php");
                            }
                            exit();
                        }}}
                        else {
                    echo "Nenhum resultado encontrado.";
                }}
             else {
                // Erro ao buscar resultados
                echo "Erro ao buscar resultados.";
            }
        } else {
            // Erro ao executar a consulta
            echo "Erro: " . $stmt->error;
        }

        // Fechar a conexão com o banco de dados
        $conexao->close();
    }
}

?>
