<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST") header("Location: ../../");

$aA = $db->escape(GlobalVar::POST("aA"));

$bad_tags = ["<script>", "</script>"]; //array di tutti i tag che si vogliono rimuovere dalla stringa html

foreach($bad_tags as $tag){
    $aA = str_replace($tag, "", $aA);
}

$modificaEff = $db->queryDB("UPDATE AltreAttivita SET Lista='".$aA."' WHERE ID=1");

echo ($modificaEff) ? "modifica-effettuata" : "modifica-non-effettuata";