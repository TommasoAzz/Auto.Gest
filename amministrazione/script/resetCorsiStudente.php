<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";
Session::open();
$utente = Session::get("utente");

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST" || !GlobalVar::issetPOST("ID")) header("Location: ../../");

$ID_Persona = intval(GlobalVar::POST("ID"));

//SCRIPT DI RESET TOTALE DI UNO STUDENTE

// PASSAGGIO 1: recupero gli ID delle sessioni dei corsi a cui si è iscritto
$corsiStudente = getCorsiStudente($db, $ID_Persona);
if($corsiStudente === "errore_db_corsi_iscritti_studente") echo $corsiStudente;
else {
    $ID_SessioneCorso = [];
    for ($i = 0, $l = sizeof($corsiStudente); $i < $l; $i++) array_push($ID_SessioneCorso, $corsiStudente[$i]["ID_SessioneCorso"]);

    unset($corsiStudente);

    // PASSAGGIO 2: recupero gli ID delle iscrizioni dello studente
    $ID_Iscrizione = getIscrizioniStudente($db, $ID_Persona, $ID_SessioneCorso);
    if ($ID_Iscrizione === "errore_iscrizioni_sessioni") echo $ID_Iscrizione;

    // PASSAGGIO 3: rimozione delle iscrizioni con codice sessione corso presente nell'array $ID_SessioneCorso
    elseif (!rimuoviIscrizioni($db, $ID_Persona, $ID_SessioneCorso)) echo "errore_rimozione_iscrizioni";

    // PASSAGGIO 4: aggiornamento dei dati della persona
    elseif (!$db->queryDB("UPDATE Persone SET GiornoIscritto=0, OraIscritta=0 WHERE ID_Persona=$ID_Persona")) echo "errore_aggiornamento_persone";

    else {
        if($utente->getID() === $ID_Persona) {
            $utente->setGiornoIscritto(0);
            $utente->setOraIscritta(0);
        }
        echo "reset-effettuato";
    }
}
