//richiedo i corsi del relatore ordinati per giorno e ora
function richiestaCorsi() {
    const $scelta_corso=$("select#scelta_corso");
    $scelta_corso.html('');
    $.post("/registro-presenze/getCorsi.php",function(result) {
        const datiDaServer=result.trim(); //ottengo i dati dal server
        if(datiDaServer !== "false") {
            var vCorsi=$.parseJSON(datiDaServer);
            $scelta_corso.append("<option value=''></option>");
            var option=""; //variabile contenente gli elementi che verranno aggiunti alla select#corso
            for(let i=0,l=vCorsi.length;i<l;i++) {
                //option: contiene gli elementi che verranno aggiunti alla select#corso
                let option=`<option value='${vCorsi[i].ID_SessioneCorso}'>${vCorsi[i].Nome}`+" - "+`${vCorsi[i].Giorno}° giorno`+" - "+`${vCorsi[i].Ora}° ora</option>`;
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
    const $table=$("tbody#tbody_gestCorso");
    $table.html('');
    $.post("/registro-presenze/getStudenti.php",{id: id},function(result) {
        const datiDaServer=result.trim(); //ottengo i dati dal server
        if(datiDaServer !== "false") {
            const vStudenti=$.parseJSON(datiDaServer);
            for(let i=0,l=vStudenti.length;i<l;i++) {
                let presenza=parseInt(vStudenti[i].Presenza);

                let radioPresenza=`<div class='btn-group' data-toggle='buttons' id='radio_presenze_${(i+1)}'>`;
                radioPresenza+=`<label class='btn btn-success' id='bottone_s'><input type='radio' name='options_${(i+1)}' value='1' id='1' `+checked(1,presenza)+`>Presente</label>`;
                radioPresenza+=`<label class='btn btn-danger' id='bottone_d'><input type='radio' name='options_${(i+1)}' value='0' id='0' `+checked(0,presenza)+`>Assente</label>`;
                radioPresenza+=`<label class='btn btn-warning' id='bottone_w'><input type='radio' name='options_${(i+1)}' value='2' id='2' `+checked(2,presenza)+`>Ritardo</label>`;
                radioPresenza+="</div>";

                let rigaTab="<tr>";
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
    const $tbody=$('tbody#tbody_gestCorso');
    const n_rows = $tbody.children().length;
    var id = [];
    var select = [];
    var status = [];
    for(let i=0;i<n_rows;i++){
        id[i] = $('tbody#tbody_gestCorso td#id_iscr_'+(i+1)).html();
        select[i] = $('input:radio[name=options_'+(i+1)+']:checked').val();
        status[i] = [id[i],select[i]];
    }
    return status;
}

$(document).ready(function() {
    $("button#btnRegistroPresenze").prop('disabled',true);
    //richiesta dei corsi di cui è responsabile l'utente che ha effettuato il login
    richiestaCorsi();
    $("select#scelta_corso").change(function() {
        if($(this).val() !== "") {
            $("button#btnRegistroPresenze").prop('disabled',false);
        }
    });
    //aggiornamento della lista corsi al click del pulsante "Aggiorna la lista"
    $("button#btnRegistroPresenze").click(function() {
        const id_sessionecorso = $('select#scelta_corso').val();
        if(id_sessionecorso !== "") {
            richiestaStudenti(id_sessionecorso);
            $('div#divisorio, div#listaPersone').css('display','block');
        } else {
            $('div#divisorio, div#listaPersone').css('display','none');  
        }
    });  

    $("button#btnConferma").click(function() {
        //console.log("Premuto il pulsante di conferma.");
        const aggiornamenti = JSON.stringify(aggiornaDB());
        
        $.post("/registro-presenze/updateReg.php",{aggiornamenti: aggiornamenti},function(result){
            const statoUpdate=result.trim();
            if(statoUpdate === "registro-aggiornato") {
                let titolo="Operazione completata",contenuto="Il registro è stato aggiornato con successo.";
                $alert(titolo,contenuto);
            } else if(statoUpdate === "registro-non-aggiornato") {
                let titolo="Operazione non effettuata",contenuto="Ci sono stato dei problemi nell'aggiornamento del registro. Riprovare più tardi.";
                $alert(titolo,contenuto);
            }
        });
    });
});