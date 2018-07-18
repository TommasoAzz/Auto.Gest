<?php

/*************************************************************************************************/
/*                                                                                               */
/*                                          funzioni.php                                         */
/*  Questo file contiene tutte le funzioni che sono necessarie ai vari file per funzionare.      */
/*  Le funzioni saranno divise in sezioni. A ogni pagina di Auto.Gest corrisponde una sezione.   */
/*  Esiste poi la sezione "Tutte" contenente funzioni utili a tutte o più pagine/sezioni.        */
/*                                                                                               */
/*  In ordine, le sezioni sono:                                                                  */
/*   - Tutte                                                                                     */
/*   - Accesso                                                                                   */
/*   - Amministrazione                                                                           */
/*   - I miei corsi                                                                              */
/*   - Iscrizione                                                                                */
/*   - Registro presenze                                                                         */
/*                                                                                               */
/*************************************************************************************************/

/*************************************************************************************************/
/*                                          SEZIONE: Tutte                                       */
/*************************************************************************************************/

function getURL($pagina) { //recupero dell'URL della pagina per creare link assoluti
    //URL BASE 
    if((isset($_SERVER["HTTPS"]) && GlobalVar::getServer("HTTPS") == "on") || Session::is_secure()) {
        $base_url="https://";
    } else {
        $base_url='http://';
    }
    if(GlobalVar::getServer("SERVER_PORT") != "80") {
        $base_url.=GlobalVar::getServer("SERVER_NAME").":".GlobalVar::getServer("SERVER_PORT");
    } else {
        $base_url.=GlobalVar::getServer("SERVER_NAME");
    }

    //RITORNO URL BASE+URL PAGINA
    return $base_url.$pagina;
}

function controlloAccesso($db,$utente,$livelliAmmessi) {
    //se il controllo risulta verificato allora $db o $utente non sono settate (oppure entrambe), quindi torno alla homepage
    if(!isset($db) || !isset($utente)) header("Location: /");

    //$utente è settato, bisogna controllare il caso in cui lui non possa accedere a questa pagina e quindi deve essere rimandato alla homepage
    if(!$livelliAmmessi[$utente->getLivello()]) header("Location: /");
} //controlla se l'utente connesso può accedere alla pagina attuale

function getNumGiorni($db) {
    //query al db
    $numGiorni=$db->queryDB("SELECT COUNT(ID_DataEvento) AS Giorni FROM DateEvento"); //ritornato un array

    //operazioni da eseguire se il db ha restituito qualcosa
    if(!$numGiorni) return "errore_db_giorni";
    
    return $numGiorni[0]["Giorni"];
} //richiesta del numero dei giorni di evento

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

} //richiesta delle attività fuori dalla lista dei corsi a cui alcuni studenti devono partecipare


/*************************************************************************************************/
/*                                          SEZIONE: Accesso                                     */
/*************************************************************************************************/

function login($db,$utente,$cla,$sez,$ind,$postPass) {
    $query="SELECT ID_Persona FROM Persone P INNER JOIN Classi C ON P.ID_Classe=C.ID_Classe "; //lasciare spazio dopo ID_Classe
    $query.="WHERE Classe='".$cla."' AND Sezione='".$sez."' AND Indirizzo='".$ind."' AND Pwd='".$postPass."'";
    $result_id=$db->queryDB($query);
    if(!$result_id) return "errore-generico";

    $id=intval($result_id[0]["ID_Persona"]);
    $user_init=$utente->initUser($db,$id);
    if($user_init) {
        Session::set("utente",$utente);
        //controllo del login
        $browser=GlobalVar::getServer("HTTP_USER_AGENT"); //browser in uso
        Session::set("ID_Persona",$id);
        Session::set("login",hash('sha512',$postPass.$browser));
        return "utente-esistente";
    } else {
        return "password-errata";
    }
}


/*************************************************************************************************/
/*                                      SEZIONE: Amministrazione                                 */
/*************************************************************************************************/


/*************************************************************************************************/
/*                                      SEZIONE: I miei corsi                                    */
/*************************************************************************************************/

function getMese($db,$i) {
    //query al db
    $vMese=$db->queryDB("SELECT Mese FROM DateEvento");

    //controllo che il db restituisca qualcosa
    if(!$vMese) return "errore_db_mese";
    
    return $vMese[$i]["Mese"];
} //Restituisce il mese


function getGiorno($db,$i) {
    //query al db
    $vGiorni=$db->queryDB("SELECT Giorno FROM DateEvento");

    //controllo che il db restituisca qualcosa
    if(!$vGiorni) return "errore_db_giorno";
    
    return $vGiorni[$i]["Giorno"];
} //Restituisce il giorno i=0 -> "primo giorno"

function getCorsiGiorno($db,$utente,$giorno) {
    //query al db
    $q = "SELECT Nome, Aula, Ora, Durata FROM SessioniCorsi S INNER JOIN Corsi C ON C.ID_Corso=S.ID_Corso INNER JOIN Iscrizioni I ON S.ID_SessioneCorso=I.ID_SessioneCorso WHERE ID_Studente=".$utente->getId()." AND Giorno=$giorno ORDER BY Ora";
    
    $vCorsi=$db->queryDB($q);

    if(!$vCorsi) return "errore_db_corsi_iscritti_giorno";

    return $vCorsi;
} //restituisce un array, con tutti i corsi in cui l'utente si è iscritto in quel giorno

function getRigheCorsi($db,$utente,$i) {
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
} //Crea l'HTML da aggiungere alla tabella dell'elenco dei corsi

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
} //Stampa la riga del giorno riferito ai dati sottostanti

function creaTabella($db,$utente,$giorno) {
    $lista = getRigheCorsi($db,$utente,$giorno);

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
} //Crea la tabella dei corsi in cui si è iscritto l'utente a seconda del giorno

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
} //Creazione tabella Corsi dell'utente


/*************************************************************************************************/
/*                                      SEZIONE: Iscrizione                                      */
/*************************************************************************************************/

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
} //imposta il titolo alla pagina

function getNumOre($db,$nGiorno) {
    //query al db
    $numOre=$db->queryDB("SELECT MAX(Ora) AS Ore FROM SessioniCorsi WHERE Giorno=$nGiorno"); //ritornato un array
    
    //operazione da eseguire se il db non ha restituito valori
	if(!$numOre) return "errore_db_ore";
    
    //operazione da eseguire se il db ha restituito il numero delle ore del giorno
    return intval($numOre[0]["Ore"]); 
} //richiesta dell'ultima ora in cui inizia un corso del giorno passato per parametro

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
} //richiesta del giorno in cui l'utente deve iscriversi

function getOraDaIscriversi($db,$utente,$nGiorno) {
    $numOre=getNumOre($db,$nGiorno); //reperisco il numero delle ore della giornata $nGiorno

    //operazione da eseguire se getNumOre() non è riuscito a reperire il numero di ore
    if($numOre === "errore_db_ore") return "errore_db_ora_iscrizione";

    
    $iscrivi_ora=$numOre; //assegno a $iscrivi_ora il valore di $maxOra

    //se $iscrivi_ora risulterà maggiore di $numOre nel prossimo controllo significa che l'utente ha finito di iscriversi nella giornata
    for($i=0;$i<=$numOre;$i++) {
        if($i==$utente->getOraIscritta()) { //quando $i equivale al valore di OraIscritta della Persona indica che è iscritto fino a quell'ora
            $iscrivi_ora=$i+1; //assegno a $iscrivi_ora il valore di $i incrementato, sta a indicare l'ora a cui si deve iscrivere la persona
        }
    }

    //if($iscrivi_ora > $numOre) return "cambio_giorno";
    
    
    return $iscrivi_ora;
} //richiesta dell'ora in cui l'utente deve iscriversi

function getListaCorsi($db,$utente,$nGiorno,$nOra) {
    //query al db
	$query="SELECT Nome,Durata,Ora FROM Corsi C INNER JOIN SessioniCorsi S ON C.ID_Corso=S.ID_Corso WHERE Giorno=$nGiorno AND Ora=$nOra AND PostiRimasti>0";
    $lista_corsi=$db->queryDB($query);	
    
    //operazione da eseguire se il db non ha restituito valori
    if(!$lista_corsi) return "errore_db_lista_corsi";

    //operazione da eseguire se il db ha restituito la lista delle sessioni dei corsi disponibili in quella ora e giorno
    return $lista_corsi;
} //richiesta dei corsi a cui è possibile iscriversi nel giorno e ora selezionati

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
} //crea select con numero ora nei campi id e appende select alla pagina


/*************************************************************************************************/
/*                                      SEZIONE: Iscrizione                                      */
/*************************************************************************************************/


?>