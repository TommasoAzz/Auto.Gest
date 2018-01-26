<?php
    //HEADER
    if(Session::is_set("utente")) {
        $utente=Session::get("utente");
        $livello=$utente->getLivello();
        switch($livello) {
            case 1: require_once "header_user.php"; //studente
                break;
            case 2: require_once "header_respcorso.php"; //responsabile corso
                break;
            case 3: require_once "header_admin.php"; //admin
                break;
        }
    } else {
        require_once "header.php";
    }    
?>