<?php
require_once "caricaClassi.php";
require_once "connettiAlDB.php";
require_once "funzioni.php";
require_once "getInfo.php";
Session::open();
$info = Session::get("info");

//pagina si deve aprire solo quando c'è stato un click nel link inviato per mail
if(GlobalVar::SERVER("REQUEST_METHOD") !== "GET" || !(GlobalVar::issetGET("mail") && GlobalVar::issetGET("hashattivazione"))) header("Location: /");

$mail = $db->escape(GlobalVar::GET("mail"));
$activation_hash = $db->escape(GlobalVar::GET("hashattivazione"));
$messaggioPerUtente = "";

$accountPresente = $db->queryDB("SELECT ID_Persona, PrimoAccessoEffettuato FROM Persone WHERE HashAttivazioneProfilo = '" . $activation_hash  . "' AND Mail = '" . $mail . "'");

if(!$accountPresente) $messaggioPerUtente =  "C'è stato un errore nell'elaborazione della richiesta.";
else {
    $pae = $accountPresente[0]["PrimoAccessoEffettuato"];
    if($pae > 0) $messaggioPerUtente = "Hai già effettuato la verifica dell'account. Clicca <a href='" . getURL("/") . "' qui per tornare alla homepage.";
    else {
        $id_persona = $accountPresente[0]["ID_Persona"];
        $aggiornamentoProfilo = $db->queryDB("UPDATE Persone SET PrimoAccessoEffettuato = 1 WHERE ID_Persona = $id_persona");

        if(!$aggiornamentoProfilo) $messaggioPerUtente = "C'è stato un errore nell'aggiornamento del profilo collegato all'indirizzo mail: " . $mail;
        else header("Location: /"); //è andato tutto a buon fine
    }
}
?>
<html>
    <head>
        <?php require_once "../head.php"; ?>
    </head>
    <body>
    <div id="wrapper" class="clearfix"><!-- inizio wrapper -->
    <!-- NAVBAR -->
    <?php require "../caricaNavbar.php"; ?>
    <div id="content" class="container">
        <!-- INTESTAZIONE PAGINA -->
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <h1 class="text-center">Verifica account</h1>
                <h4 class="text-center sottotitolo"><?php echo $info['titolo']; ?></h4>
                <hr>
            </div>
        </div>
        <!-- CORPO PAGINA -->
        <div class="row">
            <div class="hidden-xs hidden-sm col-md-4 col-lg-4"></div>
            <div id="modulo" class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                <?php if(!$accountPresente || !$aggiornamentoProfilo): ?>
                <div class="alert alert-danger" role="alert"><? echo $messaggioPerUtente; endif; ?></div>
                <?php if($pae > 0): ?>
                <div class="alert alert-warning" role="alert"><? echo $messaggioPerUtente; endif; ?></div>
            </div>
            <div class="hidden-xs hidden-sm col-md-4 col-lg-4"></div>
        </div>
    </div>
    <!-- FOOTER -->
    <?php require_once "../footer.php"; ?>
    </div><!-- fine wrapper -->
    </body>
</html>
