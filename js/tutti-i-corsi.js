//funzione di aggiornamento variabili globali (giorno e ora)
function aggiornaDati(gg_hh) {
    gg_hh.giorno = $("select#scelta_giorno").val();
    gg_hh.ora = $("select#scelta_ora").val();
    return gg_hh;
}

//richiesta dei giorni di presenza dell'evento (autogestione)
function richiestaGiorni() {
    const $scelta_giorno = $("select#scelta_giorno");
    $scelta_giorno.html("");
    $.post("/tutti-i-corsi/script/getGiorni.php", function(result) {
        result = result.trim(); //ottengo i dati dal server

        if(result === "errore_db_date_evento") {
            let titolo = "Operazione non effettuata", contenuto = "Non è stato possibile reperire le date dell'evento dal database.";
            $alert(titolo, contenuto);
        } else {
            const dati = JSON.parse(result);

            for(let i = 0, l = dati.length; i < l; i++) 
                $scelta_giorno.append(`<option value='${(i+1)}' id='giorno_opt${(i+1)}'>${dati[i].Giorno}&nbsp;${dati[i].Mese}</option>`);
        }
    });
}

//richiesta delle ore di lezione in cui puoi iniziare un corso
function richiestaOre(giorno_ora) {
    const $scelta_ora = $("select#scelta_ora");
    $scelta_ora.html("");
    $.post("/tutti-i-corsi/script/getElencoOre.php", {giorno: giorno_ora.giorno}, function(result) {
        result = result.trim(); //ottengo i dati dal server

        if(result === "errore_db_elenco_ore") {
            let titolo = "Operazione non effettuata", contenuto = "Non è stato possibile reperire l'elenco delle ore della giornata selezionata.";
            $alert(titolo, contenuto);
        } else {
            const vOre = JSON.parse(result);

            for(let i = 0, l = vOre.length; i < l; i++)
                $scelta_ora.append(`<option value='${vOre[i].Ora}'>${vOre[i].Ora}°</option>`);
        }
    });
}

//metodo che scarica i dati dei corsi data una determinata ora e un determinato giorno
function aggiornaLista(gg_hh) {
    const $tbody = $("tbody#tbody");
    $tbody.html('');
    $.post("/tutti-i-corsi/script/getListaCorsi.php", {giorno: gg_hh.giorno,ora: gg_hh.ora}, function(result) {
        result = result.trim(); //ottengo i dati dal server
        if(result === "errore_db_lista_corsi") {
            $tbody.append("<tr><td>Non sono rimasti corsi disponibili.</td><td></td><td></td><td></td><td></td></tr>");
        } else {
            //non è valore booleano perchè non viene effettuato parsing
            const vCorsi = JSON.parse(result);

            for(let i = 0, l = vCorsi.length; i < l; i++) {
                //creazione e aggiunta riga alla tabella
                if(vCorsi[i].PostiRimasti > 0) { //aggiungo alla lista solo i corsi che hanno da 1 posto in su
                    $tbody.append(
                        "<tr>" +
                        `<td id='corso_${i}'>${vCorsi[i].Nome}</td>` +
                        `<td id='aula_${i}'>${vCorsi[i].Aula}</td>` +
                        `<td id='durata_${i}'>${vCorsi[i].Durata}</td>` +
                        `<td id='pTotali_${i}'>${vCorsi[i].PostiTotali}</td>` +
                        `<td id='pRimasti_${i}'>${vCorsi[i].PostiRimasti}</td>` +
                        "</tr>"
                    );

                    //controllo contenuto tabella
                    const pTotali = parseInt(vCorsi[i].PostiTotali);
                    const pRimasti = parseInt(vCorsi[i].PostiRimasti);

                    $("td#corso_"+i).parent().removeClass("warning danger"); //rimuovo classi "decorative"
                    if(pRimasti < pTotali) { //controllo posti
                        //se posti rimasti inferiori ad 1/3: riga colore rosso
                        if(pRimasti <= (pTotali/3)) $("td#corso_"+i).parent().addClass("danger");

                        //se posti rimasti inferiori alla metà ma superiori di 1/3: riga colore giallo
                        else if(pRimasti <= (pTotali/2)) $("td#corso_"+i).parent().addClass("warning");
                    }
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
});
