//___ DATI DEL MODULO DI LOGIN ___//

var indirizzo="";
var cla_e_sez="";
var classe="";
var sezione="";

//___ FUNZIONI VARIE ___//

function controlloPwd(psw) { //gestisce i messaggi di errore per validita' o meno della password
    if(psw.trim().length > 0 && psw.trim().length < 8) {
		$("div#campo_psw, div#extCampo_psw").removeClass("has-success").addClass("has-error");
        $("input#login_password").append("<label class='error' for='login_password'>La password deve essere lunga almeno 8 caratteri.</label>");
	} else if(psw.trim().length !== 0 && psw.trim().length >7) {
		$("div#campo_psw, div#extCampo_psw").removeClass("has-error").addClass("has-success");
        $("label.error").remove();
	}
}

function controlloUtente(pwd) { //manda query per controllo password e dati utente
    var datiDaInviare={ //creo un oggetto con i dati da inviare tramite $.post()
        classe: classe,
        sezione: sezione, //sezione
        indirizzo: indirizzo,
        psw: pwd.trim()
    };

    $.post("/login.php",datiDaInviare,function(result) {
        var risposta=result.trim();
        var $cPsw=$("div#campo_psw, div#extCampo_psw");
        switch(risposta) {
            case "utente-esistente":
                var page_url=location.href;
                window.location = page_url; //equivalente a F5 (ricarica la pagina)
                break;
            case "password-errata":
                $cPsw.removeClass("has-success").addClass("has-error").append("<label class='error' for='login_password'>La password che hai inserito non corrisponde ad alcun account.</label>");
                break;
            case "errore-generico":
                $cPsw.removeClass("has-success").addClass("has-error").append("<label class='error' for='login_password'>C'è stato un errore nel tentativo di accesso. Riprova più tardi.</label>");
                break;
        }
    });
}

//___ FUNZIONI PER LOGIN STUDENTI ___//

function recuperaDati() { //metodo che prende i dati dalle select per ottenere i dati per il login
    cla_e_sez=$("select#classe").val(); //classe + sezione
    if(cla_e_sez!=null) {
        classe=cla_e_sez.substr(0,1);
        sezione=cla_e_sez.substr(1);
    }
    indirizzo=$("select#indirizzo").val();
}

function richiestaIndirizzi() { //richiesta degli indirizzi della scuola
	var $indirizzo=$("select#indirizzo");
	$indirizzo.html('');
	$.post("/getIndirizzi.php",function(result) {
		$indirizzo.append("<option value=\"\"></option>");
        if(result!="false") {
            var vInd=$.parseJSON(result); //vettore contenente gli indirizzi dell'Istituto
            var option=''; //variabile contenente gli elementi che verranno aggiunti alla select#indirizzo
            for(var i=0;i<vInd.length;i++) {
                option="<option value=\""+vInd[i].Indirizzo+"\">"+vInd[i].Indirizzo+"</option>";
                $indirizzo.append(option);
            }
        }
	});
}

function richiestaClassi() { // richiesta delle classi dato l'indirizzo
	var $classe=$("select#classe");
	$classe.html('');
    $.post("/getClassi.php",{indirizzo: indirizzo},function(result) {
        $classe.append("<option value=\"\"></option>");
        if(result!="false") {
            var vCla=$.parseJSON(result); //vettore contenente le classi presenti per l'indirizzo selezionato
            var option=''; //variabile contenente gli elementi che verranno aggiunti alla select#classe 
            for(var i=0;i<vCla.length;i++) {
                option="<option value=\""+vCla[i].Classe+vCla[i].Sezione+"\">"+vCla[i].Classe+vCla[i].Sezione+"</option>";
                $classe.append(option);
            }
        }
    });   
}

//___ FUNZIONI PER LOGIN ESTERNI ___//

function extRecuperaDati() { //metodo che prende i dati dalle select per ottenere i dati per il login
    cla_e_sez=$("select#extIndirizzo").val(); //classe + sezione
    if(cla_e_sez!=null) {
        classe=cla_e_sez.substr(0,1);
        sezione=cla_e_sez.substr(1);
    }
    indirizzo=$("select#extIndirizzo").find('option:selected').text();
}

function extRichiestaIndirizzi() { //richiesta delle provenienze (non per studenti)
	var $extIndirizzo=$("select#extIndirizzo");
	$extIndirizzo.html('');
	$.post("/getEsterni.php",function(result) {
		$extIndirizzo.append("<option value=\"\"></option>");
        if(result!="false") {
            var vPro=$.parseJSON(result); //vettore contenente gli indirizzi dell'Istituto
            var option=''; //variabile contenente gli elementi che verranno aggiunti alla select#indirizzo
            for(var i=0;i<vPro.length;i++) {
                option="<option value=\""+vPro[i].extC+vPro[i].extS+"\">"+vPro[i].extI+"</option>";
                $extIndirizzo.append(option);
            }
        }
	});
}

$(document).ready(function() {
    //___ GESTIONE LOGIN STUDENTI ___//

    $("div#login").on("shown.bs.modal",function() { //metodi eseguiti all'apertura e chiusura del modal per l'accesso
        $("div#spiegazione").css('display','block');
        $("select#indirizzo,select#classe,input#login_password").prop('disabled',true);
        $("div#campo_indirizzo,div#campo_classe,div#campo_psw").fadeIn(); //animazione in ingresso dei menu
		richiestaIndirizzi(); //popolamento select#indirizzo e ottenimento lista indirizzi
		$("select#indirizzo").prop('disabled',false); //riabilito il menu-tendina degli indirizzi in quanto è caricato
    }).on("hidden.bs.modal",function() {
        $("select#indirizzo,select#classe,input#login_password").val("").prop('disabled',true); //reset del modal alla sua chiusura
        $("div#campo_indirizzo,div#campo_classe,div#campo_psw").fadeOut();
    });

    $("select#indirizzo").change(function() { //richiesta classi dato l'indirizzo e animazioni
        $("div#spiegazione").fadeOut("slow");
        recuperaDati();
        $("select#classe,input#login_password").html('').prop('disabled',true);  
        if(indirizzo !== "") {
            richiestaClassi(); //popolamento select#classe e ottenimento lista classi dell'indirizzo scelto
            $("select#classe").prop('disabled',false); //riabilito il menu-tendina delle classi in quanto è caricato
        } else $("select#classe,input#login_password").prop('disabled',true);
    });

    $("select#classe").change(function() { //concessione inserimento password
        recuperaDati();
        $("input#login_password").html('').prop('disabled',true);
        if(classe!=="" && sezione!=="" && indirizzo!=="") {
            $("input#login_password").prop('disabled',false);
        } else $("input#login_password").prop('disabled',true);
    });

    $("input#login_password").focusout(function() {
        controlloPwd($(this).val());//controllo validità password inserita
    }).change(function() { //tolgo has-success / has-error se le ha
        $("div#campo_psw").removeClass("has-success").removeClass("has-error");        
    }).keypress(function(e) {
        if(e.which == 13) {
            controlloUtente($(this).val());    
        }
    });

    $("button#btnLogin").click(function() { //click del pulsante Accedi tramite mouse
        controlloUtente($("input#login_password").val());
    });

    $("form#userlogin").submit(function(e) {
        e.preventDefault();
    });

    //___ GESTIONE LOGIN NON STUDENTI ___//

    $("div#extLogin").on("shown.bs.modal",function() {
        $("div#extSpiegazione").css('display','block');
        $("select#extIndirizzo,input#extLogin_password").prop('disabled',true);
        $("div#extCampo_indirizzo,div#extCampo_psw").fadeIn(); //animazione in ingresso dei menubar
		extRichiestaIndirizzi(); //popolamento select#extIndirizzo e ottenimento lista provenienze
		$("select#extIndirizzo").prop('disabled',false); //riabilito il menu-tendina degli indirizzi in quanto è caricato
    }).on("hidden.bs.modal",function() {
        //reset del modal alla sua chiusura
        $("select#extIndirizzo,input#extLogin_password").val("").prop('disabled',true);
        $("div#extCampo_indirizzo,div#extCampo_psw").fadeOut();
    });

    $("select#extIndirizzo").change(function() { //richiesta cognomi e nomi data la provenienza e animazioni
        $("div#extSpiegazione").fadeOut();
        extRecuperaDati();
        $("input#extLogin_password").html('').prop('disabled',true);
        if(indirizzo !== "") { 
			$("input#extLogin_password").prop('disabled',false); //riabilito il menu-tendina delle classi in quanto è caricato
		} else $("input#extLogin_password").prop('disabled',true);
    });

    $("input#extLogin_password").focusout(function() { //controllo validità password inserita 
        controlloPwd($(this).val());//invio password al metodo per controllo
    }).change(function() { //tolgo has-success / has-error se le ha
        $("div#extCampo_psw").removeClass("has-success").removeClass("has-error");        
    }).keypress(function(e) {
        if(e.which == 13) {
            controlloUtente($(this).val());    
        }
    });

    $("button#extBtnLogin").click(function() {
        controlloUtente($("input#extLogin_password").val());
    });

    $("form#extUserlogin").submit(function(e) {
        e.preventDefault();
    });
});