<?php
require_once "../caricaClassi.php";
require_once "../connettiAlDB.php";
require_once "../funzioni.php";
Session::open();

if(GlobalVar::SERVER("REQUEST_METHOD")==="POST") {
    //reperisco i dati
    $cla = GlobalVar::POST("classe");
    $sez = GlobalVar::POST("sezione");
    $ind = GlobalVar::POST("indirizzo");
    $postPass = md5(GlobalVar::POST("psw"));

    //eseguo la funzione login()
    $risultatoLogin = login($db, $cla, $sez, $ind, $postPass); //stringa
    echo $risultatoLogin;
} else {
    header("Location: ../");
}
?>
