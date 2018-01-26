<?php $info=Session::get("info"); ?>
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
                <li class="dropdown" id="autoGest"><a href="<?php echo getBaseURL()."/autogest/"; ?>">Auto.Gest <span class='caret'></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?php echo getBaseURL()."/licenza/"; ?>">Licenza</a></li>
                    </ul>
                </li>
                <li><a href="#login" data-toggle="modal"><span class="fa fa-sign-in"></span> Accedi</a></li>
            </ul>
        </div>
    </div>
</div>