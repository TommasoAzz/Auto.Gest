<?php
    require_once "../caricaClassi.php";
    require_once "../connettiAlDB.php";
    include_once "../getInfo.php";
    require_once "../funzioni.php";
    Session::open();
    $info = Session::get("info");
    $utente = Session::get("utente");
?>
<html>
    <head>
        <?php require_once "../head.php"; ?>
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
    <?php
        //pagina si deve aprire solo quando c'è stato un errore
        if(!Session::is_set("errIscrizione")) die("<script>location.href='/';</script>");

        //creazione messaggio di errore
        $errore = Session::get("errIscrizione");
        $h2 = "C'è stato un errore nella comunicazione con il database.";
        $h4 = "";
        $p = "Clicca <a href='/iscrizione/'>qui</a> per riprovare. Se il problema persiste, contatta i tuoi Rappresentanti degli Studenti.";

        switch($errore) {
            //funzione.php e updateDB.php
            case "errore_db_giorno_iscrizione": //errore nel reperimento del giorno d'iscrizione dal database
            case "errore_db_ora_iscrizione": //errore nel reperimento dell'ora dal database
            case "errore_db_lista_corsi": //errore nel reperimento della lista dei corsi
            case "errore_db_id_sessione_corso": //errore nel reperimento dell'id della sessione del corso
            case "errore_db_upd8_iscrizioni": //errore nell'update della tabella Iscrizioni
            case "errore_db_reperimento_nuovi_gg_hh": //errore nell'update della tabella RegPresenze
                $h4 = "Mentre stavamo per iscriverti al corso che avevi scelto, qualcosa è andato storto.";
                $p = "Codice errore: <strong>" . $errore . "</strong><br />" . $p;
                break;
            case "fine_iscrizione": //iscrizione completata
                $h2 = "Congratulazioni!";
                $h4 = "Hai completato l'iscrizione a " . $info['titolo'];
                $p = "Clicca <a href='" . getURL("/i-miei-corsi/") . "'>qui</a> per visualizzare un promemoria dei corsi da te scelti, oppure torna alla <a href='../'>Home page</a>.";
                break;
        }

        $messaggio_info = "<h2 class='text-center'>$h2</h2><h4 class='text-justify'>$h4</h4><p class='text-justify'>$p</p>";
    ?>
    <div id="content" class="container">
        <!-- INTESTAZIONE PAGINA -->
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <h1 class="text-center">Iscrizione</h1>
                <h4 class="text-center sottotitolo"><?php echo $info['titolo']; ?></h4>
                <hr>
            </div>
        </div>
        <!-- CORPO PAGINA -->
        <div class="row">
            <div class="hidden-xs hidden-sm col-md-4 col-lg-4"></div>
            <div id="modulo" class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <?php echo $messaggio_info; ?>
            </div>
            <div class="hidden-xs hidden-sm col-md-4 col-lg-4"></div>
        </div>
    </div>
    <!-- FOOTER -->
    <?php require_once "../footer.php"; ?>
    </div><!-- fine wrapper -->
    </body>
</html>
