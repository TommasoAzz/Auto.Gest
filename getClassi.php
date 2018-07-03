<?php
    require_once "classes.php";
    if(GlobalVar::getServer("REQUEST_METHOD")==="POST") {
        Session::open();
        require_once "connectToDB.php";
        header("Content-Type: text/html;charset=utf-8");
        $ind=GlobalVar::getPost("indirizzo");
        $db=Session::get("db");
        $query="SELECT Classe,Sezione FROM Classi WHERE Indirizzo='".$ind."' ORDER BY Classe,Sezione";
        $res=$db->queryDB($query); //ritornato un array
        $jsonData=json_encode($res);
        echo $jsonData;
    } else {
        header("Location: /");
    } 
?>