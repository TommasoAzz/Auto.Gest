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
            - 1° ACCESSO NON EFFETTUATO: primo_accesso_non_effettuato (l'utente non si è ancora registrato al sistema)
        */
        $cPsw.removeClass("has-success has-error");
        $("label#logerr").remove();

        if(result === "accesso_effettuato") {
            const page_url = location.href; window.location = page_url; //equivalente a F5 (ricarica la pagina)
        } else if(result === "errore_db_dati_input" || result === "errore_db_password_errata") {
            $cPsw.addClass("has-error");
            $cPsw.append("<label class='error' id='logerr'>I dati inseriti sono errati. Verifica di aver inserito correttamente il nome utente o l'indirizzo mail e la password.</label>");
        } else if(result === "primo_accesso_non_effettuato") {
            $cPsw.addClass("has-error");
            $cPsw.append("<label class='error' id='logerr'>Primo di poter accedere tramite questo pannello devi effettuare la registrazione al sistema tramite il pannello <strong>Primo accesso</strong>.</label>");
        } else if(result === "errore_db_idpersona") {
            $cPsw.addClass("has-error");
            $cPsw.append("<label class='error' id='logerr'>Ci sono dei problemi nella comunicazione con il database.</label>");
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