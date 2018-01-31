<!-- Info modal -->
<div class="modal fade" id="datiUtente" tabindex="-1" role="dialog" aria-labelledby="I tuoi dati">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                    <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                        <h2 class="text-center"><i class="fa fa-info"></i> I miei dati</h2>
                    </div>
                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                </div>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
                    <div class="col-xs-3 col-sm-3 col-md-2 col-lg-2"><p><strong>Nome</strong></p></div>
                    <div class="col-xs-9 col-sm-9 col-md-8 col-lg-8"><p><?php echo $nome; ?></p></div>
                    <div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
                </div>
                <div class="row">
                    <div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
                    <div class="col-xs-3 col-sm-3 col-md-2 col-lg-2"><p><strong>Classe</strong></p></div>
                    <div class="col-xs-9 col-sm-9 col-md-8 col-lg-8"><p><?php echo $classe; ?></p></div>
                    <div class="hidden-xs hidden-sm col-md-1 col-lg-11"></div>
                </div>
                <div class="row">
                    <div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
                    <div class="col-xs-3 col-sm-3 col-md-2 col-lg-2"><p><strong>Scuola</strong></p></div>
                    <div class="col-xs-9 col-sm-9 col-md-8 col-lg-8"><p><?php echo $scuola; ?></p></div>
                    <div class="hidden-xs hidden-sm col-md-1 col-lg-11"></div>
                </div>
                <div class="row">
                    <div class="hidden-xs hidden-sm col-md-1 col-lg-1"></div>
                    <div class="col-xs-3 col-sm-3 col-md-2 col-lg-2"><p><strong>Corsi a cui sei iscritto</strong></p></div>
                    <div class="col-xs-9 col-sm-9 col-md-8 col-lg-8"><a href="/i-miei-corsi/">Clicca qui</a></div>
                    <div class="hidden-xs hidden-sm col-md-1 col-lg-11"></div>
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