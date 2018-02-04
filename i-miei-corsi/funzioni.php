<?php
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
    
    //Restituisce il mese
    function getMese($db,$i) {
        //query al db
        $q="SELECT Mese FROM DateEvento";
        $aMese=$db->qikQuery($q);
        //controllo che il db restituisca qualcosa
        if($aMese!==false) {
            return $aMese[$i]["Mese"];
        } else {
            return "errore-query-mese";
        }
    }

    //Restituisce il giorno i=0 -> "primo giorno"
    function getGiorno($db,$i) {
        //query al db
        $q="SELECT Giorno FROM DateEvento";
        $aGiorni=$db->qikQuery($q);
        //controllo che il db restituisca qualcosa
        if($aGiorni!==false) {
            return $aGiorni[$i]["Giorno"];    
        } else {
            return "errore-query-giorno";
        }
    }

    //restituisce un array, con tutti i corsi in cui l'utente si è iscritto in quel giorno
    function getCorsiGiorno($db,$utente,$giorno) { //capire da dove prendere giorno
        $q = "SELECT Nome, Aula, Ora, Durata FROM SessioniCorsi S INNER JOIN Corsi C ON C.ID_Corso=S.ID_Corso INNER JOIN Iscrizioni I ON S.ID_SessioneCorso=I.ID_SessioneCorso WHERE ID_Studente=".$utente->getId()." AND Giorno=$giorno ORDER BY Ora";
        $aCorso=$db->qikquery($q);
        return $aCorso;
    }
    
    //Crea l'HTML da aggiungere alla tabella dell'elenco dei corsi
    function getListaCorsi($db,$utente,$i) {
        $giorno = $i + 1;
        $aCorsi = getCorsiGiorno($db,$utente,$giorno); //restituisce un array, con tutti i corsi in cui l'utente si è iscritto in quel giorno
        $corsoInTab="";
        for($i=0,$l=sizeof($aCorsi);$i<$l;$i++) {
            $corsoInTab.="<tr>";
            $corsoInTab.="<td>".$aCorsi[$i]["Ora"]."°</td>";
            $corsoInTab.="<td>".$aCorsi[$i]["Nome"]."</td>";
            $corsoInTab.="<td>".$aCorsi[$i]["Durata"]." ore</td>";
            $corsoInTab.="<td>".$aCorsi[$i]["Aula"]."</td>";
            $corsoInTab.="</tr>";
        }
        return $corsoInTab;
    }
    
    //Stampa la riga del giorno riferito ai dati sottostanti
    function stampaGiorno($db,$i) {
        $mese = getMese($db,$i);
        $panelGiorno="<div class='panel-heading'>";
        $panelGiorno.="<h2 class='panel-title'>";
        $panelGiorno.="<strong>Giorno</strong>: ".getGiorno($db,$i)." ".$mese;
        $panelGiorno.="</h2></div>";
        return $panelGiorno;
    }
    
    //Crea la tabella dei corsi in cui si è iscritto l'utente a seconda del giorno
    function creaTabella($db,$utente,$giorno) {
        $tabella="<div class='panel-body'>";
        $tabella.="<div class='table-responsive'>";
        $tabella.="<table class='table table-hover'>";
        $tabella.="<thead><tr>";
        $tabella.="<th><strong>Ora</strong></th><th><strong>Corso</strong></th><th><strong>Durata</strong></th><th><strong>Aula</strong></th>";
        $tabella.="</tr></thead>";
        $tabella.="<tbody>".getListaCorsi($db,$utente,$giorno);
        $tabella.="</tbody></table>";
        $tabella.="</div></div>";
        return $tabella;        
    }

    //Creazione tabella Corsi dell'utente
    function creazioneTabella($db,$utente) {
        $nGiorni=getNumGiorni($db);
        //Stampa tabella, fino al numero massimo di giorni restituito dalla funzione getNumGiorni()
        $panels="";
        for($i=0;$i<$nGiorni;$i++) {
            $panels.="<div class='panel panel-default'>";
            $panels.=stampaGiorno($db,$i);
            $panels.=creaTabella($db,$utente,$i);
            $panels.="</div>";
        }
        return $panels;
    }
?>