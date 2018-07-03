<?php
    require_once "connectToDB.php";
    require_once "classes.php";
    Session::open();
    header("Content-Type: text/html;charset=utf-8");
    $db=Session::get("db");
    
    $queryInfo="SELECT Titolo,PeriodoSvolgimento,NomeContatto1,NomeContatto2,NomeContatto3,LinkContatto1,LinkContatto2,LinkContatto3,Istituto FROM InfoEvento WHERE ID=1";
    $result_info=$db->queryDB($queryInfo);
    if(sizeof($result_info) > 0) {
        $info=array(
            "titolo" => $result_info[0]["Titolo"],
            "periodosvolgimento" => $result_info[0]["PeriodoSvolgimento"],
            "nomecontatto1" => $result_info[0]["NomeContatto1"],
            "linkcontatto1" => $result_info[0]["LinkContatto1"],
            "nomecontatto2" => $result_info[0]["NomeContatto2"],
            "linkcontatto2" => $result_info[0]["LinkContatto2"],
            "nomecontatto3" => $result_info[0]["NomeContatto3"],
            "linkcontatto3" => $result_info[0]["LinkContatto3"],
            "istituto" => $result_info[0]["Istituto"]
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