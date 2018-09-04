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

function getURL($pagina) {
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
} //recupero dell'URL della pagina per creare link assoluti

function controlloAccesso($db,$utente,$livelliAmmessi) {
    //se il controllo risulta verificato allora $db o $utente non sono settate (oppure entrambe), quindi torno alla homepage
    if(!isset($db) || !isset($utente)) header("Location: /");

    //$utente è settato, bisogna controllare il caso in cui lui non possa accedere a questa pagina e quindi deve essere rimandato alla homepage
    if(!$livelliAmmessi[$utente->getLivello()]) header("Location: /");
} //controlla se l'utente connesso può accedere alla pagina attuale

function getNumGiorni($db) {
    //query al db
    $numGiorni=$db->queryDB("SELECT COUNT(ID_DataEvento) AS Giorni FROM DateEvento"); //ritornato un array

    //operazione da eseguire se il db non ha restituito valori
    if(!$numGiorni) return "errore_db_giorni";
    
    return intval($numGiorni[0]["Giorni"]);
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

function getDatiPersona($db,$nome,$cognome) {
    //query al db
    $q="SELECT ID_Persona,Classe,Sezione,Indirizzo FROM Persone P INNER JOIN Classi C ON P.ID_Classe=C.ID_Classe WHERE Nome LIKE '".$nome."' AND Cognome LIKE '".$cognome."'";
    $datiPersona=$db->queryDB($q); //ritornato un array

    //operazione da eseguire se il db non ha restituito valori
    if(!$datiPersona) return "errore_db_idPersona";

    //operazione da eseguire se il db ha restituito qualcosa
    if(sizeof($datiPersona) == 1) return intval($datiPersona[0]["ID_Persona"]);

    //restituisco l'array così come l'ho ricevuto
    return $datiPersona;
} //richiesta dell'ID (o degli ID) della persona/e con quel/i nome e cognome

function getCorsiGiorno($db,$idStudente,$giorno) {
    //query al db
    $q = "SELECT Nome, Aula, Ora, Durata, ID_SessioneCorso AS id_sc FROM SessioniCorsi S INNER JOIN Corsi C ON C.ID_Corso=S.ID_Corso INNER JOIN Iscrizioni I ON S.ID_SessioneCorso=I.ID_SessioneCorso WHERE ID_Studente=$idStudente AND Giorno=$giorno ORDER BY Ora";
    $vCorsi=$db->queryDB($q);

    //operazione da eseguire se il db non ha restituito valori
    if(!$vCorsi) return "errore_db_corsi_iscritti_giorno";

    return $vCorsi;
} //restituisce un array, con tutti i corsi in cui lo studente di ID = $idStudente si è iscritto in quel giorno

function getSessioniStudente($db,$id) {
    $qID_SessioneCorso="SELECT ID_SessioneCorso FROM Iscrizioni WHERE ID_Studente=$id";
    $rID_SessioneCorso=$db->queryDB($qID_SessioneCorso);

    //operazione da eseguire se il db non ha restituito valori
    if(!$rID_SessioneCorso) return "errore_sessioni_corso_studente";

    return $rID_SessioneCorso; 
} //restituisce un array con gli ID delle sessioni dei corsi a cui è iscritto lo studente di ID=$id

function getIscrizioniStudente($db,$id_p,$id_sc) {
    $qID_Iscrizione="SELECT ID_Iscrizione FROM Iscrizioni WHERE ID_SessioneCorso IN (";
    for($i=0,$l=sizeof($id_sc),$ultimo=$l-1;$i<$l;$i++) {
        $qID_Iscrizione.=$id_sc[$i]["ID_SessioneCorso"];
        if($i < $ultimo) $qID_Iscrizione.=", ";
    }
    $qID_Iscrizione.=") AND ID_Studente=".$id_p;
    $rID_Iscrizione=$db->queryDB($qID_Iscrizione);

    if(!$rID_Iscrizione) return "errore_iscrizioni_sessioni";

    return $rID_Iscrizione;
} //restituisce un array con gli ID delle iscrizioni relative allo studente di ID=$id_p e sessioni corsi 

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
        //Session::set("ID_Persona",$id);
        Session::set("login",hash('sha512',$postPass.$browser));
        return "utente-esistente";
    } else {
        return "password-errata";
    }
} //permette il login al sito


/*************************************************************************************************/
/*                                      SEZIONE: Amministrazione                                 */
/*************************************************************************************************/
function getListaCorsi($db) {
    //query al db
    $corsi=$db->queryDB("SELECT Nome FROM Corsi ORDER BY Nome ASC"); //ritornato un array

    //operazione da eseguire se il db non ha restituito valori
    if(!$corsi) return "errore_db_corsi";

    return $corsi;
} //restituisce la lista completa dei corsi dell'evento

function getDatiCorso($db,$nomeCorso) {
    //query al db
    $q_corso="SELECT ID_Corso, Durata, Aula, MaxPosti FROM Corsi WHERE Nome='".$nomeCorso."'";
    $r_corso=$db->queryDB($q_corso); //ritornato un array

    //se qualcosa va male
    if(!$r_corso) return "errore_db_dati_corso";

    return $r_corso;
} //restituisce i dati del corso $nomeCorso

function getSessioniCorso($db,$idCorso) {
    //query al db
    $q_sessioniCorso="SELECT Giorno, Ora, PostiRimasti, ID_SessioneCorso AS id_sc FROM SessioniCorsi WHERE ID_Corso=$idCorso ORDER BY Giorno,Ora";
    $r_sessioniCorso=$db->queryDB($q_sessioniCorso);

    //se qualcosa va male
    if(!$r_sessioniCorso) return "errore_db_sessione_corso";

    return $r_sessioniCorso;
} //restituisce le sessioni del corso dall'ID_Corso: $idCorso

function getPresenzeSessione($db,$id) {
    //query al db
    $q_presenze="SELECT P.Cognome, P.Nome, R.Presenza FROM Persone AS P, Iscrizioni AS I, SessioniCorsi AS S, RegPresenze AS R WHERE I.ID_Studente=P.ID_Persona AND S.ID_SessioneCorso=I.ID_SessioneCorso AND R.ID_Iscrizione=I.ID_Iscrizione AND S.ID_SessioneCorso=$id";
    $r_presenze=$db->queryDB($q_presenze); //ritornato un array

    //se qualcosa va male
    if(!$r_presenze) return "errore_db_presenze";

    return $r_presenze;
} //restituisce il registro presenze della sessione corso con ID_SessioneCorso: $id
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

function getRigheCorsi($db,$utente,$giorno) {
    $vCorsi = getCorsiGiorno($db,$utente->getId(),$giorno); //restituisce un array, con tutti i corsi in cui l'utente si è iscritto in quel giorno

    if($vCorsi === "errore_db_corsi_iscritti_giorno") return "errore_db_lista_corsi";

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
    $tabella.="<tbody>".$lista."</tbody>";
    $tabella.="</table>";
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
        $panels.=creaTabella($db,$utente,$i+1);
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

    return intval($iscrivi_giorno);
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
    
    
    return intval($iscrivi_ora);
} //richiesta dell'ora in cui l'utente deve iscriversi

function getCorsiDisponibili($db,$utente,$nGiorno,$nOra) {
    //query al db
	$query="SELECT Nome,Durata,Ora FROM Corsi C INNER JOIN SessioniCorsi S ON C.ID_Corso=S.ID_Corso WHERE Giorno=$nGiorno AND Ora=$nOra AND PostiRimasti>0";
    $lista_corsi=$db->queryDB($query);	
    
    //operazione da eseguire se il db non ha restituito valori
    if(!$lista_corsi) return "errore_db_lista_corsi";

    //operazione da eseguire se il db ha restituito la lista delle sessioni dei corsi disponibili in quella ora e giorno
    return $lista_corsi;
} //richiesta dei corsi a cui è possibile iscriversi nel giorno e ora selezionati

function creazioneBloccoIscrizione($db,$utente,$nGiorno,$nOra) {
    $vCorsi=getCorsiDisponibili($db,$utente,$nGiorno,$nOra); //reperisco la lista dei corsi

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

function getID_SessioneCorso($db,$nomeC,$giorno,$ora) {
    //query al db
    $q_id="SELECT ID_SessioneCorso FROM SessioniCorsi S INNER JOIN Corsi C ON S.ID_Corso=C.ID_Corso WHERE Nome='".$nomeC."' AND Giorno=$giorno AND Ora=$ora";
    $r_id=$db->queryDB($q_id); //ritornato un array

    //se qualcosa va male
    if(!$r_id) return "errore_db_id_sessione_corso";

    //se invece è tutto ok
    return intval($r_id[0]["ID_SessioneCorso"]);
}

function getPostiRimastiSessione($db,$id_sc) {
    //query al db
    $q_posti="SELECT PostiRimasti FROM SessioniCorsi WHERE ID_SessioneCorso=$id_sc";
    $r_posti=$db->queryDB($q_posti);

    //se qualcosa va male
    if(!$r_posti) return "errore_db_posti_sessione_corso";

    //se invece è tutto ok
    $postiRimasti=intval($r_posti[0]["PostiRimasti"]);
    if($postiRimasti <= 0) return "posti_terminati_sessione_corso";

    return $postiRimasti;
}

function inserisciIscrizione($db,$idStudente,$id_sc) {
    //query al db
    $q_iscrizioni="INSERT INTO Iscrizioni (ID_Studente,ID_SessioneCorso) VALUES ($idStudente,$id_sc)";
    $r_iscrizioni=$db->queryDB($q_iscrizioni); //restituisce true se insert viene fatta, false altrimenti

    //se qualcosa va male
    if(!$r_iscrizioni) return "errore_db_upd8_iscrizioni";

    //se invece è tutto ok
    return $r_iscrizioni;
}

function decrementaPostiSessione($db,$id_sc) {
    //query al db
    $q_posti="UPDATE SessioniCorsi SET PostiRimasti=PostiRimasti-1 WHERE ID_SessioneCorso=$id_sc";
    $r_posti=$db->queryDB($q_posti);

    //se qualcosa va male
    if(!$r_posti) return "errore_db_upd8_posti_rimasti";

    return $r_posti;
}

function creazioneIstanzaRegistroSessione($db,$idStudente,$id_sc) {
    //-- RICERCA del CODICE della ISCRIZIONE appena creata
    //query al db
    $q_iscrizione="SELECT ID_Iscrizione FROM Iscrizioni WHERE ID_SessioneCorso=$id_sc AND ID_Studente=$idStudente";
    $r_iscrizione=$db->queryDB($q_iscrizione);

    //se qualcosa va male
    if(!$r_iscrizione) return "errore_db_id_iscrizione";

    //se invece è tutto ok
    $id_iscriz=$r_iscrizione[0]["ID_Iscrizione"];


    //-- AGGIORNAMENTO del REGISTRO PRESENZE della SESSIONE DEL CORSO
    //query al db
    $q_registro="INSERT INTO RegPresenze (ID_Iscrizione) VALUES ($id_iscriz)";
    $r_registro=$db->queryDB($q_registro);

    //se qualcosa va male
    if(!$r_registro) return "errore_db_upd8_registro"; 
    
    return $r_registro;
}

function registraIscrizione($db,$idStudente,$id_sc) {
    //-- AGGIORNAMENTO della tabella delle ISCRIZIONI
    $insIscrizioni=inserisciIscrizione($db,$idStudente,$id_sc);
    if($insIscrizioni === "errore_db_upd8_iscrizioni") return $insIscrizioni;
    else {
        //-- AGGIORNAMENTO della SESSIONE DEL CORSO (decremento i POSTI DISPONIBILI)
        $decrementoPosti=decrementaPostiSessione($db,$id_sc);
        if($decrementoPosti === "errore_db_upd8_posti_rimasti") return $decrementoPosti;
        else {
            $creataIstanzaRegistroSessione=creazioneIstanzaRegistroSessione($db,$idStudente,$id_sc);
            if($creataIstanzaRegistroSessione === "errore_db_id_iscrizione" || $creataIstanzaRegistroSessione === "errore_db_upd8_registro") return $creataIstanzaRegistroSessione;
        }
    }
    
    return true;
}

function aggiornaInfoUtente($db,$utente,$giornoCorso,$oreTotGiorno,$oraCorso,$durataCorso) {
    $nuova_oraIscritta=$utente->getOraIscritta()+$durataCorso;
    if($oraCorso === $oreTotGiorno || $nuova_oraIscritta === $oreTotGiorno) { //caso in cui utente si sta iscrivendo all'ultima ora di un giorno o alle ultime ore di un giorno
        //aggiorno oggetto utente
        $utente->setOraIscritta(0); //resetto il contatore delle ore iscritte di una giornata
        $utente->setGiornoIscritto($giornoCorso); //imposto come iscritta completamente la giornata n° $giornoCorso

        //query al db
        $q_persone="UPDATE Persone SET OraIscritta=0, GiornoIscritto=".$utente->getGiornoIscritto()." WHERE ID_Persona=".$utente->getId();
    } else {
        //aggiorno oggetto utente
        $utente->setOraIscritta($nuova_oraIscritta); //imposto come iscritta l'ora della giornata n° $giornoCorso
        //nessuna operazione su GiornoIscritto perché non è terminata la giornata, e quindi non è iscritta per intero

        //query al db
        $q_persone="UPDATE Persone SET OraIscritta=".$utente->getOraIscritta()." WHERE ID_Persona=".$utente->getId();
    }
    $r_persone=$db->queryDB($q_persone);
    
    //se qualcosa va male
    if(!$r_persone) return "errore_db_upd8_persone";

    return $r_persone;
}
/*************************************************************************************************/
/*                                  SEZIONE: Registro Presenze                                   */
/*************************************************************************************************/


?>