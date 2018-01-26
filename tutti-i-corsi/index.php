<!-- Auto.Gest -->
<?php
    require_once "../connectToDB.php";
    require_once "../classes.php";
    include "../getInfo.php";
    Session::open();
    $info=Session::get("info");
?>
<html>
    <head>
        <?php require_once "../head.php"; ?>
        <script type="text/javascript" src="../js/tutti-i-corsi.js"></script>
        <script type='text/javascript' src="../js/login.js"></script>
    </head>
    <body>
    <div id="wrapper" class="clearfix"><!-- inizio wrapper -->
    <!-- NAVBAR -->
    <?php require_once "../switch_header.php"; ?>
    <!-- BODY -->
    <div id="content" class="container">
        <!-- INTESTAZIONE PAGINA -->
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <h1 class="text-center">Tutti i corsi</h1>
                <h4 class="text-center sottotitolo">Lista di tutti i corsi disponibili, aggiornata automaticamente ogni 10 secondi</h4>
                <hr>
            </div>
            <div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
        </div>
        <!-- CORPO PAGINA -->
        <div class="row">
            <form name="scelta_corsi" id="scelta_corsi">
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="input-group input-group-lg">
                        <span class="input-group-addon">Giorno</span>
                        <select id="scelta_giorno" class="form-control"></select>
                    </div>
                </div>
                <div class="hidden-md hidden-lg col-sm-12 col-xs-12"></div>
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <div class="input-group input-group-lg">
                        <span class="input-group-addon">Ora</span>
                        <select id="scelta_ora" class="form-control"></select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 hidden-md hidden-lg hidden-xl"></div>
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <button type="button" id="updateBtn" class="btn btn-info btn-lg btn-block">Aggiorna dati <span class="fa fa-refresh fa-spin fa-fw"></span></button>
                </div>
                <div class="col-xs-12 col-sm-12 hidden-md hidden-lg hidden-xl"></div>
            </form>
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
    <!-- LOGIN MODAL -->
    <?php require_once "../login_modals.php"; ?>
    </div> <!-- fine wrapper -->
    </body>
</html>
