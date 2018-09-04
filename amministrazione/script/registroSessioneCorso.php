<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";

if(GlobalVar::getServer("REQUEST_METHOD")==="POST") {
    $id=GlobalVar::getPost("ID");

    $presenze=getPresenzeSessione($db,$id);

    if($presenze === "errore_db_presenze") echo $presenze;
    else {
        $jsonData=json_encode($presenze);
        echo $jsonData;
    }

} else {
    header("Location: ../");
}
?>
