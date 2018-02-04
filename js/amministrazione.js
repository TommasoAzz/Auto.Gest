function ricercaID() {
    const nome=$("input#nome_ricerca").val();
    const cognome=$("input#cognome_ricerca").val();
    if(nome !== "" && cognome !== "") {
        $.post("/amministrazione/getID_Persona.php",{cognome: cognome,nome: nome},function(result) {
            const $input=$("input#risultatoRicercaID");
            if(result.trim() !== "false") {
                const vID=$.parseJSON(result);
                if(vID.length == 1) {
                    $input.val(vID[0].ID_Persona);
                } else if(vID.length > 1) {
                    var lista_ID="";
                    var alertContent="";
                    for(let i=0,l=vID.length;i<l;i++) {
                        if(i != (vID.length-1)) {   
                            lista_ID+=vID[i].ID_Persona+" - ";    
                            alertContent+=vID[i].Classe+vID[i].Sezione+" "+vID[i].Indirizzo+" - Codice: "+vID[i].ID_Persona+"<br />";
                        } else {
                            lista_ID+=vID[i].ID_Persona;
                            alertContent+=vID[i].Classe+vID[i].Sezione+" "+vID[i].Indirizzo+" - Codice: "+vID[i].ID_Persona;
                        }
                    }
                    $input.val(lista_ID);
                    let titolo="Ci sono più "+nome.toUpperCase()+" "+cognome.toUpperCase();
                    $alert(titolo,alertContent);
                }
            } else {
                $input.val("Nessun risultato");
            }
        });
    } else {
        let titolo="Attenzione!",contenuto="Devi compilare entrambe le caselle di testo <strong>Nome</strong> e <strong>Cognome</strong>.";
        $alert(titolo,contenuto);
    }
}

function resetP(id) {
    $.post("/amministrazione/resetIscrizioniByID.php",{ID: id},function(result) {
        if(result.trim() == "reset-effettuato") {
            let titolo="Operazione completata",contenuto="Il reset della persona di ID: "+id+" è stato completato con successo.";
            $alert(titolo,contenuto);
        } else if(result.trim() == "reset-non-effettuato") {
            let titolo="Operazione non effettuata",contenuto="Il reset della persona di ID: "+id+" non è andata a buon fine. Riprovare più tardi.";
            $alert(titolo,contenuto);
        }
    });
}

function visualizzaCorsiPersona(id) {
    const $tbody=$("tbody#tCorsiPersona");
    $tbody.html("");
    $.post("/amministrazione/getCorsiPersona.php",{ID: id},function(result) {
        if(result.trim() != "false") {
            const vCorsi=$.parseJSON(result);
            for(let i=0,l=vCorsi.length;i<l;i++) {
                //riga: contiene il singolo oggetto di un'unica riga dell'array ricevuto come risposta
                let riga="<tr>";
                riga+="<td>"+vCorsi[i].g+"</td>";
                riga+="<td>"+vCorsi[i].o+"</td>";
                riga+="<td>"+vCorsi[i].nc+"</td>";
                riga+="<td>"+vCorsi[i].d+"</td>";
                riga+="<td>"+vCorsi[i].a+"</td>";
                riga+="<td>"+vCorsi[i].id_sc+"</td>";
                riga+="</tr>";
                $tbody.append(riga);
            }
            $("div#corsiPersona").modal("show");
        } else {
            let titolo="Attenzione",contenuto="La persona di ID: "+id+" non è ancora iscritta.";
            $alert(titolo,contenuto);  
        }
    });
}

function getListaCorsi() {
    const $select=$("select#sessioniCorso");
    $select.html("");
    $.post("/amministrazione/getListaCorsi.php",function(result) {
        const datiDaServer=result.trim(); //ottengo i dati dal server
        if(datiDaServer !== "false") {
            const vCorsi=$.parseJSON(datiDaServer);
            $select.append("<option value=''></option>")
            for(let i=0,l=vCorsi.length;i<l;i++) {
                //option: contiene la option della select generata
                let option=`<option value="${vCorsi[i].Nome}">${vCorsi[i].Nome}</option>`;
                $select.append(option);
            }
        }    
    });
}

function visualizzaSessioniCorso(nomeC) {
    const $tbody=$("tbody#tSessioniCorso");
    $tbody.html("");
    $.post("/amministrazione/getSessioniCorso.php",{nomeCorso: nomeC},function(result) {
        const datiDaServer=$.parseJSON(result.trim()); //ottengo i dati dal server
        const datiCorso=$.parseJSON(datiDaServer[0]);
        if(datiDaServer[1]!="false") {
            var vSessioni=$.parseJSON(datiDaServer[1]);
            var riga="";
            for(let i=0,l=vSessioni.length;i<l;i++) {
                riga="<tr>";
                riga+="<td>"+vSessioni[i].g+"</td>";
                riga+="<td>"+vSessioni[i].o+"</td>";
                riga+="<td>"+vSessioni[i].pr+"</td>";
                riga+="<td>"+vSessioni[i].id_sc+"</td>";
                riga+="</tr>";
                $tbody.append(riga);
            }
            $("span#nomeCorso").text(nomeC)
            $("span#idCorso").text(datiCorso[0].id);
            $("span#durataCorso").text(datiCorso[0].d);
            $("span#aulaCorso").text(datiCorso[0].a);
            $("span#postiCorso").text(datiCorso[0].pt);

            $("div#sessioniCorso").modal("show");
        } else {
            let titolo="Errore",contenuto="C'è stato un errore nell'elaborazione dei dati.";
            $alert(titolo,contenuto);
        }     
    });
}

function visualizzaPresenzeSessione(id) {
    const $tbody=$("tbody#tPresenzeSessione");
    $tbody.html("");
    $.post("/amministrazione/getPresenzeSessione.php", {ID_SessioneCorso: id},function(result) {
        var vPresenze=result.trim();
        if(vPresenze !== "false") {
            vPresenze=$.parseJSON(vPresenze);
            for(let i=0,l=vPresenze.length;i<l;i++) {
                let riga="<tr>";
                riga+="<td>"+(i+1)+"</td>";
                riga+="<td>"+vPresenze[i].Nome+" "+vPresenze[i].Cognome+"</td>";
                riga+="<td>";
                if(vPresenze[i].Presenza == 0) { //assente
                    riga+="<p class='text-center'><span class='label label-danger'>Assente</span></p>";
                } else if(vPresenze[i].Presenza == 1) {
                    riga+="<p class='text-center'><span class='label label-success'>Presente</span></p>";    
                } else if(vPresenze[i].Presenza == 2) {
                    riga+="<p class='text-center'><span class='label label-warning'>Ritardo</span></p>";
                }
                riga+="</td>";
                riga+="</tr>";
                $tbody.append(riga);
            }
            $("div#presenzeSessione").modal("show");
        } else {
            let titolo="Errore",contenuto="C'è stato un errore nell'elaborazione dei dati.";
            $alert(titolo,contenuto);
        }   
    });
}

function cambioPassword(id,nuovaPsw) {
    $.post("/amministrazione/changePasswordByID.php",{ID: id, Pwd: nuovaPsw},function(result) {
        if(result=="cambio-effettuato") {
            let titolo="Cambio password effettuato",contenuto="Il cambio di password è stato effettuato correttamente.";
            $alert(titolo,contenuto); 
        } else {
            let titolo="Cambio password non effettuato",contenuto="Il cambio di password non è stato effettuato. Riprova più tardi.";
            $alert(titolo,contenuto);   
        }
    });
}

function visualizzaListaAltreAttivita() {
    const $tbody=$("tbody#tAltreAttivita");
    $tbody.html("");
    $.post("/amministrazione/getListaAltreAttivita.php",function(result) {
        var dati=result.trim();
        if(dati !== "false") {
            vAltAtt=$.parseJSON(dati);
            for(let i=0,l=vAltAtt.length;i<l;i++) {
                let riga="<tr>";
                riga+=`<td>${vAltAtt[i].Cognome}</td><td>${vAltAtt[i].Nome}</td><td>${vAltAtt[i].Cl}°${vAltAtt[i].Sez}`+" "+`${vAltAtt[i].Ind}</td><td>${vAltAtt[i].Gg}</td><td>${vAltAtt[i].Hh}</td>`;
                riga+="</tr>";
                $tbody.append(riga);
            }
            $("div#listaAltreAttivita").modal("show");
        } else {
            let titolo="Attenzione",contenuto="Nessuno è iscritto ad altre attività.";
            $alert(titolo,contenuto);
        }  
    });
}

function stampaLiberatoria(idPersona,idSessioneCorso){
    const $body=$("div#body_lib");
    $body.html("");
    $.post("/amministrazione/getDatiLiberatoria.php",{idP: idPersona, idS: idSessioneCorso},function(result) {
        var dati=result.trim();
        if(dati !== "false") {
            res=$.parseJSON(dati);
            let testo="<img src='/img/AutoGest-A_Logo.png' alt='auto.gest_logo' class='img-responsive center-block' id='logoLiberatoria' />"
            testo+="<h3 class='text-center'>PERMESSO ECCEZIONALE</h3>";
            testo+="<hr>"
            testo+="<p class='text-justify'>Lo/a studente/ssa ";
            testo+=`<strong>${res.CognomeStud}&nbsp;${res.NomeStud}</strong> &egrave; autorizzato/a a partecipare al corso <strong>${res.NomeCorso}</strong> alla <strong>${res.Ora}° ora</strong> del giorno <strong>${res.Giorno}&nbsp;${res.Mese}</strong>, `;
            testo+="solo se il responsabile del suddetto corso acconsente.</p>"
            testo+=`<p class='text-right'><br />${res.NomeLog}&nbsp;${res.CognomeLog},<br />Rappresentante degli Studenti</p>`;
            testo+="<p style='text-justify'><br /><strong>NOTA PER IL RESPONSABILE DEL CORSO</strong>: In caso lo studente venga accettatto all'interno del corso non deve essere svolta nessuna operazione all'interno del registro presenze.</p>";
            $body.html(testo);
            $("div#stampaLiberatoria").modal("show");
        } else {
            let titolo="Attenzione",contenuto="Alcuni dati inseriti non sono corretti.";
            $alert(titolo,contenuto);
        }
    });
}

function stampaCorsi() {
    $("a#avvioStampaLiberatoria").click(function() {
            window.print();
    });
}

function getAltreAttivita() {
    const $input=$("input#txtAltreAttivita");
    $input.val("");
    $.post("/amministrazione/getAltreAttivita.php",function(result) {
        var dati=result.trim();
        if(dati !== "no-altre-attivita") {
            $input.val(dati);
        }
    });    
}
$(document).ready(function() {
    //avvio della pagina 
    getListaCorsi(); //pannello E

    stampaCorsi(); //Stampa i corsi una volta visualizzato il modal

    getAltreAttivita(); //carica il PANNELLO I

    $("a#goToPanel_A").click(function() {
        $("input#nome_ricerca").focus();
    });
    $("a#goToPanel_E").click(function() {
        $("select#sessioniCorso").focus();
    });
    
    //evento che gestisce la ricerca degli ID di una persona
    $("button#cercaID").click(ricercaID);

    $("button#resetP").click(function() {
        const id=$("input#id_reset").val();
        if(id !== "") {
            $.confirm({
                escapeKey: true,
                theme: "modern",
                title: "Conferma richiesta",
                content: "Sei sicuro di voler resettare la persona di ID: "+id+"?",
                buttons: {
                    confirm: {
                        text: "Ok",
                        btnClass: "btn-success",
                        keys: ['enter'],
                        action: function() {
                            resetP(id);  
                        }
                    },
                    cancel: {
                        text: "Annulla",
                        btnClass: "btn-danger"
                    }
                }
            });
        } else {
            let titolo="Attenzione",contenuto="Devi compilare la casella di testo dell'ID.";
            $alert(titolo,contenuto);
        }
    });

    $("button#visCorsi").click(function() {
        var id=$("input#corsiP").val();
        if(id !== "") {
            $("span#idPersona").text(id);
            visualizzaCorsiPersona(id);
        } else {
            let titolo="Attenzione",contenuto="Devi compilare la casella di testo dell'ID.";
            $alert(titolo,contenuto);
        }
    });

    $("button#visSessioniCorso").click(function() {
        var nomeC=$("select#sessioniCorso").val();
        if(nomeC !== "") {
            $("span#nomeCorso").text(nomeC);
            visualizzaSessioniCorso(nomeC);
        } else {
            let titolo="Attenzione",contenuto="Non hai selezionato un corso.";
            $alert(titolo,contenuto);
        };    
    });

    $("button#btnCambioPswP").click(function() {
        var id=$("input#cambioPswP").val();
        if(id !== "") {
            $.confirm({
                escapeKey: true,
                backgroundDismiss: true,
                theme: "modern",
                title: "Cambio password a utente di ID: "+id,
                content:"<form id='promptPsw'><div class='form-group'>" +
                        "<label>Inserisci la nuova password all'utente:</label>" +
                        "<input type='password' placeholder='Nuova password' class='name form-control' required />" +
                        "</div></form>",
                buttons: {
                    formSubmit: {
                        text: 'Conferma',
                        btnClass: 'btn-success',
                        action: function() {
                            var nuovaPsw=this.$content.find('.name').val();
                            if(!nuovaPsw) {
                                let titolo="Attenzione",contenuto="Inserisci la nuova password oppure premi Annulla.";
                                $alert(titolo,contenuto);
                                return false;
                            } else {
                                var id=$("input#cambioPswP").val(); //lo recupero nuovamente
                                cambioPassword(id,nuovaPsw);
                            }
                        }
                    },
                    cancel: {
                        text: "Annulla",
                        btnClass: "btn-danger"
                    }
                },
                onContentReady: function() {
                    var prompt = this;
                    this.$content.find('form').on('submit', function(e) {
                        e.preventDefault();
                        prompt.$$formSubmit.trigger('click');
                    });
                }
            });
        } else {
            let titolo="Attenzione",contenuto="Devi compilare la casella di testo dell'ID.";
            $alert(titolo,contenuto);
        }
    });

    $("button#visPresenzeSessione").click(function() {
        var idSessioneCorso=$("input#presenzeSessione").val();
        if(idSessioneCorso !== "") {
            $("span#idSessioneCorso").text(idSessioneCorso);
            visualizzaPresenzeSessione(idSessioneCorso);
        } else {
            let titolo="Attenzione",contenuto="Devi inserire il codice identificativo della sessione del corso.";
            $alert(titolo,contenuto);
        };    
    });

    $("button#visListaAltreAttivita").click(function() {
        visualizzaListaAltreAttivita();
    });

    $("button#stampaLib").click(function() {
        const idPersona=$("input#stampaLib_st").val();
        const idSessioneCorso=$("input#stampaLib_c").val();
        if(idPersona !== "" && idSessioneCorso !== "") {
            stampaLiberatoria(idPersona,idSessioneCorso);
        } else {
            let titolo="Attenzione",contenuto="Devi inserire il codice identificativo dello studente e della sessione del corso.";
            $alert(titolo,contenuto);
        }
    });

    $("a#avvioStampaLiberatoria").click(function() {
        window.print();
    });

    $("button#confermaAltreAttivita").click(function() {
        const newData=$("input#txtAltreAttivita").val();
        $.post("/amministrazione/updateListaAltreAttivita.php",{aA: newData},function(result) {
            const out=result.trim();
            if(out == "modifica-effettuata") {
                let titolo="Modifica effettuata",contenuto="La lista \"Altre attività\" è stata aggiornata con successo!";
                $alert(titolo,contenuto);
            } else {
                let titolo="Modifica non effettuata",contenuto="Non è stato possibile aggiornare la lista \"Altre attività\". Riprovare più tardi.";
                $alert(titolo,contenuto); 
            }
        });
    });
});