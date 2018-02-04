<?php
    require_once "classes.php";
    if(GlobalVar::getServer("REQUEST_METHOD")==="POST") {
        Session::open();
        require_once "connectToDB.php";
        header("Content-Type: text/html;charset=utf-8");
        $db=Session::get("db");
        $query="SELECT DISTINCT Indirizzo FROM Classi WHERE NOT (Classe='E' OR Classe='P') ORDER BY Indirizzo";
        $res=$db->qikQuery($query); //ritornato un array
        $jsonData=json_encode($res);
        echo $jsonData;
    } else {
        header("Location: /");
    }
?>