<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($tituloPagina) ? $tituloPagina : 'Biblioteca Escolar'; ?></title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<header class="topo">
    <h1>Sistema de Biblioteca Escolar</h1>
    <nav>
        <a href="index.php?acao=dashboard">Início</a>
        <a href="index.php?acao=alunos">Alunos</a>
        <a href="index.php?acao=livros">Livros</a>
        <a href="index.php?acao=emprestimos">Empréstimos</a>
    </nav>
</header>
<main class="conteudo">
