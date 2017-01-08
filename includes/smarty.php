<?php

// Set Path for SMARTY in the php include path
set_include_path(get_include_path() . PATH_SEPARATOR . LIBRARIES_DIR.'smarty'.SEP);
require_once('Smarty.class.php');

// Load Smarty template engine

//global $smarty; // This makes Smarty avilable as a global variable - this might be only need when not in the global scope

$smarty = new smarty;

$smarty->template_dir           = THEME_TEMPLATE_DIR;
$smarty->cache_dir              = SMARTY_CACHE_DIR;
$smarty->compile_dir            = SMARTY_COMPILE_DIR;
//$smarty->load_filter('output','trimwhitespace');
//$smarty->debug_tpl              = LIBRARIES_DIR.'smarty/debug.tpl';  // by default it is the smarty directory
$smarty->debugging              = $smarty_debug;
//[error_reporting] => 
$smarty->force_compile          = $smarty_force_compile;
$smarty->caching                = $smarty_caching;
$smarty->cache_lifetime         = $smarty_cache_lifetime;
$smarty->cache_modified_check   = $smarty_cache_modified_check;         // Smarty will respect the If-Modified-Since header sent from the client. Only works with caching enabled