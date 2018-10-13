<?php
require_once "../connettiAlDB.php";
require_once "../caricaClassi.php";
include_once "../getInfo.php";
require_once "../funzioni.php";
Session::open();
$info = Session::get("info");
$utente = Session::get("utente");
?>
<html>
    <head>
        <?php require_once "../head.php"; ?>
        <link rel="stylesheet" type="text/css" href="../css/amministrazione.css" />
        <script type='text/javascript' src='../js/amministrazione.js'></script>
    </head>
    <body>
    <div id="wrapper" class="clearfix"><!-- inizio wrapper -->
    <!-- CONTROLLO ACCESSO -->
    <?php
        // PAGINA ACCESSIBILE SOLO DA UTENTI DI LIVELLO: 3
        
        $livelliAmmessi = array(
            1 => false, //livello studente
            2 => false, //livello responsabile corso
            3 => true //livello amministratore
        );

        controlloAccesso($db,$utente,$livelliAmmessi);
    ?>
    <!-- NAVBAR -->
    <?php require "../caricaHeader.php"; ?>
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
        <?php
            /* ottengo lista altre attività */
            $altreAttivita=getAltreAttivita($db);
            /* righe di separazione fra un pannello e un altro */
            const riga_soloMobile="<div class='col-xs-12 col-sm-12 hidden-md hidden-lg'><hr></div>";
            const riga_noMobile="<div class='hidden-xs hidden-sm col-md-12 col-lg-12'><hr></div>";
            const riga_tutti="<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'><hr></div>"
        ?>
        <div class="row" id="noPrint">
        <?php include "pannelli/ricercaID_Persona.html"; ?>     <!-- PANNELLO A -->
        </div>
        <div class="row" id="noPrint">
        <?php echo riga_tutti; ?>
        </div>
        <div class="row" id="noPrint">
        <?php
            include "pannelli/resetCorsiStudente.html"; //      <!-- PANNELLO B -->
            echo riga_soloMobile;
            include "pannelli/cambioPasswordUtente.html"; //    <!-- PANNELLO C -->
        ?>
        </div>
        <div class="row" id="noPrint">
        <?php echo riga_noMobile; ?>
        </div>
        <div class="row" id="noPrint">
        <?php
            include "pannelli/visualizzaCorsiStudente.html"; // <!-- PANNELLO D -->
            echo riga_soloMobile;
            include "pannelli/visualizzaSessioniCorso.html"; // <!-- PANNELLO E -->
        ?>
        </div>
        <div class="row" id="noPrint">
        <?php echo riga_noMobile; ?>
        </div>
        <div class="row" id="noPrint">
        <?php
            include "pannelli/registroSessioneCorso.html"; //   <!-- PANNELLO F -->
            echo riga_soloMobile;
            include "pannelli/stampaLiberatoria.html"; //       <!-- PANNELLO G -->
        ?>
        </div>
        <div class="row" id="noPrint">
        <?php echo riga_noMobile; ?>
        </div>
        <div class="row" id="noPrint">
        <?php
            include "pannelli/altreAttivita.html"; //           <!-- PANNELLO H -->
            echo riga_soloMobile;
            include "pannelli/modificaAltreAttivita.html"; //   <!-- PANNELLO I -->
        ?>
        </div>
    </div>
    <!-- FOOTER -->
    <?php require_once "../footer.php"; ?>
    <!-- SESSIONI CORSO MODAL -->
    <?php
        require_once "modal/sessioniCorso.php";
        require_once "modal/corsiPersona.php";
        require_once "modal/presenzeSession.php";
        require_once "modal/listaAltreAttivita.php";
        require_once "../modal/altreAttivita.php";
        require_once "modal/stampaLiberatoria.php";
    ?>
    </div><!-- fine wrapper -->
    </body>
</html>
