//funzione di aggiornamento variabili globali (giorno e ora)
function aggiornaDati(gg_hh) {
    gg_hh.giorno = parseInt($("select#scelta_giorno").val());
    gg_hh.ora = parseInt($("select#scelta_ora").val());
    return gg_hh;
}

//richiesta dei giorni di presenza dell'evento (autogestione)
function richiestaGiorni() {
    const $scelta_giorno = $("select#scelta_giorno");
    $scelta_giorno.html("");

    $.post("/tutti-i-corsi/script/getGiorni.php", function(result) {
        result = result.trim(); //ottengo i dati dal server

        if(result === "errore_db_date_evento") {
            $alert(
                "Operazione non effettuata",
                "Non è stato possibile reperire le date dell'evento dal database."
            );
        } else {
            const date = JSON.parse(result);

            for(let i = 0, l = date.length; i < l; i++)
                $scelta_giorno.append(`<option value='${(i+1)}' id='giorno_opt${(i+1)}'>${date[i].Giorno}&nbsp;${date[i].Mese}</option>`);
        }
    });
}

//richiesta delle ore di lezione in cui puoi iniziare un corso
function richiestaOre(gg_hh) {
    const $scelta_ora = $("select#scelta_ora");
    $scelta_ora.html("");

    $.post("/tutti-i-corsi/script/getElencoOre.php", {giorno: gg_hh.giorno}, function(result) {
        result = result.trim(); //ottengo i dati dal server

        if(result === "errore_db_elenco_ore") {
            $alert(
                "Operazione non effettuata",
                "Non è stato possibile reperire l'elenco delle ore della giornata selezionata."
            );
        } else {
            const ore = JSON.parse(result);

            for(let i = 0, l = ore.length; i < l; i++)
                $scelta_ora.append(`<option value='${ore[i].Ora}'>${ore[i].Ora}°</option>`);
        }
    });
}

//metodo che scarica i dati dei corsi data una determinata ora e un determinato giorno
function aggiornaLista(gg_hh) {
    const $tbody = $("tbody#tbody");
    $tbody.html("");

    $.post("/tutti-i-corsi/script/getListaCorsi.php", {giorno: gg_hh.giorno, ora: gg_hh.ora}, function(result) {
        result = result.trim(); //ottengo i dati dal server

        if(result === "errore_db_lista_corsi" || result === "errore_db_sessione_corso") {
            $tbody.append("<tr><td>Non sono rimasti corsi disponibili.</td><td></td><td></td><td></td><td></td></tr>");
        } else {
            const corsi = JSON.parse(result);

            for(let i = 0, l = corsi.length; i < l; i++) {
                //console.log(corsi[i].Informazioni);
                $tbody.append(
                    "<tr>" +
                    `<td id='corso_${i}'>`+
                    ((corsi[i].Informazioni !== null) ? 
                        `<a class="btn btn-primary btn-xs showInfo" data-info="${corsi[i].Informazioni}"><i class="fa fa-info"></i></a>` :
                         ``) +
                    `&nbsp;${corsi[i].Nome}</td>` +
                    `<td id='aula_${i}'>${corsi[i].Aula}</td>` +
                    `<td id='durata_${i}'>${corsi[i].Durata}</td>` +
                    `<td id='pTotali_${i}'>${corsi[i].PostiTotali}</td>` +
                    `<td id='pRimasti_${i}'>${corsi[i].PostiRimasti}</td>` +
                    "</tr>"
                );
    
                const $tdCorso = $("td#corso_" + i); // !!!
                $tdCorso.parent().removeClass("warning danger"); //rimuovo classi "decorative"

                if (corsi[i].PostiRimasti < corsi[i].PostiTotali) { //controllo posti
                    //se posti rimasti inferiori ad 1/3: riga colore rosso
                    if (corsi[i].PostiRimasti <= (corsi[i].PostiTotali / 3)) $tdCorso.parent().addClass("danger");

                    //se posti rimasti inferiori alla metà ma superiori di 1/3: riga colore giallo
                    else if (corsi[i].PostiRimasti <= (corsi[i].PostiTotali / 2)) $tdCorso.parent().addClass("warning");
                }
            }
        }
    });
}

$(document).ready(function() {
    //const timerUpdateLista=10000;
    let giorno_ora = {
        "giorno": 1,
        "ora": 1
    }; //valori inizializzati (primo giorno, prima ora)

    //riempimento menu a tendina
    richiestaGiorni();
    richiestaOre(giorno_ora);

    //primo download della lista dei corsi
    aggiornaLista(giorno_ora);

    //aggiornamento della lista corsi al click del pulsante "Aggiorna la lista"
    $("button#updateBtn").click(function() {
        giorno_ora = aggiornaDati(giorno_ora);
        aggiornaLista(giorno_ora);
    });

    /*$("a.showInfo").click(function() {
        console.log('ddd');
        console.log($(this).html());
    });*/
});
