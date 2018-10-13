<?php
    require_once "connettiAlDB.php";
    require_once "caricaClassi.php";
    Session::open();
?>
<?
    $selID_SessioneCorso = "SELECT I.ID_SessioneCorso AS SessioneCorso,I.ID_Iscrizione AS Iscrizione FROM Iscrizioni I INNER JOIN SessioniCorsi S ON S.ID_SessioneCorso=I.ID_SessioneCorso WHERE Ora=5";
	$vSessioniCorsi=$db->queryDB($selID_SessioneCorso);
	for($i=0,$l=sizeof($vSessioniCorsi);$i<$l;$i++) {
        $selPostiRimasti="SELECT PostiRimasti FROM SessioniCorsi WHERE ID_SessioneCorso=".$vSessioniCorsi[$i]['SessioneCorso'];
		$PostiRimasti=$db->queryDB($selPostiRimasti);
        $nonFareUpdate="non-fare-update";
        if($PostiRimasti[0]["PostiRimasti"]==0) {
			echo "Superato il numero di posti massimi in ID_SessioneCorso=".$vSessioniCorsi[$i]['SessioneCorso'].", posizione nell'array: ".$i." di ".sizeof($vSessioniCorsi);
            $nonFareUpdate=$vSessioniCorsi[$i]['SessioneCorso'];
		} else {
            if($vSessioniCorsi[$i]['SessioneCorso'] !== $nonFareUpdate) {
                $updSessioniCorsi = "UPDATE SessioniCorsi SET PostiRimasti=PostiRimasti-1 WHERE ID_SessioneCorso=".$vSessioniCorsi[$i]['SessioneCorso'];
		        $query_fatta1=$db->queryDB($updSessioniCorsi);
                $updRegPresenze = "INSERT INTO RegPresenze (ID_Iscrizione,Presenza) VALUES (".$vSessioniCorsi[$i]['Iscrizione'].",1)";
		        $query_fatta2=$db->queryDB($updRegPresenze);
            } else {

            }
        }
	}
    echo "\nFine blob.php";
?>
