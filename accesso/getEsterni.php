<?php
require_once "../caricaClassi.php";
require_once "../connettiAlDB.php";
require_once "../funzioni.php";

if(GlobalVar::SERVER("REQUEST_METHOD")==="POST") {
    $esterni = getEsterni($db);

    if($esterni === "errore_db_esterni") echo $esterni;
    else {
        $jsonData = json_encode($esterni);
        echo $jsonData;
    }
} else {
    header("Location: ../");
}
?>
