function controlloPrimoAccesso(password, datiLogin) { //manda query per controllo password e dati utente
    const datiDaInviare = { //creo un oggetto con i dati da inviare tramite $.post()
        classe: datiLogin.classe,
        sezione: datiLogin.sezione,
        indirizzo: datiLogin.indirizzo,
        psw: password.trim()
    };

    $.post("/accesso/script/primoAccesso.php", datiDaInviare, function(result) {
        result = result.trim();
        const $cPsw = (datiDaInviare.indirizzo === "ESTERNO" || datiDaInviare.indirizzo === "PERSONALE") ? $("div#extCampo_first_access_psw") : $("div#campo_first_access_psw");
        
        /*
            Stringhe errore:
            - DATI INPUT: errore_db_dati_input (dati input potrebbero essere errati)
            - ID PERSONA: errore_db_idpersona (errore nella connessione col db possibilmente)
        */

        $cPsw.removeClass("has-success has-error");
        $("label#logerr").remove();

        if(result === "errore_db_dati_input") {
            $cPsw.addClass("has-error");
            $cPsw.append("<label class='error' id='logerr'>La password inserita non corrisponde a nessun profilo. Controlla di averla digitata correttamente.</label>");
        } else if(result === "errore_db_idpersona") {
            $cPsw.addClass("has-error");
            $cPsw.append("<label class='error' id='logerr'>Ci sono dei problemi nella comunicazione con il database.</label>");
        } else if(result === "primo_accesso_effettuato") {
            $cPsw.addClass("has-error");
            $cPsw.append("<label class='error' id='logerr'>Hai già effettuato il primo accesso! Accedi con username e password tramite il pannello <strong>Accedi</strong>.</label>");
        } else {
            try {
                const datiUtente = JSON.parse(result);

                $("p#registrazione_nome").text(datiUtente.nome);
                $("p#registrazione_cognome").text(datiUtente.cognome);
                $("p#registrazione_classe").text(datiUtente.classe);
                $("p#registrazione_ruolo").text(datiUtente.ruolo);
                $("div#registrazioneUtente").modal("show");
            } catch(json_syntax_error) {
                $cPsw.addClass("has-error");
                $cPsw.append("<label class='error' id='logerr'>Ci sono dei problemi nella comunicazione con il database.</label>");
            }
        }
    });
}

function controlloDatiRegistrazione() {
    const datiRegistrazione = {
        registrazione_nome: $("p#registrazione_nome").html(),
        registrazione_cognome: $("p#registrazione_cognome").html(),
        mail_utente: $("input#mail_utente").val().trim(),
        username_utente: $("input#username_utente").val().trim(),
        password_vecchia_utente: $("input#password_vecchia_utente").val().trim(),
        password_nuova_utente: $("input#password_nuova_utente").val().trim(),
        password_nuova2_utente: $("input#password_nuova2_utente").val().trim()
    };

    $.post("/accesso/script/convalidaRegistrazione.php", datiRegistrazione, function(result) {
        result = result.trim();
        switch(result) {
            case "errore_db_corrispondenza_nome_cognome_password":
                $alert(
                    "Operazione non effettuata",
                    "Non c'è nessuna corrispondenza fra nome, cognome e password consegnata dai Rappresentanti degli Studenti nel database. Controlla i dati inseriti. Se il problema persiste, contattaci (usando i link che trovi nel piè di pagina)."
                );
                break;
            case "errore_db_mail_username_esistenti":
                $alert(
                    "Operazione non effettuata",
                    "La mail o l'username inseriti sono già presenti nel database. Controlla i dati inseriti."
                );
                break;
            case "errore_db_formato_mail_errato":
                $alert(
                    "Operazione non effettuata",
                    "Il fomato della mail inserita non è corretto. Controlla i dati inseriti."
                );
                break;    
            case "errore_db_corrispondenza_password":
                $alert(
                    "Operazione non effettuata",
                    "La password nuova e quella ripetuta non corrispondono. Controlla i dati inseriti."
                );
                break;
            case "errore_db_profilo_non_creato":
                $alert(
                    "Operazione non effettuata",
                    "Ci sono stato dei problemi nella creazione del tuo profilo. Riprova più tardi."
                );
                break;
            case "profilo_creato":
                $alert(
                    "Operazione effettuata",
                    "Profilo creato con successo. Puoi ora accedere al sistema tramite il pannello <strong>Accedi</strong>"
                );
                $("div#registrazioneUtente").modal("hide");
                break;
        }
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
    $("form#first_access_login").submit(function(e) { // rimossa funzionalità input[type='submit']
        e.preventDefault();
    }).validate({
        rules: {
            first_access_login_password: {
                required: true
            }
        },
        messages: {
            first_access_login_password: {
                required: "Questo campo deve essere compilato.",
            }
        }
    });

    $("a#visualizza_extPrimo_accesso").click(function() {
        $("div.panel-body#primo_accesso").fadeOut();
        $("div.panel-body#extPrimo_accesso").fadeIn("slow");
    });

    // load iniziale
    $("select#indirizzo, select#classe, input#first_access_login_password").prop('disabled', true); //disabilito le select e input della psw (non devono funzionare fino a che non contengono dati)

    richiestaIndirizzi($("select#indirizzo")); //popolamento select#indirizzo e ottenimento lista indirizzi

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
        if(e.which == 13) controlloPrimoAccesso($(this).val(), datiLogin);
    });

    $("button#btnProcedi").click(function() { //click del pulsante Accedi tramite mouse
        controlloPrimoAccesso($("input#first_access_login_password").val(), datiLogin);
    });

    // -- GESTIONE 1° ACCESSO PER STUDENTI -- //
    $("form#extFirst_access_login").submit(function(e) {
        e.preventDefault();
    }).validate({
        rules: {
            extFirst_access_login_password: {
                required: true
            }
        },
        messages: {
            extFirst_access_login_password: {
                required: "Questo campo deve essere compilato.",
            }
        }
    });

    $("a#visualizza_primo_accesso").click(function() {
        $("div.panel-body#extPrimo_accesso").fadeOut();
        $("div.panel-body#primo_accesso").fadeIn("slow");
    });

    // load iniziale
    $("select#extIndirizzo, input#extFirst_access_login_password").prop('disabled', true);
    
    extRichiestaIndirizzi($("select#extIndirizzo")); //popolamento select#extIndirizzo e ottenimento lista provenienze
    
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
        $("div#extCampo_first_access_psw").removeClass("has-success has-error"); //tolgo has-success / has-error se le ha
        $('label#logerr').remove(); //reset dei messaggi di errore
    }).keypress(function(e) {
        if(e.which == 13) controlloPrimoAccesso($(this).val(), datiLogin);
    });

    $("button#extBtnProcedi").click(function() {
        controlloPrimoAccesso($("input#extFirst_access_login_password").val(), datiLogin);
    });

    // -- VALIDAZIONE FORM #registrazione_nuovo_utente -- //
    $("form#registrazione_nuovo_utente").submit(function(e) { // rimossa funzionalità input[type='submit']
        e.preventDefault();
    }).validate({
        rules: {
            mail_utente: {
                required: true,
                email: true,
                remote: {
                    url: "/accesso/script/verificaEsistenzaDati.php",
                    type: "POST"
                }
            },
            username_utente: {
                required: true,
                remote: {
                    url: "/accesso/script/verificaEsistenzaDati.php",
                    type: "POST"
                }
            },
            password_vecchia_utente: {
                required: true
            },
            password_nuova_utente: {
                required: true,
                strongPassword: true
            },
            password_nuova2_utente: {
                required: true,
                equalTo: "#password_nuova_utente"
            }
        },
        messages: {
            mail_utente: {
                required: "Questo campo deve essere compilato.",
                email: "Inserisci un valido indirizzo mail.",
                remote: $.validator.format("Con l'indirizzo mail {0} c'è già un profilo collegato. Se è il tuo, accedi tramite il pannello Accedi. Se invece non lo è, contattaci (usando i link che trovi nel piè di pagina).")
            },
            username_utente: {
                required: "Questo campo deve essere compilato.",
                remote: $.validator.format("Ci dispiace, ma {0} è già stato usato come nome utente. Creane un altro.")
            },
            password_vecchia_utente: {
                required: "Questo campo deve essere compilato.",
            },
            password_nuova_utente: {
                required: "Questo campo deve essere compilato."
            },
            password_nuova2_utente: {
                required: "Questo campo deve essere compilato.",
                equalTo: "Devi inserire la stessa password inserita nel campo precedente."
            }
        }
    });

    $("a#btnProsegui").click(controlloDatiRegistrazione);
});
