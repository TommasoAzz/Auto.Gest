<?php
    require_once "connectToDB.php";
    require_once "classes.php";
    Session::open();
    header("Content-Type: text/html;charset=utf-8");
    $db=Session::get("db");
    $queryInfo="SELECT Titolo,PeriodoSvolgimento,NomeContatto1,NomeContatto2,NomeContatto3,LinkContatto1,LinkContatto2,LinkContatto3,Istituto FROM InfoEvento WHERE ID=1";
    $seEseguita=$db->doQuery($queryInfo);
    if($db->checkQuery() && $seEseguita!==false && $db->getAffectedRows()!=0) {
        $info=array(
            "titolo"=>$db->getResult("Titolo"),
            "periodosvolgimento"=>$db->getResult("PeriodoSvolgimento"),
            "nomecontatto1"=>$db->getResult("NomeContatto1"),
            "linkcontatto1"=>$db->getResult("LinkContatto1"),
            "nomecontatto2"=>$db->getResult("NomeContatto2"),
            "linkcontatto2"=>$db->getResult("LinkContatto2"),
            "nomecontatto3"=>$db->getResult("NomeContatto3"),
            "linkcontatto3"=>$db->getResult("LinkContatto3"),
            "istituto"=>$db->getResult("Istituto")
        );
    } else {
        $info=array(
            "titolo"=>"Err. titolo",
            "periodosvolgimento"=>"Err. periodo-svolgimento",
            "nomecontatto1"=>"Err. nomecontatto1",
            "linkcontatto1"=>"Err. linkcontatto1",
            "nomecontatto2"=>"Err. nomecontatto2",
            "linkcontatto2"=>"Err. linkcontatto2",
            "nomecontatto3"=>"Err. nomecontatto3",
            "linkcontatto3"=>"Err. linkcontatto3",
            "istituto"=>"Err. istituto"
        ); 
    }
    Session::set("info",$info);
?>