<?php
require_once "../caricaClassi.php";
require_once "../connettiAlDB.php";
require_once "../funzioni.php";

if(GlobalVar::SERVER("REQUEST_METHOD")==="POST") {
    $indirizzi = getIndirizzi($db);

    if($indirizzi === "errore_db_indirizzi") echo $indirizzi;
    else {
        $jsonData = json_encode($indirizzi);
        echo $jsonData;
    }
} else {
    header("Location: ../");
}
?>
