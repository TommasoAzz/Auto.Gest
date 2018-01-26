var giorno=1,ora=1; //valori inizializzati (primo giorno, prima ora)

//funzione di aggiornamento variabili globali (giorno e ora)
function aggiornaDati() {
    giorno=$("select#scelta_giorno").val();
    ora=$("select#scelta_ora").val();
    console.log("Ora: "+ora+" - Giorno: "+giorno);
}

//richiesta dei giorni di presenza dell'evento (autogestione)
function richiestaGiorni() {
    var $scelta_giorno=$("select#scelta_giorno");
    $scelta_giorno.html('');
    $.post("/tutti-i-corsi/getGiorni.php",function(result){
        var datiDaServer=result.trim(); //ottengo i dati dal server
        if(datiDaServer!="false") {
            var dati=$.parseJSON(datiDaServer);
            var giorni=dati[0].Giorni;
            var meseAnno=dati[0].MeseAnno;
            var nGiorni=dati[0].Durata;

            var option="", gg=""; //variabile contente la option della select generata - variabile contenente il valore della giornata (formato gg)
            var inizio=0,lunghezza=2; 
            for(var i=1;i<=nGiorni;i++) {
                gg=giorni.substr(inizio,lunghezza);
                option="<option value=\""+i+"\" id=\"giorno_opt"+i+"\">"+gg+" "+meseAnno+"</option>";
                $scelta_giorno.append(option);
                inizio+=3;
            }
        }
    });
}

//richiesta delle ore di lezione in cui puoi iniziare un corso
function richiestaOre() {
    var $scelta_ora=$("select#scelta_ora");
    $scelta_ora.html('');
    $.post("/tutti-i-corsi/getOre.php",function(result) {
        var datiDaServer=result.trim(); //ottengo i dati dal server
        if(datiDaServer!="false") {
            var vOre=$.parseJSON(datiDaServer);
            var option=""; //variabile contente la option della select generata
            for(var i=0;i<vOre.length;i++) {
                option="<option value=\""+vOre[i].Ora+"\">"+vOre[i].Ora+"</option>";
                $scelta_ora.append(option);
            }
        }
    });
}

//metodo che scarica i dati dei corsi data una determinata ora e un determinato giorno
function aggiornaLista() {
    var $tbody=$("tbody#tbody");
    $tbody.html('');
    $.post("/tutti-i-corsi/getListaCorsi.php",{giorno: giorno,ora: ora},function(result) {
            var datiDaServer=result.trim(); //ottengo i dati dal server
            var riga=""; //inizializzo la riga
            if(datiDaServer!="false") { //non è valore booleano perchè non viene effettuato parsing
                var vCorsi=$.parseJSON(datiDaServer);

                var pTotali=0,pRimasti=0;
                for(var i=0;i<vCorsi.length;i++) {
                    //creazione e aggiunta riga alla tabella
                    if(vCorsi[i].PostiRimasti>0) { //aggiungo alla lista solo i corsi che hanno da 1 posto in su
                        riga="<tr>";
                        riga+="<td id=\"corso_"+i+"\">"+vCorsi[i].Nome+"</td>";
                        riga+="<td id=\"aula_"+i+"\">"+vCorsi[i].Aula+"</td>";
                        riga+="<td id=\"durata_"+i+"\">"+vCorsi[i].Durata+"</td>";
                        riga+="<td id=\"pTotali_"+i+"\">"+vCorsi[i].PostiTotali+"</td>";
                        riga+="<td id=\"pRimasti_"+i+"\">"+vCorsi[i].PostiRimasti+"</td>";
                        riga+="</tr>";
                        $tbody.append(riga); //aggiunta riga alla tabella

                        //controllo contenuto tabella  
                        pTotali=parseInt($("td#pTotali_"+i).html()); //ottengo posti totali corso
                        pRimasti=parseInt($("td#pRimasti_"+i).html()); //ottengo posti rimasti corso

                        if(pRimasti<pTotali) { //controllo posti
                            if(pRimasti<=(pTotali/2)) { //se posti rimasti inferiori alla metà: riga colore giallo
                                $("td#corso_"+i).parent().addClass("warning").removeClass("danger");
                            }
                            if(pRimasti<=(pTotali/3)) { //se posti rimasti inferiori a un terzo: riga colore rosso
                                $("td#corso_"+i).parent().addClass("danger").removeClass("warning");
                            }
                        } else {
                            $("td#corso_"+i).parent().removeClass("warning danger"); //rimuovo classi
                        }
                    }
                }    
            } else {
                riga="<tr><td>Non sono rimasti corsi disponibili.</td><td></td><td></td><td></td><td></td></tr>";    
                $tbody.append(riga);
            }
        });
}
$(document).ready(function() {
    
    //riempimento menu a tendina
    richiestaGiorni();
    richiestaOre();

    //primo download della lista dei corsi
    aggiornaLista(); 

    //aggiornamento della lista corsi al click del pulsante "Aggiorna la lista"
    $("button#updateBtn").click(function() {
        aggiornaDati();
        aggiornaLista();
    }); 

    //timer per aggiornamento automatico della lista dei corsi (senza aggiornamento dei dati)
    window.setInterval(function() {
        aggiornaLista();
    },10000); //aggiornamento della lista ogni 10 secondi
});