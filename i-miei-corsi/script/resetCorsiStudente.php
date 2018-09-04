<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";
Session::open();
$utente=Session::get("utente");

if(GlobalVar::getServer("REQUEST_METHOD")==="POST") {
    /**************************************************************/
    /*  SCRIPT DI RESET TOTALE DELLE ISCRIZIONI DI UNO STUDENTE   */
    /**************************************************************/

    $ID_Persona=$utente->getId();

    // VARIABILI DI CONTROLLO
    $tuttoOk=true;
    $controlloQuery=array();

    //----RECUPERO DELLE SESSIONI DEI CORSI A CUI SI E' ISCRITTO L'UTENTE
    $qID_SessioneCorso="SELECT ID_SessioneCorso FROM Iscrizioni WHERE ID_Studente=$ID_Persona";
    $rID_SessioneCorso=$db->queryDB($qID_SessioneCorso);
    $controlloQuery[0]=$db->checkQuery();

    //----RECUPERO I CODICI DELLE ISCRIZIONI REGISTRATE SULLE PRECEDENTI SESSIONI DEI CORSI
    $qID_Iscrizione="SELECT ID_Iscrizione FROM Iscrizioni WHERE ID_SessioneCorso IN (";
    for($i=0,$l=sizeof($rID_SessioneCorso);$i<$l;$i++) {
        $qID_Iscrizione.=$rID_SessioneCorso[$i]["ID_SessioneCorso"];
        if($i != ($l-1)) $qID_Iscrizione.=", ";
    }
    $qID_Iscrizione.=") AND ID_Studente=".$ID_Persona;
    $rID_Iscrizione=$db->queryDB($qID_Iscrizione);
    $controlloQuery[1]=$db->checkQuery();

    //----ELIMINAZIONE DELLE ISTANZE NEL REGISTRO DELLE PRESENZE RELATIVE AI CODICI DELLE ISCRIZIONI PRECEDENTI
    $qDelRegPresenze="DELETE FROM RegPresenze WHERE ID_Iscrizione IN (";
    for($i=0,$l=sizeof($rID_Iscrizione);$i<$l;$i++) {
        $qDelRegPresenze.=$rID_Iscrizione[$i]["ID_Iscrizione"];
        if($i != ($l-1)) $qDelRegPresenze.=", ";
    }
    $qDelRegPresenze.=")";
    $rDelRegPresenze=$db->queryDB($qDelRegPresenze);
    $controlloQuery[2]=$db->checkQuery();

    //----ELIMINAZIONE DEI CODICI DELLE ISCRIZIONI REGISTRATE SULLE PRECEDENTI SESSIONI DEI CORSI
    $qDelIscrizioni="DELETE FROM Iscrizioni WHERE ID_SessioneCorso IN (";
    for($i=0,$l=sizeof($rID_SessioneCorso);$i<$l;$i++) {
        $qDelIscrizioni.=$rID_SessioneCorso[$i]["ID_SessioneCorso"];
        if($i != ($l-1)) $qDelIscrizioni.=", ";
    }
    $qDelIscrizioni.=") AND ID_Studente=".$ID_Persona;
    $rDelIscrizioni=$db->queryDB($qDelIscrizioni);
    $controlloQuery[3]=$db->checkQuery();

    //----AGGIORNO I POSTI DISPONIBILI IN SessioniCorsi
    $qUpdSessioniCorsi="UPDATE SessioniCorsi SET PostiRimasti=PostiRimasti+1 WHERE ID_SessioneCorso IN (";
    for($i=0,$l=sizeof($rID_SessioneCorso);$i<$l;$i++) {
        $qUpdSessioniCorsi.=$rID_SessioneCorso[$i]["ID_SessioneCorso"];
        if($i != ($l-1)) $qUpdSessioniCorsi.=", ";
    }
    $qUpdSessioniCorsi.=")";
    $rUpdSessioniCorsi=$db->queryDB($qUpdSessioniCorsi);
    $controlloQuery[4]=$db->checkQuery();

    //----AGGIORNO I CONTATORI DELLE REGISTRAZIONI DELLL'UTENTE NEL DATABASE
    $qUpdPersone="UPDATE Persone SET GiornoIscritto=0, OraIscritta=0 WHERE ID_Persona=$ID_Persona";
    $rUpdPersone=$db->queryDB($qUpdPersone);
    $controlloQuery[5]=$db->checkQuery();

    //----AGGIORNO L'OGGETTO DELL'UTENTE
    $utente->setGiornoIscritto(0);
    $utente->setOraIscritta(0);


    //----CONTROLLO CHE TUTTE LE QUERY SIANO ANDATE A BUON FINE
    $i=0; $l=sizeof($controlloQuery);
    while($i<$l && $tuttoOk) if($controlloQuery[$i] === false) $tuttoOk=false;

    echo ($tuttoOk) ? "reset-effettuato" : "reset-non-effettuato";

} else {
    header("Location: ../../");
}
?>
