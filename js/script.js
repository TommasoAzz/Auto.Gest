/*
 *  File script di Auto.Gest
 *  Necessario in tutte le pagine del sito
 *  Creato da Tommaso Azzalin
*/

//___ FUNZIONI VARIE ___//
function setCookie(cName,cVal,durata/* in giorni */) {
    var d = new Date();
    d.setTime(d.getTime() + (durata*24*60*60*1000));
    const scadenza = "expires="+ d.toUTCString();
    document.cookie = cName + "=" + cVal + ";" + scadenza + ";path=/";
}

function getCookie(nomeCookie) { //restituisce contenuto cookie passato come parametro
    const nome = nomeCookie + "=";
    const decodedCookie = decodeURIComponent(document.cookie); //recupero dei cookie dal browser
    const vCookie = decodedCookie.split(";"); //vettore dei cookie
    for(let i=0,l=vCookie.length;i<l;i++) {
        var biscotto = vCookie[i];
        while(biscotto.charAt(0) == ' ') biscotto=biscotto.substring(1);
        if(biscotto.indexOf(nome) === 0) return biscotto.substring(nome.length,biscotto.length);
    }
    return "";
}

function reverseString(str) { //rovescia e restituisce stringa passata come parametro
    var rev=""; //variabile contenente la stringa rovesciata
    for(let i=(str.length-1);i>=0;i--) rev+=str.charAt(i);
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

function $alert(titolo, contenuto) {
    if(titolo.length > 0 || contenuto.length > 0) {
        $.alert({
            escapeKey: true,
            backgroundDismiss: true,
            theme: "modern",
            title: titolo,
            content: contenuto
        });   
    }
}
//___ METODO PRINCIPALE ___//

$(document).ready(function() {
	
    //___ GENERALE ___//

    gestioneNavbar(); //metodi che gestiscono la barra di navigazione e il footer
   
    $('a#linkModalEsterni').click(function() { //evento che gestisce l'apertura del modal per i non studenti
        $("div#login").modal('hide');
    });

    $('a#linkModalInterni').click(function() {
        $("div#extLogin").modal('hide');
    })
    
    $("body").keyup(function(e) { //evento per la gestione della chiusura dei modal con tasto ESC -- aggiungere tasto invio per l'invio dei dati
        if(e.which == 27) {
            $("div#login, div#extLogin").modal("hide");
        }
    });
    
});