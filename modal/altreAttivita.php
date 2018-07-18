<div class="modal fade" id="altreAttivita" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="row">
                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                    <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                        <h2 class="text-center"><span class='fa fa-info'></span> Altre attività</h2>
                    </div>
                    <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                </div>
            </div>
            <div class="modal-body row">
                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
                <div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
                    <h3>Cosa sono le altre attività?</h3>
                    <p class="text-justify">Durante <?php echo $info["titolo"]; ?> oltre ai corsi a cui puoi iscriverti, ce n'è uno chiamato <strong>Altre attività</strong>.</p>
                    <p class="text-justify">Questo è un <em>corso fittizio</em> (infatti appare nella lista dei corsi a cui ci si può iscrivere assieme agli altri) a cui possono 
                    iscriversi solamente coloro che sono impegnati durante le giornate dell'evento a svolgere attività diverse dai corsi frequentabili.</p>
                    <p class="text-justify">Queste <strong>altre attività</strong> sono: 
                    <?php echo $altreAttivita; /* $altreAttivita deve essere inizializzata nel file che chiama questo file */ ?>
                    </p> 
                </div>
                <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1"></div>
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