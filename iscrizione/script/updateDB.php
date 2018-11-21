<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";
Session::open();
$utente = Session::get("utente");

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST") header("Location: ../../");
$strCorso = explode("_", GlobalVar::POST("corso"));

echo GlobalVar::POST("corso");
var_dump($strCorso);

//divido le informazioni ottenute da $corso
$sc = new SessioneCorso();
$sc->setGiorno(getGiornoDaIscriversi($db, $utente));
$sc->setOra($db->escape($strCorso[1]));

$sc->corso = new Corso();
$sc->corso->setNome($db->escape($strCorso[0]));

/*************************************************************************/
/*    SCRIPT DI ISCRIZIONE AD UNA SESSIONE DI UN CORSO DI UNO STUDENTE    */
/*************************************************************************/

//------ AGGIORNO Iscrizioni, Persone, SessioniCorsi, RegPresenze --//
try {
    //-- SELEZIONE dell'ID della SESSIONE DEL CORSO >>>> $ID_SessioneCorso
    $id_sc = getID_SessioneCorso($db, $sc->corso->getNome(), $sc->getGiorno(), $sc->getOra());

    if($id_sc === "errore_db_id_sessione_corso") throw new Exception($id_sc); //possibile lancio eccezione

    $sc->setID($id_sc);

    //-- RICHIESTA dei POSTI RIMASTI della SESSIONE DEL CORSO selezionato >>>> $postiRimasti
    $postiRimasti = getPostiRimastiSessione($db, $sc->getID());

    if($postiRimasti === "errore_db_posti_sessione_corso" || $postiRimasti === "posti_terminati_sessione_corso") throw new Exception($postiRimasti);

    $sc->setPostiRimasti($postiRimasti);

    //-- RICHIESTA della DURATA del CORSO >>>> $durata
    $datiCorso = getDatiCorsi($db, "nome", $sc->corso->getNome());

    if($datiCorso === "errore_db_nome_corso" || $datiCorso === "errore_db_dati_input") throw new Exception("errore_db_durata_corso"); //cambio la stringa di errore per una maggiore comprensione in caso di errore

    $sc->corso->setDurata($datiCorso["Durata"]);

    //-- REGISTRAZIONE DELL'ISCRIZIONE (inserisco riga in Iscrizioni, decremento PostiRimasti in SessioniCorsi, inserisco riga in RegPresenze)
    $registrataIscrizione = registraIscrizione($db, $utente->getID(), $sc->getID());

    if(gettype($registrataIscrizione) === "string") throw new Exception($registrataIscrizione);

    //-- AGGIORNAMENTO della tabella PERSONE
    $utenteAggiornato = aggiornaInfoUtente($db, $utente, $sc->getGiorno(), $sc->corso->getDurata());

    if($utenteAggiornato === "errore_db_upd8_persone") throw new Exception($utenteAggiornato);

    $utente = $utenteAggiornato;
    Session::set("utente", $utente);

    //-- ISCRIZIONE ALLA SESSIONE DI CORSO TERMINATA, REFRESH DELLA PAGINA
    header("Location: ../");
} catch(Exception $exc) {
    $error = $exc->getMessage();
    echo $sc->corso->getNome(). " ". $sc->getOra();
    //Session::set("errIscrizione", $error);
    //header("Location: ../iscrizione.php");
}
