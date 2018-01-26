<?php
    require_once "connectToDB.php";
    require_once "classes.php";
    Session::open();
    $utente=Session::get("utente");
    $db=Session::get("db");
    $info=Session::get("info"); 
    if(!isset($db) && !isset($utente)) {
        header("Location: /");
    } else {
        $nome=$utente->getNome()." ".$utente->getCognome();
        $classe=$utente->getClasse().$utente->getSezione()." ".$utente->getIndirizzo();  
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
            <ul class="nav navbar-nav navbar-right">
                <li id="home"><a href="<?php echo getBaseURL().'/'; ?>"><span class="hidden-xs hidden-sm fa fa-home"></span><span class="visible-xs visible-sm">Home</span></a></li>
                <li id="tuttiICorsi"><a href="<?php echo getBaseURL()."/tutti-i-corsi/"; ?>">Tutti i corsi</a></li>
                <li id="iscrizione"><a href="<?php echo getBaseURL()."/iscrizione/"; ?>">Iscrizione</a></li>
                <li id="amministrazione"><a href="<?php echo getBaseURL()."/amministrazione/"; ?>">Amministrazione</a></li>    
                <li id="gestioneCorso"><a href="<?php echo getBaseURL()."/gestione-corso/"; ?>">Gestione corso</a></li>
                <li id="iMieiCorsi"><a href="<?php echo getBaseURL()."/i-miei-corsi/"; ?>">I miei corsi</a></li>
                <li class="dropdown" id="autoGest"><a href="<?php echo getBaseURL()."/autogest/"; ?>">Auto.Gest <span class='caret'></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo getBaseURL()."/licenza/"; ?>">Licenza</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <?php
                        echo "<a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">".$nome." <span class=\"caret\"></span></a>";
                    ?>
                    <ul class="dropdown-menu">
                        <li><a href="#" data-toggle="modal" data-target="#datiUtente"><span class="fa fa-info"></span>&nbsp;&nbsp;&nbsp;&nbsp;I tuoi dati</a></li>
                        <li><a href="<?php echo getBaseURL().'/logout.php'; ?>"><span class="fa fa-sign-out"></span>&nbsp;&nbsp;Esci</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>
<?php require_once "infoModal.php"; ?>