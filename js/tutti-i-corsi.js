//funzione di aggiornamento variabili globali (giorno e ora)
function aggiornaDati(gg_hh) {
    gg_hh.giorno=$("select#scelta_giorno").val();
    gg_hh.ora=$("select#scelta_ora").val();
    return gg_hh;
}

//richiesta dei giorni di presenza dell'evento (autogestione)
function richiestaGiorni() {
    const $scelta_giorno=$("select#scelta_giorno");
    $scelta_giorno.html('');
    $.post("/tutti-i-corsi/getGiorni.php",function(result){
        const datiDaServer=result.trim(); //ottengo i dati dal server
        if(datiDaServer !== "false") {
            const dati=$.parseJSON(datiDaServer);
            for(let i=0,l=dati.length;i<l;i++) {
                //option: contiene la option della select generata - variabile contenente il valore della giornata (formato gg)
                let option=`<option value='${(i+1)}' id='giorno_opt${(i+1)}'>${dati[i].Giorno}`+" "+`${dati[i].Mese}</option>`; //lasciare la concatenazione di stringhe perché il minify toglie lo spazio
                $scelta_giorno.append(option);
            }
        }
    });
}

//richiesta delle ore di lezione in cui puoi iniziare un corso
function richiestaOre() {
    const $scelta_ora=$("select#scelta_ora");
    $scelta_ora.html('');
    $.post("/tutti-i-corsi/getOre.php",function(result) {
        const datiDaServer=result.trim(); //ottengo i dati dal server
        if(datiDaServer !== "false") {
            const vOre=$.parseJSON(datiDaServer);
            for(let i=0,l=vOre.length;i<l;i++) {
                //option: contiene la option della select generata
                let option=`<option value='${vOre[i].Ora}'>${vOre[i].Ora}</option>`;
                $scelta_ora.append(option);
            }
        }
    });
}

//metodo che scarica i dati dei corsi data una determinata ora e un determinato giorno
function aggiornaLista(gg_hh) {
    const $tbody=$("tbody#tbody");
    $tbody.html('');
    $.post("/tutti-i-corsi/getListaCorsi.php",{giorno: gg_hh.giorno,ora: gg_hh.ora},function(result) {
            const datiDaServer=result.trim(); //ottengo i dati dal server
            if(datiDaServer !== "false") { //non è valore booleano perchè non viene effettuato parsing
                const vCorsi=$.parseJSON(datiDaServer);

                for(let i=0,l=vCorsi.length;i<l;i++) {
                    //creazione e aggiunta riga alla tabella
                    if(vCorsi[i].PostiRimasti>0) { //aggiungo alla lista solo i corsi che hanno da 1 posto in su
                        let riga="<tr>"; //inizializzo la riga
                        riga+=`<td id='corso_${i}'>${vCorsi[i].Nome}</td>`;
                        riga+=`<td id='aula_${i}'>${vCorsi[i].Aula}</td>`;
                        riga+=`<td id='durata_${i}'>${vCorsi[i].Durata}</td>`;
                        riga+=`<td id='pTotali_${i}'>${vCorsi[i].PostiTotali}</td>`;
                        riga+=`<td id='pRimasti_${i}'>${vCorsi[i].PostiRimasti}</td>`;
                        riga+="</tr>";
                        $tbody.append(riga); //aggiunta riga alla tabella

                        //controllo contenuto tabella  
                        const pTotali=parseInt($("td#pTotali_"+i).html()); //ottengo posti totali corso
                        const pRimasti=parseInt($("td#pRimasti_"+i).html()); //ottengo posti rimasti corso

                        if(pRimasti<pTotali) { //controllo posti
                            if(pRimasti<=(pTotali/2)) { //se posti rimasti inferiori alla metà: riga colore giallo
                                $("td#corso_"+i).parent().addClass("warning").removeClass("danger");
                            } else if(pRimasti<=(pTotali/3)) { //se posti rimasti inferiori a un terzo: riga colore rosso
                                $("td#corso_"+i).parent().addClass("danger").removeClass("warning");
                            }
                        } else {
                            $("td#corso_"+i).parent().removeClass("warning danger"); //rimuovo classi
                        }
                    }
                }    
            } else {
                let riga="<tr><td>Non sono rimasti corsi disponibili.</td><td></td><td></td><td></td><td></td></tr>";    
                $tbody.append(riga);
            }
        });
}
$(document).ready(function() {
    const timerUpdateLista=10000;
    var giorno_ora={
        "giorno": 1,
        "ora": 1
    }; //valori inizializzati (primo giorno, prima ora)

    //riempimento menu a tendina
    richiestaGiorni();
    richiestaOre();

    //primo download della lista dei corsi
    aggiornaLista(giorno_ora); 

    //aggiornamento della lista corsi al click del pulsante "Aggiorna la lista"
    $("button#updateBtn").click(function() {
        giorno_ora=aggiornaDati(giorno_ora);
        aggiornaLista(giorno_ora);
    }); 

    //timer per aggiornamento automatico della lista dei corsi (senza aggiornamento dei dati)
    window.setInterval(function() {
        aggiornaLista(giorno_ora);
    },timerUpdateLista); //aggiornamento della lista ogni 10 secondi
});