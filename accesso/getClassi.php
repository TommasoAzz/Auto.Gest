<?php
require_once "../caricaClassi.php";
require_once "../connettiAlDB.php";
Session::open();

if(GlobalVar::SERVER("REQUEST_METHOD")==="POST") {
    $ind = GlobalVar::POST("indirizzo");

    $classi = getClassi($ind);

    if($classi === "errore_db_classi_istituto") echo $classi;
    else {
        $jsonData = json_encode($classi);
        echo $jsonData;
    }
} else {
    header("Location: ../");
}
?>
