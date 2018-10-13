<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
Session::open();

if(GlobalVar::SERVER("REQUEST_METHOD")==="POST") {
    $giorno=GlobalVar::POST("giorno");
    $ora=GlobalVar::POST("ora");
    $q="SELECT Nome,Aula,Durata,MaxPosti AS PostiTotali,PostiRimasti FROM Corsi C INNER JOIN SessioniCorsi S ON C.ID_Corso=S.ID_Corso WHERE Giorno=$giorno AND Ora=$ora AND Nome != 'Altre attività' ORDER BY Nome ASC";
    $res=$db->queryDB($q); //ritornato un array
    $jsonData=json_encode($res);
    echo $jsonData;
} else {
    header("Location: ../../");
}
?>
