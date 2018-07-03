<?php
    require_once "classes.php";
    if(GlobalVar::getServer("REQUEST_METHOD")==="POST") {
        Session::open();
        require_once "connectToDB.php";
        header("Content-Type: text/html;charset=utf-8");
        $db=Session::get("db");
        $query="SELECT DISTINCT Classe AS extC,Sezione AS extS,Indirizzo AS extI FROM Classi WHERE Classe IN ('E','P') ORDER BY Indirizzo";
        $res=$db->queryDB($query); //ritornato un array
        $jsonData=json_encode($res);
        echo $jsonData;
    } else {
        header("Location: /");
    }
?>