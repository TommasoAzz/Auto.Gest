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
    $aA=GlobalVar::getPost("aA");

    $query="UPDATE AltreAttivita SET Lista='".$aA."' WHERE ID=1";
    $modificaEff=$db->queryDB($query);

    if($modificaEff) {
        echo "modifica-effettuata";
    } else {
        echo "modifica-non-effettuata";
    }

} else {
    header("Location: ../");
}
?>
