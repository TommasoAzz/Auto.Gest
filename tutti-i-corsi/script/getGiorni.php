<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
Session::open();

if(GlobalVar::getServer("REQUEST_METHOD")==="POST") {
    $q="SELECT Giorno,Mese,Anno FROM DateEvento";
    $res=$db->queryDB($q); //ritornato un array
    $jsonData=json_encode($res);
    echo $jsonData;
} else {
    header("Location: ../../");
}
?>
