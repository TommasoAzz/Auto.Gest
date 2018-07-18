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
    $status=GlobalVar::getPost("aggiornamenti");
    $status=json_decode($status);

    for($i=0,$l=sizeof($status);$i<$l;$i++){
        $q="UPDATE RegPresenze SET Presenza = ".$status[$i][1]." WHERE ID_Iscrizione = ".$status[$i][0]."";
        $control[$i]=$db->queryDB($q);
    }

    $problemi_zero=true;
    $l=sizeof($control);
    $i=0;
    while($problemi_zero && $i<$l) {
        if(!$control[$i]) {
            $problemi_zero=false;
        }
        $i++;
    }

    if($problemi_zero) {
        echo "registro-aggiornato";
    } else {
        echo "registro-non-aggiornato";
    }
} else {
    header("Location: ../../");
}
?>
