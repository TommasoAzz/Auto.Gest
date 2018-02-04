<?php
//imposta il titolo alla pagina
function getTitolo($db,$nGiorno) {
    $pos=$nGiorno-1;
    //query al db
    $q="SELECT Giorno,Mese FROM DateEvento";
    $datiEvento=$db->qikQuery($q);
    //controllo che il db restituisca qualcosa
    if($datiEvento!==false) { //se ci sono errori probabilemente sono in questo controllo
        //imposto la variabile contenente il valore del titolo
        $sottotitolo=$datiEvento[$pos]["Giorno"]." ".$datiEvento[$pos]["Mese"];
        return $sottotitolo;
    }
}

//richiesta del numero dei giorni di evento
function getNumGiorni($db) {
    //query al db
    $numGiorni=$db->qikQuery("SELECT COUNT(ID_DataEvento) AS Giorni FROM DateEvento"); //ritornato un array
    //operazioni da eseguire se il db ha restituito qualcosa
    if($numGiorni!==false) {
        return $numGiorni[0]["Giorni"];
    } else {
        return "errore-query-giorni";
    }
}

//richiesta dell'ultima ora in cui inizia un corso
function getNumOre($db,$nGiorno) {
    //query al db
	$numOre=$db->qikQuery("SELECT MAX(Ora) AS Ore FROM SessioniCorsi WHERE Giorno=$nGiorno"); //ritornato un array
    //controllo che il db restituisca qualcosa
	if($numOre!==false) {
        return $numOre[0]["Ore"];
    } else {
		return "errore-query-ore";
	}
}

//richiesta del giorno in cui l'utente deve iscriversi
function getGiornoDaIscriversi($db,$utente) {
    $numGiorni=getNumGiorni($db); //reperisco il numero dei giorni di evneto
    if($numGiorni != "errore-query-giorni") {
        $iscrivi_giorno=$numGiorni; //assegno a $iscrivi_giorno il valore di $numGiorni
        //se $iscrivi_giorno risulterà maggiore di $numGiorni nel prossimo controllo significa che l'utente ha finito di iscriversi
        for($i=0;$i<=$numGiorni;$i++) {
            if($i==$utente->getGiornoIscritto()) { //se $i equivale al valore di GiornoIscritto della Persona indica che è iscritto fino al giorno di valore $i
                $iscrivi_giorno=($i+1); //assegno a $iscrivi_giorno il valore di $i incrementato, sta a indicare il giorno in cui la persona si deve iscrivere
            }
        }
        if($iscrivi_giorno>$numGiorni) {
            return "fine-iscrizione";
        } else {
            return $iscrivi_giorno;
        }
    } else {
        return "errore-reperimento-giorno";
    }
}

//richiesta dell'ora in cui l'utente deve iscriversi
function getOraDaIscriversi($db,$utente,$nGiorno) {
    $numOre=getNumOre($db,$nGiorno); //reperisco l'ultima ora della giornata
    if($numOre != "errore-query-ore") {
        $iscrivi_ora=$numOre; //assegno a $iscrivi_ora il valore di $maxOra
        //se $iscrivi_ora risulterà maggiore di $numOre nel prossimo controllo significa che l'utente ha finito di iscriversi nella giornata
        for($i=0;$i<=$numOre;$i++) {
            if($i==$utente->getOraIscritta()) { //quando $i equivale al valore di OraIscritta della Persona indica che è iscritto fino a quell'ora
                $iscrivi_ora=($i+1); //assegno a $iscrivi_ora il valore di $i incrementato, sta a indicare l'ora a cui si deve iscrivere la persona
            }
        }
        if($iscrivi_ora>$numOre) {
            return "cambio-giorno"; //lascio il return per non incorrere in problemi - teoricamente non serve
        } else {
            return $iscrivi_ora;
        }
    } else {
        return "errore-reperimento-ore";
    }
}

//richiesta dei corsi a cui è possibile iscriversi nel giorno e ora selezionati
function getListaCorsi($db,$utente,$nGiorno,$nOra) {
	//$nGiorno=getGiornoDaIscriversi($db,$utente);
	//$nOra=getOraDaIscriversi($db,$utente,$nGiorno);
	$query="SELECT Nome,Durata,Ora FROM Corsi C INNER JOIN SessioniCorsi S ON C.ID_Corso=S.ID_Corso WHERE Giorno=$nGiorno AND Ora=$nOra AND PostiRimasti>0";
	$res=$db->qikQuery($query);	 
    return $res;
}

//crea select con numero ora nei campi id e appende select alla pagina
function creazioneBloccoIscrizione($db,$utente,$nGiorno,$nOra) {
    $vCorsi=getListaCorsi($db,$utente,$nGiorno,$nOra);
    $blocco="<div class='panel panel-default'><div class='panel-heading'><h2 class='panel-title'><span class='fa fa-clock-o'></span>&nbsp;&nbsp;".$nOra."° ora</h2></div>";
    $blocco.="<div class='panel-body'>";
    $blocco.="<div class='input-group input-group-lg'><select class='form-control' id='corso' name='corso'>";
    $blocco.="<option value=''></option>";
    for($i=0,$l=sizeof($vCorsi);$i<$l;$i++) {
        $corso=$vCorsi[$i];
        $tempo=intval($corso["Durata"]);
        
        if($tempo == 1) $stringCorso=$corso['Nome']." - ".$tempo." ora";
        else $stringCorso=$corso['Nome']." - ".$tempo." ore";

        $stringCorsoVal=$corso['Nome']."_".$corso['Ora'];
        $blocco.='<option value="'.$stringCorsoVal.'">'.$stringCorso.'</option>';
    }
    $blocco.="</select>";
    $blocco.="<div class='input-group-btn'><button type='submit' id='btnProsegui' class='btn btn-success'><span class='fa fa-check'></span><span class='hidden-xs hidden-sm'>&nbsp;&nbsp;Prosegui</span></button></div>";
    $blocco.="</div></div></div>";
    echo $blocco;
}
?>