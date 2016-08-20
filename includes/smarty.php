<?php

###############################
#         Setup Smarty        #
###############################

/* Set Path for SMARTY in the php include path */
set_include_path(get_include_path() . PATH_SEPARATOR . INCLUDES_DIR.'SMARTY'.SEP);
require('Smarty.class.php');

/* Load Smarty template engine */

global $smarty;

$smarty = new Smarty;
$smarty->template_dir           = THEME_DIR.'templates/';
$smarty->compile_dir            = 'cache/smarty/compile/';
$smarty->cache_dir              = 'cache/smarty/cache/';
//$smarty->load_filter('output','trimwhitespace');
$smarty->debugging              = $smarty_debug;
$smarty->force_compile          = $smarty_force_compile;
$smarty->caching                = $smarty_caching;
$smarty->cache_lifetime         = $smarty_cache_lifetime;
$smarty->cache_modified_check   = $smarty_cache_modified_check;    // Smarty will respect the If-Modified-Since header sent from the client. Only works with caching enabled

###############################
#       Setup ADODB           #
###############################

/* Set Path for ADODB in the php include path */
set_include_path(get_include_path() . PATH_SEPARATOR . INCLUDES_DIR.'ADODB'.SEP);
require('adodb.inc.php');

/* create adodb database connection */
$db = &ADONewConnection('mysqli');
$db->Connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

