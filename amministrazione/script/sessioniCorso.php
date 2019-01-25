<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";

if(GlobalVar::SERVER("REQUEST_METHOD")==="POST") header("Location: ../../");

$nomeC = $db->escape(GlobalVar::POST("nomeCorso"));

$datiCorso = getDatiCorsi($db, "nome", $nomeC);

if($datiCorso === "errore_db_nome_corso") echo $datiCorso;
else {
    $id = intval($datiCorso["ID_Corso"]);
    $sessioniCorso = getSessioniCorso($db, $id);

    if($sessioniCorso === "errore_db_sessioni_corso") echo $sessioniCorso;
    else {
        $datiDaInviare = array(
            "corso" => json_encode($datiCorso),
            "sessioniCorso" => json_encode($sessioniCorso)
        );
        
        echo json_encode($datiDaInviare);
    }
}

