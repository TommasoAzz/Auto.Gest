/*
 *  File script di Auto.Gest
 *  Necessario in tutte le pagine del sito
 *  Creato da Tommaso Azzalin
*/

//___ FUNZIONI VARIE ___//
function setCookie(cName, cVal, durata/* in giorni */) {
    let d = new Date();
    d.setTime(d.getTime() + (durata*24*60*60*1000));
    const scadenza = "expires=" + d.toUTCString();
    document.cookie = cName + "=" + cVal + ";" + scadenza + ";path=/";
}

function getCookie(nomeCookie) { //restituisce contenuto cookie passato come parametro
    const nome = nomeCookie + "=";
    const decodedCookie = decodeURIComponent(document.cookie); //recupero dei cookie dal browser
    const vCookie = decodedCookie.split(";"); //vettore dei cookie

    for(let i = 0, l = vCookie.length; i < l; i++) {
        let biscotto = vCookie[i].trim();
        if(biscotto.indexOf(nome) === 0) return biscotto.substring(nome.length, biscotto.length);
    }

    return "";
}

function reverseString(str) { //rovescia e restituisce stringa passata come parametro
    let rev = ""; //variabile contenente la stringa rovesciata

    for(let i = (str.length-1); i >= 0; i--) rev += str.charAt(i);

    return rev;
} //rimovibile

function gestioneNavbar() { //controlla la pagina e attiva menu diversamente
    // Riconoscimento pagina
    const pagina = window.location;

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

    $("body").keyup(function(e) { //evento per la gestione della chiusura dei modal con tasto ESC -- aggiungere tasto invio per l'invio dei dati
        if(e.which == 27) {
            $("div.modal").modal("hide");
        }
    });

    $("a#cambioPwModal").click(()=>{
        cambioPasswordUtente();
    });

});

$.validator.addMethod('strongPassword', function(value, element) {
    return this.optional(element) || value.length >= 8 && /\d/.test(value) && /[a-z]/.test(value) && /[A-Z]/.test(value);
}, "La password deve essere lunga almeno 8 caratteri e deve contenere almeno un carattere minuscolo, uno maiuscolo e una cifra numerica.");

function cambioPasswordUtente() {
    try{
        $.post("/modal/script/getIdUtente.php", function(result) {
            if(result==="errore_sessione_utente") throw result;
            let id = result;
            $.confirm({
                escapeKey: true,
                backgroundDismiss: true,
                theme: "modern",
                title: "Modifica la tua password",
                content:"<form id='promptNuovaPsw'><div class='form-group'>" +
                        "<label>Inserisci la tua password attuale:</label>" +
                        "<input type='password' class='form-control' id='txtVecchiaPsw' placeholder='Password attuale' required />" +
                        "<label>Inserisci la nuova password:</label>" +
                        "<input type='password' class='form-control' id='txtNuovaPsw' placeholder='Nuova password' required />" +
                        "<label>Inserisci nuovamente la nuova password:</label>" +
                        "<input type='password' class='form-control' id='txtConfermaNuovaPsw' placeholder='Ripeti la nuova password' required />" +
                        "</div></form>",
                buttons: {
                    formSubmit: {
                        text: 'Conferma',
                        btnClass: 'btn-success',
                        action: function() {
                            const vecchiaPsw = this.$content.find('input#txtVecchiaPsw').val();
                            const nuovaPsw = this.$content.find('input#txtNuovaPsw').val();
                            const confermaNuovaPsw = this.$content.find('input#txtConfermaNuovaPsw').val();
                            if(!vecchiaPsw) {
                                $alert("Attenzione", "Inserisci la tua password attuale.");
                                return false;
                            } else if(!nuovaPsw) {
                                $alert("Attenzione", "Inserisci una nuova password.");
                                return false;
                            } else if(!confermaNuovaPsw) {
                                $alert("Attenzione", "Come conferma di digitazione corretta devi inserire nuovamente la nuova password.");
                                return false;
                            } else if(confermaNuovaPsw !== nuovaPsw) {
                                $alert("Attenzione", "Le due password non combaciano. Inseriscile nuovamente.");
                                return false;
                            } else if(nuovaPsw.length < 8) {
                                $alert("Attenzione", "La nuova password deve contenere almeno 8 caratteri.");
                                return false;
                            } else {
                                $.post("/modal/script/cambioPasswordUtente.php", {ID: id, vecchiaPwd: vecchiaPsw, nuovaPwd: nuovaPsw}, function(result) {
                                    result = result.trim();
                                    if(result === "cambio-effettuato") {
                                        $alert(
                                            "Cambio password effettuato",
                                            `Il cambio della password è stato effettuato correttamente.`
                                        );
                                    }else if(result === "errore-vecchia-pwd"){
                                        $alert(
                                            "Cambio password non effettuato",
                                            `La password attuale non è corretta.`
                                        );
                                    }else {
                                        $alert(
                                            "Cambio password non effettuato",
                                            `Il cambio della password non è stato effettuato correttamente.`
                                        );
                                    }
                                });
                            }
                        }
                    },
                    cancel: {
                        text: "Annulla",
                        btnClass: "btn-danger"
                    }
                },
                onContentReady: function() {
                    let prompt = this;
                    this.$content.find('form').on('submit', function(e) {
                        e.preventDefault();
                        prompt.$$formSubmit.trigger('click');
                    });
                }
            });
        });
    }catch(error){
        $alert(
            "Attenzione",
            "C'è stato un errore nella lettura della sessione. Riprova!"
        );
    }
}