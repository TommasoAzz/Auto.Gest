<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST" || !GlobalVar::issetPOST("aA")) header("Location: ../../");

$aA = $db->escape(GlobalVar::POST("aA"));

$char_array_aA = str_split($aA);// trasforma la stringa in un array di caratteri

$banned_tags = ["script", "iframe"]; //array di tutti i tag che si vogliono rimuovere dalla stringa html

foreach($banned_tags as $tag) {
    $pos = strpos($aA, "<"); //trova il primo carattere "<" (apertura di un tag)
    while($pos) {
        if(substr($aA, $pos+1, strlen($tag)) === $tag || substr($aA, $pos+2, strlen($tag)) === $tag) { // se la stringa sopo il carattere "<" è uguale ad un tag allora:
            $char_array_aA[$pos] = "&lt;"; // sostituisce "<" con il carattere speciale
            $endTag_pos = strpos($aA, ">", $pos); // trova il carattere ">" successivo
            $char_array_aA[$endTag_pos] = "&gt;"; // sostituisce "<" con il carattere speciale
        }

        $pos = strpos($aA, "<", $pos+1); //aggiorna la posizione
    } 
}

$aA = implode("", $char_array_aA);

$modificaEff = $db->queryDB("UPDATE AltreAttivita SET Lista='".$aA."' WHERE ID=1");

echo ($modificaEff) ? "modifica-effettuata" : "modifica-non-effettuata";