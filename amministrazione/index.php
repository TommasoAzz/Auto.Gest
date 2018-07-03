<?php
    require_once "../connectToDB.php";
    require_once "../classes.php";
    include_once "../getInfo.php";
    Session::open();
    $info=Session::get("info");
    require_once "funzioni-amministrazione.php";
?>
<html>
    <head>
        <?php require_once "../head.php"; ?>
        <link rel="stylesheet" type="text/css" href="../css/amministrazione.css" />
        <script type='text/javascript' src='../js/amministrazione.js'></script>
    </head>
    <body>
    <div id="wrapper" class="clearfix"><!-- inizio wrapper -->
    <!-- NAVBAR -->
    <?php require_once "../switch_header.php"; ?>
    <!-- CONTROLLO ACCESSO -->
    <?php
        // PAGINA ACCESSIBILE SOLO DA UTENTI DI LIVELLO: 3
        /*if(!isset($utente)) { 
            header("Location: /");
        } elseif($utente->getLivello() == 2) {
            die("<script>location.href='/';</script>");
        }*/
        if(!isset($utente) || !($utente->getLivello() == 3)) {
            header("Location: /");
        }
    ?>
    <!-- BODY -->
    <div id="content" class="container">
        <!-- INTESTAZIONE PAGINA -->
        <div class="row" id="noPrint">
            <div class="hidden-xs hidden-sm col-md-12 col-lg-12">
                <h1 class="text-center">Amministrazione</h1>
                <h4 class="text-center sottotitolo">Pannello di controllo di <?php echo $info["titolo"]; ?></h4>
                <hr>
            </div>
        </div>
        <!-- CORPO PAGINA --> 
        <div class="row" id="noPrint">
            <?php include "pannelli/ricercaID_Persona.html"; ?>
        </div>
        <div class="row" id="noPrint">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><hr></div>
        </div>
        <div class="row" id="noPrint">
            <?php include "pannelli/resetIscrizioniByID.html"; ?>
            <div class="col-xs-12 col-sm-12 hidden-md hidden-lg"><hr></div>
            <?php include "pannelli/changePasswordByID.html"; ?>
        </div>
        <div class="row" id="noPrint">
            <div class="hidden-xs hidden-sm col-md-12 col-lg-12"><hr></div>
        </div>
        <div class="row" id="noPrint">
            <?php include "pannelli/corsiPersona.html"; ?>
            <div class="col-xs-12 col-sm-12 hidden-md hidden-lg"><hr></div>
            <?php include "pannelli/sessioniCorso.html"; ?>
        </div>
        <div class="row" id="noPrint">
            <div class="hidden-xs hidden-sm col-md-12 col-lg-12"><hr></div>
        </div>
        <div class="row" id="noPrint">
            <?php include "pannelli/presenzeSessioneCorso.html"; ?>
            <div class="col-xs-12 col-sm-12 hidden-md hidden-lg"><hr></div>
            <?php include "pannelli/stampaLiberatoria.html"; ?>
        </div>
        <div class="row" id="noPrint">
            <div class="hidden-xs hidden-sm col-md-12 col-lg-12"><hr></div>
        </div>
        <div class="row" id="noPrint">
            <?php include "pannelli/altreAttivita.php"; ?>
            <div class="col-xs-12 col-sm-12 hidden-md hidden-lg"><hr></div>
            <?php include "pannelli/modificaAltreAttivita.html"; ?>
        </div>
    </div>
    <!-- FOOTER -->
    <?php require_once "../footer.php"; ?>
    <!-- SESSIONI CORSO MODAL -->
    <?php 
        require_once "modal_sessioniCorso.php";
        require_once "modal_corsi.php";
        require_once "modal_presenzeSessione.php";
        require_once "modal_listaAltreAttivita.php";
        require_once "../modal_altreAttivita.php";
        require_once "modal_stampaLiberatoria.php";
    ?>
    </div><!-- fine wrapper -->
    </body>
</html>
