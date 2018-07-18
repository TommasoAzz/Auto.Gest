<?php
require_once "../../connettiAlDB.php";
require_once "../../caricaClassi.php";
include_once "../../getInfo.php";
require_once "../../funzioni.php";
Session::open();
$info=Session::get("info");
$db=Session::get("db");
$utente=Session::get("utente");

if(GlobalVar::getServer("REQUEST_METHOD")==="POST") {
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
    header("Location: ../");
}
?>
