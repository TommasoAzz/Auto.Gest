<?php
    require_once "../caricaClassi.php";
    require_once "../connettiAlDB.php";
    include_once "../getInfo.php";
    require_once "../funzioni.php";
    Session::open();
    $info=Session::get("info");
    $utente=Session::get("utente");
?>
<html>
    <head>
        <?php require_once "../head.php"; ?>
        <script type="text/javascript" src="../js/iscrizione.js"></script>
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

        controlloAccesso($db,$utente,$livelliAmmessi);
    ?>
    <!-- NAVBAR -->
    <?php require "../caricaHeader.php"; ?>
    
    <!-- REPERIMENTO DATI -->
    <?php
        // recupero giorno di iscrizione da cui partire
        $nGiorno=getGiornoDaIscriversi($db,$utente);

        if(/* 1 */ $nGiorno === "errore_db_giorno_iscrizione" || /* 2 */$nGiorno === "fine_iscrizione") {
            //eseguito nel caso (1) se c'è stato un errore con la comunicazione al db
            //eseguito nel caso (2) se l'utente ha terminato il processo di iscrizione per intero
            /* processo di comunicazione errore */
            Session::set("errIscrizione",$nGiorno);
            die("<script>location.href='iscrizione.php';</script>"); //provare a usare header()
        } else { //se non assume quei valori lì allora è un numero intero e posso convertirlo
            $nGiorno=intval($nGiorno);
        }

        // recupero ora di iscrizione da cui partire nel giorno $nGiorno
        $nOra=getOraDaIscriversi($db,$utente,$nGiorno);

        if($nOra === "errore_db_ora_iscrizione") { //eseguito in caso di errore
            Session::set("errIscrizione",$nOra);
            die("<script>location.href='iscrizione.php';</script>");
        } else { //se non assume quel valori lì allora è un numero intero e posso convertirlo
            $nOra=intval($nOra);
        }

        // recupero delle altre attività, se ce ne sono
        $altreAttivita=getAltreAttivita($db);

        // recupero del sottotitolo da inserire nella pagina
        $sottotitolo=getSottotitolo($db,$nGiorno);

        if($sottotitolo == "errore_db_sottotitolo") $sottotitolo = "Err. sottotitolo";
        /* fine reperimento dati per pagina iscrizione */
    ?>
    <div id="content" class="container">
        <!-- INTESTAZIONE PAGINA -->
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <h1 class="text-center">Iscrizione</h1>
                <h4 class="text-center sottotitolo"><?php echo $sottotitolo ?></h4>
                <hr>
            </div>
        </div>
        <!-- CORPO PAGINA -->
        <div class="row">
            <div class="hidden-xs hidden-sm col-md-3 col-lg-3"></div>
            <div id="modulo" class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <form id='iscrizione' action="script/updateDB.php" method="post">
                    <?php creazioneBloccoIscrizione($db,$utente,$nGiorno,$nOra); ?>
                </form>
                <?php if($altreAttivita !== "errore_altre_attivita" && $altreAttivita !== "no_altre_attivita"): ?>
                    <p class='text-center'><span class='fa fa-info'></span>  Che corso è <a href='#altreAttivita' data-toggle='modal' role='button'>Altre attività</a>?</p>";
                <?php endif; ?>
            </div>
            <div class="hidden-xs hidden-sm col-md-3 col-lg-3"></div>
        </div>
    </div>
    <!-- FOOTER -->
    <?php require_once "../footer.php"; ?>
    </div><!-- fine wrapper -->
    <?php require_once "../modal/altreAttivita.php"; ?>
    </body>
</html>
