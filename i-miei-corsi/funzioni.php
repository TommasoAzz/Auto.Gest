<?php
//richiesta del numero dei giorni di evento
function getNumGiorni($db) {
    //query al db
    $numGiorni=$db->queryDB("SELECT COUNT(ID_DataEvento) AS Giorni FROM DateEvento"); //ritornato un array

    //operazioni da eseguire se il db ha restituito qualcosa
    if(!$numGiorni) return "errore_db_giorni";
    
    return $numGiorni[0]["Giorni"];
}

//Restituisce il mese
function getMese($db,$i) {
    //query al db
    $vMese=$db->queryDB("SELECT Mese FROM DateEvento");

    //controllo che il db restituisca qualcosa
    if(!$vMese) return "errore_db_mese";
    
    return $vMese[$i]["Mese"];
}

//Restituisce il giorno i=0 -> "primo giorno"
function getGiorno($db,$i) {
    //query al db
    $vGiorni=$db->queryDB("SELECT Giorno FROM DateEvento");

    //controllo che il db restituisca qualcosa
    if(!$vGiorni) return "errore_db_giorno";
    
    return $vGiorni[$i]["Giorno"];
}

//restituisce un array, con tutti i corsi in cui l'utente si è iscritto in quel giorno
function getCorsiGiorno($db,$utente,$giorno) { //capire da dove prendere giorno
    //query al db
    $q = "SELECT Nome, Aula, Ora, Durata FROM SessioniCorsi S INNER JOIN Corsi C ON C.ID_Corso=S.ID_Corso INNER JOIN Iscrizioni I ON S.ID_SessioneCorso=I.ID_SessioneCorso WHERE ID_Studente=".$utente->getId()." AND Giorno=$giorno ORDER BY Ora";
    
    $vCorsi=$db->queryDB($q);

    if(!$vCorsi) return "errore_db_corsi_iscritti_giorno";

    return $vCorsi;
}

//Crea l'HTML da aggiungere alla tabella dell'elenco dei corsi
function getListaCorsi($db,$utente,$i) {
    $giorno = $i + 1;
    $vCorsi = getCorsiGiorno($db,$utente,$giorno); //restituisce un array, con tutti i corsi in cui l'utente si è iscritto in quel giorno

    if($vCorsi === "errore_db_corsi_iscritti") return "errore_db_lista_corsi";

    $corsoInTab="";
    for($i=0,$l=sizeof($vCorsi);$i<$l;$i++) {
        $corsoInTab.="<tr>";
        $corsoInTab.="<td>".$vCorsi[$i]["Ora"]."°</td>";
        $corsoInTab.="<td>".$vCorsi[$i]["Nome"]."</td>";
        $corsoInTab.="<td>".$vCorsi[$i]["Durata"]." ore</td>";
        $corsoInTab.="<td>".$vCorsi[$i]["Aula"]."</td>";
        $corsoInTab.="</tr>";
    }

    return $corsoInTab;
}

//Stampa la riga del giorno riferito ai dati sottostanti
function stampaGiorno($db,$i) {
    $giorno = getGiorno($db,$i);
    $mese = getMese($db,$i);

    if($mese === "errore_db_mese") $mese = "Err. mese";
    if($giorno === "errore_db_giorno") $giorno = "Err. giorno";

    $panelGiorno="<div class='panel-heading'>";
    $panelGiorno.="<h2 class='panel-title'>";
    $panelGiorno.="<strong>Giorno</strong>: ".$giorno." ".$mese;
    $panelGiorno.="</h2></div>";

    return $panelGiorno;
}

//Crea la tabella dei corsi in cui si è iscritto l'utente a seconda del giorno
function creaTabella($db,$utente,$giorno) {
    $lista = getListaCorsi($db,$utente,$giorno);

    if($lista === "errore_db_lista_corsi") {
        $lista = "<tr><td>Err. lista corsi</td><td></td><td></td><td></td></tr>";
    }

    $tabella="<div class='panel-body'>";
    $tabella.="<div class='table-responsive'>";
    $tabella.="<table class='table table-hover'>";
    $tabella.="<thead><tr>";
    $tabella.="<th><strong>Ora</strong></th><th><strong>Corso</strong></th><th><strong>Durata</strong></th><th><strong>Aula</strong></th>";
    $tabella.="</tr></thead>";
    $tabella.="<tbody>".$lista;
    $tabella.="</tbody></table>";
    $tabella.="</div></div>";

    return $tabella;        
}

//Creazione tabella Corsi dell'utente
function creazioneTabella($db,$utente) {
    $nGiorni=getNumGiorni($db);

    $panels="";

    if($nGiorni === "errore_db_giorni") {
        $panels.="<div class='panel panel-default'>";
        $panels.="<div class='panel-heading'><h2 class='panel-title'>Err. giorni</h2></div>";
        $panels.="<div class='panel-body'><p class='error'>Err.lista corsi</p></div>";
        $panels.="</div>"; 
        return $panels;   
    }
    //Stampa tabella, fino al numero massimo di giorni restituito dalla funzione getNumGiorni()
    
    for($i=0;$i<$nGiorni;$i++) {
        $panels.="<div class='panel panel-default'>";
        $panels.=stampaGiorno($db,$i);
        $panels.=creaTabella($db,$utente,$i);
        $panels.="</div>";
    }

    return $panels;
}
?>