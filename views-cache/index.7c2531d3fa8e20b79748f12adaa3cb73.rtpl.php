<?php if(!class_exists('Rain\Tpl')){exit;}?><!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projeto - Reastreio de Objetos</title>
    <!--   Google Fonts   -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <!--   Stylesheet   -->
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<main class="container">
    <header class="header">
        <h1>Rastrear Objeto</h1>
    </header>
    <div class="content">
        <div class="box_form">
            <form class="form" action="/api" method="get">
                <input type="text" name="code">
                <button type="submit" class="btn_search">Buscar</button>
            </form>
        </div>

    </div>

</main>
</body>
</html>


