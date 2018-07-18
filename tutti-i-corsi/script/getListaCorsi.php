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
    $giorno=GlobalVar::getPost("giorno");
    $ora=GlobalVar::getPost("ora");
    $q="SELECT Nome,Aula,Durata,MaxPosti AS PostiTotali,PostiRimasti FROM Corsi C INNER JOIN SessioniCorsi S ON C.ID_Corso=S.ID_Corso WHERE Giorno=$giorno AND Ora=$ora AND Nome != 'Altre attività' ORDER BY Nome ASC";
    $res=$db->queryDB($q); //ritornato un array
    $jsonData=json_encode($res);
    echo $jsonData;
} else {
    header("Location: ../../");
}
?>
