<?php

require_once (__DIR__.'/../include.php');

$smarty->display('core'.SEP.'blocks'.SEP.'theme_footer_block.tpl');

if(debug == 'yes'){
    
    echo 'PHP script executed in: ' . (getMicroTime() - $start .' secs<br>');
    unset($VAR);

    // Check ip from share internet
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip=$_SERVER['HTTP_CLIENT_IP'];
    }

    // To check ip is pass from proxy
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip=$_SERVER['REMOTE_ADDR'];
    }

echo ('My real IP is:'.$ip);

}