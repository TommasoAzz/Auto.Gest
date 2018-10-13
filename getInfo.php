<?php
require_once "caricaClassi.php";
require_once "connettiAlDB.php";
Session::open();
header("Content-Type: text/html;charset=utf-8");

$queryInfo = "SELECT Titolo,PeriodoSvolgimento,NomeContatto1,NomeContatto2,NomeContatto3,LinkContatto1,LinkContatto2,LinkContatto3,Istituto FROM InfoEvento WHERE ID=1";
$result_info = $db->queryDB($queryInfo);

if($resInfo = $result_info[0]) {
    $info=array(
        "titolo" => $resInfo["Titolo"],
        "periodosvolgimento" => $resInfo["PeriodoSvolgimento"],
        "nomecontatto1" => $resInfo["NomeContatto1"],
        "linkcontatto1" => $resInfo["LinkContatto1"],
        "nomecontatto2" => $resInfo["NomeContatto2"],
        "linkcontatto2" => $resInfo["LinkContatto2"],
        "nomecontatto3" => $resInfo["NomeContatto3"],
        "linkcontatto3" => $resInfo["LinkContatto3"],
        "istituto" => $resInfo["Istituto"]
    );
} else {
    $info=array(
        "titolo" => "Err. titolo",
        "periodosvolgimento" => "Err. periodo-svolgimento",
        "nomecontatto1" => "Err. nomecontatto1",
        "linkcontatto1" => "Err. linkcontatto1",
        "nomecontatto2" => "Err. nomecontatto2",
        "linkcontatto2" => "Err. linkcontatto2",
        "nomecontatto3" => "Err. nomecontatto3",
        "linkcontatto3" => "Err. linkcontatto3",
        "istituto" => "Err. istituto"
    );
}

Session::set("info",$info);
?>
