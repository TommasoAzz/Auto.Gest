<div class="modal fade" id="sessioniCorso" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                    <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                        <h2 class="text-center">Sessioni del corso: <span id="nomeCorso">nome_corso</span></h2>
                    </div>
                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                </div>
            </div>
            <div class="modal-body row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <p class="text-center"><strong>Codice identificativo</strong>: <span id="idCorso"></span></p>
                    <p class="text-center"><strong>Durata</strong>: <span id="durataCorso"></span></p>
                    <p class="text-center"><strong>Aula</strong>: <span id="aulaCorso"></span></p>
                    <p class="text-center"><strong>Posti totali</strong>: <span id="postiCorso"></span></p>
                    <div class="table-responsive">
                        <table id="tabSessioniCorso" class="table table-hover table-striped table-consensed table-bordered">
                            <thead>
                                <tr>
                                    <th>Giorno</th>
                                    <th>Ora</th>
                                    <th>Posti rimasti</th>
                                    <th title="ID_SessioneCorso">Codice sessione</th>
                                </tr>
                            </thead>
                            <tbody id="tSessioniCorso">
                            </tbody>
                        </table>
                    </div>   
                </div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-xs-8 col-sm-8 col-md-10 col-lg-10"></div>
                    <div class="col-xs-4 col-sm-4 col-md-2 col-lg-2">
                        <a class="btn btn-danger btn-block" type="button" data-dismiss="modal">Chiudi</a> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>