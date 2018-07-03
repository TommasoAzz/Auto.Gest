<?php
    require_once "../classes.php";
    if(GlobalVar::getServer("REQUEST_METHOD")==="POST") {
        Session::open();
        require_once "../connectToDB.php";
        $db=Session::get("db");
        $id=GlobalVar::getPost("id");
        $q="SELECT P.Cognome, P.Nome, R.Presenza, R.ID_Iscrizione FROM Persone AS P, Iscrizioni AS I, SessioniCorsi AS S, RegPresenze AS R WHERE I.ID_Studente=P.ID_Persona AND S.ID_SessioneCorso=I.ID_SessioneCorso AND R.ID_Iscrizione=I.ID_Iscrizione AND S.ID_SessioneCorso=$id ORDER BY Cognome, Nome";
        $res=$db->queryDB($q); //ritornato un array
        $jsonData=json_encode($res);
        echo $jsonData;
    } else {
        header("Location: /");
    }
?>