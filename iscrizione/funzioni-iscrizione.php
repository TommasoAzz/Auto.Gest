<?php
//imposta il titolo alla pagina
function getSottotitolo($db,$nGiorno) {
    //query al db
    $q="SELECT Giorno,Mese FROM DateEvento";
    $datiEvento=$db->queryDB($q);

    //operazione da eseguire se il db non ha restituito valori
    if(!$datiEvento) return "errore_db_sottotitolo";

    //operazione da eseguire se il db ha restituito le informazione per il sottotitolo
    $pos=$nGiorno-1; //creo l'indice per leggere correttamente l'array risultato dal db
    $sottotitolo=$datiEvento[$pos]["Giorno"]." ".$datiEvento[$pos]["Mese"]; //imposto la variabile contenente il valore del titolo
    return $sottotitolo;
}

//richiesta del numero dei giorni di evento
function getNumGiorni($db) {
    //query al db
    $numGiorni=$db->queryDB("SELECT COUNT(ID_DataEvento) AS Giorni FROM DateEvento"); //ritornato un array

    //operazione da eseguire se il db non ha restituito valori
    if(!$numGiorni) return "errore_db_giorni";
    
    //operazione da eseguire se il db ha restituito il numero dei giorni
    return intval($numGiorni[0]["Giorni"]);
}

//richiesta dell'ultima ora in cui inizia un corso del giorno passato per parametro
function getNumOre($db,$nGiorno) {
    //query al db
    $numOre=$db->queryDB("SELECT MAX(Ora) AS Ore FROM SessioniCorsi WHERE Giorno=$nGiorno"); //ritornato un array
    
    //operazione da eseguire se il db non ha restituito valori
	if(!$numOre) return "errore_db_ore";
    
    //operazione da eseguire se il db ha restituito il numero delle ore del giorno
    return intval($numOre[0]["Ore"]); 
}

//richiesta del giorno in cui l'utente deve iscriversi
function getGiornoDaIscriversi($db,$utente) {
    $numGiorni=getNumGiorni($db); //reperisco il numero dei giorni di evento
    
    //operazione da eseguire se getNumGiorni($db) non e' riuscito a reperire il numero di giorni
    if($numGiorni === "errore_db_giorni") return "errore_db_giorno_iscrizione";
    
    $iscrivi_giorno=$numGiorni; //assegno a $iscrivi_giorno il valore di $numGiorni per confrontarlo successivamente con $numGiorni che è costante (mentre $iscrivi_giorno varia)
    
    //se $iscrivi_giorno risulterà maggiore di $numGiorni nel prossimo controllo significa che l'utente ha finito di iscriversi
    for($i=0;$i<=$numGiorni;$i++) {
        if($i == $utente->getGiornoIscritto()) { //se $i equivale al valore di GiornoIscritto della Persona indica che è iscritto fino al giorno di valore $i
            $iscrivi_giorno=$i+1; //assegno a $iscrivi_giorno il valore di $i incrementato, sta a indicare il giorno in cui la persona si deve iscrivere
        }
    }

    if($iscrivi_giorno > $numGiorni) return "fine_iscrizione";

    return $iscrivi_giorno;
}

//richiesta dell'ora in cui l'utente deve iscriversi
function getOraDaIscriversi($db,$utente,$nGiorno) {
    $numOre=getNumOre($db,$nGiorno); //reperisco il numero delle ore della giornata $nGiorno

    //operazione da eseguire se getNumOre() non è riuscito a reperire il numero di ore
    if($numOre === "errore_db_ore") return "errore_db_ora_iscrizione";

    /*
    $iscrivi_ora=$numOre; //assegno a $iscrivi_ora il valore di $maxOra

    //se $iscrivi_ora risulterà maggiore di $numOre nel prossimo controllo significa che l'utente ha finito di iscriversi nella giornata
    for($i=0;$i<=$numOre;$i++) {
        if($i==$utente->getOraIscritta()) { //quando $i equivale al valore di OraIscritta della Persona indica che è iscritto fino a quell'ora
            $iscrivi_ora=$i+1; //assegno a $iscrivi_ora il valore di $i incrementato, sta a indicare l'ora a cui si deve iscrivere la persona
        }
    }

    if($iscrivi_ora > $numOre) return "cambio_giorno";
    
    */
    return /*$iscrivi_ora*/$numOre;
}

//richiesta dei corsi a cui è possibile iscriversi nel giorno e ora selezionati
function getListaCorsi($db,$utente,$nGiorno,$nOra) {
    //query al db
	$query="SELECT Nome,Durata,Ora FROM Corsi C INNER JOIN SessioniCorsi S ON C.ID_Corso=S.ID_Corso WHERE Giorno=$nGiorno AND Ora=$nOra AND PostiRimasti>0";
    $lista_corsi=$db->queryDB($query);	
    
    //operazione da eseguire se il db non ha restituito valori
    if(!$lista_corsi) return "errore_db_lista_corsi";

    //operazione da eseguire se il db ha restituito la lista delle sessioni dei corsi disponibili in quella ora e giorno
    return $lista_corsi;
}

//richiesta delle attività fuori dalla lista dei corsi a cui alcuni studenti devono partecipare
function getAltreAttivita($db) {
    //query al db
    $lista_aa=$db->queryDB("SELECT Lista FROM AltreAttivita WHERE ID=1");
    $flag_esistenza=$db->queryDB("SELECT COUNT(*) AS Esiste FROM Corsi WHERE Nome='Altre attività'");
    
    //operazione da eseguire se il db non ha restituito valori
    if(!$lista_aa || !$flag_esistenza) return "errore_altre_attivita";
    
    //operazione da eseguire se il db ha restituito qualcosa
    if($flag_esistenza[0]["Esiste"] === 1) {
        $lista_aa=trim($lista_aa[0]["Lista"]);

        if($lista_aa !== "") return $lista_aa;
    }
    
    return "no_altre_attivita";

}
//crea select con numero ora nei campi id e appende select alla pagina
function creazioneBloccoIscrizione($db,$utente,$nGiorno,$nOra) {
    $vCorsi=getListaCorsi($db,$utente,$nGiorno,$nOra); //reperisco la lista dei corsi

    $blocco="<div class='panel panel-default'><div class='panel-heading'><h2 class='panel-title'><span class='fa fa-clock-o'></span>&nbsp;&nbsp;".$nOra."° ora</h2></div>";
    $blocco.="<div class='panel-body'>";
    $blocco.="<div class='input-group input-group-lg'><select class='form-control' id='corso' name='corso'>";
    $blocco.="<option value=''></option>";
    for($i=0,$l=sizeof($vCorsi);$i<$l;$i++) {
        //recupero l'istanza i-esima dell'array dei corsi
        $corso=$vCorsi[$i];
        $durata=intval($corso["Durata"]);
        
        if($durata == 1) $stringCorso=$corso['Nome']." - ".$durata." ora";
        else $stringCorso=$corso['Nome']." - ".$durata." ore";

        $stringCorsoVal=$corso['Nome']."_".$corso['Ora'];
        $blocco.='<option value="'.$stringCorsoVal.'">'.$stringCorso.'</option>';
    }
    $blocco.="</select>";
    $blocco.="<div class='input-group-btn'><button type='submit' id='btnProsegui' class='btn btn-success'><span class='fa fa-check'></span><span class='hidden-xs hidden-sm'>&nbsp;&nbsp;Prosegui</span></button></div>";
    $blocco.="</div></div></div>";
    echo $blocco;
}
?>