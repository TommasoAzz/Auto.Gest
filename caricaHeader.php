<?php
$scuola=$info["istituto"];

if(isset($utente)) {
    $nome=$utente->getNome()." ".$utente->getCognome();

    if($utente->getIndirizzo() === "ESTERNO" || $utente->getIndirizzo() === "PERSONALE") {
        $classe=$utente->getIndirizzo();
    } else { //studenti
        $classe=$utente->getClasse()."°".$utente->getSezione()." ".$utente->getIndirizzo();
    }

    switch($utente->getLivello()) {
        case 1: $categoria="Studente"; break;
        case 2: $categoria="Responsabile di un corso"; break;
        case 3: $categoria="Amministratore dell'evento"; break;
    }
}

?>
<div class="navbar navbar-default navbar-fixed-top">
    <div id="navbar-background"></div>
    <div class="container">
        <!-- Logo -->
        <div class="navbar-header">
            <button class="navbar-toggle" data-toggle="collapse" data-target=".navHeaderCollapse"><span class="fa fa-bars"></span></button>
            <a class="navbar-brand" href="/"><?php echo $info["titolo"]; ?></a>
        </div>
        <!-- Menu di Navigazione -->
        <div class="collapse navbar-collapse navHeaderCollapse">
        <?php
            //HEADER
            if(isset($utente)) {
                switch($utente->getLivello()) {
                    case 1: require_once "header_studente.php"; //studente
                        break;
                    case 2: require_once "header_respcorso.php"; //responsabile corso
                        break;
                    case 3: require_once "header_admin.php"; //amministratore
                        break;
                }
                require_once "modal/datiUtente.php";
            } else {
                require_once "header.php";
            }    
        ?>
        </div>
    </div>
</div>