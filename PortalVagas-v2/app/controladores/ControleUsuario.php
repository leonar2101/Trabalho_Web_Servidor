<?php

require_once __DIR__ . '/../../configuracao/banco_dados.php';
require_once  __DIR__. '/../../vendor/autoload.php';

class ControleUsuario {
    public static function verificarCredenciais($email, $senha) {
        global $conexao;

        // Preparar a consulta SQL para buscar candidatos e empresas
        $sql = "SELECT 'candidatos' AS tipo, id, nome, email 
                FROM candidatos WHERE email = :email AND senha = :senha
                UNION
                SELECT 'empresas' AS tipo, id, nome, email 
                FROM empresas WHERE email = :email AND senha = :senha";
        $stmt = $conexao->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);

        // Executar a consulta preparada
        if ($stmt->execute()) {
            // Obter os resultados da consulta
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Verificar se há resultados
            if ($resultados) {
                // Verificar o número de linhas retornadas
                if (count($resultados) == 1) {
                    // Existem resultados, atribuir tipo de usuário e redirecionar
                    $tipo = $resultados[0]['tipo'];
                    $email = $resultados[0]['email'];
                    $id = $resultados[0]['id'];

                    if ($tipo === 'candidatos') {
                        $tipo_usuario = 'candidato';
                    } elseif ($tipo === 'empresas') {
                        $tipo_usuario = 'empresa';
                    }

                    // Armazenar o tipo de usuário e email em uma variável de sessão
                    session_start();
                    $_SESSION['tipo_usuario'] = $tipo_usuario;
                    $_SESSION['email'] = $email;
                    $_SESSION['id'] = $id;

                    // Redirecionar para o perfil correspondente
                    if ($tipo_usuario === 'candidato') {
                        header("Location: ../visualizacoes/candidato/perfil.php");
                    } elseif ($tipo_usuario === 'empresa') {
                        header("Location: ../visualizacoes/empresa/perfil.php");
                    }
                    exit();
                } else {
                    echo "Nenhum resultado encontrado.";
                }
            } else {
                echo "Nenhum resultado encontrado.";
            }
        } else {
            echo "Erro ao executar a consulta.";
        }
    }
}


?>
