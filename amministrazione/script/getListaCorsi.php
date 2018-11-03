<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";

if(GlobalVar::SERVER("REQUEST_METHOD")==="POST") {
    $corsi = getDatiCorsi($db);

    if($corsi === "errore_db_lista_corsi") echo $corsi;
    else {
        $jsonData = json_encode($corsi);
        echo $jsonData;
    } 
} else {
    header("Location: ../");
}
?>
