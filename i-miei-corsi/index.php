<?php
require_once "../caricaClassi.php";
require_once "../connettiAlDB.php";
include_once "../getInfo.php";
require_once "../funzioni.php";
Session::open();
$info=Session::get("info");
$utente=Session::get("utente");
?>
<!doctype html>
<html lang="it">
    <head>
        <?php require_once "../head.php"; ?>
        <link rel="stylesheet" type="text/css" href="../css/i-miei-corsi.css" />
        <script type="text/javascript" src="../js/i-miei-corsi.js"></script>
    </head>
    <body>
    <div id="wrapper" class="clearfix"><!-- inizio wrapper -->
    <!-- CONTROLLO ACCESSO -->
    <?php
        // PAGINA ACCESSIBILE SOLO DA UTENTI DI LIVELLO: 1, 3
        
        $livelliAmmessi = array(
            1 => true, //livello studente
            2 => false, //livello responsabile corso
            3 => true //livello amministratore
        );

        controlloAccesso($db, $utente, $livelliAmmessi);
    ?>
    <!-- NAVBAR -->
    <?php require "../caricaNavbar.php"; ?>
	<div id="content" class="container">
        <!-- INTESTAZIONE PAGINA -->
        <div id="intestazione-pagina" class="row">
            <div class="hidden-xs hidden-sm col-md-12 col-lg-12">
                <h1 class="text-center">I miei corsi</h1>
                <h4 class="text-center sottotitolo">Rivedi i corsi a cui ti sei iscritto</h4>
                <hr>
            </div>
            <div class="col-xs-12 col-sm-12 hidden-md hidden-lg">
                <h2 class="text-center">I miei corsi</h2>
                <h4 class="text-center sottotitolo">Rivedi i corsi a cui ti sei iscritto</h4>
                <hr>
            </div>
         </div>
         <div id="intestazione-stampa" class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <h1 class="text-center">Promemoria iscrizione</h1>
            </div>
         </div>
         <div id="info" class="row">
            <!-- CORPO PAGINA -->
            <div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
            <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <h4 class="text-center">
                    <strong>Nome</strong>: <?php echo $utente->getNome()." ".$utente->getCognome(); ?>&nbsp;
                    <strong>Classe</strong>: <?php echo $utente->classe->getClasse()."°".$utente->classe->getSezione()." ".$utente->classe->getIndirizzo(); ?>
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
                if($utente->getGiornoIscritto() > 0 || $utente->getOraIscritta() > 0):
                    echo creazioneTabella($db, $utente);
                else: //inizio ELSE
            ?>
                <div id='no_iscrizione' class='panel panel-danger'>
                    <div class='panel-heading'>
                        <h2 class='panel-title'>Non ti sei ancora iscritto ad alcun corso!</h2>
                    </div>
                    <div class='panel-body'>
                        <p>Quando ti sarai iscritto ad almeno un corso visualizzerai qui le tue scelte. Per iscriverti, clicca <a href="<?php echo getURL('/iscrizione/'); ?>" title='Iscrizione'>qui</a>.</p>
                    </div>
                </div>
            <?php endif; ?>
            </div>
            <div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
        </div>
    </div>
	<!-- FOOTER -->
    <?php require_once "../footer.php"; ?>
    </div><!-- fine wrapper -->
    </body>
</html>
