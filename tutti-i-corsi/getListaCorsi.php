<?php
    require_once "../classes.php";
    if(GlobalVar::getServer("REQUEST_METHOD")==="POST") {
        Session::open();
        require_once "../connectToDB.php";
        $db=Session::get("db");
        $giorno=GlobalVar::getPost("giorno");
        $ora=GlobalVar::getPost("ora");
        $q="SELECT Nome,Aula,Durata,MaxPosti AS PostiTotali,PostiRimasti FROM Corsi C INNER JOIN SessioniCorsi S ON C.ID_Corso=S.ID_Corso WHERE Giorno='".$giorno."' AND Ora='".$ora."' ORDER BY Nome ASC";
        $res=$db->qikQuery($q); //ritornato un array
        $jsonData=json_encode($res);
        echo $jsonData;
    } else {
        header("Location: /tutti-i-corsi/");
    }
?>