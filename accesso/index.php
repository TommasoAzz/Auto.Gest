<?php
require_once "../caricaClassi.php";
require_once "../connettiAlDB.php";
include_once "../getInfo.php";
require_once "../funzioni.php";
Session::open();
$info=Session::get("info");
?>
<html>
    <head>
        <?php require_once "../head.php"; ?>
        <script type='text/javascript' src="../js/login.js"></script>
    </head>
    <body>
    <div id="wrapper" class="clearfix"><!-- inizio wrapper -->
    <!-- NAVBAR -->
    <?php require_once "../caricaNavbar.php"; ?>
    <div id="content" class="container">
        <!-- INTESTAZIONE PAGINA -->
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <h1 class="text-center">Accesso ad Auto.Gest</h1>
                <h4 class="text-center sottotitolo">Accedi al sistema di <?php echo $info["titolo"]; ?></h4>
                <hr>
            </div>
        </div>
        <!-- CORPO PAGINA -->
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="text-center"><i class="fa fa-sign-in"></i> Primo accesso</h3>
                    </div>
                    <div class="panel-body">
                        <form id="1st_access_login" name="1st_access_login" method="post">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group" id="1o_accesso_spiegazione">
                                        <p class="text-justify">Per poter procedere con la registrazione del tuo profilo per <?php echo $info["titolo"]; ?>, seleziona il tuo indirizzo, la tua classe ed infine inserisci la password che ti è stata consegnata dai Rappresentanti degli Studenti. Nel caso l'avessi smarrita, non esitare a contattarci (usando i link che trovi nel piè di pagina), provvederemo a dartene una copia.</p>
                                        <p class="text-justify">Non sei uno studente? Clicca <a href="#login_esterni" id="linkModalEsterni" data-toggle="modal">qui</a>.</p>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group" id="campo_indirizzo">
                                        <label for="indirizzo" id="lblIndirizzo">Indirizzo</label>
                                        <select class="form-control" name="indirizzo" id="indirizzo">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group" id="campo_classe">
                                        <label for="classe" id="lblClasse">Classe</label>
                                        <select class="form-control" name="classe" id="classe">
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group" id="campo_psw">
                                        <label for="1st_access_login_password" class="control-label">Password</label>
                                        <input type="password" class="form-control" name="1st_access_login_password" id="1st_access_login_password" placeholder="********">
                                    </div>
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"></div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <button class="btn btn-success btn-lg btn-block" type="button" id="btnProcedi" name="btnProcedi">Procedi</button>
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="text-center"><i class="fa fa-sign-in"></i> Accedi</h3>
                    </div>
                    <div class="panel-body">
                        <form id="userlogin" name="userlogin" method="post">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group" id="accesso_spiegazione">
                                        <p class="text-justify">Se hai già effettuato il primo accesso (tramite il pannello <strong>Primo accesso</strong>), puoi accedere con le credenziali che hai creato.</p>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group" id="campo_username">
                                        <label for="login_username" class="control-label">Nome utente</label>
                                        <input type="text" class="form-control" name="login_username" id="login_username" placeholder="NomeCognome123" />
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                    <div class="form-group" id="campo_psw">
                                        <label for="login_password" class="control-label">Password</label>
                                        <input type="password" class="form-control" name="login_password" id="login_password" placeholder="********">
                                    </div>
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"></div>
                                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
                                    <button class="btn btn-success btn-lg btn-block" type="button" id="btnAccedi" name="btnAccedi">Accedi</button>
                                </div>
                                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- FOOTER -->
    <?php require_once "../footer.php"; ?>
    </div><!-- fine wrapper -->
    </body>
</html>
