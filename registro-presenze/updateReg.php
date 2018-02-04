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
            $db->sendQuery($q);
            $control[$i]=$db->checkQuery();
        }
        $problemi_zero=true;
        for($i=0,$l=sizeof($control);$i<$l;$i++) {
            if($control[$i]==false) {
                $problemi_zero=false;
            }
        }
        if($problemi_zero==true) {
            echo "registro-aggiornato";
        } else {
            echo "registro-non-aggiornato";
        }
    } else {
        header("Location: /");
    }
?>