<?php

###############################
#         Setup Smarty        #
###############################

$link = mysqli_connect( $DB_HOST, $DB_USER, $DB_PASS ); /*this might be redundant */

/* Load required Includes */
require(INCLUDE_URL.'session.php');
require(INCLUDE_URL.'auth.php');

/* Set Path for SMARTY in the php include path */
set_include_path(get_include_path() . PATH_SEPARATOR . INCLUDE_URL.'SMARTY'.SEP);
require('Smarty.class.php');

/* Set Path for ADODB in the php include path */
set_include_path(get_include_path() . PATH_SEPARATOR . INCLUDE_URL.'ADODB'.SEP);
require('adodb.inc.php');

/* Load smarty template engine */
global $smarty;
$smarty = new Smarty;
$smarty->template_dir   = FILE_ROOT.'themes/default/templates/';
$smarty->compile_dir    = FILE_ROOT.'cache';
$smarty->config_dir     = SMARTY_URL.'configs';
$smarty->cache_dir      = SMARTY_URL.'cache';
$smarty->load_filter('output','trimwhitespace');

/* create adodb database connection */
$db = &ADONewConnection('mysqli');
$db->Connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);