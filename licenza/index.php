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
                <h1 class="text-center">Licenza</h1>
                <h4 class="text-center sottotitolo">Licenza del software Auto.Gest</h4>
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
                <h3>Licenza di Auto.Gest</h3>
                <h4>La licenza MIT</h4>
                <p class="text-justify">Copyright &copy; <?php echo date('Y');?> Tommaso Azzalin</p>
                <p class="text-justify">
                    Con la presente si concede, a chiunque ottenga una copia di questo software e dei file di documentazione associati (il "Software"), l'autorizzazione a usare gratuitamente il Software senza alcuna limitazione, compresi i diritti di usare, copiare, modificare, unire, pubblicare, distribuire, cedere in sottolicenza e/o vendere copie del Software, nonché di permettere ai soggetti cui il Software è fornito di fare altrettanto, alle seguenti condizioni:
                    <br />L'avviso di copyright indicato sopra e questo avviso di autorizzazione devono essere inclusi in ogni copia o parte sostanziale del Software.
                    <br />IL SOFTWARE VIENE FORNITO "COSÌ COM'È", SENZA GARANZIE DI ALCUN TIPO, ESPLICITE O IMPLICITE, IVI INCLUSE, IN VIA ESEMPLIFICATIVA, LE GARANZIE DI COMMERCIABILITÀ, IDONEITÀ A UN FINE PARTICOLARE E NON VIOLAZIONE DEI DIRITTI ALTRUI. IN NESSUN CASO GLI AUTORI O I TITOLARI DEL COPYRIGHT SARANNO RESPONSABILI PER QUALSIASI RECLAMO, DANNO O ALTRO TIPO DI RESPONSABILITÀ, A SEGUITO DI AZIONE CONTRATTUALE, ILLECITO O ALTRO, DERIVANTE DA O IN CONNESSIONE AL SOFTWARE, AL SUO UTILIZZO O AD ALTRE OPERAZIONI CON LO STESSO.
                </p>
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
