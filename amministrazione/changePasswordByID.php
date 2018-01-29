<?php
require_once "../classes.php";
    if(GlobalVar::getServer("REQUEST_METHOD")==="POST") {
        Session::open();
        require_once "../connectToDB.php";
        $db=Session::get("db");
        $ID=GlobalVar::getPost("ID");
        $password=md5(GlobalVar::getPost("Pwd"));

        $query="UPDATE `Persone` SET `Pwd`='".$password."' WHERE `ID_Persona`=".intval($ID);
        $cambioEff=$db->sendQuery($query); 

        if($cambioEff) {
            echo "cambio-effettuato";
        } else {
            echo "cambio-non-effettuato";
        }

    } else {
        header("Location: /");
    }
?>