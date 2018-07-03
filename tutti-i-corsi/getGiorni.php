<?php
    require_once "../classes.php";
    if(GlobalVar::getServer("REQUEST_METHOD")==="POST") {
        Session::open();
        require_once "../connectToDB.php";
        $db=Session::get("db");
        $q="SELECT Giorno,Mese,Anno FROM DateEvento";
        $res=$db->queryDB($q); //ritornato un array
        $jsonData=json_encode($res);
        echo $jsonData;
    } else {
        header("Location: /");
    }
?>