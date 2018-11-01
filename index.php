<?php
    require_once "caricaClassi.php";
    require_once "connettiAlDB.php";
    include_once "getInfo.php";
    require_once "funzioni.php";
    Session::open();
    $info = Session::get("info");
    $utente = Session::get("utente");
?>
<html>
    <head>
        <?php require_once "head.php"; ?>
        <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
        <script type='text/javascript' src="/js/login.js"></script>
    </head>
    <body>
    <div id="wrapper" class="clearfix"><!-- inizio wrapper -->
    <!-- NAVBAR -->
    <?php
        require_once "caricaNavbar.php";
        //scelta pulsante per Jumbotron -> viene inserito nel div.jumbotron
        if(!isset($utente)) {
            $button = "<p class='text-center'><a class='btn btn-primary btn-lg' href='#login_interni' data-toggle='modal' role='button'>Accedi al sito</a></p>";
        } else {
            switch($utente->getLivello()) {
                case 1: $button = "<p class='text-center'><a class='btn btn-primary btn-lg' href='" . getURL("/iscrizione/") . "'>Iscriviti qui</a></p>";
                    break; //studente
                case 2: $button = "<p class='text-center'><a class='btn btn-primary btn-lg' href='" . getURL("/registro-presenze/") . "'>Gestisci il tuo corso</a></p>";
                    break;
                case 3: $button = "<p class='text-center'><a class='btn btn-primary btn-lg' href='" . getURL("/amministrazione/") . "'>Amministra l'evento</a></p>";
                    break;
            }
        }
    ?>
    <div id="content" class="container">
        <!-- INTESTAZIONE PAGINA -->
        <div class="jumbotron">
            <div id="background"></div>
            <h1 class="text-center"><?php echo $info["titolo"]; ?></h1>
            <h5 class="text-center sottotitolo"><?php echo $info["istituto"]; ?></h5>
            <p class="text-center sottotitolo"><?php echo $info["periodosvolgimento"]; ?></p>
            <?php echo $button; ?>
        </div>
        <!-- CORPO PAGINA -->
        <div class="row">
          <div class="hidden-xs hidden-sm col-md-3 col-lg-3"></div>
              <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6"><?php include "testoHome.php"; ?></div>
          <div class="hidden-xs hidden-sm col-md-3 col-lg-3"></div>
        </div>
    </div>
    <!-- FOOTER -->
    <?php require_once "footer.php"; ?>
    <!-- LOGIN MODAL -->
    <?php require_once "caricaModalLogin.php"; ?>
    </div><!-- fine wrapper -->
    </body>
</html>
