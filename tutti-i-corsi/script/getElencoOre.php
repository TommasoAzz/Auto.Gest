<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST") header("Location: ../../");
$giorno = GlobalVar::POST("giorno");

$elencoOre = getElencoOre($db, ($giorno == "") ? 0 : intval($giorno));

if($elencoOre === "errore_db_elenco_ore") echo $elencoOre;
else echo json_encode($elencoOre);
