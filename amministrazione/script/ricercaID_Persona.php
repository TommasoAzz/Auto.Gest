<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";

if(GlobalVar::SERVER("REQUEST_METHOD")==="POST") {
    $nome=GlobalVar::POST("nome");
    $cognome=GlobalVar::POST("cognome");
    
    $dati=getDatiPersona($db,$nome,$cognome);

    if($dati === "errore_db_idPersona") echo $dati;
    else if(gettype($dati) == "integer") { //il risultato ottenuto è semplicemente l'ID richiesto (ovvero una sola persona si chiama $nome $cognome)
        echo $dati;
    } else {
        $jsonData=json_encode($dati);
        echo $jsonData;
    }
} else {
    header("Location: ../");
}
?>
