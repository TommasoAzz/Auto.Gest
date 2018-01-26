<?php
require_once "../classes.php";
    if(GlobalVar::getServer("REQUEST_METHOD")==="POST") {
        Session::open();
        require_once "../connectToDB.php";
        $db=Session::get("db");
        $ID=GlobalVar::getPost("ID");
        $password=md5(GlobalVar::getPost("Password"));

        $query="UPDATE `Persone` SET `Password`='".$password."' WHERE `ID_Persona`=".intval($ID);
        $cambio=$db->sendQuery($query); //restituisce falso anche se esegue la query

        $queryControllo="SELECT `Password` AS psw FROM `Persone` WHERE `ID_Persona`=".intval($ID);
        $res=$db->qikQuery($query);

        if($res[0]["psw"] == $password) {
            echo "cambio-effettuato";
        } else {
            echo "cambio-non-effettuato";
        }

    } else {
        header("Location: /");
    }
?>