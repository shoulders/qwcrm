<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Set Path for SMARTY in the php include path
set_include_path(get_include_path() . PATH_SEPARATOR . LIBRARIES_DIR.'smarty/');
require_once('Smarty.class.php');

// Load Smarty template engine - http://www.smarty.net/docs/en/api.variables.tpl

$smarty = new smarty;

$smarty->template_dir           = THEME_TEMPLATE_DIR;
$smarty->cache_dir              = SMARTY_CACHE_DIR;
$smarty->compile_dir            = SMARTY_COMPILE_DIR;
$smarty->force_compile          = $QConfig->smarty_force_compile;

// Enable caching
if($QConfig->smarty_caching == '1') { $smarty->caching = Smarty::CACHING_LIFETIME_CURRENT;}
if($QConfig->smarty_caching == '2') { $smarty->caching = Smarty::CACHING_LIFETIME_SAVED;}

// Other Caching settings
$smarty->force_cache            = $QConfig->smarty_force_cache;
$smarty->cache_lifetime         = $QConfig->smarty_cache_lifetime;
$smarty->cache_modified_check   = $QConfig->smarty_cache_modified_check;
$smarty->cache_locking          = $QConfig->cache_locking;


// Debugging

$smarty->debugging              = $QConfig->smarty_debugging;                                     // Does not work with fetch()
$smarty->debugging_ctrl         = $QConfig->smarty_debugging_ctrl;
//$smarty->debugging_ctrl         = ($_SERVER['SERVER_NAME'] == 'localhost') ? 'URL' : 'NONE';    // Restrict debugging URL to work only on localhost
//$smarty->debug_tpl              = LIBRARIES_DIR.'smarty/debug.tpl';                             // By default it is in the Smarty directory

// Other Settings

//$smarty->load_filter('output','trimwhitespace');  // removes all whitespace from output. useful to get smaller page payloads
//$smarty->error_unassigned = true;                 // to enable notices.
//$smarty->error_reporting = E_ALL | E_STRICT;      // Uses standard PHP error levels.
//$smarty->compileAllTemplates();                   // this is a really cool feature and useful for translations
//$smarty->clearAllCache();                         // clears all of the cache
//$smarty->clear_cache()();                         // clear individual cache files (or groups)
//$smarty->clearCompiledTemplate();                 // Clears the compile dirctory