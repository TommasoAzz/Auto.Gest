<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";
Session::open();
$utente = Session::get("utente");

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST" || !GlobalVar::issetPOST("corso")) header("Location: ../../");
$strCorso = explode("_", GlobalVar::POST("corso"));

//divido le informazioni ottenute da $corso
$sc = new SessioneCorso();
$sc->setGiorno(getGiornoDaIscriversi($db, $utente));
$sc->setOra($db->escape($strCorso[1]));

$sc->corso = new Corso();
$sc->corso->setNome($db->escape($strCorso[0]));

/*************************************************************************/
/*   SCRIPT DI ISCRIZIONE AD UNA SESSIONE DI UN CORSO DI UNO STUDENTE    */
/*************************************************************************/

//------ AGGIORNO Iscrizioni, Persone, SessioniCorsi, RegPresenze --//
try {
    //-- SELEZIONE dell'ID della SESSIONE DEL CORSO >>>> $ID_SessioneCorso
    $id_sc = getID_SessioneCorso($db, $sc->corso->getNome(), $sc->getGiorno(), $sc->getOra());

    if($id_sc === "errore_db_id_sessione_corso") throw new Exception($id_sc); //possibile lancio eccezione

    $sc->setID($id_sc);

    //-- REGISTRAZIONE ISCRIZIONE (INSERT INTO Iscrizioni, il resto delle operazioni le fanno i due trigger)
    $iscrizione_salvata = inserisciIscrizione($db, $utente->getID(), $sc->getID());
    
    if(!$iscrizione_salvata) throw new Exception("errore_db_upd8_iscrizioni");

    //-- AGGIORNAMENTO della tabella PERSONE
    $utenteAggiornato = aggiornaInfoUtente($db, $utente);

    if($utenteAggiornato === "errore_db_reperimento_nuovi_gg_hh") throw new Exception($utenteAggiornato);
    
    $utente = $utenteAggiornato;
    Session::set("utente", $utente);

    //-- ISCRIZIONE ALLA SESSIONE DI CORSO TERMINATA, REFRESH DELLA PAGINA
    header("Location: ../");
} catch(Exception $exc) {
    $error = $exc->getMessage();
    Session::set("errIscrizione", $error);
    header("Location: ../iscrizione.php");
}
