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
    $q="SELECT Nome FROM Corsi ORDER BY Nome ASC";
    $res=$db->qikQuery($q); //ritornato un array
    $jsonData=json_encode($res);
    echo $jsonData;
} else {
    header("Location: ../");
}
?>
