<?php
require_once "../caricaClassi.php";
require_once "../connettiAlDB.php";
require_once "../funzioni.php";

if(GlobalVar::SERVER("REQUEST_METHOD")==="POST") {
    header("Content-Type: text/html;charset=utf-8");
    
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
