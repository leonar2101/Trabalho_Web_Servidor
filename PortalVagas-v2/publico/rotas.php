<?php
use Pecee\SimpleRouter\SimpleRouter;
// Define as rotas
SimpleRouter::get('/', function (){
    header("location: index.html");
});
SimpleRouter::get('/vagas', function (){
    header("location: app/visualizacoes/candidato/vagas_disponiveis.php");
});
SimpleRouter::get('/candidatos', function (){
    header("location: app/visualizacoes/empresa/candidatos.php");
});
SimpleRouter::delete('/vaga/{id}', function (){
});

SimpleRouter::start();
?>