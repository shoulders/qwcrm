<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/workorder.php');

// Check that there is a workorder_id set
if($workorder_id == ''){
    force_page('workorder', 'overview', 'warning_msg='.$smarty->getTemplateVars('translate_workorder_advisory_message_details_edit_resolution_noworkorderid'));
    exit;
}

// Check if we can edit the workorder resolution
if(!resolution_edit_status_check($db, $workorder_id)) {
    force_page('workorder', 'details&workorder_id='.$workorder_id, 'warning_msg='.$smarty->getTemplateVars('translate_workorder_advisory_message_details_edit_resolution_cannotedit'));
    exit;
}
    
// Update Work Resolution Only
if(isset($VAR['submitchangesonly'])) {
    update_workorder_resolution($db, $workorder_id, $VAR['workorder_resolution']);
    force_page('workorder', 'details&workorder_id='.$workorder_id, 'information_msg='.$smarty->getTemplateVars('translate_workorder_advisory_message_details_edit_resolution_updated'));
    exit;
}

// Close without invoice
if(isset($VAR['closewithoutinvoice'])) {
    close_workorder_without_invoice($db, $workorder_id, $VAR['workorder_resolution']);
    force_page('workorder', 'detailsworkorder_id='.$workorder_id, 'information_msg='.$smarty->getTemplateVars('translate_workorder_advisory_message_details_edit_resolution_workorderclosedwithoutinvoice'));
    exit; 
}

// Close with invoice
if(isset($VAR['closewithinvoice'])) {
    close_workorder_with_invoice($db, $workorder_id, $VAR['workorder_resolution']);       
    force_page('invoice', 'new&workorder_id='.$workorder_id, 'information_msg='.$smarty->getTemplateVars('translate_workorder_advisory_message_details_edit_resolution_workorderclosedwithinvoice'));
    exit;
}
        
// Fetch the page with the resolution from the database 
$smarty->assign('workorder_id', $workorder_id);
$smarty->assign('workorder_resolution', get_workorder_details($db, $workorder_id, 'WORK_ORDER_RESOLUTION'));

$BuildPage .= $smarty->fetch('workorder/details_edit_resolution.tpl');

    
    
