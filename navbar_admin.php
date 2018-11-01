<ul class="nav navbar-nav navbar-right">
    <li id="home"><a href="<?php echo getURL('/'); ?>"><span class="hidden-xs hidden-sm fa fa-home"></span><span class="visible-xs visible-sm">Home</span></a></li>
    <li id="tuttiICorsi"><a href="<?php echo getURL('/tutti-i-corsi/'); ?>">Tutti i corsi</a></li>
    <li id="iscrizione"><a href="<?php echo getURL('/iscrizione/'); ?>">Iscrizione</a></li>
    <li id="amministrazione"><a href="<?php echo getURL('/amministrazione/'); ?>">Amministrazione</a></li>
    <li id="registroPresenze"><a href="<?php echo getURL('/registro-presenze/'); ?>">Registro presenze</a></li>
    <li id="iMieiCorsi"><a href="<?php echo getURL('/i-miei-corsi/'); ?>">I miei corsi</a></li>
    <li class="dropdown" id="autoGest"><a href="<?php echo getURL('/autogest/'); ?>">Auto.Gest <span class='caret'></span></a>
        <ul class="dropdown-menu">
            <li><a href="<?php echo getURL('/licenza/'); ?>">Licenza</a></li>
        </ul>
    </li>
    <li class="dropdown">
        <a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-haspopup='true' aria-expanded='false'><?php echo $nome; ?><span class='caret'></span></a>
        <ul class="dropdown-menu">
            <li><a href="#" data-toggle="modal" data-target="#datiUtente"><span class="fa fa-info"></span>&nbsp;&nbsp;&nbsp;&nbsp;I miei dati</a></li>
            <li><a href="<?php echo getURL('/accesso/logout.php'); ?>"><span class="fa fa-sign-out"></span>&nbsp;&nbsp;Esci</a></li>
        </ul>
    </li>
</ul>
