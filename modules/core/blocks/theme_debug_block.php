<?php

// The header might be disabled but the wrapper include is still needed
require_once('includes'.SEP.'modules'.SEP.'core_theme.php');

$smarty->assign('IPaddress',                get_ip_address()                    );  // IP address of the Visitor
$smarty->assign('pageLoadTime',             microtime(1) - $startTime           );  // Time to load the page to the nearest microsecond
$smarty->assign('pageDisplayController',    $page_display_controller            );  // the location of the real php file that loads the page
$smarty->assign('loadedModule',             $module                             );  // Loaded module
$smarty->assign('loadedPageTpl',            $page_tpl                           );  // Loaded page
$smarty->assign('startMem',                 $startMem / 1048576                 );  // PHP Memory used when starting QWcrm (in MB)
$smarty->assign('currentMem',               memory_get_usage() / 1048576        );  // PHP Memory used at the time this php is called (in MB)
$smarty->assign('peakMem',                  memory_get_peak_usage() / 1048576   );  // Peak PHP Memory used during the page load (in MB)

$smarty->display('core'.SEP.'blocks'.SEP.'theme_debug_block.tpl');

/* Advanced Debug - Only use in offline sites and for developement only */

if($qwcrm_advanced_debug === 'on'){

    echo '<div><p><strong>QWcrm Advanced Debug</strong></p></div>';
 
    /* 
     * All defined PHP Variables Dump
     *  
     * pick your poison - http://web-profile.net/php/dev/var_dump-print_r-var_export/
     * It is on several lines on purpose - a PHP quirk 
     */    
    echo '<div><p><strong>All defined PHP Variables</strong></p></div>';
    echo '<pre>';
    print_r(get_defined_vars());
    echo '</pre>';
    
    
    /* 
     * All defined PHP Constants
     */    
    echo '<div><p><strong>All defined PHP Constants</strong></p></div>';
    echo '<pre>';
    print_r(get_defined_constants());
    echo '</pre>';
       
    
}