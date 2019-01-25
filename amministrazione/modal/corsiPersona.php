<div class="modal fade" id="corsiPersona" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                    <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                        <h2 class="text-center">Corsi dello studente con ID: <span id="idPersona">id</span></h2>
                    </div>
                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                </div>
            </div>
            <div class="modal-body row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="table-responsive">
                        <table id="tabCorsiPersona" class="table table-hover table-striped table-consensed table-bordered">
                            <thead>
                                <tr>
                                    <th>Giorno</th>
                                    <th>Ora</th>
                                    <th>Corso</th>
                                    <th>Durata</th>
                                    <th>Aula</th>
                                    <th title="ID_SessioneCorso">Codice sessione</th>
                                </tr>
                            </thead>
                            <tbody id="tCorsiPersona">
                                <tr>
                                    <td>Lo studente con ID: <span id="idPersona"></span> non è iscritto ad alcun corso.</td><td></td><td></td><td></td><td></td><td></td>
                                </tr>
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