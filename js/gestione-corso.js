//richiedo i corsi del relatore ordinati per giorno e ora
function richiestaCorsi() {
    var $scelta_corso=$("select#scelta_corso");
    $scelta_corso.html('');
    $.post("/gestione-corso/getCorsi.php",function(result) {
        var datiDaServer=result.trim(); //ottengo i dati dal server
        if(datiDaServer!="false") {
            var vCorsi=$.parseJSON(datiDaServer);
            $scelta_corso.append("<option value=\"\"></option>");
            var option=""; //variabile contenente gli elementi che verranno aggiunti alla select#corso
            for(var i=0;i<vCorsi.length;i++) {
                option="<option value=\""+vCorsi[i].ID_SessioneCorso+"\">"+vCorsi[i].Nome+" - "+vCorsi[i].Giorno+"° giorno - "+vCorsi[i].Ora+"° ora</option>";
                $scelta_corso.append(option);
            }
        }
    });
}

function checked(val_radio, presenza) {
    if(val_radio === presenza) {
        return "checked='checked'";
    }
}

//richiesta degli studenti iscritti al corso selezionato
function richiestaStudenti(id) {
    var $table=$("tbody#tbody_gestCorso");
    $table.html('');
    $.post("/gestione-corso/getStudenti.php",{id: id},function(result) {
        var datiDaServer=result.trim(); //ottengo i dati dal server
        if(datiDaServer!="false") {
            var vStudenti=$.parseJSON(datiDaServer);
            var rigaTab = '';
            var presenza = 0;
            for(var i=0;i<vStudenti.length;i++) {
                presenza=parseInt(vStudenti[i].Presenza);

                radioPresenza="<div class='btn-group' data-toggle='buttons' id='radio_presenze_"+(i+1)+"'>";
                radioPresenza+="<label class='btn btn-success'><input type='radio' name='options_"+(i+1)+"' value='1' id='1' "+checked(1,presenza)+">Presente</label>";
                radioPresenza+="<label class='btn btn-danger'><input type='radio' name='options_"+(i+1)+"' value='0' id='0' "+checked(0,presenza)+">Assente</label>";
                radioPresenza+="<label class='btn btn-warning'><input type='radio' name='options_"+(i+1)+"' value='2' id='2' "+checked(2,presenza)+">Ritardo</label>";
                radioPresenza+="</div>";
                
                rigaTab="<tr>";
                rigaTab+="<td>"+(i+1)+"</td>";
                rigaTab+="<td id='nome_"+(i+1)+"'>"+vStudenti[i].Nome+"</td>";
                rigaTab+="<td id='cognome_"+(i+1)+"'>"+vStudenti[i].Cognome+"</td>";
                rigaTab+="<td id='presenza_"+(i+1)+"'>"+radioPresenza+"</td>";
                rigaTab+="<td id='id_iscr_"+(i+1)+"' style='width: 1px; display:none;'>"+vStudenti[i].ID_Iscrizione+"</td>";
                rigaTab+="</tr>";
                
                $table.append(rigaTab);
            }
            $("input[checked='checked']").parent().addClass('active');;
        } else {
            $table.append("<tr><td>Non sono presenti iscritti in quest\'ora.</td><td></td><td></td><td></td></tr>");
        }
    });
}

function aggiornaDB() {
    var $tbody=$('tbody#tbody_gestCorso');
    var n_rows = $tbody.children().length;
    //console.log("Numero righe: "+n_rows);
    var id =[];
    var nome = [];
    var cognome = [];
    var select = [];
    var status = [];
    for(var i=0;i<n_rows;i++){
        id[i] = $('tbody#tbody_gestCorso td#id_iscr_'+(i+1)).html();
        //nome[i] = $('tbody#tbody_gestCorso td#nome_'+(i+1)).html();
        //cognome[i] = $('tbody#tbody_gestCorso td#cognome_'+(i+1)).html();
        select[i] = $('input:radio[name=options_'+(i+1)+']:checked').val();
        status[i] = [id[i],/*nome[i],cognome[i],*/select[i]];
        //console.log(status[i]);
    }
    return status;
}

$(document).ready(function() {
    $("button#btnGestioneCorso").prop('disabled',true);
    //richiesta dei corsi di cui è responsabile l'utente che ha effettuato il login
    richiestaCorsi();
    $("select#scelta_corso").change(function() {
        if($(this).val() !== "") {
            $("button#btnGestioneCorso").prop('disabled',false);
        }
    });
    //aggiornamento della lista corsi al click del pulsante "Aggiorna la lista"
    $("button#btnGestioneCorso").click(function() {
        var id_sessionecorso = $('select#scelta_corso').val();
        if(id_sessionecorso !== "") {
            richiestaStudenti(id_sessionecorso);
            $('div#divisorio, div#listaPersone').css('display','block');
        } else {
            $('div#divisorio, div#listaPersone').css('display','none');  
        }
    });  

    $("button#btnConferma").click(function() {
        //console.log("Premuto il pulsante di conferma.");
        var status=aggiornaDB();
        aggiornamenti = JSON.stringify(status);
        
        $.post("/gestione-corso/updateReg.php",{aggiornamenti: aggiornamenti},function(result){
            var statoUpdate=result.trim();
            if(statoUpdate=="registro-aggiornato") {
                $.alert({
                    escapeKey: true,
                    backgroundDismiss: true,
                    theme: "modern",
                    title: "Operazione completata",
                    content: "Il registro è stato aggiornato con successo."
                });
            } else if(statoUpdate=="registro-non-aggiornato") {
                $.alert({
                    escapeKey: true,
                    backgroundDismiss: true,
                    theme: "modern",
                    title: "Operazione non effettuata",
                    content: "Ci sono stato dei problemi nell'aggiornamento del registro. Riprovare più tardi."
                });
            }
        });
    });
});