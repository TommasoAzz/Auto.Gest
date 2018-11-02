//richiedo i corsi del relatore ordinati per giorno e ora
function richiestaCorsi() {
    const $scelta_corso = $("select#scelta_corso");
    $scelta_corso.html('');
    $.post("/registro-presenze/script/getCorsi.php", function(result) {
        const datiDaServer = result.trim(); //ottengo i dati dal server
        if(datiDaServer !== "false") {
            const vCorsi = JSON.parse(datiDaServer);
            $scelta_corso.append("<option value=''></option>");
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
    const $table = $("tbody#tbody_gestCorso");
    $table.html('');
    $.post("/registro-presenze/script/getStudenti.php", {id: id}, function(result) {
        const datiDaServer = result.trim(); //ottengo i dati dal server
        if(datiDaServer === "false") {
            $table.append("<tr><td>Non sono presenti iscritti in quest\'ora.</td><td></td><td></td><td></td></tr>");
        } else {
            const vStudenti = JSON.parse(datiDaServer);

            for(let i = 0, l = vStudenti.length; i < l; i++) {
                const presenza = parseInt(vStudenti[i].Presenza);

                const radioPresenza = `<div class='btn-group' data-toggle='buttons' id='radio_presenze_${(i+1)}'>` +
                    `<label class='btn btn-success' id='bottone_s'><input type='radio' name='options_${(i+1)}' value='1' id='1' `+checked(1,presenza)+`>Presente</label>` +
                    `<label class='btn btn-danger' id='bottone_d'><input type='radio' name='options_${(i+1)}' value='0' id='0' `+checked(0,presenza)+`>Assente</label>` +
                    `<label class='btn btn-warning' id='bottone_w'><input type='radio' name='options_${(i+1)}' value='2' id='2' `+checked(2,presenza)+`>Ritardo</label>`+
                    "</div>";

                const rigaTab = "<tr>" +
                    `<td>${(i+1)}</td>` +
                    `<td id='nome_${(i+1)}'>${vStudenti[i].Nome}</td>` +
                    `<td id='cognome${(i+1)}'>${vStudenti[i].Cognome}</td>` +
                    `<td id='presenza_${(i+1)}'>${radioPresenza}</td>` +
                    `<td id='id_iscr_${(i+1)}' style='width:1px;display:none'>${vStudenti[i].ID_Iscrizione}</td>` +
                    "</tr>";

                $table.append(rigaTab);
            }

            $("input[checked='checked']").parent().addClass('active');
        }
    });
}

function aggiornaDB() {
    const n_rows = $('tbody#tbody_gestCorso').children().length;
    let status = [];

    for(let i = 0; i < n_rows; i++){
        status[i] = [
            parseInt($('tbody#tbody_gestCorso td#id_iscr_'+(i+1)).html()),
            $('input:radio[name=options_'+(i+1)+']:checked').val()
        ];
    }

    return status;
}

$(document).ready(function() {
    const $btnRegPres = $("button#btnRegistroPresenze"); //ATTENZIONE AL CONST
    const $selectCorso = $("select#scelta_corso");
    $btnRegPres.prop('disabled', true);

    //richiesta dei corsi di cui è responsabile l'utente che ha effettuato il login
    richiestaCorsi();

    $selectCorso.change(function() {
        if($(this).val() !== "") $btnRegPres.prop('disabled', false);
    });

    //aggiornamento della lista corsi al click del pulsante "Aggiorna la lista"
    $btnRegPres.click(function() {
        const id_sessionecorso = $selectCorso.val();
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

        $.post("/registro-presenze/script/aggiornaRegistro.php", {aggiornamenti: aggiornamenti}, function(result){
            const statoUpdate = result.trim();
            if(statoUpdate === "registro-aggiornato") {
                let titolo = "Operazione completata", contenuto = "Il registro è stato aggiornato con successo.";
                $alert(titolo, contenuto);
            } else {
                let titolo = "Operazione non effettuata", contenuto = "Ci sono stato dei problemi nell'aggiornamento del registro. Riprovare più tardi.";
                $alert(titolo, contenuto);
            }
        });
    });
});
