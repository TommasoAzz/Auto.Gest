<?php
require_once "../caricaClassi.php";
require_once "../connettiAlDB.php";

if(GlobalVar::getServer("REQUEST_METHOD")==="POST") {
    header("Content-Type: text/html;charset=utf-8");
    $query="SELECT DISTINCT Indirizzo FROM Classi WHERE NOT (Classe='E' OR Classe='P') ORDER BY Indirizzo";
    $res=$db->queryDB($query); //ritornato un array
    $jsonData=json_encode($res);
    echo $jsonData;
} else {
    header("Location: ../");
}
?>
