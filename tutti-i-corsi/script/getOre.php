<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
Session::open();

if(GlobalVar::SERVER("REQUEST_METHOD")==="POST") {
    $q="SELECT DISTINCT Ora FROM SessioniCorsi ORDER BY Ora ASC";
    $res=$db->queryDB($q); //ritornato un array
    $jsonData=json_encode($res);
    echo $jsonData;
} else {
    header("Location: ../../");
}
?>
