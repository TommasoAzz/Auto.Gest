<?php
    require_once "access.php"; //recupero dati di accesso
    require_once "classes.php"; //recupero classi
    
    Session::open();
    if(!isset($db)) {
        $db=new Database(dbHost,dbUser,dbPwd,dbName); //allocamento del database 
    }
    $db->connect(); //connessione a database
    $utf8_set=$db->queryDB("SET NAMES 'utf8'");
    Session::set("db",$db);
    
    function getBaseURL() { //recupero dell'URL della pagina per creare link assoluti
        if((isset($_SERVER["HTTPS"]) && GlobalVar::getServer("HTTPS") == "on") || Session::is_secure()) {
            $base_url="https://";
        } else {
            $base_url='http://';
        }
        if(GlobalVar::getServer("SERVER_PORT") != "80") {
            $base_url.=GlobalVar::getServer("SERVER_NAME").":".GlobalVar::getServer("SERVER_PORT");
        } else {
            $base_url.=GlobalVar::getServer("SERVER_NAME");
        }
        return $base_url;
    }

?>