<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";
Session::open();

if(GlobalVar::SERVER("REQUEST_METHOD") !== "POST" || !(GlobalVar::issetPOST("username") && GlobalVar::issetPOST("psw"))) header("Location: ../../");

$username = $db->escape(GlobalVar::POST("username"));
$psw = $db->escape(GlobalVar::POST("psw"));

$risultatoLogin = login($db, $username, $psw);

echo json_encode($risultatoLogin);