<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";
Session::open();
$utente=Session::get("utente");

if(GlobalVar::getServer("REQUEST_METHOD")==="POST") {
    //reperisco i dati POST
    $corso=explode("_",GlobalVar::getPost("corso")); //è un array
    //divido le informazioni ottenute da $corso
    $nomeCorso=$corso[0]; echo $nomeCorso."\t";
    $oraCorso=intval($corso[1]); echo $oraCorso."\t";
    $giornoCorso=intval(getGiornoDaIscriversi($db,$utente)); echo $giornoCorso."\t";
    $oreTotGiorno=getNumOre($db,$giornoCorso); echo $oreTotGiorno."\t";
    /*************************************************************************/
    /*    SCRIPT DI ISCRIZIONE AD UNA SESSIONE DI UN CORSO DI UNA PERSONA    */
    /*************************************************************************/

    //------ AGGIORNO Iscrizioni, Persone, SessioniCorsi, RegPresenze --//
    try {
        //-- SELEZIONE dell'ID della SESSIONE DEL CORSO >>>> $ID_SessioneCorso
        $ID_SessioneCorso=getID_SessioneCorso($db,$nomeCorso,$giornoCorso,$oraCorso);
        //se qualcosa va male
        if($ID_SessioneCorso === "errore_db_id_sessione_corso") throw new Exception($ID_SessioneCorso); //possibile lancio eccezione
  

        //-- RICHIESTA dei POSTI RIMASTI della SESSIONE DEL CORSO selezionato >>>> $postiRimasti
        $postiRimasti=getPostiRimastiSessione($db,$ID_SessioneCorso);
        //se qualcosa va male
        if(gettype($postiRimasti) === "string") throw new Exception($postiRimasti);


        //-- RICHIESTA della DURATA del CORSO >>>> $durata
        $datiCorso=getDatiCorso($db,$nomeCorso);
        //se qualcosa va male
        if($datiCorso === "errore_db_dati_corso") throw new Exception("errore_db_durata_corso");
        //se invece è tutto ok
        $durata=intval($datiCorso[0]["Durata"]);


        //-- REGISTRAZIONE DELL'ISCRIZIONE (inserisco riga in Iscrizioni, decremento PostiRimasti in SessioniCorsi, inserisco riga in RegPresenze)
        $registrataIscrizione=registraIscrizione($db,$utente->getId(),$ID_SessioneCorso);
        //se qualcosa va male
        if(gettype($registrataIscrizione) === "string") throw new Exception($registrataIscrizione);


        //-- AGGIORNAMENTO della tabella PERSONE 
        $utenteAggiornato=aggiornaInfoUtente($db,$utente,$giornoCorso,$oreTotGiorno,$oraCorso,$durata);
        //se qualcosa va male
        if($utenteAggiornato === "errore_db_upd8_persone") throw new Exception($utenteAggiornato);

        //se invece è tutto ok (è terminata l'iscrizione)
        header("Location: ../");

    } catch(Exception $exc) {
        $error=$exc->getMessage();
        Session::set("errIscrizione",$error);
        header("Location: ../messaggio.php");
    }
} else {
    header("Location: ../../");
}
?>
