<ul class="nav navbar-nav navbar-right">
    <li id="home"><a href="<?php echo getURL('/'); ?>"><span class="hidden-xs hidden-sm fa fa-home"></span><span class="visible-xs visible-sm">Home</span></a></li>
    <li id="tuttiICorsi"><a href="<?php echo getURL('/tutti-i-corsi/'); ?>">Tutti i corsi</a></li>
    <li class="dropdown" id="autoGest"><a href="<?php echo getURL('/autogest/'); ?>">Auto.Gest <span class='caret'></span></a>
        <ul class="dropdown-menu">
            <li><a href="<?php echo getURL('/licenza/'); ?>">Licenza</a></li>
        </ul>
    </li>
    <li><a href="<?php echo getURL('/accesso/'); ?>"><span class="fa fa-sign-in"></span> Accedi</a></li>
</ul>