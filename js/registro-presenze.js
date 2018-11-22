//richiedo i corsi del relatore ordinati per giorno e ora
function richiestaCorsi($scelta_corso) {
    $scelta_corso.html("").append("<option value=''></option>");

    $.post("/registro-presenze/script/getCorsiGestiti.php", function(result) {
        result = result.trim(); //ottengo i dati dal server
        if(result === "errore_db_corsi_gestiti") {
            $alert(
                "Errore nel reperimento dei corsi gestiti",
                "Ci sono stati dei problemi nel reperimento della lista dei corsi che tieni. Riprovare più tardi."
            );
        } else {
            const c_gest = JSON.parse(result);

            for(let i = 0, l = c_gest.length; i < l; i++)
                $scelta_corso.append("<option value='" + c_gest[i].ID_SessioneCorso + "'>" + c_gest[i].Nome + " - " + c_gest[i].Giorno + " - " + c_gest[i].Ora + "° ora</option>");
        }
    });
}

function checked(val_radio, presenza) {
    return (val_radio === presenza) ? "checked='checked'" : "";
}

//richiesta degli studenti iscritti al corso selezionato
function registroPresenzeSessioneCorso(id) {
    const $table = $("tbody#tbody_gestCorso");
    $table.html('');

    $.post("/registro-presenze/script/registroPresenzeSessioneCorso.php", {id: id}, function(result) {
        result = result.trim(); //ottengo i dati dal server

        if(result === "errore_db_registro_presenze") {
            $table.append("<tr><td>Per il momento non c'è alcun iscritto a questa sessione di corso.</td><td></td><td></td><td></td></tr>");
        } else {
            const regPres = JSON.parse(result);

            for(let i = 0, l = regPres.length; i < l; i++) {
                const n_riga = i+1;
                const presenza = parseInt(regPres[i].Presenza);

                const radioBtnPresenza = `<div class='btn-group' data-toggle='buttons' id='radio_presenze_${n_riga}'>` +
                    `<label class='btn btn-success' id='bottone_s'><input type='radio' name='options_${n_riga}' value='1' id='1' ` + checked(1, presenza) + `>Presente</label>` +
                    `<label class='btn btn-danger' id='bottone_d'><input type='radio' name='options_${n_riga}' value='0' id='0' ` + checked(0, presenza) + `>Assente</label>` +
                    `<label class='btn btn-warning' id='bottone_w'><input type='radio' name='options_${n_riga}' value='2' id='2' ` + checked(2, presenza) + `>Ritardo</label>`+
                    "</div>";

                const rigaTab = "<tr>" +
                    `<td>${n_riga}</td>` +
                    `<td id='nome_${n_riga}'>${regPres[i].Nome}</td>` +
                    `<td id='cognome${n_riga}'>${regPres[i].Cognome}</td>` +
                    `<td id='presenza_${n_riga}'>${radioBtnPresenza}</td>` +
                    `<td id='id_iscr_${n_riga}' style='width:1px;display:none'>${regPres[i].ID_Iscrizione}</td>` +
                    "</tr>";

                $table.append(rigaTab);
            }

            $("input[checked='checked']").parent().addClass('active');
        }

        $('div#divisorio, div#listaPersone').css('display','block');
    });
}

function datiPerUpdateDB() {
    const n_righe = $('tbody#tbody_gestCorso').children().length;
    let status = [];

    for(let i = 0; i < n_righe; i++) {
        const n_riga = i + 1;
        status.push([
            parseInt($(`tbody#tbody_gestCorso td#id_iscr_${n_riga}`).html()),
            $(`input:radio[name=options_${n_riga}]:checked`).val()
        ]);
    }

    return status;
}

$(document).ready(function() {
    const $btnRegPres = $("button#btnRegistroPresenze"); //ATTENZIONE AL CONST
    const $selectCorso = $("select#scelta_corso");

    $btnRegPres.prop('disabled', true);

    //richiesta dei corsi di cui è responsabile l'utente che ha effettuato il login
    richiestaCorsi($selectCorso);

    $selectCorso.change(function() {
        if($(this).val() !== "") $btnRegPres.prop('disabled', false);
    });

    //aggiornamento della lista corsi al click del pulsante "Aggiorna la lista"
    $btnRegPres.click(function() {
        const id_sc = $selectCorso.val();
        if(id_sc !== "") {
            registroPresenzeSessioneCorso(id_sc);
        } else {
            $('div#divisorio, div#listaPersone').css('display','none');
        }
    });

    $("button#btnConferma").click(function() {
        const aggiornamenti = JSON.stringify(datiPerUpdateDB());

        $.post("/registro-presenze/script/aggiornaRegistro.php", {aggiornamenti: aggiornamenti}, function(result) {
            const statoUpdate = result.trim();
            if(statoUpdate === "registro-aggiornato") {
                $alert(
                    "Operazione completata",
                    "Il registro è stato aggiornato con successo."
                );
            } else {
                $alert(
                    "Operazione non effettuata",
                    "Ci sono stato dei problemi nell'aggiornamento del registro. Riprovare più tardi."
                );
            }
        });
    });
});
