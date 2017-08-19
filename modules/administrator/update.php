<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/administrator.php');

if(isset($VAR['submit'])) {
    
    // Check for updates
    check_for_qwcrm_update();

}

// Build the page
$smarty->assign('current_version', QWCRM_VERSION); 
$BuildPage .= $smarty->fetch('administrator/update.tpl');

