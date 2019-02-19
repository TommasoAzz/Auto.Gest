<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST") header("Location: ../../");

$aA = $db->escape(GlobalVar::POST("aA"));

$banned_tags = ["script"]; //array di tutti i tag che si vogliono rimuovere dalla stringa html

foreach($banned_tags as $tag){
    $n_of_words = substr_count($aA, $tag);
    $end = "";
    for($i=0; $i<$n_of_words; $i++){
        $pos_iniziale = strpos($aA, $tag)-1;
        if($i == 0)
            $end = substr($aA, 0, $pos_iniziale); //la prima parte di stringa senza il primo <script
        if($aA[$pos_iniziale] == "<"){
            $pos_finale = strpos($aA, ">", 0)+1;
            $str = substr($aA, $pos_finale, strlen($aA));
            $pos_finale1 = strpos($str, ">", 0)+1;
            $pos_finale+=$pos_finale1;
            $aA = substr($aA, $pos_finale, strlen($aA));
            $end.=$aA;
        }
    }
}

$modificaEff = $db->queryDB("UPDATE AltreAttivita SET Lista='".$end."' WHERE ID=1");

echo ($modificaEff) ? "modifica-effettuata" : "modifica-non-effettuata";