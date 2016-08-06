<?php

#####################################
#    Load all Configs               #
#####################################

/* Initilise smarty */
require('configuration.php');
require('includes/defines.php');
require(INCLUDES_DIR.'session.php');
require(INCLUDES_DIR.'auth.php');
require(INCLUDES_DIR.'smarty.php');

/* get company logo */
$q = 'SELECT COMPANY_LOGO FROM '.PRFX.'TABLE_COMPANY LIMIT 1';
if(!$rs = $db->execute($q)) {
    force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
    exit;
}

$smarty->assign('company_logo',$rs->fields['COMPANY_LOGO']);

#####################################
#    Initial Authorization          #
#####################################

$smarty->assign('page_title', 'Login');

#####################################
#    Display Any Errors             #
##################################### 

if(isset($_GET["error_msg"])){
    $smarty->assign('error_msg', $_GET["error_msg"]);
}

#####################################
#    Display the pages              #
#####################################

$smarty->display('core'.SEP.'login.tpl');