<?php

require "../visualizacoes/cadastro.html";
require_once 'InserirUsuario.php';
require_once  __DIR__. '/../../vendor/autoload.php';

        // Verificar se o formulário foi submetido
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Recebe os dados do formulário
            $tipoUsuario = $_POST['tipo-usuario'];
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $senha = $_POST['senha'];

            // Validar os dados
            if (!empty($tipoUsuario) && !empty($nome) && !empty($email) && !empty($senha)) {
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    // Chama o método apropriado para inserir os dados na base de dados
                    if ($tipoUsuario === 'candidato') {
                        cadastrarCandidato($nome, $email, $senha);
                        echo "$email $senha";
                    } elseif ($tipoUsuario === 'empresa') {
                        cadastrarEmpresa($nome, $email, $senha);
                        echo "$email $senha";
                    }
                } else {
                    echo "Email inválido.";
                }
            } else {
                echo "Todos os campos são obrigatórios.";
            }
        }

     function cadastrarCandidato($nome, $email, $senha) {
        echo "Cadastrando usuario candidato";
         print_r("$senha");
        // Insere os dados do candidato na base de dados
        InserirUsuario::inserirCandidato($nome, $email, $senha);
    }

     function cadastrarEmpresa($nome, $email, $senha) {
        echo "Cadastrando usuario empresa";
         print_r("$senha");
        // Insere os dados da empresa na base de dados
        InserirUsuario::inserirEmpresa($nome, $email, $senha);
    }

