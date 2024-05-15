<?php

// iniciar sessao
//session_start();
// Verifique se a sessão está definida e redireciona ao perfil correto
//if (isset($_SESSION['email'])) {
  //  $email = $_SESSION['email'];
    //$tipo = $_SESSION['tipo_usuario'];
   // if($tipo == 'candidato'){
     //   header("location: /Portal_Vagas_Servidor_v1.1/Portal_Vagas_Servidor/app/visualizacoes/candidato/perfil.php");
   // }else{

     //   header("location: /Portal_Vagas_Servidor_v1.1/Portal_Vagas_Servidor/app/visualizacoes/empresa/perfil.php");
   // }
//}
require "../visualizacoes/login.html";
require_once 'ControleUsuario.php';
require_once  __DIR__. '/../../vendor/autoload.php';

// Verificar se o formulário foi submetido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Receber os dados do formulário
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Validar os dados
    if (!empty($email) && !empty($senha)) {
        // Verificar se as credenciais de login estão corretas
        if (ControleUsuario::verificarCredenciais($email, $senha)){

        }else {
            // Credenciais inválidas, exibir uma mensagem de erro
            echo "Email ou senha incorretos.";
        }
    } else {
        // Se algum campo estiver vazio, exibir uma mensagem de erro
        echo "Todos os campos são obrigatórios.";
    }
}