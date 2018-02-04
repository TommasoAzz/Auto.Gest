<?php
require_once "../classes.php";
    if(GlobalVar::getServer("REQUEST_METHOD")==="POST") {
        Session::open();
        require_once "../connectToDB.php";
        $db=Session::get("db");
        $ID_Persona=GlobalVar::getPost("ID");
        
        //variabili di controllo
        $tuttoOk=true;
        $controlloQuery=array();
        
        //script di reset totale delle iscrizioni di una persona
        $qID_SessioneCorso="SELECT ID_SessioneCorso FROM Iscrizioni WHERE ID_Studente=$ID_Persona";
        $rID_SessioneCorso=$db->qikQuery($qID_SessioneCorso);
        $controlloQuery[0]=$db->checkQuery();
        
        $qID_Iscrizione="SELECT ID_Iscrizione FROM Iscrizioni WHERE ID_SessioneCorso IN (";
        for($i=0,$l=sizeof($rID_SessioneCorso);$i<$l;$i++) {
            $qID_Iscrizione.=$rID_SessioneCorso[$i]["ID_SessioneCorso"];
            if($i != ($l-1)) {
                $qID_Iscrizione.=", ";
            }
        }
        $qID_Iscrizione.=") AND ID_Studente=".$ID_Persona;
        $rID_Iscrizione=$db->qikQuery($qID_Iscrizione);
        $controlloQuery[1]=$db->checkQuery();

        $qDelRegPresenze="DELETE FROM RegPresenze WHERE ID_Iscrizione IN (";
        for($i=0,$l=sizeof($rID_Iscrizione);$i<$l;$i++) {
            $qDelRegPresenze.=$rID_Iscrizione[$i]["ID_Iscrizione"];
            if($i != ($l-1)) {
                $qDelRegPresenze.=", ";
            }
        }
        $qDelRegPresenze.=")";
        $db->sendQuery($qDelRegPresenze);
        $controlloQuery[2]=$db->checkQuery();

        $qDelIscrizioni="DELETE FROM Iscrizioni WHERE ID_SessioneCorso IN (";
        for($i=0,$l=sizeof($rID_SessioneCorso);$i<$l;$i++) {
            $qDelIscrizioni.=$rID_SessioneCorso[$i]["ID_SessioneCorso"];
            if($i != ($l-1)) {
                $qDelIscrizioni.=", ";
            }
        }
        $qDelIscrizioni.=") AND ID_Studente=".$ID_Persona;
        $db->sendQuery($qDelIscrizioni);
        $controlloQuery[3]=$db->checkQuery();

        $qUpdSessioniCorsi="UPDATE SessioniCorsi SET PostiRimasti=PostiRimasti+1 WHERE ID_SessioneCorso IN (";
        for($i=0,$l=sizeof($rID_SessioneCorso);$i<$l;$i++) {
            $qUpdSessioniCorsi.=$rID_SessioneCorso[$i]["ID_SessioneCorso"];
            if($i != ($l-1)) {
                $qUpdSessioniCorsi.=", ";
            }
        }
        $qUpdSessioniCorsi.=")";
        $db->sendQuery($qUpdSessioniCorsi);
        $controlloQuery[4]=$db->checkQuery();

        $qUpdPersone="UPDATE Persone SET GiornoIscritto=0, OraIscritta=0 WHERE ID_Persona=$ID_Persona";
        $db->sendQuery($qUpdPersone);
        $controlloQuery[5]=$db->checkQuery();

        //controllo che le query siano andate tutte a buon fine
        for($i=0,$l=sizeof($controlloQuery);$i<$l;$i++) {
            if($controlloQuery[$i]==false) {
                $tuttoOk=false;
            }
        }
        if($tuttoOk==true) {
            echo "reset-effettuato";
        } else {
            echo "reset-non-effettuato";
        }

    } else {
        header("Location: /");
    }
?>




