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
        $q_corsiStudente .= "SELECT Nome, Aula, Ora, Durata, I.ID_SessioneCorso, Giorno FROM SessioniCorsi S INNER JOIN Corsi C ON C.ID_Corso=S.ID_Corso INNER JOIN Iscrizioni I ON S.ID_SessioneCorso=I.ID_SessioneCorso WHERE ID_Studente=$idStudente ORDER BY Giorno, Ora";
    } else { //giorno specificato, reperisco quelle del giorno richiesto
        $q_corsiStudente .= "SELECT Nome, Aula, Ora, Durata, I.ID_SessioneCorso FROM SessioniCorsi S INNER JOIN Corsi C ON C.ID_Corso=S.ID_Corso INNER JOIN Iscrizioni I ON S.ID_SessioneCorso=I.ID_SessioneCorso WHERE ID_Studente=$idStudente AND Giorno=$giorno ORDER BY Ora";
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
    $dateEvento = $db->queryDB("SELECT Giorno, Mese, Anno FROM DateEvento");

    if(!$dateEvento) return "errore_db_date_evento";

    return $dateEvento;
} //RESTITUITO: giorno, mese, anno delle date dell'evento o messaggio di errore. Viene interrogato il database.

function getCorsiDisponibili($db, $nGiorno, $nOra) {
    $lista_corsi = $db->queryDB("SELECT Nome, Aula, Durata, Informazioni, MaxPosti AS PostiTotali, PostiRimasti FROM Corsi C INNER JOIN SessioniCorsi S ON C.ID_Corso=S.ID_Corso WHERE Giorno=$nGiorno AND Ora=$nOra AND PostiRimasti>0 ORDER BY Nome ASC");

    //operazione da eseguire se il db non ha restituito valori
    if(!$lista_corsi) return "errore_db_lista_corsi";

    //operazione da eseguire se il db ha restituito la lista delle sessioni dei corsi disponibili in quella ora e giorno
    return $lista_corsi;
} //RESTITUITO: corsi disponibili per l'iscrizione all'ora=$nOra e giorno=$nGiorno o messaggio di errore. Viene interrogato il database.

function getRegistroPresenzeSessioneCorso($db, $id) {
    $regPresenze = $db->queryDB("SELECT P.Cognome, P.Nome, R.Presenza, R.ID_Iscrizione FROM Persone AS P, Iscrizioni AS I, SessioniCorsi AS S, RegPresenze AS R WHERE I.ID_Studente=P.ID_Persona AND S.ID_SessioneCorso=I.ID_SessioneCorso AND R.ID_Iscrizione=I.ID_Iscrizione AND S.ID_SessioneCorso=$id ORDER BY Cognome, Nome");
    
    if(!$regPresenze) return "errore_db_registro_presenze";

    return $regPresenze;
} //RESTITUITO: registro presenze della sessione corso con ID_SessioneCorso=$id o messaggio di errore. Viene interrogato il database.


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
        $dati["Username"],
        $dati["Mail"],
        $dati["PrimoAccessoEffettuato"],
        $dati["GiornoIscritto"],
        $dati["OraIscritta"],
        $dati["Livello"]
    );

    return $utente;
} //RESTITUITO: oggetto di classe Utente se l'interrogazione è andata a buon fine, false altrimenti. Viene interrogato il database.

function primoAccesso($db, $cl, $s, $ind, $postPass) {
    $password = md5($postPass); // DA CAMBIARE
    $id_paf = $db->queryDB("SELECT ID_Persona, PrimoAccessoEffettuato FROM Persone P INNER JOIN Classi C ON P.ID_Classe=C.ID_Classe WHERE Classe='".$cl."' AND Sezione='".$s."' AND Indirizzo='".$ind."' AND Pwd='".$password."'");
    if(!$id_paf) return "errore_db_dati_input";

    $id = intval($id_paf[0]["ID_Persona"]);
    $pae = intval($id_paf[0]["PrimoAccessoEffettuato"]);
    
    if($pae > 0) return "primo_accesso_effettuato";

    $utente = inizializzaUtente($db, $id);
    if(!$utente) return "errore_db_idpersona";

    return array(
        "nome" => $utente->getNome(),
        "cognome" => $utente->getCognome(),
        "classe" => $utente->classe->getClasse() . "°" . $utente->classe->getSezione() . " " . $utente->classe->getIndirizzo(),
        "ruolo" => $utente->getLivello() == 1 ? "Studente" : ($utente->getLivello() == 2 ? "Responsabile di corso" : "Amministratore dell'evento")
    );
} //RESTITUITO: array contenente i dati da mostrare nel form di registrazione se il login è stato effettuato, "errore_db_idpersona" se l'id è non trovato, "errore_db_dati_input" se i dati input non corrispondono. Viene interrogato il database.

function login($db, $user_identification, $pwd_user) {
    $data = $db->queryDB("SELECT ID_Persona, Pwd, PrimoAccessoEffettuato FROM Persone WHERE (Mail = '" . $user_identification . "' OR Username = '" . $user_identification . "')");
    if(!$data) return "errore_db_dati_input";
    
    $id = intval($data[0]["ID_Persona"]);
    $pae = intval($data[0]["PrimoAccessoEffettuato"]);
    $pwd_db = $data[0]["Pwd"];

    if($pae == 0) return "primo_accesso_non_effettuato";

    if(!password_verify($pwd_user, $pwd_db)) return "errore_db_password_errata";

    $utente = inizializzaUtente($db, $id);
    if(!$utente) return "errore_db_idpersona";

    Session::set("utente", $utente);

    return "accesso_effettuato";
} //RESTITUITO: "accesso_effettuato" se il login è stato effettuato, "primo_accesso_non_effettuato" se l'utente non ha effettuato la registrazione, "errore_db_idpersona" se l'id è non trovato, "errore_db_dati_input" se i dati input non corrispondono. Viene interrogato il database.

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

function mailEsistente($db, $mail_utente) {
    $result = $db->queryDB("SELECT * FROM Persone WHERE Mail = '" . $mail_utente . "'");
    
    return $result ? true : false;
} //RESTITUITO: true se mail presente nel database, false altrimenti. Viene interrogato il database.

function usernameEsistente($db, $username_utente) {
    $result = $db->queryDB("SELECT * FROM Persone WHERE Username = '" . $username_utente . "'");

    return $result ? true : false;
} //RESTITUITO: true se mail presente nel database, false altrimenti. Viene interrogato il database.

function invioMailConfermaAttivazione($evento, $nome, $cognome, $username, $destinatario, $activation_hash) {
    $subject = $evento . " - Auto.Gest - Conferma iscrizione"; //oggetto della mail 

    $message = "<p>Ciao " . $nome. " " . $cognome . ",<br />"; //messaggio della mail
    $message .= "abbiamo ricevuto la tua richiesta di iscrizione al sistema di gestione di " . $evento . "in cui ti sei registrato con:</p>";
    $message .= "<ul><li>Mail: " . $destinatario . "</li><li>Username: " . $username . "</li></ul>";
    $message .= "<p>Per proseguire con l'attivazione del tuo account clicca sul link seguente:<br />";
    $message .= "<a href='" . getURL("/") . "verificaAccount.php?mail=" . $destinatario ."&hashattivazione=" . $activation_hash . "'>Attiva il tuo profilo per " . $evento . "</a></p>";
                        
    $headers = 'From:auto.gest.ag@gmail.com' . "\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8" . "\r\n";

    mail($destinatario, $subject, $message, $headers);
}

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

function cambioPassword($db, $id_persona, $nuovapwd) { //!!! (da cambiare)
    $cambioEff = $db->queryDB("UPDATE `Persone` SET `Pwd`='" . $nuovapwd . "' WHERE `ID_Persona`=$id_persona");
    
    return $cambioEff;
} //RESTITUITO: true se cambio password a persona di ID_Persona = $id_persona è stato fatto, false altrimenti. Viene interrogato il database.

function iscrittiAltreAttivita($db, $nome_corso = "Altre attività") {
    $iscritti = $db->queryDB("SELECT Cognome, P.Nome AS Nome, Cl.Classe AS Cl, Cl.Sezione AS Sez, Cl.Indirizzo AS Ind, Sc.Giorno AS Gg, Sc.Ora AS Hh FROM Persone AS P, Classi AS Cl, Corsi AS Co, SessioniCorsi AS Sc, Iscrizioni AS I WHERE P.ID_Classe=Cl.ID_Classe AND Sc.ID_Corso=Co.ID_Corso AND I.ID_SessioneCorso=Sc.ID_SessioneCorso AND I.ID_Studente=P.ID_Persona AND Co.Nome='".$nome_corso."' ORDER BY Cognome, Nome, Indirizzo, Classe, Sezione, Ora, Giorno");
    if(!$iscritti) return "errore_db_iscritti_altre_attivita";

    return $iscritti;
} //RESTITUITO: lista di persone iscritte ad altre attività (corso Altre attività) o messaggio di errore. Viene interrogato il database.

/*************************************************************************************************/
/*                                      SEZIONE: I miei corsi                                    */
/*************************************************************************************************/

function getMese($db, $i) {
    $date_evento = getDateEvento($db);

    if($date_evento === "errore_db_date_evento") return "errore_db_mese";
    
    return $date_evento[$i]["Mese"];
} //RESTITUITO: mese del $i+1-esimo giornata dell'evento o messaggio di errore. Viene interrogato il database.

function getGiorno($db, $i) {
    $date_evento = getDateEvento($db);

    if($date_evento === "errore_db_date_evento") return "errore_db_giorno";
    
    return $date_evento[$i]["Giorno"];
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
            $info = $corso['Informazioni'];

            if ($durata === 1) $stringCorso = htmlspecialchars($corso['Nome'], ENT_QUOTES) . " - " . $durata . " ora";
            else $stringCorso = htmlspecialchars($corso['Nome'], ENT_QUOTES) . " - " . $durata . " ore";

            $stringCorsoVal = htmlspecialchars($corso['Nome'], ENT_QUOTES) . "_" . $nOra;
            $blocco .= "<option value='" . $stringCorsoVal . "' data-info='" .$info. "'>$stringCorso</option>";
        }
    }

    $blocco .=          "</select>" .
                        "<div class='input-group-btn'>" .
                            "<button type='submit' id='btnProsegui' class='btn btn-success'><span class='fa fa-check'></span><span class='hidden-xs hidden-sm'>  Prosegui</span></button>" .
                        "</div>" .
                    "</div>" .
                "</div>" .
            "</div>" .
            "<div class='alert alert-warning' role='alert' id='informazioni' hidden></div>";

    echo $blocco;
} //RESTITUITO: pannello di iscrizione con select popolata o vuota (in caso di errore). Viene interrogato il database e creato il pannello.

function getID_SessioneCorso($db, $nomeC, $giorno, $ora) {
    $id_sc = $db->queryDB("SELECT ID_SessioneCorso FROM SessioniCorsi S INNER JOIN Corsi C ON S.ID_Corso=C.ID_Corso WHERE Nome='".$nomeC."' AND Giorno=$giorno AND Ora=$ora"); //ritornato un array

    //se qualcosa va male
    if(!$id_sc) return "errore_db_id_sessione_corso";

    //se invece è tutto ok
    return intval($id_sc[0]["ID_SessioneCorso"]);
} //RESTITUITO: ID_SessioneCorso del corso con nome=$nomeC, del giorno=$giorno e ora=$ora o messaggio di errore. Viene interrogato il database.

function inserisciIscrizione($db, $idStudente, $id_sc) {
    $iscrizione = $db->queryDB("INSERT INTO Iscrizioni (ID_Studente, ID_SessioneCorso) VALUES ($idStudente, $id_sc)"); //restituisce true se insert viene fatta, false altrimenti

    return $iscrizione;
} //RESTITUITO: true se la riga in Iscrizioni è stata inserita, false altrimenti. Viene interrogato il database.

function aggiornaInfoUtente($db, $utente) {
    $gg_hh_aggiornati = $db->queryDB("SELECT GiornoIscritto, OraIscritta FROM Persone WHERE ID_Persona = " . $utente->getID());

    //se qualcosa va male
    if(!$gg_hh_aggiornati) return "errore_db_reperimento_nuovi_gg_hh";

    $utente->setGiornoIscritto($gg_hh_aggiornati[0]["GiornoIscritto"]);
    $utente->setOraIscritta($gg_hh_aggiornati[0]["OraIscritta"]);

    return $utente;
} //RESTITUITO: $utente (aggiornato) se è stata aggiornata la tabella Persone o messaggio di errore. Viene interrogato il database.


/*************************************************************************************************/
/*                                  SEZIONE: Registro Presenze                                   */
/*************************************************************************************************/
function getCorsiGestiti($db, $id_respcorso) {
    $c_gestiti = $db->queryDB("SELECT Nome, Giorno, Ora, ID_SessioneCorso FROM Corsi C INNER JOIN SessioniCorsi S ON C.ID_Corso=S.ID_Corso WHERE ID_Responsabile=$id_respcorso ORDER BY Giorno, Ora");

    if($c_gestiti && (($date = getDateEvento($db)) !== "errore_db_date_evento")) {
        for($i = 0, $l = sizeof($c_gestiti); $i < $l; $i++) {
            $j = intval($c_gestiti[$i]["Giorno"]) - 1; //se il giorno della sessione del corso è 1 (primo), per ottenere la data del calendario devo avere $j = 1 - 1 (ovvero 0);
            $c_gestiti[$i]["Giorno"] = $date[$j]["Giorno"] . " " . $date[$j]["Mese"]; //sostituisco il numero del giorno con l'effettiva data per una maggior comprensione nella lettura
        }
        return $c_gestiti;
    }

    return "errore_db_corsi_gestiti";
} //RESTITUITO: lista delle sessioni dei corsi che gestisce l'utente di id=$id_respcorso o messaggio di errore. Vengono interrogati il database e svolte operazioni sui dati.

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