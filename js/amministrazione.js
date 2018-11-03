//PANNELLO A - RICERCA ID PERSONA
function ricercaID_Persona() {
    const nc = {
        nome: $("input#nome_ricerca").val(),
        cognome: $("input#cognome_ricerca").val()
    };

    if(nc.nome === "" || nc.cognome !== "") {
        let titolo = "Attenzione!", contenuto = "Devi compilare entrambe le caselle di testo <strong>Nome</strong> e <strong>Cognome</strong>.";
        $alert(titolo, contenuto);
    } else {
        $.post("/amministrazione/script/ricercaID_Persona.php",nc,function(result) {
            const $input=$("input#risultatoRicercaID");
            
            if(result.trim() === "errore_db_dati_persona") $input.val("Nessun risultato");
            else {
                const datiPersona = JSON.parse(result.trim());
                if(datiPersona.length === 1) {
                    const id = parseInt(datiPersona[0].ID_Persona);
                    $input.val(id);
                } else {
                    let lista_ID = "", alertContent = "";
                    const ultimo = datiPersona.length-1;

                    for(let i = 0; i  < ultimo; i++) {
                        let dp = datiPersona[i];
                        lista_ID += dp.ID_Persona + " - ";
                        alertContent += dp.Classe + "°" + dp.Sezione + " " + dp.Indirizzo + " - Codice: " + dp.ID_Persona + "<br />"; 
                    }

                    let dp = datiPersona[ultimo];
                    lista_ID += dp.ID_Persona;
                    alertContent += dp.Classe + "°" + dp.Sezione + " " + dp.Indirizzo + " - Codice: " + dp.ID_Persona;
                    
                    $input.val(lista_ID);

                    let titolo = "Ci sono più " + nc.nome.toUpperCase() + " " + nc.cognome.toUpperCase();
                    $alert(titolo, alertContent);
                }
            }
        });
    }
}

//PANNELLO B - RESET DEI CORSI DI UNO STUDENTE
function resetCorsiStudente(id) {
    $.post("/amministrazione/script/resetCorsiStudente.php", {ID: id}, function(result) {
        let titolo = "", contenuto = "";

        if(result.trim() === "reset-effettuato") {
            titolo = "Operazione completata";
            contenuto = "Il reset dei corsi dello studente di codice <strong>"+id+"</strong> è stato completato con successo.";
        } else if(result.trim() === "reset-non-effettuato") {
            titolo = "Operazione non effettuata";
            contenuto = "Il reset dei corsi dello studente di codice <strong>"+id+"</strong> non è andato a buon fine. Riprovare più tardi.";
        }

        $alert(titolo,contenuto);
    });
}

//PANNELLO C - CAMBIO PASSWORD AD UN UTENTE
function cambioPasswordUtente(id, nuovaPsw) {
    $.post("/amministrazione/script/cambioPasswordUtente.php", {ID: id, Pwd: nuovaPsw}, function(result) {
        let titolo = "", contenuto = "";
        if(result === "cambio-effettuato") {
            titolo = "Cambio password effettuato";
            contenuto = `Il cambio della password all'utente di codice <strong>${id}</strong> è stato effettuato correttamente.`;
        } else {
            titolo = "Cambio password non effettuato";
            contenuto = `Il cambio della password all'utente di codice <strong>${id}</strong> non è stato effettuato. Riprovare più tardi.`;
        }

        $alert(titolo,contenuto);
    });
}

//PANNELLO D - VISUALIZZAZIONE DEI CORSI SCELTI DA UNO STUDENTE
function visualizzaCorsiStudente(id) {
    const $tbody = $("tbody#tCorsiPersona");
    $tbody.html("");
    $.post("/amministrazione/script/visualizzaCorsiStudente.php", {ID: id}, function(result) {
        result = result.trim();
        if(result === "errore_db_numero_giorni" || result !== "errore_db_corsi_iscritti_giorno") {
            let titolo = "Attenzione", contenuto = `La persona di ID: <strong>${id}</strong> risulta non iscritta (oppure c'è stato un errore nel controllo dei suoi corsi).`;
            $alert(titolo, contenuto);
        } else {
            const vCorsi = JSON.parse(result);
            for(let i = 0, num_giorni = vCorsi.length; i < num_giorni; i++) {
                for(let j = 0, num_corsi = vCorsi[i].length; j < num_corsi; j++) {
                    $tbody.append(
                        "<tr>" +
                        `<td>${(i+1)}</td>` +                               //Giorno
                        `<td>${vCorsi[i][j].Ora}</td>` +
                        `<td>${vCorsi[i][j].Nome}</td>` +
                        `<td>${vCorsi[i][j].Durata}</td>` +
                        `<td>${vCorsi[i][j].Aula}</td>` +
                        `<td>${vCorsi[i][j].ID_SessioneCorso}</td>` +       //ID_SessioneCorso
                        `</tr>`
                    );
                } 
            }

            $("div#corsiPersona").modal("show");
        }
    });
}

//PANNELLO E - VISUALIZZAZIONE DELLE SESSIONI DEI CORSI
function getListaCorsi() {
    const $select = $("select#sessioniCorso");
    $select.html("");
    $.post("/amministrazione/script/getListaCorsi.php", function(result) {
        const datiDaServer = result.trim(); //ottengo i dati dal server
        if(datiDaServer === "errore_db_lista_corsi") {
            let titolo = "Attenzione", contenuto = "C'è stato un problema nel caricamento della lista dei corsi per il <strong>Pannello E</strong>";
            $alert(titolo, contenuto);
        } else {
            const vCorsi = JSON.parse(datiDaServer);
            $select.append("<option value=''></option>")
            for(let i = 0, l = vCorsi.length; i < l; i++) $select.append(`<option value="${vCorsi[i].Nome}">${vCorsi[i].Nome}</option>`);
        }
    });
}

function visualizzaSessioniCorso(nomeC) {
    const $tbody = $("tbody#tSessioniCorso");
    $tbody.html("");
    $.post("/amministrazione/script/visualizzaSessioniCorso.php", {nomeCorso: nomeC}, function(result) {
        if(result.trim() === "errore_db_nome_corso" || result.trim() === "errore_db_sessioni_corso") {
            let titolo = "Errore", contenuto = "C'è stato un errore nell'elaborazione dei dati.";
            $alert(titolo, contenuto);
        } else {
            //ottengo i dati dal server
            const datiDaServer = JSON.parse(result.trim()); //spacchetto l'array ottenuto da POST

            const datiCorso = JSON.parse(datiDaServer.corso); //spacchetto l'array associativo ottenuto dalla query (dati del Corso)
            const vSessioni = JSON.parse(datiDaServer.sessioniCorso); //spacchetto l'array associativo ottenuto dalla query (dati delle sessioni del corso)

            for(let i = 0, l = vSessioni.length; i < l; i++) {
                $tbody.append(
                    "<tr>" +
                    `<td>${vSessioni[i].Giorno}</td>` +
                    `<td>${vSessioni[i].Ora}</td>` +
                    `<td>${vSessioni[i].PostiRimasti}</td>` +
                    `<td>${vSessioni[i].ID_SessioneCorso}</td>` +
                    "</tr>"
                );
            }

            $("span#nomeCorso").text(nomeC);
            $("span#idCorso").text(datiCorso[0].ID_Corso);
            $("span#durataCorso").text(datiCorso[0].Durata);
            $("span#aulaCorso").text(datiCorso[0].Aula);
            $("span#postiCorso").text(datiCorso[0].MaxPosti);

            $("div#sessioniCorso").modal("show");
        }
    });
}

//PANNELLO F - REGISTRO PRESENZE DI UNA SESSIONE DI UN CORSO
function registroSessioneCorso(id) {
    const $tbody = $("tbody#tPresenzeSessione");
    $tbody.html("");
    $.post("/amministrazione/script/registroSessioneCorso.php", {ID: id}, function(result) {
        result = result.trim();
        if(result === "errore_db_presenze") {
            let titolo = "Errore", contenuto = "C'è stato un errore nell'elaborazione dei dati.";
            $alert(titolo, contenuto);
        } else {
            const vPresenze = JSON.parse(result);
            for(let i = 0, l = vPresenze.length; i < l; i++) {
                let riga = "<tr>" +
                    `<td>${(i+1)}</td>` +
                    `<td>${vPresenze[i].Nome}&nbsp;${vPresenze[i].Cognome}</td>` +
                    `<td><p class='text-center'>`;
                if(vPresenze[i].Presenza == 0) //assente
                    riga += "<span class='label label-danger'>Assente</span>";
                else if(vPresenze[i].Presenza == 1) //presente
                    riga += "<span class='label label-success'>Presente</span>";
                else if(vPresenze[i].Presenza == 2) //ritardo
                    riga += "<span class='label label-warning'>Ritardo</span>";
                riga += "</p></td></tr>";

                $tbody.append(riga);
            }
            $("div#presenzeSessione").modal("show");
        }
    });
}

function visualizzaListaAltreAttivita() {
    const $tbody = $("tbody#tAltreAttivita");
    $tbody.html("");
    $.post("/amministrazione/script/getListaAltreAttivita.php", function(result) {
        result = result.trim();
        if(result === "false") {
            let titolo = "Attenzione", contenuto = "Nessuno è iscritto ad altre attività.";
            $alert(titolo, contenuto);
        } else {
            const vAltAtt = JSON.parse(result);

            for(let i = 0, l = vAltAtt.length; i < l; i++) {
                $tbody.append(
                    "<tr>" +
                    `<td>${vAltAtt[i].Cognome}</td>` +
                    `<td>${vAltAtt[i].Nome}</td>` +
                    `<td>${vAltAtt[i].Cl}°${vAltAtt[i].Sez}`+" "+`${vAltAtt[i].Ind}</td>` +
                    `<td>${vAltAtt[i].Gg}</td>` +
                    `<td>${vAltAtt[i].Hh}</td>` +
                    "</tr>"
                );
            }

            $("div#listaAltreAttivita").modal("show");
        }
    });
}

function stampaLiberatoria(idPersona, idSessioneCorso){
    const $body = $("div#body_lib");
    $body.html("");
    $.post("/amministrazione/script/getDatiLiberatoria.php", {idP: idPersona, idS: idSessioneCorso}, function(result) {
        result = result.trim();
        if(result === "false") {
            let titolo = "Attenzione", contenuto = "Alcuni dati inseriti non sono corretti.";
            $alert(titolo, contenuto);
        } else {
            const datiUtente = JSON.parse(dati);

            $body.html(
                "<img src='/img/AutoGest-A_Logo.png' alt='auto.gest_logo' class='img-responsive center-block' id='logoLiberatoria' />" +
                "<h3 class='text-center'>PERMESSO ECCEZIONALE</h3>" +
                "<hr>" +
                "<p class='text-justify'>Lo/a studente/ssa " +
                `<strong>${res.CognomeStud}&nbsp;${res.NomeStud}</strong> è autorizzato/a a partecipare al corso <strong>${res.NomeCorso}</strong> alla <strong>${res.Ora}° ora</strong> del giorno <strong>${res.Giorno}&nbsp;${res.Mese}</strong>, ` +
                "solo se il Responsabile del suddetto corso acconsente.</p>" +
                `<p class='text-right'><br />${res.NomeLog}&nbsp;${res.CognomeLog},<br />Rappresentante degli Studenti</p>` +
                "<p style='text-justify'><br /><strong>NOTA PER IL RESPONSABILE DEL CORSO</strong>: In caso lo studente venga accettatto all'interno del corso non deve essere svolta nessuna operazione all'interno del registro presenze.</p>"
            );

            $("div#stampaLiberatoria").modal("show");
        }
    });
}

function stampaCorsi() {
    $("a#avvioStampaLiberatoria").click(function() {
            window.print();
    });
}

//PANNELLO I - MODIFICA LISTA DELLE ALTRE ATTIVITA'
function getAltreAttivita() {
    const $textarea = $("textarea#txtAltreAttivita");
    $textarea.val("");
    $.post("/amministrazione/script/getAltreAttivita.php", function(result) {
        result = result.trim();
        if(result !== "no_altre_attivita") {
            $textarea.val(result);
        }
    });
}

$(document).ready(function() {
    //RECUPERO DATI per PANNELLO E
    getListaCorsi();

    //RECUPERO DATI per PANNELLO I
    getAltreAttivita();

    //CARICO FUNZIONALITA' per PANNELLO G
    stampaCorsi();


    $("a#goToPanel_A").click(function() {
        $("input#nome_ricerca").focus();
    });
    $("a#goToPanel_E").click(function() {
        $("select#sessioniCorso").focus();
    });

    //PANNELLO A - RICERCA ID PERSONA
    $("button#cercaID").click(ricercaID_Persona);

    //PANNELLO B - RESET DEI CORSI DI UNO STUDENTE
    $("button#resetP").click(function() {
        let id = $("input#id_reset").val();
        try {
            id = parseInt(id);
            
            //controllo se il parsing non è andato a buon fine
            if(isNaN(id)) throw("id_non_numerico");

            $.confirm({
                escapeKey: true,
                theme: "modern",
                title: "Conferma richiesta",
                content: "Sei sicuro di voler resettare lo studente dal codice identificativo: <strong>" + id + "</strong>?",
                buttons: {
                    confirm: {
                        text: "Sì, prosegui",
                        btnClass: "btn-success",
                        keys: ['enter'],
                        action: function() {
                            resetCorsiStudente(id);
                        }
                    },
                    cancel: {
                        text: "No, annulla",
                        btnClass: "btn-danger"
                    }
                }
            });

        } catch(parsing_error) {
            let titolo = "Attenzione", contenuto = "Devi compilare la casella di testo dell'ID con un codice numerico.";
            $alert(titolo, contenuto);
        }
    });

    //PANNELLO C - CAMBIO PASSWORD AD UN UTENTE
    $("button#btnCambioPswP").click(function() {
        let id = $("input#cambioPswP").val();
        try {
            id = parseInt(id);

            //controllo se il parsing non è andato a buon fine
            if(isNaN(id)) throw("id_non_numerico");

            $.confirm({
                escapeKey: true,
                backgroundDismiss: true,
                theme: "modern",
                title: "Cambio password ad un utente",
                content:"<form id='promptNuovaPsw'><div class='form-group'>" +
                        "<label>Inserisci la nuova password per l'utente di codice <strong>"+id+"</strong>:</label>" +
                        "<input type='password' class='form-control' id='txtNuovaPsw' placeholder='Nuova password' required />" +
                        "<label>Inserisci nuovamente la nuova password:</label>" +
                        "<input type='password' class='form-control' id='txtConfermaNuovaPsw' required />" +
                        "</div></form>",
                buttons: {
                    formSubmit: {
                        text: 'Conferma',
                        btnClass: 'btn-success',
                        action: function() {
                            const nuovaPsw = this.$content.find('input#txtNuovaPsw').val();
                            const confermaNuovaPsw = this.$content.find('input#txtConfermaNuovaPsw').val();
                            if(!nuovaPsw) {
                                let titolo = "Attenzione", contenuto = "Inserisci la nuova password oppure premi Annulla.";
                                $alert(titolo, contenuto);
                                return false;
                            } else if(!confermaNuovaPsw) {
                                let titolo = "Attenzione", contenuto = "Come conferma di digitazione corretta devi inserire nuovamente la nuova password.";
                                $alert(titolo, contenuto);
                                return false;
                            } else if(confermaNuovaPsw !== nuovaPsw) {
                                let titolo = "Attenzione", contenuto = "Le due password non combaciano. Inseriscile nuovamente.";
                                $alert(titolo, contenuto);
                                return false;
                            } else {
                                const id = $("input#cambioPswP").val(); //recupero nuovamente l'ID dell'utente a cui cambiare password
                                cambioPasswordUtente(id, nuovaPsw);
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
        } catch(parsing_error) {
            let titolo = "Attenzione", contenuto = "Devi compilare la casella di testo dell'ID con un codice numerico.";
            $alert(titolo, contenuto);
        }
    });

    //PANNELLO D - VISUALIZZAZIONE DEI CORSI SCELTI DA UNO STUDENTE
    $("button#visCorsi").click(function() {
        let id = $("input#corsiP").val();
        try {
            id = parseInt(id);
            
            //controllo se il parsing non è andato a buon fine
            if(isNaN(id)) throw("id_non_numerico");

            $("span#idPersona").text(id);
            visualizzaCorsiStudente(id);
        } catch(parsing_error) {
            let titolo = "Attenzione", contenuto = "Devi compilare la casella con il codice numerico identificativo dello studente.";
            $alert(titolo, contenuto);
        }
    });

    //PANNELLO E - VISUALIZZAZIONE DELLE SESSIONI DEI CORSI
    $("button#visSessioniCorso").click(function() {
        let nomeC = $("select#sessioniCorso").val();
        if(nomeC !== "") {
            $("span#nomeCorso").text(nomeC);
            visualizzaSessioniCorso(nomeC);
        } else {
            let titolo = "Attenzione", contenuto = "Non hai selezionato un corso.";
            $alert(titolo, contenuto);
        };
    });

    //PANNELLO F - REGISTRO PRESENZE DI UNA SESSIONE DI UN CORSO
    $("button#visPresenzeSessione").click(function() {
        let id = $("input#presenzeSessione").val();
        try {
            id = parseInt(id);

            //controllo se il parsing non è andato a buon fine
            if(isNaN(id)) throw("id_non_numerico");

            $("span#idSessioneCorso").text(id);
            registroSessioneCorso(id);

        } catch(parsing_error) {
            let titolo = "Attenzione", contenuto = "Devi inserire il codice identificativo della sessione del corso.";
            $alert(titolo, contenuto);
        }
    });

    $("button#visListaAltreAttivita").click(function() {
        visualizzaListaAltreAttivita();
    });

    $("button#stampaLib").click(function() {
        const idPersona = $("input#stampaLib_st").val();
        const idSessioneCorso = $("input#stampaLib_c").val();
        if(idPersona !== "" && idSessioneCorso !== "") {
            stampaLiberatoria(idPersona, idSessioneCorso);
        } else {
            let titolo = "Attenzione", contenuto = "Devi inserire il codice identificativo dello studente e della sessione del corso.";
            $alert(titolo, contenuto);
        }
    });

    $("a#avvioStampaLiberatoria").click(function() {
        window.print();
    });

    $("button#confermaAltreAttivita").click(function() {
        const newData = $("input#txtAltreAttivita").val();
        $.post("/amministrazione/script/updateListaAltreAttivita.php", {aA: newData}, function(result) {
            const out = result.trim();
            if(out == "modifica-effettuata") {
                let titolo = "Modifica effettuata", contenuto = "La lista \"Altre attività\" è stata aggiornata con successo!";
                $alert(titolo, contenuto);
            } else {
                let titolo = "Modifica non effettuata", contenuto = "Non è stato possibile aggiornare la lista \"Altre attività\". Riprovare più tardi.";
                $alert(titolo, contenuto);
            }
        });
    });
});
