<?php

error_reporting(E_ERROR | E_PARSE);

require_once "GamesMemory.php";

$db = new GamesMemory(); //Classe para controlar a BD, precisas de mysql server a correr com um user (ver credenciais no file SaveInfo.php)


$all = $db->selectUniqueNames();


?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">

    <title>Gaming Cheap Procurar</title>
    <link rel="icon" href="Gaming Cheap.png">

    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/sign-in/">



    <!-- Bootstrap core CSS -->
    <link href="assets/dist/css/bootstrap-grid.min.css" rel="stylesheet">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>


    <!-- Custom styles for this template -->
    <link href="PaginaProcurar.css" rel="stylesheet">
</head>

<body class="text-center">

    <main class="form-signin">
        <form action="PaginaDeResultados.php" method="POST">
            <img class="mb-4" src="Gaming Cheap.png" alt="" width="300" height="200">
            <h1 id='PesquisaJogo' class="h3 mb-3 fw-normal"><span style="font-weight:bold;">Pesquisa o teu jogo</span></h1>
            <input type="text" id="inputJogo" class="form-control" required autofocus name="jogo">
            <br>
            <button class="w-100 btn btn-lg btn-outline-light" type="submit">Pesquisar</button>
            <br>
            <br>
            <div style="background-color: white; border-radius: 15px;">
                <h1>Jogos Pesquisados:</h1>
                <?php foreach ($all as $registo) {
                    echo "<p>" . $registo['NOMEJOGO'] . "</p>";
                } ?>
            </div>
        </form>
    </main>


    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>

</body>

</html>