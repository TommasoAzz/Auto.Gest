<div class="modal fade" id="login_esterni" role="dialog" aria-labelledby="Login esterni">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" id="extUserlogin" name="extUserlogin" method="post">
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
                        <div class="form-group" id="extSpiegazione">
                            <p class="text-justify">Per poter gestire il tuo corso, seleziona dal menu a tendina se fai parte del personale della scuola o se sei una persona esterna, poi inserisci la password che ti è stata consegnata dai Rappresentanti degli Studenti. Nel caso l'avessi smarrita, non esitare a contattarci, provvederemo a dartene una copia.</p>
                            <p class="text-justify">Non fai parte del personale oppure non sei un esterno alla scuola? Clicca <a href="#login_interni" id="linkModalInterni" data-toggle="modal">qui</a>.</p>
                        </div>
                        <div class="form-group" id="campo_show_hide_extSpiegazione">
                            <p class="text-justify"><a id="show_hide_extSpiegazione">Nascondi il paragrafo</a> qui sopra.</p>
                        </div>
			            <div class="form-group" id="extCampo_indirizzo">
                            <label for="extIndirizzo" id="extLblIndirizzo">Personale/Esterno</label>
                            <select class="form-control" name="extIndirizzo" id="extIndirizzo">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="form-group" id="extCampo_psw">
                            <label for="extLogin_password" class="control-label">Password</label>
                            <input type="password" class="form-control" name="extLogin_password" id="extLogin_password" placeholder="********">
                        </div>
                    </div>
                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                        <div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
                            <button class="btn btn-primary btn-block" type="button" id="extBtnLogin" name="extBtnLogin">Accedi</button>
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
