<!-- Auto.Gest -->
<?php
    require_once "../classes.php";
    require_once "../connectToDB.php";
    include "../getInfo.php"; 
    Session::open();
    $info=Session::get("info");
    $db=Session::get("db");
?>
<html>
    <head>
        <?php require_once "../head.php"; ?>
        <script type="text/javascript" src="../js/iscrizione.js"></script>
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
    <!-- REPERIMENTO DATI -->
    <?php
        require_once "funzioni-iscrizione.php";

        $nGiorno=1; $nOra=1; //nGiorno è il giorno in cui ci si deve iscrivere, nOra è l'ora della quale cercare i corsi

        //recupero il giorno in cui deve iscriversi l'utente
        $nGiorno=getGiornoDaIscriversi($db,$utente);

        if($nGiorno == "errore-reperimento-giorno") { //eseguito in caso di errore
            //processo di comunicazione errore
            Session::set("errIscrizione","errore_giorno");
            die("<script>location.href='messaggio.php';</script>");
        }
        if($nGiorno == "fine-iscrizione") { //eseguito in caso di fine dell'iscrizione
            //processo di iscrizione ultimato
            Session::set("errIscrizione","fine");
            die("<script>location.href='messaggio.php';</script>");
        }

        /* reperimento dati per pagina iscrizione */
        $nGiorno=intval($nGiorno);
        
        //reperimento dell'ora da iscrivere
        $nOra=getOraDaIscriversi($db,$utente,$nGiorno);

        if($nOra == "errore-reperimento-ore") { //eseguito in caso di errore
            Session::set("errIscrizione","errore_ora");
            die("<script>location.href='messaggio.php';</script>");
        }

        if($nOra !== "cambio-giorno") { //eseguito in caso di $nOra di valore numerico
            $nOra=intval($nOra);
        }

        $query="SELECT Lista FROM AltreAttivita WHERE ID=1";
        $query2="SELECT COUNT(*) AS Esiste FROM Corsi WHERE Nome='Altre attività'";
        $res=$db->qikQuery($query);
        $res2=$db->qikQuery($query2);
        if($res !== false && trim($res[0]["Lista"]) !== "" && $res2[0]["Esiste"] !== "0") {
            $altreAttivita=trim($res[0]["Lista"]);
        } else {
            $altreAttivita="no-altre-attivita";
        }

        /* fine reperimento dati per pagina iscrizione */
    ?>
    <div id="content" class="container">
        <!-- INTESTAZIONE PAGINA -->
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <h1 class="text-center">Iscrizione</h1>
                <h4 class="text-center sottotitolo"><?php echo getTitolo($db,$nGiorno); ?></h4>
                <hr>
            </div>
        </div>
        <!-- CORPO PAGINA -->
        <div class="row">
            <div class="hidden-xs hidden-sm col-md-3 col-lg-3"></div>
            <div id="modulo" class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <form id='iscrizione' action="updateDB.php" method="post"> 
                    <?php creazioneBloccoIscrizione($db,$utente,$nGiorno,$nOra); ?>
                </form>
                <?php
                    if($altreAttivita !== "no-altre-attivita") {
                        echo "<p class='text-center'><span class='fa fa-info'></span>  Che corso è <a href='#altreAttivita' data-toggle='modal' role='button'>Altre attività</a>?</p>";
                    }
                ?>
            </div>
            <div class="hidden-xs hidden-sm col-md-3 col-lg-3"></div>
        </div>
    </div>
    <!-- FOOTER -->
    <?php require_once "../footer.php"; ?>
    </div><!-- fine wrapper -->
    <?php require_once "../modal_altreAttivita.php"; ?>
    </body>
</html>
