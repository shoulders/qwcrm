<?php

defined('_QWEXEC') or die;

// Set Path for SMARTY in the php include path
set_include_path(get_include_path() . PATH_SEPARATOR . LIBRARIES_DIR.'smarty'.SEP);
require_once('Smarty.class.php');

// Load Smarty template engine

$smarty = new smarty;

$smarty->template_dir           = THEME_TEMPLATE_DIR;
$smarty->cache_dir              = SMARTY_CACHE_DIR;
$smarty->compile_dir            = SMARTY_COMPILE_DIR;
$smarty->force_compile          = $smarty_force_compile;
$smarty->caching                = $smarty_caching;
$smarty->cache_lifetime         = $smarty_cache_lifetime;
$smarty->cache_modified_check   = $smarty_cache_modified_check;         // Smarty will respect the If-Modified-Since header sent from the client. Only works with caching enabled
//$smarty->load_filter('output','trimwhitespace');
//$smarty->error_unassigned = true; // to enable notices.
//$smarty->error_reporting = E_ALL | E_STRICT;  // Uses standard PHP error levels.

// Debugging

//$smarty->debugging              = $smarty_debugging;                                            // Does not work with fetch()
//$smarty->debug_tpl              = LIBRARIES_DIR.'smarty/debug.tpl';                             // By default it is in the Smarty directory
//$smarty->debugging_ctrl         = ($_SERVER['SERVER_NAME'] == 'localhost') ? 'URL' : 'NONE';    // Restrict debugging URL to work only on localhost


