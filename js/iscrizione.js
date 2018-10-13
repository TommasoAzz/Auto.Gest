$(document).ready(function() {
    //ALERT CHE INFORMA SU COME DISISCRIVERSI
    var alertInfo="<div class='alert alert-info' role='alert'>"; //html da appendere
    alertInfo+="<button id='btnCloseAlertForever' type='button' class='close' data-dismiss='alert' aria-label='Chiudi'><span aria-hidden='true'>&times;</span></button>";
    alertInfo+="<strong>Attenzione!</strong> Se ti accorgi di esserti sbagliato ad iscrivere, vai nella pagina <code>I miei corsi</code> e premi il pulsante <strong><span class='fa fa-ban' aria-hidden='true'></span> Annulla l'iscrizione</strong>.";
    alertInfo+="</div>";

    $('form#iscrizione').submit(function(e) {
        e.preventDefault();
    });

    $("button#btnProsegui").prop('disabled',true);

    if(getCookie("alertClosed") == "") { //controllo il contenut dell'alert
        setCookie("alertClosed","false",1);
        $("div#modulo").append(alertInfo);
    } else if(getCookie("alertClosed") == "false") {
        $("div#modulo").append(alertInfo);
    }

    $('button#btnCloseAlertForever').click(function() {
        setCookie("alertClosed","true",1);
    });

    $('select#corso').change(function() {
        if($(this).val() == "") {
            $("button#btnProsegui").prop('disabled',true);
        } else {
            $("button#btnProsegui").prop('disabled',false);
        }
    });

    $('button#btnProsegui').click(function() {
        $("form#iscrizione")[0].submit();
    });
});
