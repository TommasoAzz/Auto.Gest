<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";
Session::open();

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST") header("Location: ../../");

$user_identification = $db->escape(GlobalVar::POST("user_identification"));
$psw = $db->escape(GlobalVar::POST("psw"));

$risultatoLogin = login($db, $user_identification, $psw);

echo JSON_encode($risultatoLogin);