<?php
require_once "datiDB.php"; //recupero dati di accesso
require_once "caricaClassi.php"; //recupero classi

$db=new Database(dbHost,dbUser,dbPwd,dbName); //allocamento del database

$db->connect(); //connessione a database

$utf8_set=$db->queryDB("SET NAMES 'utf8'");
?>
