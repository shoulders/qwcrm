<?php

###############################
#         Setup Smarty        #
###############################

/* Set Path for SMARTY in the php include path */
set_include_path(get_include_path() . PATH_SEPARATOR . INCLUDES_DIR.'SMARTY'.SEP);
require('Smarty.class.php');

/* Set Path for ADODB in the php include path */
set_include_path(get_include_path() . PATH_SEPARATOR . INCLUDES_DIR.'ADODB'.SEP);
require('adodb.inc.php');

/* Load smarty template engine */

global $smarty;

$smarty = new Smarty;
$smarty->template_dir   = THEME_DIR.'templates/';
$smarty->compile_dir    = 'cache/';
$smarty->config_dir     = 'smarty/configs/';
$smarty->cache_dir      = 'smarty/cache/';
$smarty->load_filter('output','trimwhitespace');
//$smarty->caching = 0;

/* create adodb database connection */
$db = &ADONewConnection('mysqli');
$db->Connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);

/* Set Template variables into smarty - currently login and main are seperate so this need to be here and not index.php as they both need it */
$smarty->assign('theme_dir', THEME_DIR); // set template directory
$smarty->assign('theme_images_dir', THEME_IMAGES_DIR); // set template images directory
$smarty->assign('theme_css_dir', THEME_CSS_DIR); // set template CSS directory
$smarty->assign('theme_js_dir', THEME_JS_DIR); // set template JS directory
$smarty->assign('root_media_dir', QWROOT_MEDIA_DIR); // set template JS directory

// QWCRM_MEDIA_DIR