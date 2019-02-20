function controlloUtente(password, datiLogin) { //manda query per controllo password e dati utente
    const datiDaInviare = { //creo un oggetto con i dati da inviare tramite $.post()
        classe: datiLogin.classe,
        sezione: datiLogin.sezione,
        indirizzo: datiLogin.indirizzo,
        psw: password.trim()
    };

    $.post("/accesso/script/login.php", datiDaInviare, function(result) {
        result = result.trim();
        const $cPsw = $("div#campo_first_access_psw, div#extCampo_first_access_psw");
        /*
            Stringhe errore:
            - DATI INPUT: errore_db_dati_input (dati input potrebbero essere errati)
            - ID PERSONA: errore_db_idpersona (errore nella connessione col db possibilmente)
            - UTENTE ESISTENTE: utente_esistente (login effettuato)
        */

        if(result === "utente_esistente") {
            const page_url = location.href; window.location = page_url; //equivalente a F5 (ricarica la pagina)
        } else if(result === "errore_db_dati_input")
            $cPsw.removeClass("has-success").addClass("has-error").append("<label class='error' id='logerr'>I dati che hai inserito sono errati (controlla soprattutto di aver digitato correttamente la password).</label>");
        else if(result === "errore_db_idpersona")
            $cPsw.removeClass("has-success").addClass("has-error").append("<label class='error' id='logerr'>Ci sono dei problemi nella comunicazione con il database.</label>");
    });
}

// 1° ACCESSO STUDENTI

function recuperaDati($indirizzo, $classe = null) { //metodo che prende i dati dalle select per ottenere i dati per il login
    let datiLogin = {
        "indirizzo": "",
        "classe": "",
        "sezione": ""
    }; // dati per il modulo di login

    datiLogin.indirizzo = $indirizzo.val();
    if($classe != null) {
        const cla_sez = $classe.val(); //classe + sezione
        if(cla_sez !== "") {
            datiLogin.classe = cla_sez.charAt(0);
            datiLogin.sezione = cla_sez.charAt(1);
        }
    }

    return datiLogin;
}

function richiestaIndirizzi($indirizzo) { //richiesta degli indirizzi dell'istituto
	$indirizzo.html("").append("<option value=''></option>");

	$.post("/accesso/script/getIndirizzi.php", function(result) {
	    result = result.trim();

        if(result === "errore_db_indirizzi") {
            $alert(
                "Operazione non effettuata",
                "Ci sono stato dei problemi nel reperimento della lista degli indirizzi dell'Istituto. Riprova più tardi."
            );
        } else {
            const indirizzi = JSON.parse(result);

            indirizzi.forEach(function(indirizzo) {
                $indirizzo.append(`<option value='${indirizzo}'>${indirizzo}</option>`);
            });

            $indirizzo.prop("disabled", false);
        }
	});
}

function richiestaClassi($classe, datiLogin) { // richiesta delle classi dato l'indirizzo
    $classe.html("").append("<option value=''></option>");

    $.post("/accesso/script/getClassi.php", {indirizzo: datiLogin.indirizzo}, function(result) {
        result = result.trim();

        if(result === "errore_db_classi_istituto") {
            $alert(
                "Operazione non effettuata",
                `Ci sono stato dei problemi nel reperimento della lista delle classi di indirizzo ${datiLogin.indirizzo}. Riprova più tardi.`
            );
        } else {
            const classi = JSON.parse(result); //vettore contenente le classi presenti per l'indirizzo selezionato

            classi.forEach(function(classe) {
                $classe.append(`<option value='${classe.Classe}${classe.Sezione}'>${classe.Classe}°${classe.Sezione}</option>`);
            });

            $classe.prop("disabled", false);
        }
    });
}

// 1° ACCESSO NON STUDENTI (PERSONALE/ESTERNO)

function extRecuperaDati($indirizzo) { //metodo che prende i dati dalle select per ottenere i dati per il login
    if($indirizzo.val() !== "") {
        let datiLogin = {
            "indirizzo": $indirizzo.find('option:selected').text(),
            "classe": $indirizzo.val().charAt(0),
            "sezione": $indirizzo.val().charAt(1)
        }; // dati per il modulo di login esterni/personale
    
        return datiLogin;
    } else return null;
}

function extRichiestaIndirizzi($extIndirizzo) { //richiesta delle provenienze (non per studenti)
	$extIndirizzo.html("").append("<option value=''></option>");

	$.post("/accesso/script/getEsterni.php", function(result) {
	    result = result.trim();

        if(result === "errore_db_esterni") {
            $alert(
                "Operazione non effettuata",
                "Ci sono stato dei problemi nel reperimento della lista delle categorie di utenti esterni all'Istituto. Riprova più tardi."
            );
        } else {
            const esterni = JSON.parse(result); //vettore contenente gli indirizzi dell'Istituto
            
            esterni.forEach(function(esterno) {
                $extIndirizzo.append(`<option value='${esterno.extC}${esterno.extS}'>${esterno.extI}</option>`);
            })

            $extIndirizzo.prop("disabled", false);
        }
	});
}

$(document).ready(function() {
    let datiLogin = {
        "indirizzo": "",
        "classe": "",
        "sezione": ""
    }; // dati per il modulo di login

    // -- GESTIONE 1° ACCESSO PER STUDENTI -- //
    $("form#first_access_login").submit(function(e) {
        e.preventDefault();
    }); // rimossa funzionalità input[type='submit']

    $("a#visualizza_extPrimo_accesso").click(function() {
        $("div.panel-body#primo_accesso").fadeOut();
        $("div.panel-body#extPrimo_accesso").fadeIn("slow");
    });

    // load iniziale
    $("select#indirizzo, select#classe, input#first_access_login_password").prop('disabled', true); //disabilito le select e input della psw (non devono funzionare fino a che non contengono dati)

    richiestaIndirizzi($("select#indirizzo")); //popolamento select#indirizzo e ottenimento lista indirizzi

    $("a#show_hide_primo_accesso_spiegazione").click(function() { //nasconde la spiegazione su come utilizzare il modulo di login
        const $spiegazione = $("div#primo_accesso_spiegazione");

        if($spiegazione.css("display") === "block") {
            $spiegazione.fadeOut();
            $(this).html("Rimostra il paragrafo");
        } else if($spiegazione.css("display") === "none") {
            $spiegazione.fadeIn();
            $(this).html("Nascondi il paragrafo");
        }
    });

    $("select#indirizzo").change(function() { //richiesta classi dato l'indirizzo e animazioni
        datiLogin = recuperaDati($(this));
        const $selettori = $("select#classe, input#first_access_login_password");

        $selettori.html("").prop('disabled', true);

        if(datiLogin.indirizzo !== "")
            richiestaClassi($("select#classe"), datiLogin);
        else
            $selettori.prop('disabled', true);
    });

    $("select#classe").change(function() { //concessione inserimento password
        datiLogin = recuperaDati($("select#indirizzo"), $(this));
        const $inputPsw = $("input#first_access_login_password");

        $inputPsw.html("").prop('disabled', true);

        if(datiLogin.classe !== "" && datiLogin.sezione !== "" && datiLogin.indirizzo !== "")
            $inputPsw.prop('disabled', false);
        else
            $inputPsw.prop('disabled', true);
    });

    $("input#first_access_login_password").change(function() { //tolgo has-success / has-error se le ha
        $("div#campo_first_access_psw").removeClass("has-success has-error");
        $('label#logerr').remove(); //reset dei messaggi di errore
    }).keypress(function(e) {
        if(e.which == 13) controlloUtente($(this).val(), datiLogin);
    });

    $("button#btnProcedi").click(function() { //click del pulsante Accedi tramite mouse
        controlloUtente($("input#first_access_login_password").val(), datiLogin);
    });

    // -- GESTIONE 1° ACCESSO PER STUDENTI -- //
    $("form#extFirst_access_login").submit(function(e) {
        e.preventDefault();
    });

    $("a#visualizza_primo_accesso").click(function() {
        $("div.panel-body#extPrimo_accesso").fadeOut();
        $("div.panel-body#primo_accesso").fadeIn("slow");
    });

    // load iniziale
    $("select#extIndirizzo, input#extFirst_access_login_password").prop('disabled', true);
    
    extRichiestaIndirizzi($("select#extIndirizzo")); //popolamento select#extIndirizzo e ottenimento lista provenienze
    
    $("a#extShow_hide_primo_accesso_spiegazione").click(function() { //nasconde la spiegazione su come utilizzare il modulo di login esterni
        const $extSpiegazione = $("div#extPrimo_accesso_spiegazione");

        if($extSpiegazione.css("display") === "block") {
            $extSpiegazione.fadeOut();
            $(this).html("Rimostra il paragrafo");
        } else if($extSpiegazione.css("display") === "none") {
            $extSpiegazione.fadeIn();
            $(this).html("Nascondi il paragrafo");
        }
    });

    $("select#extIndirizzo").change(function() { //richiesta cognomi e nomi data la provenienza e animazioni
        datiLogin = extRecuperaDati($(this));
        const $inputPsw = $("input#extFirst_access_login_password");

        $inputPsw.html("").prop('disabled', true);

        if(datiLogin !== null && datiLogin.indirizzo) {
            $inputPsw.prop('disabled', false); //riabilito il menu-tendina delle classi in quanto è caricato
		} else
		    $inputPsw.prop('disabled', true);
    });
    
    
    $("input#extFirst_access_login_password").change(function() {
        $("div#extFirst_access_psw").removeClass("has-success has-error"); //tolgo has-success / has-error se le ha
        $('label#logerr').remove(); //reset dei messaggi di errore
    }).keypress(function(e) {
        if(e.which == 13) controlloUtente($(this).val(), datiLogin);
    });

    $("button#extBtnProcedi").click(function() {
        controlloUtente($("input#extFirst_access_login_password").val(), datiLogin);
    });
});
