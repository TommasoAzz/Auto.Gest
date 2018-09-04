<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";

if(GlobalVar::getServer("REQUEST_METHOD")==="POST") {
    $altreAttivita=getAltreAttivita($db);
    echo $altreAttivita;
} else {
    header("Location: ../");
}
?>
