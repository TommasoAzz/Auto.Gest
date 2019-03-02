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
        <script type='text/javascript' src='../js/registro-presenze.js'></script>
    </head>
    <body>
    <div id="wrapper" class="clearfix"><!-- inizio wrapper -->
    <!-- CONTROLLO ACCESSO -->
    <?php
        // PAGINA ACCESSIBILE SOLO DA UTENTI DI LIVELLO: 2, 3
        
        $livelliAmmessi = array(
            1 => false, //livello studente
            2 => true, //livello responsabile corso
            3 => true //livello amministratore
        );

        controlloAccesso($db,$utente,$livelliAmmessi);
    ?>
    <!-- NAVBAR -->
    <?php require "../caricaNavbar.php"; ?>
    <!-- BODY -->
    <div id="content" class="container">
        <!-- INTESTAZIONE PAGINA -->
        <div class="row">
            <div class="hidden-xs hidden-sm col-md-12 col-lg-12">
                <h1 class="text-center">Registro presenze di <?php echo $utente->getNome()." ".$utente->getCognome(); ?></h1>
                <h4 class="text-center sottotitolo">Registro presenze</h4>
                <hr>
            </div>
            <div class="col-xs-12 col-sm-12 hidden-md hidden-lg">
                <h2 class="text-center">Registro presenze di <?php echo $utente->getNome()." ".$utente->getCognome(); ?></h2>
                <h4 class="text-center sottotitolo">Registro presenze</h4>
                <hr>
            </div>
        </div>
        <!-- CORPO PAGINA -->
        <div class="row">
            <form name="lista_corsi" id="lista_corsi">
                <div class="hidden-xs hidden-sm col-md-2 col-lg-2"></div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="input-group input-group-lg">
                        <span class="input-group-addon">Corso</span>
                        <select id="scelta_corso" class="form-control"><!-- riempito con JS --></select>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                    <button type="button" id="btnRegistroPresenze" class="btn btn-info btn-lg btn-block center-block"><span class="fa fa-check"></span>&nbsp;&nbsp;Visualizza</button>
                </div>
                <div class="hidden-xs hidden-sm col-md-2 col-lg-2"></div>
            </form>
        </div>
        <div id="divisorio" class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><hr></div>
        </div>
        <div id="infoPresenze" class="row">
            <div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                <p class="text-center">Per segnare la presenza di uno o più studenti sarà sufficiente selezionare <strong>Presente</strong>/<strong>Assente</strong>/<strong>Ritardo</strong> scegliendo fra i tre pulsanti della colonna <strong>Presenza</strong> e successivamente premere <strong>Conferma</strong>. In questo modo la partecipazione degli altri iscritti rimarrà invariata.</p>
            </div>
            <div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
        </div>
        <div id="listaPersone" class="row">
            <div class="hidden-xs hidden-sm col-md-2 col-lg-2"></div>
            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                <div class="table-responsive">
                    <table class="table" id="gestTable">
                        <thead>
                             <tr>
                               <th><strong>#</strong></th>
                                <th><strong>Nome</strong></th>
                                <th><strong>Cognome</strong></th>
                                <th><strong>Presenza</strong></th>
                            </tr>
                        </thead>
                        <tbody id="tbody_gestCorso">
                        </tbody>
                    </table>
                </div>
                <button type="button" id="btnConferma" class="btn btn-info btn-lg btn-block"><span class="fa fa-check"></span>&nbsp;&nbsp;Conferma</button>
            </div>
            <div class="hidden-xs hidden-sm col-md-2 col-lg-2"></div>
        </div>
    </div>
    <!-- FOOTER -->
    <?php require_once "../footer.php"; ?>
    </div>
    </body>
</html>
