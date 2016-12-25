<?php

// Set Path for SMARTY in the php include path
set_include_path(get_include_path() . PATH_SEPARATOR . LIBRARIES_DIR.'smarty'.SEP);
require('Smarty.class.php');

// Load Smarty template engine

global $smarty; // is this required, is it not automatically deinfed in global scope when called below.

$smarty = new Smarty;

$smarty->template_dir           = THEME_TEMPLATE_DIR;
$smarty->cache_dir              = SMARTY_CACHE_DIR;
$smarty->compile_dir            = SMARTY_COMPILE_DIR;
//$smarty->load_filter('output','trimwhitespace');
//$smarty->debug_tpl              = 'includes/smarty/debug.tpl';
$smarty->debugging              = $smarty_debug;
$smarty->force_compile          = $smarty_force_compile;
$smarty->caching                = $smarty_caching;
$smarty->cache_lifetime         = $smarty_cache_lifetime;
$smarty->cache_modified_check   = $smarty_cache_modified_check;    // Smarty will respect the If-Modified-Since header sent from the client. Only works with caching enabled