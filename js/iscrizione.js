$(document).ready(function() {
    //ALERT CHE INFORMA SU COME DISISCRIVERSI
    var alertInfo="<div class='alert alert-info' role='alert'>"; //html da appendere
    alertInfo+="<button id='btnCloseAlertForever' type='button' class='close' data-dismiss='alert' aria-label='Chiudi'><span aria-hidden='true'>&times;</span></button>";
    alertInfo+="<strong>Attenzione!</strong> Se ti accorgi di esserti sbagliato ad iscrivere, vai nella pagina <code>I miei corsi</code> e premi il pulsante <strong><span class='fa fa-ban' aria-hidden='true'></span> Annulla l'iscrizione</strong>.";
    alertInfo+="</div>";
    
    $("button#btnProsegui").prop('disabled',true);

    if(getCookie("alertClosed")=="") { //controllo il contenut dell'alert
        setCookie("alertClosed","false",1);
        $("div#modulo").append(alertInfo);
    } else if(getCookie("alertClosed")=="false") {
        $("div#modulo").append(alertInfo);   
    }

    $('button#btnCloseAlertForever').click(function() {
        setCookie("alertClosed","true",1);
    });
    
    /* QUESTO QUI SOTTO E' UN EASTER EGG. RIMUOVERE L'INTERO BLOCCO QUANDO NON SERVE PIU' */
    var easterEggScoperto=false;
    /* FINE BLOCCO EASTER EGG */
    $('select#corso').change(function(){
        if($(this).val() == "") {
            $("button#btnProsegui").prop('disabled',true);
        } else {
            $("button#btnProsegui").prop('disabled',false);
        }
        /* QUESTO QUI SOTTO E' UN EASTER EGG. RIMUOVERE L'INTERO BLOCCO QUANDO NON SERVE PIU' */
        if($('select#corso').val() !== "Triplo sette su ogni cosa_1" || easterEggScoperto) {
            $("audio#dpgAUDIO, img#dpgIMG, p#dpgTEXT").fadeOut("slow").remove();
            $("body").css('background-image','').css('background-repeat','');
        }
        /* FINE BLOCCO EASTER EGG */
        
    });

    $('form#iscrizione').submit(function(e) {
        e.preventDefault();
    });

    /* FORM DA LASCIARE A EASTER EGG CONCLUSO
    $('button#btnProsegui').click(function() {
        $("form#iscrizione")[0].submit();    
    });
    */

    /* QUESTO QUI SOTTO E' UN EASTER EGG. RIMUOVERE L'INTERO BLOCCO QUANDO NON SERVE PIU' */
    $('button#btnProsegui').click(function() {
        if($('select#corso').val() == "Triplo sette su ogni cosa_1" && !easterEggScoperto) {
            easterEggScoperto=true;
            let dpg="<img id='dpgIMG' class='img-responsive center-block' style='display:none' src='/img/dpgIMG.png' />";
            dpg+="<p id='dpgTEXT' class='text-center' style='display:none'><kbd>Ti piacerebbe, eh? Scegli un altro corso, BUFU!</kbd></p>";
            $('div#modulo').prepend(dpg);
            $("img#dpgIMG, p#dpgTEXT").fadeIn("slow");
            $("body").css('background-image','url("/img/dpgBG.png")').css('background-repeat','repeat');
            let audio = document.createElement("audio");
            audio.setAttribute("src","/img/dpgAUDIO.mp3");
            audio.setAttribute("id","dpgAUDIO");
            audio.play();
        } else if($('select#corso').val() !== "Triplo sette su ogni cosa_1") {
            $("form#iscrizione")[0].submit();
        }
    });
    /* FINE BLOCCO EASTER EGG */
    
});