function controlloLogin(password, user_identification) {
    const datiDaInviare = {
        user_identification: user_identification,
        psw: password
    }

    $.post("/accesso/script/login.php", datiDaInviare, function(result) {
        result = result.trim();
        const $cPsw = $("div#campo_psw");
        
        /*
            Stringhe errore:
            - DATI INPUT: errore_db_dati_input (dati input potrebbero essere errati)
            - ID PERSONA: errore_db_idpersona (errore nella connessione col db possibilmente)
        */
        $cPsw.removeClass("has-success has-error");
        $("label#logerr").remove();

        result = JSON.parse(result);

        switch(result.msg) {
            case "accesso_effettuato":
                const page_url = location.href; window.location = page_url; //equivalente a F5 (ricarica la pagina)
                break;
            case "errore_db_dati_input":
            case "errore_db_password_errata":
                $cPsw.addClass("has-error").append("<label class='error' id='logerr'>I dati inseriti sono errati. Verifica di aver inserito correttamente il nome utente o l'indirizzo mail e la password.</label>");
                break;
            case "max_tentativi_raggiunto":
                $cPsw.addClass("has-error").append("<label class='error' id='logerr'>Hai raggiunto il limite massimo di tentativi di login con credenziali errate. Riprova tra " +  (10 - parseInt(result.minuti)) + " minuti.</label>");
                break;
            case "errore_db_idpersona":
            case "errore_db_insert_tentativilogin":
            case "errore_delete_tentativi":
                $cPsw.addClass("has-error").append("<label class='error' id='logerr'>Ci sono dei problemi nella comunicazione con il database.</label>");
                break;
        }
    });
}

$(document).ready(function() {
    $login_psw = $("input#login_password");
    $user_identification = $("input#login_username");

    $login_psw.change(function() { //tolgo has-success / has-error se le ha
        $("div#campo_psw").removeClass("has-success has-error");
        $('label#logerr').remove(); //reset dei messaggi di errore
    }).keypress(function(e) {
        if(e.which == 13) controlloLogin($login_psw.val(), $user_identification.val());
    });

    $("button#btnAccedi").click(function() { //click del pulsante Accedi tramite mouse
        controlloLogin($login_psw.val(), $user_identification.val());
    });

    $("form#userlogin").submit(function(e) { // rimossa funzionalità input[type='submit']
        e.preventDefault();
    }).validate({
        rules: {
            login_username: {
                required: true
            },
            login_password: {
                required: true
            }
        },
        messages: {
            login_username: {
                required: "Questo campo deve essere compilato.",
            },
            login_password: {
                required: "Questo campo deve essere compilato.",
            }
        }
    });
});