<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";
Session::open();

if(GlobalVar::SERVER("REQUEST_METHOD")==="POST") {
    $giorno = GlobalVar::POST("giorno");

    $elencoOre = getElencoOre($db, ($giorno == "") ? 0 : intval($giorno));
    
    if($elencoOre === "errore_db_elenco_ore") echo $elencoOre;
    else {
        $jsonData = json_encode($elencoOre);
        echo $jsonData;
    }
} else {
    header("Location: ../../");
}
?>
