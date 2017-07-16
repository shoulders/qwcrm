<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/administrator.php');



// Update Company details
if(isset($VAR['submit'])) {   
    
    if(update_qwcrm_config($VAR)) {
        
        $smarty->assign('information_msg', gettext("Config settings updated successfully."));
        
    } else {
        
        $smarty->assign('warning_msg', gettext("Some information was invalid, please check for erros and try again."));
        $smarty->assign('qwcrm_config', $VAR);
        
    }
}
     
// Fetch page
$smarty->assign('qwcrm_config', get_qwcrm_config());
$BuildPage .= $smarty->fetch('administrator/config.tpl');
