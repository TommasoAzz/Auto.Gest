<?php
require_once "../../connettiAlDB.php";
require_once "../../caricaClassi.php";
include_once "../../getInfo.php";
require_once "../../funzioni.php";
Session::open();
$info=Session::get("info");
$db=Session::get("db");
$utente=Session::get("utente");

if(GlobalVar::getServer("REQUEST_METHOD")==="POST") {
    $query="SELECT Lista FROM AltreAttivita WHERE ID=1";
    $query2="SELECT COUNT(*) AS Esiste FROM Corsi WHERE Nome='Altre attività'";
    $res=$db->qikQuery($query);
    $res2=$db->qikQuery($query2);
    if($res !== false && trim($res[0]["Lista"]) !== "" && $res2[0]["Esiste"] !== "0") {
        $altreAttivita=trim($res[0]["Lista"]);
    } else {
        $altreAttivita="no-altre-attivita";
    }
    echo $altreAttivita;
} else {
    header("Location: ../");
}
?>
