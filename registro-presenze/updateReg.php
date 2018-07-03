<?php
require_once "../classes.php";
if(GlobalVar::getServer("REQUEST_METHOD")==="POST") {
    Session::open();
    require_once "../connectToDB.php";
    $db=Session::get("db");
    $utente=Session::get("utente");
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
    header("Location: /");
}
?>