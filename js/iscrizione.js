$(document).ready(function() {
    //ALERT CHE INFORMA SU COME DISISCRIVERSI
    const alertInfo="<div class='alert alert-info' role='alert'>" +
        "<button id='btnCloseAlertForever' type='button' class='close' data-dismiss='alert' aria-label='Chiudi'><span aria-hidden='true'>&times;</span></button>" +
        "<strong>Attenzione!</strong> Se ti accorgi di esserti sbagliato ad iscrivere, vai nella pagina <code>I miei corsi</code> e premi il pulsante <strong><span class='fa fa-ban' aria-hidden='true'></span> Annulla l'iscrizione</strong>." +
        "</div>";
    const $btnProsegui = $("button#btnProsegui");

    $('form#iscrizione').submit(function(e) {
        e.preventDefault();
    });

    $btnProsegui.prop('disabled',true);

    if(getCookie("alertClosed") == "") { //controllo il contenut dell'alert
        setCookie("alertClosed", "false", 1);
        $("div#modulo").append(alertInfo);
    } else if(getCookie("alertClosed") == "false") {
        $("div#modulo").append(alertInfo);
    }

    $('button#btnCloseAlertForever').click(function() {
        setCookie("alertClosed", "true", 1);
    });

    $('select#corso').change(function() {
        if($(this).val() == "") {
            $btnProsegui.prop('disabled',true);
        } else {
            $btnProsegui.prop('disabled',false);
        }
    });

    $btnProsegui.click(function() {
        $("form#iscrizione").submit();
    });
});
