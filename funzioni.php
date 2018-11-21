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
/*   - Tutti i corsi                                                                             */
/*                                                                                               */
/*************************************************************************************************/

/*************************************************************************************************/
/*                                          SEZIONE: Tutte                                       */
/*************************************************************************************************/

function getURL($pagina) {
    //
    if((isset($_SERVER["HTTPS"]) && GlobalVar::SERVER("HTTPS") == "on") || Session::is_secure()) {
        $url = "https://";
    } else {
        $url = 'http://';
    }

    $url .= GlobalVar::SERVER("SERVER_NAME");

    if(GlobalVar::SERVER("SERVER_PORT") != "80" && GlobalVar::SERVER("SERVER_PORT") != "443") {
        $url .= ":" . GlobalVar::SERVER("SERVER_PORT");
    }

    //RITORNO URL BASE+URL PAGINA
    return $url . $pagina;
} //RESTITUITO: URL della pagina $pagina. Viene recuperato da $_SERVER l'URL generale e concatenata la stringa corrispondendente alla pagina.

function controlloAccesso($db, $utente, $livelliAmmessi) {
    if(!isset($db) || !isset($utente)) header("Location: /");

    //$utente è settato, controllo se può accedere alla pagina che chiama questa funzione
    if(!$livelliAmmessi[$utente->getLivello()]) header("Location: /");
} //RESITUITO: niente. Se l'utente non può accedere alla pagina richiesta lo reindirizzo alla home.

function getNumGiorni($db) {
    $numGiorni = $db->queryDB("SELECT COUNT(ID_DataEvento) AS Giorni FROM DateEvento");

    if(!$numGiorni) return "errore_db_numero_giorni";
    
    return intval($numGiorni[0]["Giorni"]);
} //RESTITUITO: numero di giorni dell'evento o messaggio di errore. Viene interrogato il database.

function getAltreAttivita($db) {
    //query per ottenere la lista
    $lista_aa = $db->queryDB("SELECT Lista FROM AltreAttivita WHERE ID=1");
    //query per ottenere il numero di corsi dal nome "Altre attività" (può essere 0 o 1)
    $flag_esistenza = $db->queryDB("SELECT COUNT(*) AS Esiste FROM Corsi WHERE Nome='Altre attività'");

    //affinché possa essere considerata esistente "Altre attività" entrambe le variabili devono contenere il risultato dell'interrogazione != false
    if(!$lista_aa || !$flag_esistenza) return "errore_altre_attivita";
    
    //operazione da eseguire se il db ha restituito qualcosa
    if($flag_esistenza[0]["Esiste"] == 1) { //uguaglianza non stretta perché il db restituisce stringhe
        $lista_aa = trim($lista_aa[0]["Lista"]); //faccio il trim del risultato del db, poi ridefinisco $lista_aa

        if($lista_aa !== "") return $lista_aa; //se $lista_aa è non vuota restituisco la lista
    }
    
    return "no_altre_attivita";

} //RESTITUITO: lista delle "Altre attività" o messaggio di errore. Vengono interrogati il database e effettuati dei controlli di validità.

function getDatiPersona($db, $nome, $cognome) {
    $dati = $db->queryDB("SELECT ID_Persona,Classe,Sezione,Indirizzo FROM Persone P INNER JOIN Classi C ON P.ID_Classe=C.ID_Classe WHERE Nome LIKE '".$nome."' AND Cognome LIKE '".$cognome."'"); //ritornato un array

    if(!$dati) return "errore_db_dati_persona";

    return $dati;
} //RESTITUITO: dati della/e persona/e con nome=$nome e cognome=$cognome o messaggio di errore. Viene interrogato il database.

function getCorsiStudente($db, $idStudente, $giorno = 0) {
    $q_corsiStudente = "";
    
    if($giorno === 0) { //nessun giorno specificato, reperisco tutte
        $q_corsiStudente .= "SELECT Nome, Aula, Ora, Durata, I.ID_SessioneCorso AS ID_SessioneCorso FROM SessioniCorsi S INNER JOIN Corsi C ON C.ID_Corso=S.ID_Corso INNER JOIN Iscrizioni I ON S.ID_SessioneCorso=I.ID_SessioneCorso WHERE ID_Studente=$idStudente ORDER BY Giorno, Ora";
    } else { //giorno specificato, reperisco quelle del giorno richiesto
        $q_corsiStudente .= "SELECT Nome, Aula, Ora, Durata, I.ID_SessioneCorso AS ID_SessioneCorso FROM SessioniCorsi S INNER JOIN Corsi C ON C.ID_Corso=S.ID_Corso INNER JOIN Iscrizioni I ON S.ID_SessioneCorso=I.ID_SessioneCorso WHERE ID_Studente=$idStudente AND Giorno=$giorno ORDER BY Ora";
    }

    $r_corsiStudente = $db->queryDB($q_corsiStudente);
    
    if(!$r_corsiStudente) return "errore_db_corsi_iscritti_studente";

    return $r_corsiStudente;
} //RESTITUITO: nome, aula, durata dei corsi e rispettivi id_sessionecorso dello studente di id=$idStudente, di tutti i giorni o solo di $giorno. Viene interrogato il database.

function getIscrizioniStudente($db, $id_p, $id_sc) { // ATTENZIONE: POSSIBILE RIDONDANZA
    $qID_Iscrizione = "SELECT ID_Iscrizione FROM Iscrizioni WHERE ID_SessioneCorso IN (";
    for($i = 0, $l = sizeof($id_sc)-1; $i < $l; $i++) $qID_Iscrizione .= $id_sc[$i] . ", ";
    $qID_Iscrizione .= $id_sc[sizeof($id_sc)-1] . ") AND ID_Studente=" . $id_p;

    $rID_Iscrizione = $db->queryDB($qID_Iscrizione);

    if(!$rID_Iscrizione) return "errore_iscrizioni_sessioni";

    for($i = 0, $l = sizeof($rID_Iscrizione); $i < $l; $i++) $id_isc[$i] = $rID_Iscrizione[$i]["ID_Iscrizione"];

    return $id_isc;
} //RESTITUITO: tutti gli ID_Iscrizione dello studente di ID=$id_p nei corsi di ID_SessioneCorso presenti nell'array $id_sc. Viene interrogato il database.

function rimuoviIstanzeRegistro($db, $id_isc) {
    $qDelRegPresenze = "DELETE FROM RegPresenze WHERE ID_Iscrizione IN (";
    $l = sizeof($id_isc)-1;

    for($i = 0; $i < $l; $i++) $qDelRegPresenze .= $id_isc[$i] . ", ";
    $qDelRegPresenze .= $id_isc[$l] . ")";

    return $db->queryDB($qDelRegPresenze);
} //RESTITUITO: true se le istanze nel registro delle iscrizioni di id presenti nell'array $id_isc sono state rimosse dal db, false altrimenti. Viene interrogato il database.

function rimuoviIscrizioni($db, $id_p, $id_isc) {
    $qDelIscrizioni = "DELETE FROM Iscrizioni WHERE ID_SessioneCorso IN (";
    $l = sizeof($id_isc)-1;

    for($i = 0; $i < $l; $i++) $qDelIscrizioni .= $id_isc[$i] . ", ";
    $qDelIscrizioni .= $id_isc[$l] . ") AND ID_Studente=" . $id_p;

    return $db->queryDB($qDelIscrizioni);
} //RESTITUITO: true se le iscrizioni della persona di id=$id_p sono state rimosse dal db, false altrimenti. Viene interrogato il database.

function aggiornaSessioniCorsi($db, $id_sc) {
    $qUpdSessioniCorsi = "UPDATE SessioniCorsi SET PostiRimasti=PostiRimasti+1 WHERE ID_SessioneCorso IN (";
    $l = sizeof($id_sc)-1;

    for($i = 0; $i < $l; $i++) $qUpdSessioniCorsi .= $id_sc[$i] . ", ";
    $qUpdSessioniCorsi .= $id_sc[$i] . ")";

    return $db->queryDB($qUpdSessioniCorsi);
} //RESTITUITO: true se SessioniCorsi è stato aggiornato correttamente, false altrimenti. Viene interrogato il database.

function getDateEvento($db) {
    //query al db
    $dateEvento = $db->queryDB("SELECT Giorno, Mese, Anno FROM DateEvento");

    if(!$dateEvento) return "errore_db_date_evento";

    return $dateEvento;
} //RESTITUITO: giorno, mese, anno delle date dell'evento o messaggio di errore. Viene interrogato il database.

function getCorsiDisponibili($db, $nGiorno, $nOra) {
    $lista_corsi = $db->queryDB("SELECT Nome, Aula, Durata, MaxPosti AS PostiTotali, PostiRimasti FROM Corsi C INNER JOIN SessioniCorsi S ON C.ID_Corso=S.ID_Corso WHERE Giorno=$nGiorno AND Ora=$nOra AND PostiRimasti>0");

    //operazione da eseguire se il db non ha restituito valori
    if(!$lista_corsi) return "errore_db_lista_corsi";

    //operazione da eseguire se il db ha restituito la lista delle sessioni dei corsi disponibili in quella ora e giorno
    return $lista_corsi;
} //RESTITUITO: corsi disponibili per l'iscrizione all'ora=$nOra e giorno=$nGiorno o messaggio di errore. Viene interrogato il database.


/*************************************************************************************************/
/*                                          SEZIONE: Accesso                                     */
/*************************************************************************************************/

function inizializzaUtente($db, $ID_Persona) {
    $richiesta = $db->queryDB("SELECT * FROM Persone P INNER JOIN Classi C ON P.ID_Classe=C.ID_Classe WHERE ID_Persona=$ID_Persona");

    if(!$richiesta) return false;

    //recupero i valori dalla risposta del database
    $dati = $richiesta[0]; //copio in altra variabile per semplicità di lettura

    $utente = new Utente(
        $dati["ID_Persona"],
        $dati["Nome"],
        $dati["Cognome"],
        new Classe(
            $dati["ID_Classe"],
            $dati["Classe"],
            $dati["Sezione"],
            $dati["Indirizzo"]
        ),
        $dati["GiornoIscritto"],
        $dati["OraIscritta"],
        $dati["Livello"]
    );

    return $utente;
} //RESTITUITO: oggetto di classe Utente se l'interrogazione è andata a buon fine, false altrimenti. Viene interrogato il database.

function cifraPassword($stringa) { // !!!
    $s_cifrata = md5($stringa); //ASSOLUTAMENTE da cambiare (temporaneo)

    return $s_cifrata;
} //RESTITUITO: $stringa cifrata

function login($db, $cl, $s, $ind, $postPass) {
    $password = cifraPassword($postPass);
    $id_persona = $db->queryDB("SELECT ID_Persona FROM Persone P INNER JOIN Classi C ON P.ID_Classe=C.ID_Classe WHERE Classe='".$cl."' AND Sezione='".$s."' AND Indirizzo='".$ind."' AND Pwd='".$password."'");
    if(!$id_persona) return "errore_db_dati_input";

    $id = intval($id_persona[0]["ID_Persona"]);
    $utente = inizializzaUtente($db, $id);
    if(!$utente) return "errore_db_idpersona";

    Session::set("utente", $utente);

    //controllo del login
    $browser = GlobalVar::SERVER("HTTP_USER_AGENT"); //browser in uso
    Session::set("login", hash('sha512', $password.$browser));

    return "utente_esistente";
} //RESTITUITO: "utente_esistente" se il login è stato effettuato, "errore_db_idpersona" se l'id è non trovato, "errore_db_dati_input" se i dati input non corrispondono. Viene interrogato il database.

function getIndirizzi($db) {
    $indirizzi = $db->queryDB("SELECT DISTINCT Indirizzo FROM Classi WHERE NOT (Classe='E' OR Classe='P') ORDER BY Indirizzo");

    if(!$indirizzi) return "errore_db_indirizzi";

    for($i = 0, $l = sizeof($indirizzi); $i < $l; $i++) $indirizzi[$i] = $indirizzi[$i]["Indirizzo"];

    return $indirizzi;
} //RESTITUITO: array con indirizzi dell'istituto o messsaggio di errore. Viene interrogato il database.

function getEsterni($db) {
    $esterni = $db->queryDB("SELECT DISTINCT Classe AS extC, Sezione AS extS, Indirizzo AS extI FROM Classi WHERE Classe IN ('E', 'P') ORDER BY Indirizzo");

    if(!$esterni) return "errore_db_esterni";

    return $esterni;
} //RESTITUITO: array con 'classi, sezioni, indirizzi' degli indirizzi ESTERNO e PERSONALE o messaggio di errore. Viene interrogato il database.

function getClassi($db, $indirizzo) {
    $classi = $db->queryDB("SELECT Classe, Sezione FROM Classi WHERE Indirizzo='".$indirizzo."' ORDER BY Classe, Sezione");

    if(!$classi) return "errore_db_classi_istituto";

    return $classi;
} //RESTITUITO: array con 'classi, sezioni' dell'indirizzo = $indirizzo o messaggio di errore. Viene interrogato il database.


/*************************************************************************************************/
/*                                      SEZIONE: Amministrazione                                 */
/*************************************************************************************************/

function getDatiCorsi($db, $condizione = null/* id/nome */, $dato = null/*dato integer/dato string*/) {
    $corsi = null;

    if($condizione === null || $dato === null) {
        $corsi = $db->queryDB("SELECT * FROM Corsi ORDER BY Nome ASC");
        
        if(!$corsi) $corsi = "errore_db_lista_corsi";
        
    } elseif($condizione === "id" && gettype($dato) === "integer") {
        $corsi = $db->queryDB("SELECT * FROM Corsi WHERE ID_Corso=$dato");

        if(!$corsi) $corsi = "errore_db_id_corso";
        else $corsi = $corsi[0];
    } elseif($condizione === "nome" && gettype($dato) === "string") {
        $corsi = $db->queryDB("SELECT * FROM Corsi WHERE Nome='" . $dato . "'");

        if(!$corsi) $corsi = "errore_db_nome_corso";
        else $corsi = $corsi[0];
    } else $corsi = "errore_db_dati_input";

    return $corsi;
} //RESTITUITO: array con lista completa corsi ($condizione e $dato sono null), array con dati corso ($condizione e $dato non sono null e sono rispettivamente "nome" e un valore string oppure "id" e un valore integer) oppure un messaggio di errore. Viene interrogato il database.

function getSessioniCorso($db, $idCorso, $giorno = 0, $ora = 0) {
    $query = "";

    if($giorno === 0 && $ora === 0)
        $query = "SELECT Giorno, Ora, PostiRimasti, ID_SessioneCorso FROM SessioniCorsi WHERE ID_Corso=$idCorso ORDER BY Giorno, Ora";
    elseif($giorno > 0 && $ora === 0)
        $query = "SELECT Giorno, Ora, PostiRimasti, ID_SessioneCorso FROM SessioniCorsi WHERE ID_Corso=$idCorso AND Giorno=$giorno ORDER BY Ora";
    elseif($giorno > 0 && $ora > 0)
        $query = "SELECT Giorno, Ora, PostiRimasti, ID_SessioneCorso FROM SessioniCorsi WHERE ID_Corso=$idCorso AND Giorno=$giorno AND Ora=$ora";

    $sc = $db->queryDB($query);
        
    if(!$sc) return "errore_db_sessione_corso";

    return $sc;
} //RESTITUITO: array con lista sessioni corsi del corso di id=$idCorso o messaggio di errore. Viene interrogato il database

function getRegistroPresenzeSessioneCorso($db, $id) {
    $regPresenze = $db->queryDB("SELECT P.Cognome, P.Nome, R.Presenza FROM Persone AS P, Iscrizioni AS I, SessioniCorsi AS S, RegPresenze AS R WHERE I.ID_Studente=P.ID_Persona AND S.ID_SessioneCorso=I.ID_SessioneCorso AND R.ID_Iscrizione=I.ID_Iscrizione AND S.ID_SessioneCorso=$id");

    if(!$regPresenze) return "errore_db_registro_presenze";

    return $regPresenze;
} //RESTITUITO: registro presenze della sessione corso con ID_SessioneCorso=$id o messaggio di errore. Viene interrogato il database.


/*************************************************************************************************/
/*                                      SEZIONE: I miei corsi                                    */
/*************************************************************************************************/

function getMese($db, $i) {
    $mesi = $db->queryDB("SELECT Mese FROM DateEvento");

    if(!$mesi) return "errore_db_mese";
    
    return $mesi[$i]["Mese"];
} //RESTITUITO: mese del $i+1-esimo giornata dell'evento o messaggio di errore. Viene interrogato il database.

function getGiorno($db, $i) {
    $giorni = $db->queryDB("SELECT Giorno FROM DateEvento");

    if(!$giorni) return "errore_db_giorno";
    
    return $giorni[$i]["Giorno"];
} //RESTITUITO: giorno del calendario della $i+1-esima giornata di evento o messaggio di errore. Viene interrogato il database.

function getRigheCorsi($db, $utente, $giorno) {
    $corsi = getCorsiStudente($db, $utente->getID(), $giorno);

    if($corsi === "errore_db_corsi_iscritti_studente") return $corsi;

    $corsoInTab = "";
    for($i = 0, $l = sizeof($corsi); $i < $l; $i++) {
        $corsoInTab .= "<tr>" .
        "<td>" . $corsi[$i]["Ora"] . "°</td>" .
        "<td>" . htmlspecialchars($corsi[$i]["Nome"], ENT_QUOTES) . "</td>" .
        "<td>" . $corsi[$i]["Durata"] . " ore</td>" .
        "<td>" . $corsi[$i]["Aula"] . "</td>" .
        "</tr>";
    }

    return $corsoInTab;
} //RESTITUITO: righe della tabella contenenti le informazione dei corsi a cui è iscritto lo studente o messaggio di errore. Viene interrogato il database.

function headerPanelGiornata($giorno, $mese) {
    if($mese === "errore_db_mese") $mese = "Err. mese";
    if($giorno === "errore_db_giorno") $giorno = "Err. giorno";

    $panelGiorno = "<div class='panel-heading'><h2 class='panel-title'><strong>Giorno</strong>: $giorno $mese</h2></div>";

    return $panelGiorno;
} //RESTITUITO: header pannello dei corsi a cui è iscritto uno studente in una giornata o messaggio di errore. Viene interrogato il database.

function bodyPanelGiornata($listaCorsi) {
    $tabella = "<div class='panel-body'>" .
    "<div class='table-responsive'>" .
    "<table class='table table-hover'>" .
    "<thead><tr>" .
    "<th><strong>Ora</strong></th><th><strong>Corso</strong></th><th><strong>Durata</strong></th><th><strong>Aula</strong></th>" .
    "</tr></thead>" .
    "<tbody>" . $listaCorsi . "</tbody>" .
    "</table>" .
    "</div></div>";

    return $tabella;        
}  //RESTITUITO: corpo pannello dei corsi a cui è iscritto uno studente in una giornata o messaggio di errore. Viene interrogato il database.

function creazioneTabella($db, $utente) {
    $nGiorni = getNumGiorni($db);

    $panels = "";

    if($nGiorni === "errore_db_numero_giorni") {
        $panels .= "<div class='panel panel-default'>" .
        "<div class='panel-heading'><h2 class='panel-title'>Err. giorni</h2></div>" .
        "<div class='panel-body'><p class='error'>Err.lista corsi</p></div>" .
        "</div>";
    } else {
        // stampa tabella, fino al numero massimo di giorni restituito dalla funzione getNumGiorni()
        for($i = 0; $i < $nGiorni; $i++) {
            $righecorsi = getRigheCorsi($db, $utente, $i+1);

            if($righecorsi !== "errore_db_corsi_iscritti_studente") {
                $panels .= "<div class='panel panel-default'>" .
                headerPanelGiornata(getGiorno($db, $i), getMese($db, $i)) .
                bodyPanelGiornata($righecorsi) .
                "</div>";
            }  
        }
    }

    return $panels;
}  //RESTITUITO: pannello dei corsi a cui è iscritto uno studente o messaggio di errore. Viene interrogato il database.


/*************************************************************************************************/
/*                                      SEZIONE: Iscrizione                                      */
/*************************************************************************************************/

function getSottotitolo($db, $nGiorno) {
    $dateEvento = getDateEvento($db);

    if($dateEvento === "errore_db_date_evento") return "errore_db_sottotitolo";

    //operazione da eseguire se il db ha restituito le informazione per il sottotitolo
    $pos = $nGiorno - 1; //creo l'indice per leggere correttamente l'array risultato dal db
    $sottotitolo = $dateEvento[$pos]["Giorno"] . " " . $dateEvento[$pos]["Mese"] . " " . $dateEvento[$pos]["Anno"]; //imposto la variabile contenente il valore del titolo

    return $sottotitolo;
} //RESTITUITO: gg/mm/aa di iscrizione o messaggio di errore. Viene interrogato il database.

function getNumOre($db, $nGiorno) {
    //query al db
    $numOre = $db->queryDB("SELECT MAX(Ora) AS Ore FROM SessioniCorsi WHERE Giorno=$nGiorno"); //ritornato un array
    
    //operazione da eseguire se il db non ha restituito valori
	if(!$numOre) return "errore_db_ore";
    
    //operazione da eseguire se il db ha restituito il numero delle ore del giorno
    return intval($numOre[0]["Ore"]); 
} //RESTITUITO: numero di ore nel giorno=$nGiorno (ovvero l'ultima ora in cui è disponibile un corso) o messaggio di errore. Viene interrogato il database.

function getGiornoDaIscriversi($db, $utente) {
    $numGiorni = getNumGiorni($db); //reperisco il numero dei giorni di evento
    
    //operazione da eseguire se getNumGiorni($db) non e' riuscito a reperire il numero di giorni
    if($numGiorni === "errore_db_numero_giorni") return "errore_db_giorno_iscrizione";
    
    $iscrivi_giorno = $numGiorni; //assegno a $iscrivi_giorno il valore di $numGiorni per confrontarlo successivamente con $numGiorni che è costante (mentre $iscrivi_giorno varia)
    
    //se $iscrivi_giorno risulterà maggiore di $numGiorni nel prossimo controllo significa che l'utente ha finito di iscriversi
    for($i = 0; $i <= $numGiorni; $i++) {
        if($i === $utente->getGiornoIscritto()) { //se $i equivale al valore di GiornoIscritto della Persona indica che è iscritto fino al giorno di valore $i
            $iscrivi_giorno = $i + 1; //assegno a $iscrivi_giorno il valore di $i incrementato, sta a indicare il giorno in cui la persona si deve iscrivere
        }
    }

    if($iscrivi_giorno > $numGiorni) return "fine_iscrizione";

    return $iscrivi_giorno;
} //RESTITUITO: ((numero del giorno in cui lo studente deve iscriversi) <= (numero ultima giornata) o messaggio "fine_iscrizione") o messaggio di errore. Vengono interrogati il database e effettuati dei controlli.

function getOraDaIscriversi($db, $utente, $nGiorno) {
    $numOre = getNumOre($db, $nGiorno); //reperisco il numero delle ore della giornata $nGiorno

    //operazione da eseguire se getNumOre() non è riuscito a reperire il numero di ore
    if($numOre === "errore_db_ore") return "errore_db_ora_iscrizione";

    
    $iscrivi_ora = $numOre; //assegno a $iscrivi_ora il valore di $maxOra

    //se $iscrivi_ora risulterà maggiore di $numOre nel prossimo controllo significa che l'utente ha finito di iscriversi nella giornata
    for($i = 0; $i <= $numOre; $i++) {
        if($i === $utente->getOraIscritta()) { //quando $i equivale al valore di OraIscritta della Persona indica che è iscritto fino a quell'ora
            $iscrivi_ora = $i+1; //assegno a $iscrivi_ora il valore di $i incrementato, sta a indicare l'ora a cui si deve iscrivere la persona
        }
    }

    //if($iscrivi_ora > $numOre) return "cambio_giorno";
    
    return intval($iscrivi_ora);
} //RESTITUITO: (ora in cui lo studente deve iscriversi) <= (ultima giornata della giornata) o messaggio di errore. Vengono interrogati il database e effettuati dei controlli.

function creazioneBloccoIscrizione($db, $nGiorno, $nOra) {
    $sessioniCorsi = getCorsiDisponibili($db, $nGiorno, $nOra); //reperisco la lista delle sessioni corsi al giorno e all'ora passati per parametro

    $blocco = "<div class='panel panel-default'>" .
                "<div class='panel-heading'>" .
                    "<h2 class='panel-title'><span class='fa fa-clock-o'></span>  " . $nOra . "° ora</h2>" .
                "</div>" .
                "<div class='panel-body'>" .
                    "<div class='input-group input-group-lg'>" .
                        "<select class='form-control' id='corso' name='corso'>" .
                            "<option value=''></option>";

    if($sessioniCorsi !== "errore_db_lista_corsi") {
        for ($i = 0, $l = sizeof($sessioniCorsi); $i < $l; $i++) {
            //recupero l'istanza i-esima dell'array dei corsi
            $corso = $sessioniCorsi[$i];
            $durata = intval($corso["Durata"]);

            if ($durata === 1) $stringCorso = htmlspecialchars($corso['Nome'], ENT_QUOTES) . " - " . $durata . " ora";
            else $stringCorso = htmlspecialchars($corso['Nome'], ENT_QUOTES) . " - " . $durata . " ore";

            $stringCorsoVal = htmlspecialchars($corso['Nome'], ENT_QUOTES) . "_" . $nOra;
            $blocco .= "<option value='" . $stringCorsoVal . "'>$stringCorso</option>";
        }
    }

    $blocco .=          "</select>" .
                        "<div class='input-group-btn'>" .
                            "<button type='submit' id='btnProsegui' class='btn btn-success'><span class='fa fa-check'></span><span class='hidden-xs hidden-sm'>  Prosegui</span></button>" .
                        "</div>" .
                    "</div>" .
                "</div>" .
            "</div>";

    echo $blocco;
} //RESTITUITO: pannello di iscrizione con select popolata o vuota (in caso di errore). Viene interrogato il database e creato il pannello.

function getID_SessioneCorso($db, $nomeC, $giorno, $ora) {
    $id_sc = $db->queryDB("SELECT ID_SessioneCorso FROM SessioniCorsi S INNER JOIN Corsi C ON S.ID_Corso=C.ID_Corso WHERE Nome='".$nomeC."' AND Giorno=$giorno AND Ora=$ora"); //ritornato un array

    //se qualcosa va male
    if(!$id_sc) return "errore_db_id_sessione_corso";

    //se invece è tutto ok
    return intval($id_sc[0]["ID_SessioneCorso"]);
} //RESTITUITO: ID_SessioneCorso del corso con nome=$nomeC, del giorno=$giorno e ora=$ora o messaggio di errore. Viene interrogato il database.

function getPostiRimastiSessione($db, $id_sc) {
    $posti = $db->queryDB("SELECT PostiRimasti FROM SessioniCorsi WHERE ID_SessioneCorso=$id_sc");

    if(!$posti) return "errore_db_posti_sessione_corso";

    $postiRimasti = intval($posti[0]["PostiRimasti"]);
    if($postiRimasti <= 0) return "posti_terminati_sessione_corso";

    return $postiRimasti;
} //RESTITUITO: ((posti rimasti della sessione di corso di id=$id_sc > 0) o messaggio "posti_terminati_sessione_corso") o messaggio di errore. Viene interrogato il database.

function inserisciIscrizione($db, $idStudente, $id_sc) {
    $iscrizione = $db->queryDB("INSERT INTO Iscrizioni (ID_Studente, ID_SessioneCorso) VALUES ($idStudente, $id_sc)"); //restituisce true se insert viene fatta, false altrimenti

    return $iscrizione;
} //RESTITUITO: true se la riga in Iscrizioni è stata inserita, false altrimenti. Viene interrogato il database.

function decrementaPostiSessione($db, $id_sc) {
    $decremento = $db->queryDB("UPDATE SessioniCorsi SET PostiRimasti=PostiRimasti-1 WHERE ID_SessioneCorso=$id_sc");

    return $decremento;
} //RESTITUITO: true se nella riga con ID_SessioneCorso=$id_sc SessioniCorsi.PostiRimasti è stato decrementato unitariamente, false altrimenti. Viene interrogato il database.

function creazioneIstanzaRegistroPresenze($db, $idStudente, $id_sc) {
    //-- RICERCA del CODICE della ISCRIZIONE appena creata
    $id_iscrizione = $db->queryDB("SELECT ID_Iscrizione FROM Iscrizioni WHERE ID_SessioneCorso=$id_sc AND ID_Studente=$idStudente");

    if(!$id_iscrizione) return "errore_db_id_iscrizione";

    $id_iscrizione = $id_iscrizione[0]["ID_Iscrizione"];

    //-- AGGIORNAMENTO del REGISTRO PRESENZE della SESSIONE DEL CORSO
    $regPresenze = $db->queryDB("INSERT INTO RegPresenze (ID_Iscrizione) VALUES ($id_iscrizione)");

    return $regPresenze;
} //RESTITUITO: true se istanza nel registro presenze è stata registrata, false altrimenti o messaggio di errore. Viene interrogato il database.

function registraIscrizione($db, $idStudente, $id_sc) {
    //-- AGGIORNAMENTO della tabella delle ISCRIZIONI
    if(!inserisciIscrizione($db, $idStudente, $id_sc)) return "errore_db_upd8_iscrizioni";

    //-- DECREMENTO posti nella tabella delle SESSIONI CORSI
    elseif(!decrementaPostiSessione($db, $id_sc)) return "errore_db_upd8_posti_rimasti";

    //-- AGGIORNAMENTO della tabella dei REGISTRI PRESENZE
    else {
        $istanzaRegPresenze = creazioneIstanzaRegistroPresenze($db, $idStudente, $id_sc);
        if($istanzaRegPresenze === "errore_db_id_iscrizione") return $istanzaRegPresenze;
        if(!$istanzaRegPresenze) return "errore_db_upd8_registro";
    }
    
    return true;
} //RESTITUITO: true se la registrazione dell'iscrizione è andata a buon fine o messaggio di errore. Vengono interrogati il database e processati i risultati delle funzioni di supporto.

function aggiornaInfoUtente($db, $utente, $giorno, $durata) {
    $oreTotGiorno = getNumOre($db, $giorno);
    $updateFatto = false;

    if($oreTotGiorno !== "errore_db_ore") {
        $utente->setOraIscritta(($utente->getOraIscritta() + $durata) % $oreTotGiorno); //raggiunto il max ore deve tornare a 0, ecco perche il % $oreTotGiorno

        if($utente->getOraIscritta() === 0) $utente->setGiornoIscritto($giorno);

        $updateFatto = $db->queryDB("UPDATE Persone SET OraIscritta=" . $utente->getOraIscritta() . ", GiornoIscritto=" . $utente->getGiornoIscritto() . " WHERE ID_Persona=". $utente->getID());
    }

    if($oreTotGiorno === "errore_db_ore" || !$updateFatto) return "errore_db_upd8_persone";

    return $utente;
} //RESTITUITO: $utente (aggiornato) se è stata aggiornata la tabella Persone o messaggio di errore. Viene interrogato il database.


/*************************************************************************************************/
/*                                  SEZIONE: Registro Presenze                                   */
/*************************************************************************************************/


/*************************************************************************************************/
/*                                    SEZIONE: Tutti i corsi                                     */
/*************************************************************************************************/
function getElencoOre($db, $giorno = 0) {
    $q_elencoOre = "";
    
    if($giorno === 0) { //nessun giorno specificato, reperisco tutte
        $q_elencoOre .= "SELECT DISTINCT Ora FROM SessioniCorsi ORDER BY Ora ASC";
    } else { //giorno specificato, reperisco quelle del giorno richiesto
        $q_elencoOre .= "SELECT DISTINCT Ora FROM SessioniCorsi WHERE Giorno=$giorno ORDER BY Ora ASC";
    }

    $elencoOre = $db->queryDB($q_elencoOre);
    
    if(!$elencoOre) return "errore_db_elenco_ore";

    return $elencoOre;
} //RESTITUITO: elenco ore di tutti i giorni ($giorno === 0) o di uno specifico giorno ($giorno !== 0) o messaggio di errore. Viene interrogato il database.