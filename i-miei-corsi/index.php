<!-- Auto.Gest -->
<?php
    require_once "../connectToDB.php";
    require_once "../classes.php";
    include_once "../getInfo.php";
    Session::open();
    $info=Session::get("info");
    require_once "funzioni.php";
    $db=Session::get("db"); 
?>
<html>
    <head>
        <?php require_once "../head.php"; ?>
        <link rel="stylesheet" type="text/css" href="../css/i-miei-corsi.css" />
        <script type="text/javascript" src="../js/i-miei-corsi.js"></script>
    </head>
    <body>
    <div id="wrapper" class="clearfix"><!-- inizio wrapper -->
    <!-- NAVBAR -->
    <?php require "../switch_header.php"; ?>
    <!-- CONTROLLO ACCESSO -->
    <?php
        // PAGINA ACCESSIBILE SOLO DA UTENTI DI LIVELLO: 1, 3
        if(!isset($utente)) { 
            header("Location: /");
        } elseif($utente->getLivello() == 2) {
            die("<script>location.href='/';</script>");
        }
    ?>
	<div id="content" class="container">
        <!-- INTESTAZIONE PAGINA -->
        <div id="intestazione" class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <h1 class="text-center">I miei corsi</h1>
                <h4 class="text-center sottotitolo">Rivedi i corsi a cui ti sei iscritto</h4>
                <hr>
            </div>
         </div>
         <div id="info" class="row">
            <!-- CORPO PAGINA -->
            <div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <h4 class="text-center">
                    <strong>Nome</strong>: <?php echo $utente->getNome()." ".$utente->getCognome(); ?>&nbsp;
                    <strong>Classe</strong>: <?php echo $utente->getClasse()."°".$utente->getSezione()." ".$utente->getIndirizzo(); ?>
                </h4>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                <button type="button" id="btnPrint" class="btn btn-success btn-lg btn-block"><span class="fa fa-print" aria-hidden='true'></span> Stampa</button>    
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                <button type="button" id="btnReset" class="btn btn-danger btn-lg btn-block"><span class="fa fa-ban" aria-hidden='true'></span> Annulla l'iscrizione</button>    
            </div>
            <div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
        </div>
        <div id="separatore" class="row">
            <div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10"><hr></div>
            <div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
        </div>
        <div id="listaCorsi" class="row">
            <div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <?php
                if($utente->getGiornoIscritto() > 0 || $utente->getOraIscritta() > 0) {
                    echo creazioneTabella($db,$utente);
                } else {
                    echo "<div id='no_iscrizione' class='panel panel-danger'>";
                    echo "<div class='panel-heading'>";
                    echo "<h2 class='panel-title'>Non ti sei ancora iscritto ad alcun corso!</h2>";
                    echo "</div>";
                    echo "<div class='panel-body'>";
                    echo "<p>Quando ti sarai iscritto ad almeno un corso visualizzerai qui le tue scelte. Per iscriverti, clicca <a href=".getBaseUrl()."/iscrizione/ title='Iscrizione'>qui</a>.</p>";
                    echo "</div></div>";
                }
            ?>
            </div>
            <div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
        </div>
    </div>
	<!-- FOOTER -->
    <?php require_once "../footer.php"; ?>
    </div><!-- fine wrapper -->
    </body>
</html>
