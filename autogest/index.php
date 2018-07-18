<?php
    require_once "../connettiAlDB.php";
    require_once "../caricaClassi.php";
    include_once "../getInfo.php";
    require_once "../funzioni.php";
    Session::open();
    $info=Session::get("info");
    $db=Session::get("db");
    $utente=Session::get("utente");
?>
<html>
    <head>
        <?php require_once "../head.php"; ?>
        <script type='text/javascript' src="../js/login.js"></script>
    </head>
    <body>
    <div id="wrapper" class="clearfix"><!-- inizio wrapper -->
    <!-- NAVBAR -->
    <?php require_once "../caricaHeader.php"; ?>
    <div id="content" class="container">
        <!-- INTESTAZIONE PAGINA -->
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <h1 class="text-center">Auto.Gest</h1>
                <h4 class="text-center sottotitolo">Il sistema che permette al sito di <?php echo $info["titolo"]; ?> di funzionare</h4>
                <hr>
            </div>
        </div>
        <!-- CORPO PAGINA -->
        <div class="row">
            <div id="logoAutoGest" class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <img class="img-responsive" alt="logoAutoGest" src="../img/AutoGest-Logo.png" />
            </div>
            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                <h3><strong><?php echo $info["titolo"]; ?></strong> funziona grazie ad Auto.Gest.</h3>
                <p class="text-justify">Ideato e progettato da <a href="https://facebook.com/tommasoazzalin" target="_blank" title="Tommaso Azzalin su Facebook">Tommaso Azzalin</a>.</p>
                <p class="text-justify">Sviluppato da <a href="https://facebook.com/tommasoazzalin" target="_blank" title="Tommaso Azzalin su Facebook">Tommaso Azzalin</a>, <a href="https://www.facebook.com/RangoMatteo" target="_blank" title="Matteo Rango su Facebook">Matteo Rango</a>, <a href="https://facebook.com/DiBellaSaverio" target="_blank" title="Saverio Di Bella su Facebook">Saverio Di Bella</a>.</p>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?php include "testoAutoGest.php"; ?>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <?php include "testoLicenze.php"; ?>
            </div>
        </div>
    </div>
    <!-- FOOTER -->
    <?php require_once "../footer.php"; ?>
    <!-- LOGIN MODAL -->
    <?php require_once "../caricaModalLogin.php"; ?>
    </div><!-- fine wrapper -->
    </body>
</html>
