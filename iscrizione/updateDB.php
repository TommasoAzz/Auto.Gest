<?php
require_once "../classes.php";
if(GlobalVar::getServer("REQUEST_METHOD")==="POST") {
    Session::open();
    require_once "../connectToDB.php";
    require_once "funzioni-iscrizione.php";
    $db=Session::get("db");
    $utente=Session::get("utente");
    //reperisco i dati POST 
    $corso=explode("_",GlobalVar::getPost("corso"));
    $nomeCorso=$corso[0];
    $giornoCorso=getGiornoDaIscriversi($db,$utente);
    $oraCorso=$corso[1];
    
    //-- CONTROLLO CHE LO STUDENTE NON SIA GIA' ISCRITTO NELL'ORA E NEL GIORNO SELEZIONATI --//
   
    $q_controllo="SELECT ID_Iscrizione FROM Iscrizioni I INNER JOIN SessioniCorsi S ON I.ID_SessioneCorso=S.ID_SessioneCorso WHERE ID_Studente=".$utente->getId()." AND Giorno=$giornoCorso AND Ora=$oraCorso";
    $r_controllo=$db->qikQuery($q_controllo);
    //se il valore è diverso da false significa che lo studente aveva già effettuato una scelta per quell'ora
    if($r_controllo!==false) { //assicurarsi che sia il controllo giusto
        $ID_Iscrizione=$r_controllo[0]["ID_Iscrizione"];
        //preparo query per tabella Iscrizioni
        $q_del_Iscrizioni="DELETE FROM Iscrizioni WHERE ID_Iscrizione=".$ID_Iscrizione;
        //se OraIscritta=0 e GiornoIscritto>0 significa che la persona è iscritta totalmente a 1, 2, n giorni e nessun'altra ora
        //prendo la durata del corso
        $q_durata='SELECT Durata FROM Corsi WHERE Nome="'.$nomeCorso.'"'; //cerco per nome tanto è univoco
        $r_durata=$db->qikQuery($q_durata);
        $durata=intval($r_durata[0]["Durata"]);
        $q_upd_Persone="UPDATE Persone SET OraIscritta=OraIscritta-".$durata." WHERE ID_Persona=".$utente->getId();  
        $db->sendQuery($q_del_Iscrizioni);
        $db->sendQuery($q_upd_Persone);
    }

    //-- AGGIORNO Iscrizioni, Persone, SessioniCorsi, RegPresenze --//
   
    //seleziono l'ID della sessione di corso
    $q_id='SELECT ID_SessioneCorso FROM SessioniCorsi S INNER JOIN Corsi C ON S.ID_Corso=C.ID_Corso WHERE Nome="'.$nomeCorso.'" AND Giorno=$giornoCorso AND Ora=$oraCorso';
    $r_id=$db->qikQuery($q_id); //ritornato un array
    $ID_SessioneCorso=$r_id[0]["ID_SessioneCorso"];


    //seleziono i posti rimasti del corso selezionato
    $q_posti="SELECT PostiRimasti FROM SessioniCorsi WHERE ID_SessioneCorso=$ID_SessioneCorso";
    $r_posti=$db->qikQuery($q_posti);
    $PostiRimasti=intval($r_posti[0]["PostiRimasti"]);
    
    //controllo se iscrivere la persona è fattibile (i posti rimasti sono ancora superiori a 0)
    if($PostiRimasti>0) {
        //SQL UPDATE di tabella Iscrizioni
        $q_iscrizioni="INSERT INTO Iscrizioni (ID_Studente,ID_SessioneCorso) VALUES (".$utente->getId().",$ID_SessioneCorso)";
        $db->sendQuery($q_iscrizioni);

        //trovo la durata del corso
        $q_durata="SELECT Durata FROM Corsi C INNER JOIN SessioniCorsi S ON C.ID_Corso=S.ID_Corso WHERE ID_SessioneCorso=$ID_SessioneCorso"; //cerco per nome tanto è univoco
        $r_durata=$db->qikQuery($q_durata);
        $durata=$r_durata[0]["Durata"];

        //SQL UPDATE di tabella Persone
        if($oraCorso != getNumOre($db,$giornoCorso)) {   
            $oraIscritta_new=$utente->getOraIscritta()+intval($durata);
            if($oraIscritta_new==getNumOre($db,$giornoCorso)) {
                $utente->setOraIscritta(0);  
                $utente->setGiornoIscritto(intval($giornoCorso));
                $q_persone="UPDATE Persone SET OraIscritta=0, GiornoIscritto=".$utente->getGiornoIscritto()." WHERE ID_Persona=".$utente->getId();
            } else {
                $utente->setOraIscritta($oraIscritta_new);
                $q_persone="UPDATE Persone SET OraIscritta=".$utente->getOraIscritta()." WHERE ID_Persona=".$utente->getId();
            }

            //SQL UPDATE SessioniCorsi in PostiRimasti
            $q_posti = "UPDATE SessioniCorsi SET PostiRimasti=PostiRimasti-1 WHERE ID_SessioneCorso=$ID_SessioneCorso";
            $db->sendQuery($q_posti);
            
            $q_iscrizione="SELECT ID_Iscrizione FROM Iscrizioni WHERE ID_SessioneCorso=$ID_SessioneCorso AND ID_Studente=".$utente->getId()." ORDER BY ID_Iscrizione DESC"; //cerco per nome tanto è univoco
            $r_iscrizione=$db->qikQuery($q_iscrizione);
            $ID_Iscrizione=$r_iscrizione[0]["ID_Iscrizione"];

            //SQL INSERT RegPresenze
            $q_registro="INSERT INTO RegPresenze (ID_Iscrizione, Presenza) VALUES ($ID_Iscrizione,1)";
            $db->sendQuery($q_registro);
        } else {
            $utente->setOraIscritta(0);
            $utente->setGiornoIscritto(intval($giornoCorso));
            $q_persone="UPDATE Persone SET OraIscritta=0, GiornoIscritto=".$utente->getGiornoIscritto()." WHERE ID_Persona=".$utente->getId();
            //SQL UPDATE SessioniCorsi in PostiRimasti
            $q_posti = "UPDATE SessioniCorsi SET PostiRimasti=PostiRimasti-1 WHERE ID_SessioneCorso=$ID_SessioneCorso";
            $db->sendQuery($q_posti);

            $q_iscrizione="SELECT ID_Iscrizione FROM Iscrizioni WHERE ID_SessioneCorso=$ID_SessioneCorso AND ID_Studente=".$utente->getId()." ORDER BY ID_Iscrizione DESC"; //cerco per nome tanto è univoco
            $r_iscrizione=$db->qikQuery($q_iscrizione);
            $ID_Iscrizione=$r_iscrizione[0]["ID_Iscrizione"];

            //SQL INSERT RegPresenze
            $q_registro="INSERT INTO RegPresenze (ID_Iscrizione) VALUES ($ID_Iscrizione)";
            $db->sendQuery($q_registro);
        }
        $db->sendQuery($q_persone);
        header("Location: /iscrizione/");
    } else {
        $ris="sessioneCorso";
        Session::set("errIscrizione",$ris);
        header("Location: messaggio.php");
    }
} else {
    header("Location: /");
}
?>