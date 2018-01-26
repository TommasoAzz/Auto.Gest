/*
 *  File script di Auto.Gest
 *  Necessario in tutte le pagine del sito
 *  Creato da Tommaso Azzalin
*/

//___ FUNZIONI VARIE ___//
function setCookie(cName,cVal,durata/* in giorni */) {
    var d = new Date();
    d.setTime(d.getTime() + (durata*24*60*60*1000));
    var scadenza = "expires="+ d.toUTCString();
    document.cookie = cName + "=" + cVal + ";" + scadenza + ";path=/";
}

function getCookie(cName) { //restituisce contenuto cookie passato come parametro
    var name = cName + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(";");
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) === 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function reverseString(str) { //rovescia e restituisce stringa passata come parametro
    var rev=""; //variabile contenente la stringa rovesciata
    for(var i=str.length;i>=0;i--) rev+=str.charAt(i);
    return rev;    
} //rimovibile

function gestioneNavbar() { //controlla la pagina e attiva menu diversamente
    // Riconoscimento pagina
    var pagina = window.location;
    $('.nav > li > a[href="'+pagina+'"]').parent().addClass('active');
    $('.nav > li > a').mouseenter(function() {
        $(this).parent().addClass('active');
    }).mouseleave(function() {
        $(this).parent().removeClass('active');
        $('.nav > li > a[href="'+pagina+'"]').parent().addClass('active');
    });
}

//___ METODO PRINCIPALE ___//

$(document).ready(function() {
	
    //___ GENERALE ___//

    gestioneNavbar(); //metodi che gestiscono la barra di navigazione e il footer
   
    $('a#linkModalEsterni').click(function() { //evento che gestisce l'apertura del modal per i non studenti
        $("div#login").modal('hide');
    });
    
    $("body").keyup(function(e) { //evento per la gestione della chiusura dei modal con tasto ESC -- aggiungere tasto invio per l'invio dei dati
        if(e.which == 27) {
            $("div#login, div#extLogin").modal("hide");
        }
    });
    
});