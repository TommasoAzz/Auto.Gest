<?php
// funzione che stampa HTML per questo modal (è tutto uguale, per questo)
function stampaDatiUtente($dato) {
    $div = "<div class='row'>" .
            "<div class='hidden-xs hidden-sm col-md-1 col-lg-1'></div>" .
            "<div class='col-xs-3 col-sm-3 col-md-2 col-lg-2'><p><strong>" . $dato . "</strong></p></div>" .
            "<div class='col-xs-9 col-sm-9 col-md-8 col-lg-8'><p id='registrazione_" . strtolower($dato) . "'></p></div>" .
            "<div class='hidden-xs hidden-sm col-md-1 col-lg-1'></div>" .
            "</div>";
    
    return $div;
}
?>
<div class="modal fade" id="registrazioneUtente" role="dialog" aria-labelledby="Registrazione utente">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                    <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                        <h2 class="text-center"><i class="fa fa-user"></i> Registrazione utente</h2>
                    </div>
                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                </div>
            </div>
            <form id='registrazione_nuovo_utente'>
            <div class="modal-body">
                <div class="row">
                    <div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
                    <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                        <p>Compila questo modulo per poter creare il tuo account in Auto.Gest e poter poi accedere alle funzionalità a te dedicate per <?php echo $info["titolo"]; ?>.</p>
                        <p><strong>ATTENZIONE</strong>: se ti risulta che alcuni dati visualizzati siano errati, contattaci (usando i link che trovi nel piè di pagina).</p>
                    </div>
                    <div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
                </div>
                <?php 
                    echo stampaDatiUtente("Nome");
                    echo stampaDatiUtente("Cognome");
                    echo stampaDatiUtente("Classe");
                    echo stampaDatiUtente("Ruolo");
                ?>
                <div class="row">
                    <div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
                    <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                        <div class="form-group" id="campo_username_utente">
                            <label for="username_utente" class="control-label">Crea un nome utente con cui potrai accedere in <?php echo $info["titolo"]; ?>. Crealo in modo da riuscire a ricordartelo. </label>
                            <input type="text" class="form-control" name="username_utente" id="username_utente" placeholder="NomeCognome123" />
                        </div>
                    </div>
                    <div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
                </div>
                <div class="row">
                    <div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
                    <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                        <div class="form-group" id="campo_password_vecchia_utente">
                            <label for="password_vecchia_utente" class="control-label">Digita la password che ti è stata consegnata dai Rappresentanti degli Studenti.</label>
                            <input type="password" class="form-control" name="password_vecchia_utente" id="password_vecchia_utente" placeholder="********" />
                        </div>
                    </div>
                    <div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
                </div>
                <div class="row">
                    <div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
                    <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                        <div class="form-group" id="campo_password_nuova_utente">
                            <label for="password_nuova_utente" class="control-label">Crea una password con cui potrai accedere in <?php echo $info["titolo"]; ?>. Deve avere almeno un numero e un carattere speciale.</label>
                            <input type="password" class="form-control" name="password_nuova_utente" id="password_nuova_utente" placeholder="********" />
                        </div>
                    </div>
                    <div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
                </div>
                <div class="row">
                    <div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
                    <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
                        <div class="form-group" id="campo_password_nuova2_utente">
                            <label for="password_nuova2_utente" class="control-label">Digita nuovamente la password che hai appena creato.</label>
                            <input type="password" class="form-control" name="password_nuova2_utente" id="password_nuova2_utente" placeholder="********" />
                        </div>
                    </div>
                    <div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3">
                        <a id="btnProsegui" class="btn btn-success btn-block" type="button">Prosegui</a>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-6 col-lg-6"></div>
                    <div class="col-xs-4 col-sm-4 col-md-3 col-lg-3">
                        <a class="btn btn-danger btn-block" type="button" data-dismiss="modal">Chiudi</a>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
