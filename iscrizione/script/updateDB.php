<?php
require_once "../../connettiAlDB.php";
require_once "../../caricaClassi.php";
include_once "../../getInfo.php";
require_once "../../funzioni.php";
Session::open();
$info=Session::get("info");
$db=Session::get("db");
$utente=Session::get("utente");

if(GlobalVar::getServer("REQUEST_METHOD")==="POST") {
    //reperisco i dati POST
    $corso=explode("_",GlobalVar::getPost("corso")); //è un array
    //divido le informazioni ottenute da $corso
    $nomeCorso=$corso[0];
    $oraCorso=intval($corso[1]);
    $giornoCorso=intval(getGiornoDaIscriversi($db,$utente));
    $oreTotGiorno=getNumOre($db,$giornoCorso);
    /*************************************************************************/
    /*    SCRIPT DI ISCRIZIONE AD UNA SESSIONE DI UN CORSO DI UNA PERSONA    */
    /*************************************************************************/

    //------ AGGIORNO Iscrizioni, Persone, SessioniCorsi, RegPresenze --//
    try {
        //-- SELEZIONE dell'ID della SESSIONE DEL CORSO
        //query al db
        $q_id="SELECT ID_SessioneCorso FROM SessioniCorsi S INNER JOIN Corsi C ON S.ID_Corso=C.ID_Corso WHERE Nome='".$nomeCorso."' AND Giorno=".$giornoCorso." AND Ora=".$oraCorso;
        $r_id=$db->queryDB($q_id); //ritornato un array

        //se qualcosa va male
        if(!$r_id) throw new Exception("errore_db_id_sessione_corso"); //possibile lancio eccezione
        //se invece è tutto ok
        $ID_SessioneCorso=$r_id[0]["ID_SessioneCorso"];

        //-- RICHIESTA dei POSTI RIMASTI della SESSIONE DEL CORSO selezionato
        //query al db
        $q_posti="SELECT PostiRimasti FROM SessioniCorsi WHERE ID_SessioneCorso=$ID_SessioneCorso";
        $r_posti=$db->queryDB($q_posti);

        //se qualcosa va male
        if(!$r_posti) throw new Exception("errore_db_posti_sessione_corso"); //possibile lancio eccezione
        //se invece è tutto ok
        $PostiRimasti=intval($r_posti[0]["PostiRimasti"]);
        if($PostiRimasti <= 0) throw new Exception("posti_terminati_sessione_corso"); //possibile lancio eccezione (si spera per il = 0 e non per il minore di 0)

        //-- AGGIORNAMENTO della tabella delle ISCRIZIONI
        //query al db
        $q_iscrizioni="INSERT INTO Iscrizioni (ID_Studente,ID_SessioneCorso) VALUES (".$utente->getId().",$ID_SessioneCorso)";
        $r_iscrizioni=$db->queryDB($q_iscrizioni);

        //se qualcosa va male
        if(!$r_iscrizioni) throw new Exception("errore_db_upd8_iscrizioni"); //possibile lancio eccezione

        //-- RICHIESTA della DURATA del CORSO
        //query al db
        $q_durata="SELECT Durata FROM Corsi C INNER JOIN SessioniCorsi S ON C.ID_Corso=S.ID_Corso WHERE ID_SessioneCorso=$ID_SessioneCorso";
        $r_durata=$db->queryDB($q_durata);

        //se qualcosa va male
        if(!$r_durata) throw new Exception("errore_db_durata_corso"); //possibile lancio eccezione
        //se invece è tutto ok
        $durata=intval($r_durata[0]["Durata"]);

        //-- AGGIORNAMENTO della tabella PERSONE
        $nuova_oraIscritta=$utente->getOraIscritta()+$durata;
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
        if(!$r_persone) throw new Exception("errore_db_upd8_persone"); //possibile lancio eccezione

        //-- AGGIORNAMENTO della SESSIONE DEL CORSO (decremento i POSTI DISPONIBILI)
        //query al db
        $q_posti="UPDATE SessioniCorsi SET PostiRimasti=".($PostiRimasti-1)." WHERE ID_SessioneCorso=$ID_SessioneCorso";
        $r_posti=$db->queryDB($q_posti);

        //se qualcosa va male
        if(!$r_posti) throw new Exception("errore_db_upd8_posti_rimasti");

        //-- RICERCA del CODICE della ISCRIZIONE creata
        //query al db
        $q_iscrizione="SELECT ID_Iscrizione FROM Iscrizioni WHERE ID_SessioneCorso=$ID_SessioneCorso AND ID_Studente=".$utente->getId();
        $r_iscrizione=$db->queryDB($q_iscrizione);

        //se qualcosa va male
        if(!$r_iscrizione) throw new Exception("errore_db_id_iscrizione");
        //se invece è tutto ok
        $ID_Iscrizione=$r_iscrizione[0]["ID_Iscrizione"];

        //-- AGGIORNAMENTO del REGISTRO PRESENZE della SESSIONE DEL CORSO
        //query al db
        $q_registro="INSERT INTO RegPresenze (ID_Iscrizione) VALUES ($ID_Iscrizione)";
        $r_registro=$db->queryDB($q_registro);

        //se qualcosa va male
        if(!$r_registro) throw new Exception("errore_db_upd8_registro");
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
