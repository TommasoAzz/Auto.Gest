//PANNELLO A - RICERCA ID PERSONA
function ricercaID_Persona() {
    const nc = {
        nome: $("input#nome_ricerca").val().trim(),
        cognome: $("input#cognome_ricerca").val().trim()
    };

    if(nc.nome === "" || nc.cognome === "") {
        $alert(
            "Attenzione!",
            "Devi compilare entrambe le caselle di testo <strong>Nome</strong> e <strong>Cognome</strong>."
        );
    } else {
        $.post("/amministrazione/script/ricercaID_Persona.php", nc, function(result) {
            result = result.trim();
            const $input = $("input#risultatoRicercaID");
            
            if(result === "errore_db_dati_persona") $input.val("Nessun risultato");
            else {
                const datiPersona = JSON.parse(result);
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

                    $alert(
                        "Ci sono più " + nc.nome.toUpperCase() + " " + nc.cognome.toUpperCase(),
                        alertContent
                    );
                }
            }
        });
    }
}

//PANNELLO B - RESET DEI CORSI DI UNO STUDENTE
function resetCorsiStudente() {
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
                        $.post("/amministrazione/script/resetCorsiStudente.php", {ID: id}, function(result) {
                            result = result.trim();
                            if(result === "reset-effettuato") {
                                $alert(
                                    "Operazione completata",
                                    "Il reset dei corsi dello studente di codice <strong>" + id + "</strong> è stato completato con successo."
                                );
                            } else {
                                $alert(
                                    "Operazione non effettuata",
                                    "Il reset dei corsi dello studente di codice <strong>" + id + "</strong> non è andato a buon fine (codice errore: <strong>" + result + "</strong>). Riprovare più tardi."
                                );
                            }
                        });
                    }
                },
                cancel: {
                    text: "No, annulla",
                    btnClass: "btn-danger"
                }
            }
        });
    } catch(parsing_error) {
        $alert(
            "Attenzione",
            "Devi compilare la casella di testo dell'ID con un codice numerico."
        );
    }
}

//PANNELLO C - CAMBIO PASSWORD AD UN UTENTE
function cambioPasswordUtente() {
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
                    "<label>Inserisci la nuova password per l'utente di codice <strong>" + id + "</strong>:</label>" +
                    "<input type='password' class='form-control' id='txtNuovaPsw' placeholder='Nuova password' required />" +
                    "<label>Inserisci nuovamente la nuova password:</label>" +
                    "<input type='password' class='form-control' id='txtConfermaNuovaPsw' required />" +
                    "</div></form>",
            buttons: {
                formSubmit: {
                    text: 'Conferma',
                    btnClass: 'btn-success',
                    action: function() {
                        const id = $("input#cambioPswP").val(); //recupero nuovamente l'ID dell'utente a cui cambiare password
                        const nuovaPsw = this.$content.find('input#txtNuovaPsw').val();
                        const confermaNuovaPsw = this.$content.find('input#txtConfermaNuovaPsw').val();
                        if(!nuovaPsw) {
                            $alert("Attenzione", "Inserisci la nuova password oppure premi Annulla.");
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
                            $.post("/amministrazione/script/cambioPasswordUtente.php", {ID: id, Pwd: nuovaPsw}, function(result) {
                                result = result.trim();
                                if(result === "cambio-effettuato") {
                                    $alert(
                                        "Cambio password effettuato",
                                        `Il cambio della password all'utente di codice <strong>${id}</strong> è stato effettuato correttamente.`
                                    );
                                } else {
                                    $alert(
                                        "Cambio password non effettuato",
                                        `Il cambio della password all'utente di codice <strong>${id}</strong> non è stato effettuato. Riprovare più tardi.`
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
    } catch(parsing_error) {
        $alert(
            "Attenzione",
            "Devi compilare la casella di testo dell'ID con un codice numerico."
        );
    }
}

//PANNELLO D - VISUALIZZAZIONE DEI CORSI SCELTI DA UNO STUDENTE
function visualizzaCorsiStudente() {
    let id = $("input#corsiP").val();
    const $tbody = $("tbody#tCorsiPersona");
    try {
        id = parseInt(id);
        
        //controllo se il parsing non è andato a buon fine
        if(isNaN(id)) throw("id_non_numerico");

        $("span#idPersona").text(id);
        $tbody.html("");
        $.post("/amministrazione/script/corsiSceltiStudente.php", {ID: id}, function(result) {
            result = result.trim();

            if(result === "errore_db_corsi_iscritti_studente") {
                $alert(
                    "Attenzione",
                    `La persona di ID: <strong>${id}</strong> risulta non iscritta`
                );
            } else {
                const corsi_stud = JSON.parse(result);
                for(let i = 0, num_sessioni = corsi_stud.length; i < num_sessioni; i++) {
                    $tbody.append(
                        "<tr>" +
                        `<td>${corsi_stud[i].Giorno}</td>` +
                        `<td>${corsi_stud[i].Ora}</td>` +
                        `<td>${corsi_stud[i].Nome}</td>` +
                        `<td>${corsi_stud[i].Durata}</td>` +
                        `<td>${corsi_stud[i].Aula}</td>` +
                        `<td>${corsi_stud[i].ID_SessioneCorso}</td>` +
                        `</tr>`
                    );
                }

                $("div#corsiPersona").modal("show");
            }
        }); 
    } catch(parsing_error) {
        $alert(
            "Attenzione",
            "Devi compilare la casella con il codice numerico identificativo dello studente."
        );
    }
}

//PANNELLO E - VISUALIZZAZIONE DELLE SESSIONI DEI CORSI
function getListaCorsi($select) {
    $select.html("").append("<option value=''></option>");

    $.post("/amministrazione/script/getListaCorsi.php", function(result) {
        result = result.trim(); //ottengo i dati dal server

        if(result === "errore_db_lista_corsi") {
            $alert(
                "Attenzione",
                "C'è stato un problema nel caricamento della lista dei corsi per il <strong>Pannello E</strong>"
            );
        } else {
            const corsi = JSON.parse(result);
            for(let i = 0, l = corsi.length; i < l; i++) $select.append(`<option value="${corsi[i].Nome}">${corsi[i].Nome}</option>`);
        }
    });
}

function visualizzaSessioniCorso(corso) {
    const $tbody = $("tbody#tSessioniCorso");
    $tbody.html("");

    $.post("/amministrazione/script/sessioniCorso.php", {nomeCorso: corso}, function(result) {
        result = result.trim();

        if(result === "errore_db_nome_corso" || result === "errore_db_sessioni_corso") {
            $alert(
                "Errore",
                "C'è stato un errore nell'elaborazione dei dati."
            );
        } else {
            const datiDaServer = JSON.parse(result); //spacchetto l'array ottenuto da POST

            const datiCorso = JSON.parse(datiDaServer.corso); //spacchetto l'array associativo ottenuto dalla query (dati del Corso)
            const sessioniCorso = JSON.parse(datiDaServer.sessioniCorso); //spacchetto l'array associativo ottenuto dalla query (dati delle sessioni del corso)

            for(let i = 0, l = sessioniCorso.length; i < l; i++) {
                $tbody.append(
                    "<tr>" +
                    `<td>${sessioniCorso[i].Giorno}</td>` +
                    `<td>${sessioniCorso[i].Ora}</td>` +
                    `<td>${sessioniCorso[i].PostiRimasti}</td>` +
                    `<td>${sessioniCorso[i].ID_SessioneCorso}</td>` +
                    "</tr>"
                );
            }

            $("span#nomeCorso").text(corso);
            $("span#idCorso").text(datiCorso.ID_Corso);
            $("span#durataCorso").text(datiCorso.Durata);
            $("span#aulaCorso").text(datiCorso.Aula);
            $("span#postiCorso").text(datiCorso.MaxPosti);

            $("div#sessioniCorso").modal("show");
        }
    });
}

//PANNELLO F - REGISTRO PRESENZE DI UNA SESSIONE DI UN CORSO
function registroSessioneCorso(id) {
    const $tbody = $("tbody#tPresenzeSessione");
    $tbody.html("");

    $.post("/amministrazione/script/registroPresenzeSessioneCorso.php", {ID: id}, function(result) {
        result = result.trim();

        if(result === "errore_db_registro_presenze") {
            $alert(
                "Errore",
                "C'è stato un errore nell'elaborazione dei dati."
            );
        } else {
            const regPresenze = JSON.parse(result);

            for(let i = 0, l = regPresenze.length; i < l; i++) {
                let riga = "<tr>" +
                    `<td>${(i+1)}</td>` +
                    `<td>${regPresenze[i].Nome}&nbsp;${regPresenze[i].Cognome}</td>` +
                    `<td><p class='text-center'>`;
                
                if(regPresenze[i].Presenza == 0) //assente
                    riga += "<span class='label label-danger'>Assente</span>";
                else if(regPresenze[i].Presenza == 1) //presente
                    riga += "<span class='label label-success'>Presente</span>";
                else if(regPresenze[i].Presenza == 2) //ritardo
                    riga += "<span class='label label-warning'>Ritardo</span>";
                riga += "</p></td></tr>";

                $tbody.append(riga);
            }

            $("div#presenzeSessione").modal("show");
        }
    });
}

//PANNELLO G - STAMPA LIBERATORIA PER ISCRIZIONE DI UNO STUDENTE AD UN CORSO
function stampaLiberatoria(idPersona, idSessioneCorso){
    const $body = $("div#body_lib");
    $body.html("");

    $.post("/amministrazione/script/datiLiberatoria.php", {idP: idPersona, idS: idSessioneCorso}, function(result) {
        result = result.trim();

        if(result === "errore_db_dati_liberatoria") {
            $alert(
                "Attenzione",
                "Alcuni dati inseriti non sono corretti."
            );
        } else {
            const dati = JSON.parse(result);

            $body.html(
                "<img src='/img/AutoGest-A_Logo.png' alt='auto.gest_logo' class='img-responsive center-block' id='logoLiberatoria' />" +
                "<h3 class='text-center'>PERMESSO ECCEZIONALE</h3>" +
                "<hr>" +
                "<p class='text-justify'>Lo/a studente/ssa " +
                `<strong>${dati.CognomeStud}&nbsp;${dati.NomeStud}</strong> (classe ${dati.ClasseStud}) è autorizzato/a a partecipare al corso <strong>${res.NomeCorso}</strong> alla <strong>${res.Ora}° ora</strong> del giorno <strong>${res.Giorno}&nbsp;${res.Mese}</strong>, ` +
                "solo se il Responsabile del suddetto corso acconsente.</p>" +
                `<p class='text-right'><br />${res.NomeAdmin}&nbsp;${res.CognomeAdmin},<br />Rappresentante degli Studenti</p>` +
                "<p style='text-justify'><br /><strong>NOTA PER IL RESPONSABILE DEL CORSO</strong>: In caso lo studente venga accettatto all'interno del corso non deve essere svolta nessuna operazione all'interno del registro presenze.</p>"
            );

            $("div#stampaLiberatoria").modal("show");
        }
    });
}

//PANNELLO H - ISCRITTI AD ALTRE ATTIVITA'
function iscrittiAltreAttivita() {
    const $tbody = $("tbody#tAltreAttivita");
    $tbody.html("");

    $.post("/amministrazione/script/iscrittiAltreAttivita.php", function(result) {
        result = result.trim();

        if(result === "errore_db_iscritti_altre_attivita") {
            $alert(
                "Attenzione",
                "Nessuno è iscritto ad altre attività."
            );
        } else {
            const iscritti = JSON.parse(result);

            for(let i = 0, l = iscritti.length; i < l; i++) {
                $tbody.append(
                    "<tr>" +
                    `<td>${iscritti[i].Cognome}</td>` +
                    `<td>${iscritti[i].Nome}</td>` +
                    `<td>${iscritti[i].Cl}°${iscritti[i].Sez}&nbsp;${iscritti[i].Ind}</td>` +
                    `<td>${iscritti[i].Gg}</td>` +
                    `<td>${iscritti[i].Hh}</td>` +
                    "</tr>"
                );
            }

            $("div#listaAltreAttivita").modal("show");
        }
    });
}

function avvioStampaLiberatoria() {
    $("a#avvioStampaLiberatoria").click(function() {
            window.print();
    });
}

//PANNELLO I - MODIFICA LISTA ALTRE ATTIVITA'
function getAltreAttivita($textarea) {
    $textarea.val("");

    $.post("/amministrazione/script/getAltreAttivita.php", function(result) {
        result = result.trim();
        if(result !== "no_altre_attivita" && result !== "errore_altre_attivita") {
            $textarea.val(result);
        }
    });
}



$(document).ready(function() {
    const $select_SessioniCorso = $("select#sessioniCorso");
    const $txtArea_AltreAttivita = $("textarea#txtAltreAttivita");

    //RECUPERO DATI per PANNELLO E
    getListaCorsi($select_SessioniCorso);

    //RECUPERO DATI per PANNELLO I
    getAltreAttivita($txtArea_AltreAttivita);

    //CARICO FUNZIONALITA' per PANNELLO G
    avvioStampaLiberatoria();

    //LINK A PARTI PAGINA
    $("a#goToPanel_A").click(function() {
        $("input#nome_ricerca").focus();
    });
    $("a#goToPanel_E").click(function() {
        $select_SessioniCorso.focus();
    });

    //PANNELLO A - RICERCA ID PERSONA
    $("button#cercaID").click(ricercaID_Persona);

    //PANNELLO B - RESET DEI CORSI DI UNO STUDENTE
    $("button#resetP").click(resetCorsiStudente);

    //PANNELLO C - CAMBIO PASSWORD AD UN UTENTE
    $("button#btnCambioPswP").click(cambioPasswordUtente);

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
            $alert(
                "Attenzione",
                "Devi compilare la casella con il codice numerico identificativo dello studente."
            );
        }
    });

    //PANNELLO E - VISUALIZZAZIONE DELLE SESSIONI DEI CORSI
    $("button#visSessioniCorso").click(function() {
        const nome_corso = $select_SessioniCorso.val();
        if(nome_corso !== "") {
            $("span#nomeCorso").text(nome_corso);
            visualizzaSessioniCorso(nome_corso);
        } else {
            $alert(
                "Attenzione",
                "Non hai selezionato un corso."
            );
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
            $alert(
                "Attenzione",
                "Devi inserire il codice identificativo della sessione del corso."
            );
        }
    });

    //PANNELLO G - STAMPA LIBERATORIA PER ISCRIZIONE DI UNO STUDENTE AD UN CORSO
    $("button#stampaLib").click(function() {
        let idPersona = $("input#stampaLib_st").val();
        let idSessioneCorso = $("input#stampaLib_c").val();

        try {
            idPersona = parseInt(idPersona);
            idSessioneCorso = parseInt(idSessioneCorso);
            
            //controllo se il parsing non è andato a buon fine
            if(isNaN(idPersona) || isNaN(idSessioneCorso)) throw("id_non_numerico");

            stampaLiberatoria(idPersona, idSessioneCorso);
        } catch(parsing_error) {
            $alert(
                "Attenzione",
                "Devi inserire il codice identificativo dello studente e della sessione del corso."
            );
        }
    });

    //PANNELLO H - ISCRITTI AD ALTRE ATTIVITA'
    $("button#visListaAltreAttivita").click(function() {
        iscrittiAltreAttivita();
    });

    //PANNELLO I - MODIFICA LISTA ALTRE ATTIVITA'
    $("button#confermaAltreAttivita").click(function() {
        const newData = $("input#txtAltreAttivita").val().trim();

        $.post("/amministrazione/script/aggiornaListaAltreAttivita.php", {aA: newData}, function(result) {
            result = result.trim();

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
