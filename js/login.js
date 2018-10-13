//___ FUNZIONI VARIE ___//
function controlloUtente(password,datiLogin) { //manda query per controllo password e dati utente
    const datiDaInviare={ //creo un oggetto con i dati da inviare tramite $.post()
        classe: datiLogin.classe,
        sezione: datiLogin.sezione,
        indirizzo: datiLogin.indirizzo,
        psw: password.trim()
    };

    $.post("/accesso/login.php",datiDaInviare,function(result) {
        const risposta=result.trim();
        const $cPsw=$("div#campo_psw, div#extCampo_psw");
        
        //stringhe errore:
        /*
            - DATI INPUT: errore_db_dati_input (dati input potrebbero essere errati)
            - ID PERSONA: errore_db_idpersona (errore nella connessione col db possibilmente)
            - UTENTE ESISTENTE: utente_esistente (login effettuato)
        */

        $('label#logerr').remove(); //reset dei messaggi di errore

        if(risposta === "utente_esistente") {
            const page_url=location.href;
            window.location = page_url; //equivalente a F5 (ricarica la pagina)
        } else if(risposta === "errore_db_dati_input") {
            $cPsw.removeClass("has-success").addClass("has-error");
            $cPsw.append("<label class='error' for='login_password' id='logerr'>I dati che hai inserito sono errati (controlla soprattutto di aver digitato correttamente la password).</label>"); 
        } else if(risposta === "errore_db_idpersona") {
            $cPsw.removeClass("has-success").addClass("has-error");
            $cPsw.append("<label class='error' for='login_password' id='logerr'>Ci sono dei problemi nella comunicazione con il database.</label>"); 
        }
    });
}

//___ FUNZIONI PER LOGIN STUDENTI ___//

function recuperaDati(datiLogin) { //metodo che prende i dati dalle select per ottenere i dati per il login
    const cla_sez=$("select#classe").val(); //classe + sezione
    if(cla_sez !== "") {
        datiLogin.classe=cla_sez.charAt(0);
        datiLogin.sezione=cla_sez.charAt(1);
    }
    datiLogin.indirizzo=$("select#indirizzo").val();
    return datiLogin;
}

function richiestaIndirizzi() { //richiesta degli indirizzi della scuola
	const $indirizzo=$("select#indirizzo");
	$indirizzo.html('');
	$.post("/accesso/getIndirizzi.php",function(result) {
		$indirizzo.append("<option value=''></option>");
        if(result === "errore_db_indirizzi") {
            let titolo="Operazione non effettuata",contenuto="Ci sono stato dei problemi nel reperimento della lista degli indirizzi dell'Istituto. Riprovare più tardi.";
            $alert(titolo,contenuto);
        } else {
            const vInd=$.parseJSON(result); //vettore contenente gli indirizzi dell'Istituto
            for(let i=0,l=vInd.length;i<l;i++) {
                //option: contiene gli elementi che verranno aggiunti alla select#indirizzo
                let option=`<option value='${vInd[i]}'>${vInd[i]}</option>`;
                $indirizzo.append(option);
            }
        }
	});
}

function richiestaClassi(datiLogin) { // richiesta delle classi dato l'indirizzo
    const $classe=$("select#classe");
    $classe.html('');
    $.post("/accesso/getClassi.php",{indirizzo: datiLogin.indirizzo},function(result) {
        $classe.append("<option value=''></option>");
        if(result !== "false") {
            const vCla=$.parseJSON(result); //vettore contenente le classi presenti per l'indirizzo selezionato
            for(let i=0,l=vCla.length;i<l;i++) {
                //option: contiene gli elementi che verranno aggiunti alla select#classe
                let option=`<option value='${vCla[i].Classe}${vCla[i].Sezione}'>${vCla[i].Classe}°${vCla[i].Sezione}</option>`;
                $classe.append(option);
            }
        }
    });
}

//___ FUNZIONI PER LOGIN ESTERNI ___//

function extRecuperaDati(datiLogin) { //metodo che prende i dati dalle select per ottenere i dati per il login
    const cla_sez=$("select#extIndirizzo").val(); //classe + sezione
    if(cla_sez !== "") {
        datiLogin.classe=cla_sez.charAt(0);
        datiLogin.sezione=cla_sez.charAt(1);
    }
    datiLogin.indirizzo=$("select#extIndirizzo").find('option:selected').text();
    return datiLogin;
}

function extRichiestaIndirizzi() { //richiesta delle provenienze (non per studenti)
	const $extIndirizzo=$("select#extIndirizzo");
	$extIndirizzo.html('');
	$.post("/accesso/getEsterni.php",function(result) {
		$extIndirizzo.append("<option value=''></option>");
        if(result!="false") {
            const vPro=$.parseJSON(result); //vettore contenente gli indirizzi dell'Istituto
            for(let i=0,l=vPro.length;i<l;i++) {
                //option: contiene gli elementi che verranno aggiunti alla select#indirizzo
                let option=`<option value='${vPro[i].extC}${vPro[i].extS}'>${vPro[i].extI}</option>`;
                $extIndirizzo.append(option);
            }
        }
	});
}

$(document).ready(function() {
    //___ DATI DEL MODULO DI LOGIN ___//

    var datiLogin = {
        "indirizzo": "",
        "classe": "",
        "sezione": ""
    } //oggetto

    //___ GESTIONE LOGIN STUDENTI ___//

    $("div#login_interni").on("shown.bs.modal",function() { //metodi eseguiti all'apertura e chiusura del modal per l'accesso
        $("select#indirizzo,select#classe,input#login_password").prop('disabled',true);
        $("div#spiegazione,div#campo_indirizzo,div#campo_classe,div#campo_psw").fadeIn(); //animazione in ingresso dei menu
        $("div#spiegazione").css('display','block');
		richiestaIndirizzi(); //popolamento select#indirizzo e ottenimento lista indirizzi
		$("select#indirizzo").prop('disabled',false); //riabilito il menu-tendina degli indirizzi in quanto è caricato
    }).on("hidden.bs.modal",function() {
        $("select#indirizzo,select#classe,input#login_password").val("").prop('disabled',true); //reset del modal alla sua chiusura
		$('label#logerr').remove(); //reset dei messaggi di errore
        $("div#campo_psw").removeClass("has-success").removeClass("has-error");  //reset colore campo pws
        $("a#show_hide_spiegazione").html("Nascondi il paragrafo");
        $("div#campo_indirizzo,div#campo_classe,div#campo_psw").fadeOut();
    });

    $("a#show_hide_spiegazione").click(function() { //nasconde la spiegazione su come utilizzare il modulo di login
        const $spiegazione=$("div#spiegazione");
        if($spiegazione.css("display") === "block") {
            $("div#spiegazione").fadeOut();
            $(this).html("Rimostra il paragrafo");
        } else if($spiegazione.css("display") === "none") {
            $("div#spiegazione").fadeIn();
            $(this).html("Nascondi il paragrafo");
        }
    });

    $("select#indirizzo").change(function() { //richiesta classi dato l'indirizzo e animazioni
        datiLogin=recuperaDati(datiLogin);
        $("select#classe,input#login_password").html('').prop('disabled',true);
        if(datiLogin.indirizzo !== "") {
            richiestaClassi(datiLogin); //popolamento select#classe e ottenimento lista classi dell'indirizzo scelto
            $("select#classe").prop('disabled',false); //riabilito il menu-tendina delle classi in quanto è caricato
        } else $("select#classe,input#login_password").prop('disabled',true);
    });

    $("select#classe").change(function() { //concessione inserimento password
        datiLogin=recuperaDati(datiLogin);
        $("input#login_password").html('').prop('disabled',true);
        if(datiLogin.classe !== "" && datiLogin.sezione !== "" && datiLogin.indirizzo !== "") {
            $("input#login_password").prop('disabled',false);
        } else $("input#login_password").prop('disabled',true);
    });

    $("input#login_password").change(function() { //tolgo has-success / has-error se le ha
        $("div#campo_psw").removeClass("has-success").removeClass("has-error");
    }).keypress(function(e) {
        if(e.which == 13) {
            const password=$(this).val();
            controlloUtente(password,datiLogin);
        }
    });

    $("button#btnLogin").click(function() { //click del pulsante Accedi tramite mouse
        const password=$("input#login_password").val();
        controlloUtente(password,datiLogin);
    });

    $("form#userlogin").submit(function(e) {
        e.preventDefault();
    });

    //___ GESTIONE LOGIN NON STUDENTI ___//

    $("div#login_esterni").on("shown.bs.modal",function() {
        $("select#extIndirizzo,input#extLogin_password").prop('disabled',true);
        $("div#extSpiegazione,div#extCampo_indirizzo,div#extCampo_psw").fadeIn(); //animazione in ingresso dei menubar
        $("div#extSpiegazione").css('display','block');
		extRichiestaIndirizzi(); //popolamento select#extIndirizzo e ottenimento lista provenienze
		$("select#extIndirizzo").prop('disabled',false); //riabilito il menu-tendina degli indirizzi in quanto è caricato
    }).on("hidden.bs.modal",function() {
        //reset del modal alla sua chiusura
        $("select#extIndirizzo,input#extLogin_password").val("").prop('disabled',true);
        $('label#logerr').remove(); //reset dei messaggi di errore
        $("div#campo_psw").removeClass("has-success").removeClass("has-error");
        $("a#show_hide_spiegazione").html("Nascondi il paragrafo");
        $("div#extCampo_indirizzo,div#extCampo_psw").fadeOut();
    });

    $("a#show_hide_extSpiegazione").click(function() { //nasconde la spiegazione su come utilizzare il modulo di login esterni
        const $extSpiegazione=$("div#extSpiegazione");
        if($extSpiegazione.css("display") === "block") {
            $("div#extSpiegazione").fadeOut();
            $(this).html("Rimostra il paragrafo");
        } else if($extSpiegazione.css("display") === "none") {
            $("div#extSpiegazione").fadeIn();
            $(this).html("Nascondi il paragrafo");
        }
    });

    $("select#extIndirizzo").change(function() { //richiesta cognomi e nomi data la provenienza e animazioni
        datiLogin=extRecuperaDati(datiLogin);
        $("input#extLogin_password").html('').prop('disabled',true);
        if(datiLogin.indirizzo !== "") {
			$("input#extLogin_password").prop('disabled',false); //riabilito il menu-tendina delle classi in quanto è caricato
		} else $("input#extLogin_password").prop('disabled',true);
    });

    $("input#extLogin_password").focusout(function() { //controllo validità password inserita
        const psw=$(this).val();
        controlloPwd(psw);//invio password al metodo per controllo
    }).change(function() { 
        $("div#extCampo_psw").removeClass("has-success").removeClass("has-error"); //tolgo has-success / has-error se le ha
        $('label#logerr').remove(); //reset dei messaggi di errore
    }).keypress(function(e) {
        if(e.which == 13) {
            const password=$(this).val();
            controlloUtente(password,datiLogin);
        }
    });

    $("button#extBtnLogin").click(function() {
        const password=$("input#extLogin_password").val();
        controlloUtente(password,datiLogin);
    });

    $("form#extUserlogin").submit(function(e) {
        e.preventDefault();
    });
});
