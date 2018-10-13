<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";

if(GlobalVar::SERVER("REQUEST_METHOD")==="POST") {
    $ID_Persona=GlobalVar::POST("ID");

    //SCRIPT DI RESET TOTALE DELLA PERSONA

    //recupero gli ID delle sessioni dei corsi a cui si è iscritto
    $ID_SessioniCorsi=getSessioniStudente($db,$ID_Persona);
    if($ID_SessioniCorsi === "errore_sessioni_corso_studente") echo $ID_SessioniCorsi;
    else {
        $ID_Iscrizione=getIscrizioniStudente($db,$ID_SessioniCorsi);
        if($ID_Iscrizione === "errore_iscrizioni_sessioni") {

        }
    }

    //con gli ID appena recuperati seleziono gli ID delle sue iscrizioni ai suoi corsi scelti
    $qID_Iscrizione="SELECT ID_Iscrizione FROM Iscrizioni WHERE ID_SessioneCorso IN (";
    for($i=0,$l=sizeof($rID_SessioneCorso);$i<$l;$i++) {
        $qID_Iscrizione.=$rID_SessioneCorso[$i]["ID_SessioneCorso"];
        if($i != ($l-1)) $qID_Iscrizione.=", ";
    }
    $qID_Iscrizione.=") AND ID_Studente=".$ID_Persona;
    $rID_Iscrizione=$db->queryDB($qID_Iscrizione);
    $controlloQuery[1]=$db->checkQuery();

    //con gli ID delle iscrizioni appena ottenuti aggiorno i registri presenze cancellando le iscrizioni dello studente
    $qDelRegPresenze="DELETE FROM RegPresenze WHERE ID_Iscrizione IN (";
    for($i=0,$l=sizeof($rID_Iscrizione);$i<$l;$i++) {
        $qDelRegPresenze.=$rID_Iscrizione[$i]["ID_Iscrizione"];
        if($i != ($l-1)) $qDelRegPresenze.=", ";
    }
    $qDelRegPresenze.=")";
    $rDelRegPresenze=$db->queryDB($qDelRegPresenze);
    $controlloQuery[2]=$db->checkQuery();

    //con gli ID delle iscrizioni ottenuti aggiorno le iscrizioni ai corsi
    $qDelIscrizioni="DELETE FROM Iscrizioni WHERE ID_SessioneCorso IN (";
    for($i=0,$l=sizeof($rID_SessioneCorso);$i<$l;$i++) {
        $qDelIscrizioni.=$rID_SessioneCorso[$i]["ID_SessioneCorso"];
        if($i != ($l-1)) $qDelIscrizioni.=", ";
    }
    $qDelIscrizioni.=") AND ID_Studente=".$ID_Persona;
    $rDelIscrizioni=$db->queryDB($qDelIscrizioni);
    $controlloQuery[3]=$db->checkQuery();

    //incremento i posti disponibili dei corsi che lo studente non frequenta più
    $qUpdSessioniCorsi="UPDATE SessioniCorsi SET PostiRimasti=PostiRimasti+1 WHERE ID_SessioneCorso IN (";
    for($i=0,$l=sizeof($rID_SessioneCorso);$i<$l;$i++) {
        $qUpdSessioniCorsi.=$rID_SessioneCorso[$i]["ID_SessioneCorso"];
        if($i != ($l-1)) $qUpdSessioniCorsi.=", ";
    }
    $qUpdSessioniCorsi.=")";
    $rUpdSessioniCorsi=$db->queryDB($qUpdSessioniCorsi);
    $controlloQuery[4]=$db->checkQuery();

    //aggiorno le informazioni sullo studente in modo che possa iscriversi nuovamente
    $qUpdPersone="UPDATE Persone SET GiornoIscritto=0, OraIscritta=0 WHERE ID_Persona=$ID_Persona";
    $rUpdPersone=$db->queryDB($qUpdPersone);
    $controlloQuery[5]=$db->checkQuery();

    //controllo che le query siano andate tutte a buon fine
    $i=0; $l=sizeof($controlloQuery);
    while($i<$l && $tuttoOk) if($controlloQuery[$i] === false) $tuttoOk=false;

    //inoltro il risultato al chiamante
    echo ($tuttoOk) ? "reset-effettuato" : "reset-non-effettuato";

} else {
    header("Location: ../");
}
?>
