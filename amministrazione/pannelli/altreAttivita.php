<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2 class="panel-title"><span class='fa fa-chevron-right'></span>  Pannello H</h2>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <?php
                        // recupero delle altre attività, se ce ne sono
                        $altreAttivita=getAltreAttivita($db);
                    ?>
                    <h3 class="text-center">Visualizzazione degli studenti impegnati in altre attività</h3>
                    <h5 class="text-center">Clicca per visualizzare cosa sono le <a href="#altreAttivita" data-toggle="modal" >altre attività</a>.</h5>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <label for="btnStampaPsw" class="control-label">Visualizza la lista</label>
                    <button type="button" class="btn btn-block btn-primary" id="visListaAltreAttivita" data-toggle='modal' role='button'>Visualizza</button>
                </div>
            </div>
        </div>
    </div>
</div>