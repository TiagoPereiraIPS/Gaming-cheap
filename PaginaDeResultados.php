<?php

require_once "GamingCheap.php";
require_once "GamesMemory.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['jogo'];
    $bot = new GamingCheap($name);
    $dados = $bot->retrieveData();
}

$db = new GamesMemory(); //Classe para controlar a BD, precisas de mysql server a correr com um user (ver credenciais no file SaveInfo.php)
$db->install(); //verifica se tem as tabela criada, se não, cria uma

foreach ($dados as $jogo){
    //var_dump($jogo->mCouponText);
	//Verificar se o registo já existe
    if($db->alreadyThere($jogo->mGame, $jogo->mOnlineStore, $jogo->mPlatform, $jogo->mGameVersion)){
        //Se já existe dá update
		$db->update(
        $jogo->mGame,
        $jogo->mOnlineStore,
        $jogo->mPlatform,
        $jogo->mGameVersion,
        $jogo->mCouponPercentageAndName,
        $jogo->mPriceWithoutCoupon,
        $jogo->mActualPrice);
    } else {
		//Se não existe faz um novo registo
        $db->insertGames(
        $jogo->mGame,
        $jogo->mOnlineStore,
        $jogo->mPlatform,
        $jogo->mGameVersion,
        $jogo->mCouponPercentageAndName,
        $jogo->mPriceWithoutCoupon,
        $jogo->mActualPrice);
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">


    <title>Gaming Cheap Página de Resultados</title>
    <link rel="icon" href="Gaming Cheap.png">
    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/album/">



    <!-- Bootstrap core CSS -->
    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">

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

    <link href="PaginaDeResultados.css" rel="stylesheet">
</head>

<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="PaginaProcurar.php">
                    <img src="Gaming Cheap.png" alt="" width="150" height="100px" class="d-inline-block align-top">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                    </ul>
                    <form class="d-flex" method="POST">
                        <input class="form-control me-2" type="search" aria-label="Search" name="jogo">
                        <button class="btn btn-outline-light" type="submit" name="pesquisar">Pesquisar</button>
                    </form>
                </div>
            </div>
        </nav>
    </header>

    <main>

        <section class="py-5 text-center container">

            <div class="col-lg-6 col-md-8 mx-auto">
                <h1 class="fw-light"><span style="font-weight:bold;">Página de Resultados</span></h1>

            </div>
        </section>

        <div class="album py-5">
            <div class="container">

                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-1 g-3">
                    <?php foreach ($dados as $registo) { ?>
                        <div class="col">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <p class="card-text"><span style="font-weight:bold;"><?php echo $registo->{'mGame'} ?></span></p>
                                    <p class="card-text"><span style="font-weight:bold;">Loja online: </span><?php echo $registo->{'mOnlineStore'} ?></p>
                                    <p class="card-text"><span style="font-weight:bold;">Plataforma: </span><?php echo $registo->{'mPlatform'} ?></p>
                                    <p class="card-text"><span style="font-weight:bold;">Versão do jogo: </span><?php echo $registo->{'mGameVersion'} ?></p>
                                    <?php if (empty($registo->{'mCouponPercentageAndName'})) {
                                        echo '<p class="card-text"><span style="font-weight:bold;">Cupão inexistente </span></p>';
                                    } else { ?>
                                        <p class="card-text"><span style="font-weight:bold;">Cupão: </span><?php echo $registo->{'mCouponPercentageAndName'} ?></p>
                                        <p class="card-text"><span style="font-weight:bold;">Preço sem cupão: </span><?php echo $registo->{'mPriceWithoutCoupon'} ?></p>
                                    <?php  }  ?>
                                    <p class="card-text"><span style="font-weight:bold;">Preço final: </span><?php echo $registo->{'mActualPrice'} ?></p>
                                    <img id="ImagemJogo" class="rounded float-right "  style="position: absolute; right: 0%; top: 0%; " src="ImagensJogos/<?php echo $registo->{'mGame'} ?>.jpg">
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

    </main>


    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>

    <script src="assets/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>