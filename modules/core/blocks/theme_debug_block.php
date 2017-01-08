<?php

// The header might be disabled but the wrapper include is still needed
require_once(INCLUDES_DIR.'modules/core_theme.php');

$smarty->assign('IPaddress',                get_ip_address()                    );  // IP address of the Visitor
$smarty->assign('pageLoadTime',             microtime(1) - $startTime           );  // Time to load the page to the nearest microsecond
$smarty->assign('pageDisplayController',    $page_display_controller            );  // the location of the real php file that loads the page
$smarty->assign('loadedModule',             $module                             );  // Loaded module
$smarty->assign('loadedPageTpl',            $page_tpl                           );  // Loaded page
$smarty->assign('startMem',                 $startMem / 1048576                 );  // PHP Memory used when starting QWcrm (in MB)
$smarty->assign('currentMem',               memory_get_usage() / 1048576        );  // PHP Memory used at the time this php is called (in MB)
$smarty->assign('peakMem',                  memory_get_peak_usage() / 1048576   );  // Peak PHP Memory used during the page load (in MB)

$BuildPage .= $smarty->fetch('core'.SEP.'blocks'.SEP.'theme_debug_block.tpl');

/* Advanced Debug - Only use in offline sites and for developement only */

if($qwcrm_advanced_debug == true) {

    $BuildPage .= "\r\n\r\n<div><p><strong>".$smarty->get_template_vars('translate_core_debug_qwcrm_advanced_debug_section_title')."</strong></p></div>\r\n";
 
    /* 
     * All defined PHP Variables
     *  
     * pick your poison - http://web-profile.net/php/dev/var_dump-print_r-var_export/
     * It is on several lines on purpose - a PHP quirk
     * 
     * BuildPage (and other variables and objects) seem to be shown twice
     * I think this is because they are registered in ['GLOBALS'] and the global scope (not 100% sure)
     * ['BuildPage']  and  ['GLOBALS']['BuildPage'] - these are different variables
     * 
     * This explains the page seemingly being display 3 times when debug is on
     * 
     */    
    $BuildPage .= "<div><p><strong>".$smarty->get_template_vars('translate_core_debug_all_defined_php_variables_title').":</strong></p></div>\r\n";    
    $BuildPage .= '<pre>'.print_r(get_defined_vars(), true).'</pre>';
    
    /* 
     * All defined PHP Constants
     */    
    $BuildPage .= "<div><p><strong>".$smarty->get_template_vars('translate_core_debug_all_defined_php_constants_title').":</strong></p></div>\r\n";
    $BuildPage .= '<pre>'.print_r(get_defined_constants(), true).'</pre>';

    /* 
     * All defined PHP functions
     */    
    $BuildPage .= "<div><p><strong>".'defined functions'.":</strong></p></div>\r\n";
    $BuildPage .= '<pre>'.print_r(get_defined_functions(), true).'</pre>';    

    /* 
     * All declared PHP Classes
     */    
    $BuildPage .= "<div><p><strong>".'get declared classes'.":</strong></p></div>\r\n";
    $BuildPage .= '<pre>'.print_r(get_declared_classes(), true).'</pre>';      
    
}
