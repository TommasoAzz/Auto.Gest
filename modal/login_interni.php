<div class="modal fade" id="login_interni" role="dialog" aria-labelledby="Login interni">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" id="userlogin" name="userlogin" method="post">
                <div class="modal-header">
                    <div class="row">
                        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                        <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                            <h2 class="text-center"><i class="fa fa-sign-in"></i> Accedi</h2>
                        </div>
                        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                    </div>
                </div>
                <div class="modal-body row">
                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                    <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                        <div class="form-group" id="spiegazione">
                            <p class="text-justify">Per poter procedere con l'iscrizione ai corsi di <?php echo $info["titolo"]; ?>, seleziona il tuo indirizzo, la tua classe ed infine inserisci la password che ti è stata consegnata dai Rappresentanti degli Studenti. Nel caso l'avessi smarrita, non esitare a contattarci, provvederemo a dartene una copia.</p>
                            <p class="text-justify">Non sei uno studente? Clicca <a href="#login_esterni" id="linkModalEsterni" data-toggle="modal">qui</a>.</p>
                        </div>
                        <div class="form-group" id="campo_show_hide_spiegazione">
                            <p class="text-justify"><a id="show_hide_spiegazione">Nascondi il paragrafo</a> qui sopra.</p>
                        </div>
			            <div class="form-group" id="campo_indirizzo">
                            <label for="indirizzo" id="lblIndirizzo">Indirizzo</label>
                            <select class="form-control" name="indirizzo" id="indirizzo">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="form-group" id="campo_classe">
                            <label for="classe" id="lblClasse">Classe</label>
                            <select class="form-control" name="classe" id="classe">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="form-group" id="campo_psw">
                            <label for="login_password" class="control-label">Password</label>
                            <input type="password" class="form-control" name="login_password" id="login_password" placeholder="********">
                        </div>
                    </div>
                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                        <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                            <button class="btn btn-primary btn-block" type="button" id="btnLogin" name="btnLogin">Accedi</button>
                        </div>
                        <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                            <a class="btn btn-danger btn-block" type="button" data-dismiss="modal">Annulla</a>
                        </div>
                        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
