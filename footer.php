<div id="footer" class="navbar navbar-default">
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">
            <?php
                $info=Session::get("info");
                if($info["nomecontatto1"]!="") $nc1=$info["nomecontatto1"]; else $nc1="no";
                if($info["linkcontatto1"]!="") $lc1=$info["linkcontatto1"]; else $lc1="no";
                if($info["nomecontatto2"]!="") $nc2=$info["nomecontatto2"]; else $nc2="no";
                if($info["linkcontatto2"]!="") $lc2=$info["linkcontatto2"]; else $lc2="no";
                if($info["nomecontatto3"]!="") $nc3=$info["nomecontatto3"]; else $nc3="no";
                if($info["linkcontatto3"]!="") $lc3=$info["linkcontatto3"]; else $lc3="no";
            ?>
                <p class="navbar-text pull-left"><small><strong><?php echo $info["titolo"]; ?></strong> utilizza <a href="/autogest/" title="Auto.Gest">Auto.Gest</a>.<br />Per qualsiasi problema riguardante il sito, puoi contattare 
                <?php
                    if($nc1!="no" && $lc1!="no") echo "<a target=\"_blank\" href=\"".$lc1."\">".$nc1."</a>";
                    if($nc2!="no" && $lc2!="no") echo ", <a target=\"_blank\" href=\"".$lc2."\">".$nc2."</a>";
                    if($nc3!="no" && $lc3!="no") echo " o <a target=\"_blank\" href=\"".$lc3."\">".$nc3."</a>";
                ?>. Visualizza la <a href="https://www.iubenda.com/privacy-policy/8154259/full-legal" title="Privacy Policy" target="_blank">Privacy Policy</a>.</small></p>
            </div>
            <div id="logoAutoGest" class="hidden-xs hidden-sm col-md-2 col-lg-2">
                <p class="navbar-text pull-right">
                    <a href="https://github.com/tommasoazz/Auto.Gest/" target="_blank" alt="logoAutoGest" title="<?php echo $info['titolo']; ?> utilizza Auto.Gest"><img class="img-responsive" src="../img/AutoGest-Logo.png" /></a>
                </p>
            </div>
        </div>
    </div>
</div>