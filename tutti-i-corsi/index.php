<?php
    require_once "../caricaClassi.php";
    require_once "../connettiAlDB.php";
    include_once "../getInfo.php";
    require_once "../funzioni.php";
    Session::open();
    $info = Session::get("info");
    $utente = Session::get("utente");
?>
<!doctype html>
<html lang="it">
    <head>
        <?php require_once "../head.php"; ?>
        <script type="text/javascript" src="../js/tutti-i-corsi.js"></script>
    </head>
    <body>
    <div id="wrapper" class="clearfix"><!-- inizio wrapper -->
    <!-- NAVBAR -->
    <?php require_once "../caricaNavbar.php"; ?>
    <!-- BODY -->
    <div id="content" class="container">
        <!-- INTESTAZIONE PAGINA -->
        <div class="row">
            <div class="hidden-xs hidden-sm col-md-12 col-lg-12">
                <h1 class="text-center">Tutti i corsi</h1>
                <h4 class="text-center sottotitolo">Lista di tutti i corsi disponibili durante l'evento</h4>
                <hr>
            </div>
            <div class="col-xs-12 col-sm-12 hidden-md hidden-lg">
                <h2 class="text-center">Tutti i corsi</h2>
                <h4 class="text-center sottotitolo">Lista di tutti i corsi disponibili durante l'evento</h4>
                <hr>
            </div>
        </div>
        <!-- CORPO PAGINA -->
        <div class="row">
            <form name="scelta_corsi" id="scelta_corsi">
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="input-group input-group-lg">
                        <span class="input-group-addon"><span class='fa fa-calendar'></span>  Giorno</span>
                        <select id="scelta_giorno" class="form-control"></select>
                    </div>
                </div>
                <div class="hidden-md hidden-lg col-sm-12 col-xs-12"></div>
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="input-group input-group-lg">
                        <span class="input-group-addon"><span class='fa fa-clock-o'></span>  Ora</span>
                        <select id="scelta_ora" class="form-control"></select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 hidden-md hidden-lg hidden-xl"></div>
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <button type="button" id="updateBtn" class="btn btn-info btn-lg btn-block"><span class="fa fa-refresh fa-spin fa-fw"></span>  Aggiorna lista</button>
                </div>
                <div class="col-xs-12 col-sm-12 hidden-md hidden-lg hidden-xl"></div>
            </form>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 hidden-md hidden-lg"><hr></div>
        </div>
        <div class="row">
            <div class="col-xs-1 col-sm-1 hidden-md hidden-lg"></div>
            <div class="col-xs-10 col-sm-10 hidden-md hidden-lg">
                <p class='text-justify'><span class='label label-default'>Suggerimento</span> Fai swipe a sinistra e destra all'interno della tabella per avere più informazioni sui corsi!</p>
            </div>
            <div class="col-xs-1 col-sm-1 hidden-md hidden-lg"></div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 hidden-md hidden-lg"><hr></div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="table-responsive">
                    <table id="listaCorsi" class="table table-hover">
                        <thead id="thead">
                            <tr>
                                <th><strong>Corso</strong></th>
                                <th><strong>Aula</strong></th>
                                <th><strong>Durata</strong></th>
                                <th><strong>Posti totali</strong></th>
                                <th><strong>Posti rimasti</strong></th>
                            </tr>
                        </thead>
                        <tbody id="tbody">
                            <tr>
                                <td>Nessun corso disponibile.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- FOOTER -->
    <?php require_once "../footer.php"; ?>
    </div> <!-- fine wrapper -->
    </body>
</html>
