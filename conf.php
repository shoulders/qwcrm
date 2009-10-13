<?php
#############################################################
# MyIT CRM 
# index.php																
# Version 2	30/03/2009 4:38:15 PM					
#############################################################


include('version.php');
@define('SEP','/');
@define('FILE_ROOT','F:\\M\\MyITCRM Dev/MyITCRM/myitcrm'.SEP);
@define('WWW_ROOT','http://localhost/myitcrm');
@define('IMG_URL',WWW_ROOT.'images');
@define('INCLUDE_URL',FILE_ROOT.'include'.SEP);
// TODO remove these if it does not break anything
//@define('SQL_URL',FILE_ROOT.'sql');
//@define('CALENDAR_PATH',FILE_ROOT.'DateTime');
@define('SMARTY_URL',INCLUDE_URL.'SMARTY'.SEP);
@define('ACCESS_LOG',FILE_ROOT.'log'.SEP.'access.log');
@define('LANG','english.xml');
@define('INSTALL_DATE','Sep 16 2009 12:11:50 PM');
@define('debug', 'no');

/* Database Settings */
@define('PRFX',	'MYIT_');
@define('DB_HOST','localhost');
@define('DB_USER','root');
@define('DB_PASS','myitcrm');
@define('DB_NAME','myitcrm028');

/* MySQL Database Settings*/
$DB_HOST = "localhost" ;
$DB_USER = "root" ;
$DB_PASS = "myitcrm" ;
$DB_NAME = "myitcrm028" ;

//$link = mysql_connect( $DB_HOST, $DB_USER, $DB_PASS );

// Currency
$currency_code = 'AUD';
$currency_sym = '$';

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
$smarty->template_dir 	= FILE_ROOT.'templates';
$smarty->compile_dir	= FILE_ROOT.'cache';
$smarty->config_dir	= SMARTY_URL.'configs';
$smarty->cache_dir	= SMARTY_URL.'cache';
$smarty->load_filter('output','trimwhitespace');

$strKey = 'kcmp7n2permbtr0dqebme6mpejhn3ki';

/* create adodb database connection */
$db = &ADONewConnection('mysql');
$db->Connect(DB_HOST,DB_USER,DB_PASS,DB_NAME);



