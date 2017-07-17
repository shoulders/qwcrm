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
$smarty->force_compile          = $QConfig->smarty_force_compile;

// Bnable caching
if($QConfig->smarty_caching == '1') { $smarty->caching = Smarty::CACHING_LIFETIME_CURRENT;}
if($QConfig->smarty_caching == '2') { $smarty->caching = Smarty::CACHING_LIFETIME_SAVED;}

// Other Cahcing settings
$smarty->cache_lifetime         = $QConfig->smarty_cache_lifetime;
$smarty->cache_modified_check   = $QConfig->smarty_cache_modified_check;         // Smarty will respect the If-Modified-Since header sent from the client. Only works with caching enabled


// Debugging

$smarty->debugging              = $QConfig->smarty_debugging;                                     // Does not work with fetch()
//$smarty->debug_tpl              = LIBRARIES_DIR.'smarty/debug.tpl';                             // By default it is in the Smarty directory
//$smarty->debugging_ctrl         = ($_SERVER['SERVER_NAME'] == 'localhost') ? 'URL' : 'NONE';    // Restrict debugging URL to work only on localhost

// Other Settings

//$smarty->load_filter('output','trimwhitespace');  // removes all whitespace from output. useful to get smaller page payloads
//$smarty->error_unassigned = true;                 // to enable notices.
//$smarty->error_reporting = E_ALL | E_STRICT;      // Uses standard PHP error levels.
//$smarty->compileAllTemplates();                   // this is a really cool feature and useful for translations


// You can clear all the cache files with the clear_all_cache() function, or individual cache files (or groups) with the clear_cache() function. 
