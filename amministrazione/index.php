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
            /* righe di separazione fra un pannello e un altro */
            const riga_soloMobile="<div class='col-xs-12 col-sm-12 hidden-md hidden-lg'><hr></div>";
            const riga_noMobile="<div class='hidden-xs hidden-sm col-md-12 col-lg-12'><hr></div>";
            const riga_tutti="<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12'><hr></div>"
       ?>
        <div class="row" id="noPrint">
        <?php include "pannelli/ricercaID_Persona.html"; ?>
        </div>
        <div class="row" id="noPrint">
        <?php echo riga_tutti; ?>
        </div>
        <div class="row" id="noPrint">
        <?php
            include "pannelli/resetIscrizioniByID.html";
            echo riga_soloMobile;
            include "pannelli/changePasswordByID.html";
        ?>
        </div>
        <div class="row" id="noPrint">
        <?php echo riga_noMobile; ?>
        </div>
        <div class="row" id="noPrint">
        <?php
            include "pannelli/corsiPersona.html";
            echo riga_soloMobile;
            include "pannelli/sessioniCorso.html";
        ?>
        </div>
        <div class="row" id="noPrint">
        <?php echo riga_noMobile; ?>
        </div>
        <div class="row" id="noPrint">
        <?php
            include "pannelli/presenzeSessioneCorso.html";
            echo riga_soloMobile;
            include "pannelli/stampaLiberatoria.html";
        ?>
        </div>
        <div class="row" id="noPrint">
        <?php echo riga_noMobile; ?>
        </div>
        <div class="row" id="noPrint">
        <?php
            include "pannelli/altreAttivita.php";
            echo riga_soloMobile;
            include "pannelli/modificaAltreAttivita.html";
        ?>
        </div>
    </div>
    <!-- FOOTER -->
    <?php require_once "../footer.php"; ?>
    <!-- SESSIONI CORSO MODAL -->
    <?php
        require_once "modal/sessioniCorso.php";
        require_once "modal/corsiPersona.php";
        require_once "modal/presenzeSessione.php";
        require_once "modal/listaAltreAttivita.php";
        require_once "../modal/altreAttivita.php";
        require_once "modal/stampaLiberatoria.php";
    ?>
    </div><!-- fine wrapper -->
    </body>
</html>
