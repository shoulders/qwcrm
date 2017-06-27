<?php

defined('_QWEXEC') or die;

// ADODB Options
//define('ADODB_ASSOC_CASE', 1);  // set what case to use: 0 = lowercase, 1 = uppercase, 2 = native case

// Set Path for ADODB in the php include path
set_include_path(get_include_path() . PATH_SEPARATOR . LIBRARIES_DIR.'adodb'.SEP);
require('adodb.inc.php');

// create adodb database connection
$db = ADONewConnection('mysqli');
$db->Connect($GConfig->db_host, $GConfig->db_user, $GConfig->db_pass, $GConfig->db_name);
