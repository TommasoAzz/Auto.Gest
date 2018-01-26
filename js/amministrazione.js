function ricercaID() {
    var nome=$("input#nome_ricerca").val();
    var cognome=$("input#cognome_ricerca").val();
    if(nome !== "" && cognome !== "") {
        $.post("/amministrazione/getID_Persona.php",{cognome: cognome,nome: nome},function(result) {
            if(result.trim() !== "false") {
                var vID=$.parseJSON(result);
                if(vID.length == 1) {
                    $("input#risultatoRicercaID").val(vID[0].ID_Persona);
                } else if(vID.length > 1) {
                    var lista_ID="";
                    for(var i=0;i<vID.length;i++) {
                        lista_ID+=vID[i].ID_Persona+" - ";
                    }
                    lista_ID=lista_ID.substr(0,(lista_ID.length)-3);
                    $("input#risultatoRicercaID").val(lista_ID);
                }
            } else {
                $("input#risultatoRicercaID").val("Nessun risultato");
            }
        });
    } else {
        $.alert({
            escapeKey: true,
            backgroundDismiss: true,
            theme: "modern",
            title: "Attenzione",
            content: "Devi compilare entrambe le caselle di testo <strong>Nome</strong> e <strong>Cognome</strong>."
        });
    }
}

function resetP(id) {
    $.post("/amministrazione/resetIscrizioniByID.php",{ID: id},function(result) {
        if(result.trim() == "reset-effettuato") {
            $.alert({
                escapeKey: true,
                backgroundDismiss: true,
                theme: "modern",
                title: "Operazione completata",
                content: "Il reset della persona di ID: "+id+" è stato completato con successo."
            });
        } else if(result.trim() == "reset-non-effettuato") {
            $.alert({
                escapeKey: true,
                backgroundDismiss: true,
                theme: "modern",
                title: "Operazione non effettuata",
                content: "Il reset della persona di ID: "+id+" non è andata a buon fine. Riprovare più tardi."
            });
        }
    });
}

function visualizzaCorsiPersona(id) {
    var $tbody=$("tbody#tCorsiPersona");
    $tbody.html("");
    $.post("/amministrazione/getCorsiPersona.php",{ID: id},function(result) {
        var riga="";
        if(result.trim() != "false") {
            var vCorsi=$.parseJSON(result);
            var v=""; //variabile contenente il singolo oggetto di un'unica riga dell'array ricevuto come risposta
            for(var i=0;i<vCorsi.length;i++) {
                riga="<tr>";
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
            $.alert({
                escapeKey: true,
                backgroundDismiss: true,
                theme: "modern",
                title: "Attenzione",
                content: "La persona di ID: "+id+" non è ancora iscritta."
            });    
        }
    });
}

function getListaCorsi() {
    var $select=$("select#sessioniCorso");
    $select.html("");
    $.post("/amministrazione/getListaCorsi.php",function(result) {
        datiDaServer=result.trim(); //ottengo i dati dal server
        if(datiDaServer!="false") {
            var vCorsi=$.parseJSON(datiDaServer);
            var option=""; //variabile contente la option della select generata
            $select.append("<option value=''></option>")
            for(var i=0;i<vCorsi.length;i++) {
                option="<option value=\""+vCorsi[i].Nome+"\">"+vCorsi[i].Nome+"</option>";
                $select.append(option);
            }
        }    
    });
}

function visualizzaSessioniCorso(nomeC) {
    var $tbody=$("tbody#tSessioniCorso");
    $tbody.html("");
    $.post("/amministrazione/getSessioniCorso.php",{nomeCorso: nomeC},function(result) {
        var datiDaServer=$.parseJSON(result.trim()); //ottengo i dati dal server
        var datiCorso=$.parseJSON(datiDaServer[0]);
        if(datiDaServer[1]!="false") {
            var vSessioni=$.parseJSON(datiDaServer[1]);
            var riga="";
            for(var i=0;i<vSessioni.length;i++) {
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
            $.alert({
                escapeKey: true,
                backgroundDismiss: true,
                theme: "modern",
                title: "Errore",
                content: "C'è stato un errore nell'elaborazione dei dati."
            });
        }     
    });
}

function visualizzaPresenzeSessione(id) {
    var $tbody=$("tbody#tPresenzeSessione");
    $tbody.html("");
    $.post("/amministrazione/getPresenzeSessione.php", {ID_SessioneCorso: id},function(result) {
        var vPresenze=result.trim();
        if(vPresenze !== "false") {
            vPresenze=$.parseJSON(vPresenze);
            var riga="";
            for(var i=0;i<vPresenze.length;i++) {
                riga="<tr>";
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
            $.alert({
                escapeKey: true,
                backgroundDismiss: true,
                theme: "modern",
                title: "Errore",
                content: "C'è stato un errore nell'elaborazione dei dati."
            });
        }   
    });
}

function cambioPassword(id,nuovaPsw) {
    $.post("/amministrazione/changePasswordByID.php",{ID: id, Password: nuovaPsw},function(result) {
        if(result=="cambio-effettuato") {
            $.alert({
                escapeKey: true,
                backgroundDismiss: true,
                theme: "modern",
                title: "Cambio password effettuato",
                content: "Il cambio di password è stato effettuato correttamente."
            });    
        } else {
            $.alert({
                escapeKey: true,
                backgroundDismiss: true,
                theme: "modern",
                title: "Cambio password non effettuato",
                content: "Il cambio di password non è stato effettuato. Riprova più tardi."
            });    
        }
    });
}

$(document).ready(function() {
    //avvio della pagina 
    getListaCorsi(); //pannello E

    $("a#goToPanel_A").click(function() {
        $("input#nome_ricerca").focus();
    });
    $("a#goToPanel_E").click(function() {
        $("select#sessioniCorso").focus();
    });
    
    //evento che gestisce la ricerca degli ID di una persona
    $("button#cercaID").click(function() {
        ricercaID();
    });

    $("button#resetP").click(function() {
        var id=$("input#id_reset").val();
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
            $.alert({
                escapeKey: true,
                backgroundDismiss: true,
                theme: "modern",
                title: "Attenzione",
                content: "Devi compilare la casella di testo dell'ID."
            });
        }
    });

    $("button#visCorsi").click(function() {
        var id=$("input#corsiP").val();
        if(id !== "") {
            $("span#idPersona").text(id);
            visualizzaCorsiPersona(id);
        } else {
           $.alert({
               escapeKey: true,
               backgroundDismiss: true,
               theme: "modern",
               title: "Attenzione",
               content: "Devi compilare la casella di testo dell'ID."
            });
        }
    });

    $("button#visSessioniCorso").click(function() {
        var nomeC=$("select#sessioniCorso").val();
        if(nomeC !== "") {
            $("span#nomeCorso").text(nomeC);
            visualizzaSessioniCorso(nomeC);
        } else {
            $.alert({
                escapeKey: true,
                backgroundDismiss: true,
                theme: "modern",
                title: "Attenzione",
                content: "Non hai selezionato un corso."
            });
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
                                $.alert({
                                    escapeKey: true,
                                    backgroundDismiss: true,
                                    theme: "modern",
                                    title: "Attenzione",
                                    content: "Inserisci la nuova password oppure premi Annulla."
                                });
                                return false;
                            } else {
                                var id=$("input#cambioPswP").val(); //lo recupero nuovamente
                                alert("ID: "+id+" Password: "+nuovaPsw);
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
            $.alert({
                escapeKey: true,
                backgroundDismiss: true,
                theme: "modern",
                title: "Attenzione",
                content: "Devi compilare la casella di testo dell'ID."
            });
        }
    });

    $("button#visPresenzeSessione").click(function() {
        var idSessioneCorso=$("input#presenzeSessione").val();
        if(idSessioneCorso !== "") {
            $("span#idSessioneCorso").text(idSessioneCorso);
            visualizzaPresenzeSessione(idSessioneCorso);
        } else {
            $.alert({
                escapeKey: true,
                backgroundDismiss: true,
                theme: "modern",
                title: "Attenzione",
                content: "Devi inserire il codice identificativo della sessione del corso."
            });
        };    
    });
});