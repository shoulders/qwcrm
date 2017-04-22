<?php

require(INCLUDES_DIR.'modules/workorder.php');

// Check that there is a workorder_id set
if(empty($VAR['workorder_id'])){
    force_page('workorder', 'overview', 'warning_msg='.$smarty->getTemplateVars('translate_workorder_advisory_message_delete_noworkorderid'));
    exit;
}

// Delete the Workorder
if(delete_workorder($db, $workorder_id)) {
    force_page('workorder', 'overview', 'information_msg='.$smarty->getTemplateVars('translate_workorder_advisory_message_delete_deleted'));
    exit;  
} else {
    force_page('workorder', 'status', 'workorder_id='.$workorder_id);
    exit;  
}
    