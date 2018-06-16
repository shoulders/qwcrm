<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// The header might be disabled but the wrapper include is still needed
require_once(INCLUDES_DIR.'components/core_theme.php');

$smarty->assign('IPaddress',                get_ip_address()                    );  // IP address of the Visitor
$smarty->assign('pageLoadTime',             microtime(1) - $startTime           );  // Time to load the page to the nearest microsecond
$smarty->assign('pageDisplayController',    $page_controller                    );  // the location of the real php file that loads the page
$smarty->assign('loadedComponent',          $VAR['component']                   );  // Loaded component
$smarty->assign('loadedPageTpl',            $VAR['page_tpl']                    );  // Loaded page
$smarty->assign('startMem',                 $startMem / 1048576                 );  // PHP Memory used when starting QWcrm (in MB)
$smarty->assign('currentMem',               memory_get_usage() / 1048576        );  // PHP Memory used at the time this php is called (in MB)
$smarty->assign('peakMem',                  memory_get_peak_usage() / 1048576   );  // Peak PHP Memory used during the page load (in MB)

$BuildPage .= $smarty->fetch('core/blocks/theme_debug_block.tpl');

// Smarty Debugging - Done this way because $smarty_debugging is not supported when using fetch()
if($config->get('qwcrm_smarty_debugging')) {
    $BuildPage .= $smarty->fetch('core/blocks/theme_debug_smarty_debug_block.tpl');
}

// Advanced Debug - Only use in offline sites and for developement only
if($config->get('qwcrm_advanced_debug')) {

    $BuildPage .= "\r\n\r\n<div><h2><strong>"._gettext("QWcrm Advanced Debug Section")."</strong></h2></div>\r\n";
 
    /* 
     * All defined PHP Variables
     *  
     * Pick your poison - http://web-profile.net/php/dev/var_dump-print_r-var_export/
     *       
     */    
    $BuildPage .= "<div><h3><strong>"._gettext("All Defined PHP Variables").":</strong></h3></div>\r\n";     
    $BuildPage .= '<pre>'.htmlspecialchars(print_r(get_defined_vars(), true)).'</pre>';        
    
    /* 
     * All defined PHP Constants
     */    
    $BuildPage .= "<div><h3><strong>"._gettext("All Defined PHP Constants").":</strong></h3></div>\r\n";
    $BuildPage .= '<pre>'.htmlspecialchars(print_r(get_defined_constants(), true)).'</pre>';

    /* 
     * All defined PHP functions
     */    
    $BuildPage .= "<div><h3><strong>"._gettext("All Defined PHP Functions").":</strong></h3></div>\r\n";
    $BuildPage .= '<pre>'.print_r(get_defined_functions(), true).'</pre>';    

    /* 
     * All declared PHP Classes
     */    
    $BuildPage .= "<div><h3><strong>"._gettext("All Declared PHP Classes").":</strong></h3></div>\r\n";
    $BuildPage .= '<pre>'.print_r(get_declared_classes(), true).'</pre>'; 
    
    /* 
     * All Server Enviromental Variables
     */        
    $BuildPage .= "<div><h3><strong>"._gettext("All Server Enviromental Variables").":</strong></h3></div>\r\n";
    $BuildPage .= '<pre>'.htmlspecialchars(print_r($_SERVER, true)).'</pre>';     
    
}