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
        <script type='text/javascript' src='../js/amministrazione.js'></script>
    </head>
    <body>
    <div id="wrapper" class="clearfix"><!-- inizio wrapper -->
    <!-- NAVBAR -->
    <?php require_once "../switch_header.php"; ?>
    <!-- CONTROLLO ACCESSO -->
    <?php
        // PAGINA ACCESSIBILE SOLO DA UTENTI DI LIVELLO: 3
        if(!isset($utente)) { 
            header("Location: /");
        } elseif($utente->getLivello() == 2) {
            die("<script>location.href='/';</script>");
        }
    ?>
    <!-- BODY -->
    <div id="content" class="container">
        <!-- INTESTAZIONE PAGINA -->
        <div class="row">
            <div class="hidden-xs hidden-sm col-md-12 col-lg-12">
                <h1 class="text-center">Amministrazione</h1>
                <h4 class="text-center sottotitolo">Pannello di controllo di <?php echo $info["titolo"]; ?></h4>
                <hr>
            </div>
        </div>
        <!-- CORPO PAGINA --> 
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">Pannello A</h2>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <h3 class="text-center">Ricerca della chiave primaria (ID) di una persona</h3>
                                <h5 class="text-center"><span style='color: #a94442'>*</span> Campo obbligatorio</h5>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-4">
                                <div class="form-group" id="campo_nome">
                                    <label for="nome_ricerca" class="control-label">Nome <span style='color: #a94442'>*</span></label>
                                    <input type="text" class="form-control" name="nome_ricerca" id="nome_ricerca" />
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-4">
                                <div class="form-group" id="campo_cognome">
                                    <label for="cognome_ricerca" class="control-label">Cognome <span style='color: #a94442'>*</span></label>
                                    <input type="text" class="form-control" name="cognome_ricerca" id="cognome_ricerca" />
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                <label for="cercaID" class="control-label">Cerca ID della persona</label>
                                <button type="button" class="btn btn-primary btn-block" id="cercaID">Cerca</button>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                                <label for="risultatoRicercaID" class="control-label">Risultato ricerca</label>
                                <input type="text" class="form-control" name="risultatoRicercaID" id="risultatoRicercaID" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"><hr></div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">Pannello B</h2>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <h3 class="text-center">Reset totale dei corsi di un utente</h3>
                                <h5 class="text-center">&Egrave; richiesto l'<a id="goToPanel_A" title='Pannello A'>ID della persona</a> da resettare</h5>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group" id="campo_id_reset">
                                    <label for="id_reset" class="control-label">ID Persona</label>
                                    <input type="text" class="form-control" name="id_reset" id="id_reset" />
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <label for="cercaID" class="control-label">Reset della persona</label>
                                <button type="button" class="btn btn-block btn-danger" id="resetP">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 hidden-md hidden-lg"><hr></div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">Pannello C</h2>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <h3 class="text-center">Visualizzazione dei corsi a cui è iscritto un utente</h3>
                                <h5 class="text-center">&Egrave; richiesto l'<a id="goToPanel_A" title='Pannello A'>ID della persona</a> per visualizzarne i corsi</h5>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group" id="campo_corsiP">
                                    <label for="corsiP" class="control-label">ID Persona</label>
                                    <input type="text" class="form-control" name="corsiP" id="corsiP" />
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <label for="visCorsi" class="control-label">Visualizza i corsi</label>
                                <button type="button" class="btn btn-block btn-info" id="visCorsi">Visualizza</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="hidden-xs hidden-sm col-md-12 col-lg-12"><hr></div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">Pannello D</h2>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <h3 class="text-center">Cambio password ad un utente</h3>
                                <h5 class="text-center">&Egrave; richiesto l'<a id="goToPanel_A" title='Pannello A'>ID della persona</a> a cui effettuare il cambio password</h5>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group" id="campo_cambioPswP">
                                    <label for="cambioPswP" class="control-label">ID Persona</label>
                                    <input type="text" class="form-control" name="cambioPswP" id="cambioPswP" />
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <label for="btnCambioPswP" class="control-label">Clicca per proseguire</label>
                                <button type="button" class="btn btn-block btn-info" id="btnCambioPswP" data-toggle='modal' role='button'>Prosegui</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 hidden-md hidden-lg"><hr></div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">Pannello E</h2>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                 <h3 class="text-center">Visualizzazione delle sessioni di un corso</h3>
                                 <h5 class="text-center">Seleziona il corso dal menu a tendina</h5>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group" id="campo_corsiP">
                                    <label for="sessioniCorso" class="control-label">Scelta del corso</label>
                                    <select class="form-control" name="sessioniCorso" id="sessioniCorso"></select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <label for="visSessioniCorso" class="control-label">Visualizza i corsi</label>
                                <button type="button" class="btn btn-block btn-info" id="visSessioniCorso">Visualizza</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="hidden-xs hidden-sm col-md-12 col-lg-12"><hr></div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">Pannello F</h2>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <h3 class="text-center">Stampa la lista delle password</h3>
                                <h5 class="text-center">Stampati pronti per la consegna alle classi</h5>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label for="btnStampaPsw" class="control-label">Clicca per proseguire</label>
                                <button type="button" class="btn btn-block btn-info" id="btnStampaPsw" data-toggle='modal' role='button'>Prosegui</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 hidden-md hidden-lg"><hr></div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h2 class="panel-title">Pannello G</h2>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <h3 class="text-center">Visualizza il registro presenze di un corso</h3>
                                <h5 class="text-center">Inserisci il <a id="goToPanel_E" title="Pannello E">codice della sessione del corso</a></h5>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-group" id="campo_presenzeSessione">
                                    <label for="presenzeSessione" class="control-label">Scelta del corso</label>
                                    <input type="text" class="form-control" name="presenzeSessione" id="presenzeSessione" />
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                                <label for="visPresenzeSessione" class="control-label">Visualizza le sessioni</label>
                                <button type="button" class="btn btn-block btn-primary" id="visPresenzeSessione">Visualizza</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- FOOTER -->
    <?php require_once "../footer.php"; ?>
    <!-- SESSIONI CORSO MODAL -->
    <?php require_once "modal_sessioniCorso.php"; ?>
    <?php require_once "modal_corsi.php"; ?>
    <?php require_once "modal_presenzeSessione.php"; ?>
    </div><!-- fine wrapper -->
    </body>
</html>
