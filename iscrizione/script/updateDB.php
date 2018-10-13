<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";
Session::open();
$utente = Session::get("utente");

if(GlobalVar::SERVER("REQUEST_METHOD")==="POST") {
    //reperisco i dati POST
    $strCorso = explode("_", GlobalVar::POST("corso")); //è un array
    
    //divido le informazioni ottenute da $corso
    $sc = new SessioneCorso();
    $sc->setGiorno(getGiornoDaIscriversi($db, $utente));
    $sc->setOra($strCorso[1]);

    $sc->corso = new Corso();
    $sc->corso->setNome($db->escape($strCorso[0]));

    $oreTotGiorno = getNumOre($db, $sc->getGiorno());
    /*************************************************************************/
    /*    SCRIPT DI ISCRIZIONE AD UNA SESSIONE DI UN CORSO DI UNA PERSONA    */
    /*************************************************************************/

    //------ AGGIORNO Iscrizioni, Persone, SessioniCorsi, RegPresenze --//
    try {
        //-- SELEZIONE dell'ID della SESSIONE DEL CORSO >>>> $ID_SessioneCorso
        $id_sc = getID_SessioneCorso($db, $sc->corso->getNome(), $sc->getGiorno(), $sc->getOra());
        //se qualcosa va male
        if($id_sc === "errore_db_id_sessione_corso") throw new Exception($id_sc); //possibile lancio eccezione
        else {
            $sc->setID($id_sc);
            unset($id_sc);
        }

        //-- RICHIESTA dei POSTI RIMASTI della SESSIONE DEL CORSO selezionato >>>> $postiRimasti
        $postiRimasti = getPostiRimastiSessione($db, $sc->getID());
        //se qualcosa va male
        if(gettype($postiRimasti) === "string") throw new Exception($postiRimasti);
        else {
            $sc->setPostiRimasti($postiRimasti);
            unset($postiRimasti);
        }

        //-- RICHIESTA della DURATA del CORSO >>>> $durata
        $datiCorso = getDatiCorso($db, $sc->corso->getNome());
        //se qualcosa va male
        if($datiCorso === "errore_db_dati_corso") throw new Exception("errore_db_durata_corso"); //cambio la stringa di errore per una maggiore comprensione in caso di errore
        else {
            $sc->corso->setDurata($datiCorso["Durata"]);
            unset($datiCorso);
        }

        //-- REGISTRAZIONE DELL'ISCRIZIONE (inserisco riga in Iscrizioni, decremento PostiRimasti in SessioniCorsi, inserisco riga in RegPresenze)
        $registrataIscrizione = registraIscrizione($db, $utente->getID(), $sc->getID());
        //se qualcosa va male
        if(gettype($registrataIscrizione) === "string") throw new Exception($registrataIscrizione);
        else {
            unset($registrataIscrizione);
        }

        //-- AGGIORNAMENTO della tabella PERSONE 
        $utenteAggiornato = aggiornaInfoUtente($db, $utente, $sc->getGiorno(), $oreTotGiorno, $sc->getOra(), $sc->corso->getDurata());
        //se qualcosa va male
        if($utenteAggiornato === "errore_db_upd8_persone") throw new Exception($utenteAggiornato);
        else {
            unset($utenteAggiornato);
        }
        //se invece è tutto ok (è terminata l'iscrizione)
        header("Location: ../");
    } catch(Exception $exc) {
        $error = $exc->getMessage();
        Session::set("errIscrizione",$error);
        header("Location: ../iscrizione.php");
    }
} else {
    header("Location: ../../");
}
?>
