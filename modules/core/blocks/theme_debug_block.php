<?php

$smarty->display('core'.SEP.'blocks'.SEP.'theme_debug_block.tpl');

/*-------------*/
   
  /*
   * add to diagnostics it gives the real php file loaded
   * 
   * echo $VAR['page'].'<br />'; //workorder:closed
    echo $page.'<br />';    //closed
    echo $page_display_controller.'<br />'; //modules/workorder/closed.php
    
   */   

if($qwcrm_advanced_debug === 'on'){
    
    /* PHP Variable Dump */
    echo'<pre>';
    echo var_dump($_SESSION); // $_SESSION Variables
    echo'<br />';
    print_r(get_defined_vars()); // All defined PHP Variables
    echo '</pre>';
}

/* ----------- */

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

/* Get the Time to redure the page to the nearest microsecond */
$endTime = $startTime = microtime(1);
echo $endtime