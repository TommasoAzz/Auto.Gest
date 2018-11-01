<?php
$scuola = $info["istituto"];

if(isset($utente)) {
    $nome = $utente->getNome() . " " . $utente->getCognome();

    if($utente->classe->getIndirizzo() === "ESTERNO" || $utente->classe->getIndirizzo() === "PERSONALE") {
        $classe = $utente->classe->getIndirizzo();
    } else { //studenti
        $classe = $utente->classe->getClasse() . "°" . $utente->classe->getSezione() . " " . $utente->classe->getIndirizzo();
    }

    switch($utente->getLivello()) {
        case 1: $categoria = "Studente"; break;
        case 2: $categoria = "Responsabile di un corso"; break;
        case 3: $categoria = "Amministratore dell'evento"; break;
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
                //seleziono la barra di navigazione in base al livello dell'utente
                switch($utente->getLivello()) {
                    case 1: require_once "navbar_studente.php"; //studente
                        break;
                    case 2: require_once "navbar_respcorso.php"; //responsabile corso
                        break;
                    case 3: require_once "navbar_admin.php"; //amministratore
                        break;
                }
            } else {
                //seleziono la barra di navigazione base
                require_once "navbar.php";
            }    
        ?>
        </div>
    </div>
</div>
<?php if(isset($utente)): require_once "modal/datiUtente.php"; endif; ?>