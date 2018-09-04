<?php
require_once "../caricaClassi.php";
require_once "../connettiAlDB.php";
require_once "../funzioni.php";
Session::open();

if(GlobalVar::getServer("REQUEST_METHOD")==="POST") {
    if(!isset($utente)) {
        $utente=new User(); //dichiaro utente, oggetto di User
    }

    //reperisco i dati
    $cla=GlobalVar::getPost("classe");
    $sez=GlobalVar::getPost("sezione");
    $ind=GlobalVar::getPost("indirizzo");
    $postPass=md5(GlobalVar::getPost("psw"));

    //eseguo la funzione login()
    $risultatoLogin=login($db,$utente,$cla,$sez,$ind,$postPass); //stringa
    echo $risultatoLogin;
} else {
    header("Location: ../");
}
?>
