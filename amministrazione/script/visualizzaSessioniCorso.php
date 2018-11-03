<?php
require_once "../../caricaClassi.php";
require_once "../../connettiAlDB.php";
require_once "../../funzioni.php";

if(GlobalVar::SERVER("REQUEST_METHOD")==="POST") {
    $nomeC = GlobalVar::POST("nomeCorso");

    $datiCorso = getDatiCorsi($db,"nome",$db->escape($nomeC));

    if($datiCorso === "errore_db_nome_corso") echo $datiCorso;
    else {
        $id = intval($datiCorso["ID_Corso"]);
        $sessioniCorso = getSessioniCorso($db, $id);

        if($sessioniCorso === "errore_db_sessioni_corso") echo $sessioniCorso;
        else {
            $datiDaInviare["corso"] = json_encode($datiCorso);
            $datiDaInviare["sessioniCorso"] = json_encode($sessioniCorso);

            $jsonData = json_encode($datiDaInviare);
            echo $jsonData;
        }
    }
} else {
    header("Location: ../");
}
?>
