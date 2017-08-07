<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/administrator.php');
require(INCLUDES_DIR.'modules/user.php');

// Update Company details
if($VAR['submit'] == 'update') {   
    
    if(update_qwcrm_config($VAR)) {
        
        // Reload Page to get the new settings - QConfig has already been decalred before the settings are updated
        force_page('administrator', 'config', 'information_msg='.gettext("Config settings updated successfully."));
        
    } else {
        
        // Load the submitted values
        $smarty->assign('warning_msg', gettext("Some information was invalid, please check for errors and try again."));
        $smarty->assign('qwcrm_config', $VAR);        
        
    }
    
} else {
    
    // No data submitted so just load the current config settings
    $smarty->assign('qwcrm_config', get_qwcrm_config() );
    
}

// Send a test mail
if($VAR['send_test_mail'] == 'true') {
    
    send_test_mail($db);
    die();
    
}

// Fetch page
$BuildPage .= $smarty->fetch('administrator/config.tpl');

