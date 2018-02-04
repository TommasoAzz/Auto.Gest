<!-- Auto.Gest -->
<?php
    require_once "../connectToDB.php";
    require_once "../classes.php";
    include_once "../getInfo.php";
    Session::open();
    $info=Session::get("info");
?>
<html>
    <head>
        <?php require_once "../head.php"; ?>
    </head>
    <body>
    <div id="wrapper" class="clearfix"><!-- inizio wrapper -->
    <!-- NAVBAR -->
    <?php require_once "../switch_header.php"; ?>
    <!-- CONTROLLO ACCESSO -->
    <?php
        // PAGINA ACCESSIBILE SOLO DA UTENTI DI LIVELLO: 1, 3
        if(!isset($utente)) { 
            header("Location: /");
        } elseif($utente->getLivello() == 2) {
            die("<script>location.href='/';</script>");
        }
    ?>
    <?php
        if(Session::is_set("errIscrizione")) {
            $err=Session::get("errIscrizione");
            switch($err) {
                case "fine": //iscrizione completata
                    $msg="<h2 class='text-center'>Congratulazioni!</h2><h4 class='text-justify'>Ti sei iscritto con successo a ".$info["titolo"].".</h4><p class='text-justify'>Clicca <a href='".getBaseURL()."/i-miei-corsi/'>qui</a> per visualizzare un promemoria dei corsi da te scelti, oppure torna alla <a href='../'>pagina principale</a>.</p>";
                    break;
                case "sessioneCorso": //corso non disponibile (posti terminati)
                    $msg="<h2 class='text-center'>Siamo spiacenti!</h1><h4 class='text-justify'>I posti disponibili nel corso da te selezionato sono terminati.</h4><p class='text-justify'>Clicca <a href='/'>qui</a> per tornare ad iscriverti.</p>";
                    break;
                case "errore_giorno": //errore nel reperimento del giorno dal database
                    $msg="<h2 class='text-center'>Errore nel reperimento del giorno.</h2><h4 class='text-justify'>Ci sono stati dei problemi nella comunicazione con il database durante la richiesta del giorno a cui ti devi iscrivere.</h4><p class='text-justify'>Clicca <a href='/'>qui</a> per riprovare. Se il problema persiste, contatta i tuoi Rappresentanti degli Studenti.</p>";
                    break;
                case "errore_ora": //errore nel reperimento dell'ora dal database
                    $msg="<h2 class='text-center'>Errore nel reperimento dell'ora.</h2><h4 class='text-justify'>Ci sono stati dei problemi nella comunicazione con il database durante la richiesta dell'ora a cui ti devi iscrivere.</h4><p class='text-justify'>Clicca <a href='/'>qui</a> per riprovare. Se il problema persiste, contatta i tuoi Rappresentanti degli Studenti.</p>";
                    break;
            }
        } else {
            die("<script>location.href='/';</script>");
        }
    ?>
    <div id="content" class="container">
        <!-- INTESTAZIONE PAGINA -->
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <h1 class="text-center">Iscrizione</h1>
                <h4 class="text-center sottotitolo"><?php echo $info["titolo"]; ?></h4>
                <hr>
            </div>
        </div>
        <!-- CORPO PAGINA -->
        <div class="row">
            <div class="hidden-xs hidden-sm col-md-4 col-lg-4"></div>
            <div id="modulo" class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <?php echo $msg; ?>
            </div>
            <div class="hidden-xs hidden-sm col-md-4 col-lg-4"></div>
        </div>
    </div>
    <!-- FOOTER -->
    <?php require_once "../footer.php"; ?>
    </div><!-- fine wrapper -->
    </body>
</html>