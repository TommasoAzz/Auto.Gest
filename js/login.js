function controlloUtente(password, datiLogin) { //manda query per controllo password e dati utente
    const datiDaInviare = { //creo un oggetto con i dati da inviare tramite $.post()
        classe: datiLogin.classe,
        sezione: datiLogin.sezione,
        indirizzo: datiLogin.indirizzo,
        psw: password.trim()
    };

    $.post("/accesso/login.php", datiDaInviare, function(result) {
        result = result.trim();
        const $cPsw = $("div#campo_psw, div#extCampo_psw");
        /*
            Stringhe errore:
            - DATI INPUT: errore_db_dati_input (dati input potrebbero essere errati)
            - ID PERSONA: errore_db_idpersona (errore nella connessione col db possibilmente)
            - UTENTE ESISTENTE: utente_esistente (login effettuato)
        */

        if(result === "utente_esistente") {
            const page_url = location.href; window.location = page_url; //equivalente a F5 (ricarica la pagina)
        } else if(result === "errore_db_dati_input")
            $cPsw.removeClass("has-success").addClass("has-error").append("<label class='error' for='login_password' id='logerr'>I dati che hai inserito sono errati (controlla soprattutto di aver digitato correttamente la password).</label>");
        else if(result === "errore_db_idpersona")
            $cPsw.removeClass("has-success").addClass("has-error").append("<label class='error' for='login_password' id='logerr'>Ci sono dei problemi nella comunicazione con il database.</label>");
    });
}

// LOGIN STUDENTI

function recuperaDati(datiLogin) { //metodo che prende i dati dalle select per ottenere i dati per il login
    const cla_sez = $("select#classe").val(); //classe + sezione

    if(cla_sez !== "") {
        datiLogin.classe = cla_sez.charAt(0);
        datiLogin.sezione = cla_sez.charAt(1);
    }
    datiLogin.indirizzo = $("select#indirizzo").val();

    return datiLogin;
}

function richiestaIndirizzi($indirizzo) { //richiesta degli indirizzi dell'istituto
	$indirizzo.html("").append("<option value=''></option>");

	$.post("/accesso/getIndirizzi.php", function(result) {
	    result = result.trim();

        if(result === "errore_db_indirizzi") {
            $alert(
                "Operazione non effettuata",
                "Ci sono stato dei problemi nel reperimento della lista degli indirizzi dell'Istituto. Riprova più tardi."
            );
        } else {
            const indirizzi = JSON.parse(result);
            for(let i = 0, l = indirizzi.length; i < l; i++)
                $indirizzo.append(`<option value='${indirizzi[i]}'>${indirizzi[i]}</option>`);

            $indirizzo.prop("disabled", false);
        }
	});
}

function richiestaClassi($classe, datiLogin) { // richiesta delle classi dato l'indirizzo
    $classe.html("").append("<option value=''></option>");

    $.post("/accesso/getClassi.php", {indirizzo: datiLogin.indirizzo}, function(result) {
        result = result.trim();

        if(result === "errore_db_classi_istituto") {
            $alert(
                "Operazione non effettuata",
                `Ci sono stato dei problemi nel reperimento della lista delle classi di indirizzo ${datiLogin.indirizzo}. Riprova più tardi.`
            );
        } else {
            const classi = JSON.parse(result); //vettore contenente le classi presenti per l'indirizzo selezionato
            for(let i = 0, l = classi.length; i < l; i++)
                $classe.append(`<option value='${classi[i].Classe}${classi[i].Sezione}'>${classi[i].Classe}°${classi[i].Sezione}</option>`);

            $classe.prop("disabled", false);
        }
    });
}

//LOGIN NON STUDENTI (PERSONALE/ESTERNI)

function extRecuperaDati(datiLogin) { //metodo che prende i dati dalle select per ottenere i dati per il login
    const cla_sez = $("select#extIndirizzo").val(); //classe + sezione

    if(cla_sez !== "") {
        datiLogin.classe = cla_sez.charAt(0);
        datiLogin.sezione = cla_sez.charAt(1);
    }
    datiLogin.indirizzo = $("select#extIndirizzo").find('option:selected').text();

    return datiLogin;
}

function extRichiestaIndirizzi($extIndirizzo) { //richiesta delle provenienze (non per studenti)
	$extIndirizzo.html("").append("<option value=''></option>");

	$.post("/accesso/getEsterni.php", function(result) {
	    result = result.trim();

        if(result === "errore_db_esterni") {
            $alert(
                "Operazione non effettuata",
                "Ci sono stato dei problemi nel reperimento della lista delle categorie di utenti esterni all'Istituto. Riprova più tardi."
            );
        } else {
            const esterni = JSON.parse(result); //vettore contenente gli indirizzi dell'Istituto
            for(let i = 0, l = esterni.length; i < l; i++)
                $extIndirizzo.append(`<option value='${esterni[i].extC}${esterni[i].extS}'>${esterni[i].extI}</option>`);

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

    // gestione login per STUDENTI
    $("form#userlogin").submit(function(e) {
        e.preventDefault();
    }); // rimossa funzionalità input[type='submit']

    $("div#login_interni").on("shown.bs.modal", function() {
        $("select#indirizzo, select#classe, input#login_password").prop('disabled', true); //disabilito le select e input della psw (non devono funzionare fino a che non contengono dati)
        $("div#spiegazione, div#campo_indirizzo, div#campo_classe, div#campo_psw").fadeIn(); //ingresso dei field di inserimento
        $("div#spiegazione").css('display', 'block'); //mostro il blocco di spiegazione

		richiestaIndirizzi($("select#indirizzo")); //popolamento select#indirizzo e ottenimento lista indirizzi
    }).on("hidden.bs.modal", function() {
        $("select#indirizzo, select#classe, input#login_password").prop('disabled', true).val(""); //reset del modal alla sua chiusura
        $("div#campo_indirizzo, div#campo_classe, div#campo_psw").fadeOut();

        $('label#logerr').remove(); //reset dei messaggi di errore
        $("div#campo_psw").removeClass("has-success has-error"); //reset colore campo psw
        $("a#show_hide_spiegazione").html("Nascondi il paragrafo");
    });

    $("a#show_hide_spiegazione").click(function() { //nasconde la spiegazione su come utilizzare il modulo di login
        const $spiegazione = $("div#spiegazione");

        if($spiegazione.css("display") === "block") {
            $spiegazione.fadeOut();
            $(this).html("Rimostra il paragrafo");
        } else if($spiegazione.css("display") === "none") {
            $spiegazione.fadeIn();
            $(this).html("Nascondi il paragrafo");
        }
    });

    $("select#indirizzo").change(function() { //richiesta classi dato l'indirizzo e animazioni
        datiLogin = recuperaDati(datiLogin);
        const $selettori = $("select#classe, input#login_password");

        $selettori.html("").prop('disabled', true);

        if(datiLogin.indirizzo !== "")
            richiestaClassi($("select#classe"), datiLogin);
        else
            $selettori.prop('disabled', true);
    });

    $("select#classe").change(function() { //concessione inserimento password
        datiLogin = recuperaDati(datiLogin);
        const $inputPsw = $("input#login_password");

        $inputPsw.html("").prop('disabled', true);

        if(datiLogin.classe !== "" && datiLogin.sezione !== "" && datiLogin.indirizzo !== "") {
            $inputPsw.prop('disabled', false);
        } else $inputPsw.prop('disabled', true);
    });

    $("input#login_password").change(function() { //tolgo has-success / has-error se le ha
        $("div#campo_psw").removeClass("has-success has-error");
        $('label#logerr').remove(); //reset dei messaggi di errore
    }).keypress(function(e) {
        if(e.which == 13) controlloUtente($(this).val(), datiLogin);
    });

    $("button#btnLogin").click(function() { //click del pulsante Accedi tramite mouse
        controlloUtente($("input#login_password").val(), datiLogin);
    });

    // gestione login per ESTERNI
    $("form#extUserlogin").submit(function(e) {
        e.preventDefault();
    });

    $("div#login_esterni").on("shown.bs.modal", function() {
        $("select#extIndirizzo, input#extLogin_password").prop('disabled',true);
        $("div#extSpiegazione, div#extCampo_indirizzo, div#extCampo_psw").fadeIn(); //animazione in ingresso dei menubar
        $("div#extSpiegazione").css('display','block');

		extRichiestaIndirizzi($("select#extIndirizzo")); //popolamento select#extIndirizzo e ottenimento lista provenienze
    }).on("hidden.bs.modal", function() {
        $("select#extIndirizzo, input#extLogin_password").prop('disabled',true).val("");
        $("div#extCampo_indirizzo, div#extCampo_psw").fadeOut();

        $('label#logerr').remove(); //reset dei messaggi di errore
        $("div#campo_psw").removeClass("has-success has-error");
        $("a#show_hide_spiegazione").html("Nascondi il paragrafo");
    });

    $("a#show_hide_extSpiegazione").click(function() { //nasconde la spiegazione su come utilizzare il modulo di login esterni
        const $extSpiegazione = $("div#extSpiegazione");

        if($extSpiegazione.css("display") === "block") {
            $extSpiegazione.fadeOut();
            $(this).html("Rimostra il paragrafo");
        } else if($extSpiegazione.css("display") === "none") {
            $extSpiegazione.fadeIn();
            $(this).html("Nascondi il paragrafo");
        }
    });

    $("select#extIndirizzo").change(function() { //richiesta cognomi e nomi data la provenienza e animazioni
        datiLogin = extRecuperaDati(datiLogin);
        const $inputPsw = $("input#extLogin_password");

        $inputPsw.html("").prop('disabled', true);

        if(datiLogin.indirizzo !== "") {
            $inputPsw.prop('disabled', false); //riabilito il menu-tendina delle classi in quanto è caricato
		} else
		    $inputPsw.prop('disabled', true);
    });

    $("input#extLogin_password").change(function() {
        $("div#extCampo_psw").removeClass("has-success has-error"); //tolgo has-success / has-error se le ha
        $('label#logerr').remove(); //reset dei messaggi di errore
    }).keypress(function(e) {
        if(e.which == 13) controlloUtente($(this).val(), datiLogin);
    });

    $("button#extBtnLogin").click(function() {
        controlloUtente($("input#extLogin_password").val(), datiLogin);
    });
});
