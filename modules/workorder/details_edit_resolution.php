<?php

require(INCLUDES_DIR.'modules/workorder.php');

// Check that there is a workorder_id set
if($workorder_id == ''){
    force_page('workorder', 'open', 'warning_msg='.$smarty->get_template_vars('translate_workorder_error_message_details_edit_resolution_loadpage_no_workorder_id'));
    exit;
}

// Check if we can edit the workorder resolution
if(!resolution_edit_status_check($db, $workorder_id)) {
    force_page('workorder', 'details', '&workorder_id='.$workorder_id);
    exit;
}
    
// Update Work Resolution Only
if(isset($VAR['submitchangesonly'])) {
    update_workorder_resolution($db, $workorder_id, $VAR['workorder_resolution']);
    force_page('workorder', 'details', 'workorder_id='.$workorder_id);
    exit;
}

// Close without invoice
if(isset($VAR["closewithoutinvoice"])) {
    close_workorder_without_invoice($db, $workorder_id, $VAR['workorder_resolution']);
    force_page('workorder', 'details','workorder_id='.$workorder_id);
    exit; 
}

// Close with invoice
if(isset($VAR["closewithinvoice"])) {
    close_workorder_with_invoice($db, $workorder_id, $VAR['workorder_resolution']);       
    force_page('invoice', 'new','workorder_id='.$workorder_id);
    exit;
}
        
// Display the page with the resolution from the database 
$smarty->assign('workorder_id', $workorder_id);
$smarty->assign('workorder_resolution', get_workorder_resolution($db, $workorder_id));

$smarty->display('workorder'.SEP.'details_edit_resolution.tpl');

    
    
