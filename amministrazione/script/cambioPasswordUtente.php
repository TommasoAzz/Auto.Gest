<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";

if(GlobalVar::SERVER("REQUEST_METHOD")==="POST") {
    $ID=GlobalVar::POST("ID");
    $password=md5(GlobalVar::POST("Pwd"));

    $query="UPDATE `Persone` SET `Pwd`='".$password."' WHERE `ID_Persona`=".intval($ID);
    $cambioEff=$db->queryDB($query);

    if($cambioEff) {
        echo "cambio-effettuato";
    } else {
        echo "cambio-non-effettuato";
    }

} else {
    header("Location: ../");
}
?>