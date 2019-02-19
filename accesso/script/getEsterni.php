<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST") header("Location: ../../");

$esterni = getEsterni($db);

if($esterni === "errore_db_esterni") echo $esterni;
else echo json_encode($esterni);