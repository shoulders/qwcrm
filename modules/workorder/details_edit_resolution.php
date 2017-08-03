<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/workorder.php');

// Check if we have a workorder_id
if($workorder_id == '') {
    force_page('workorder', 'search', 'warning_msg='.gettext("No Workorder ID supplied."));
    exit;
}

// Check if we can edit the workorder resolution
if(!resolution_edit_status_check($db, $workorder_id)) {
    force_page('workorder', 'details&workorder_id='.$workorder_id, 'warning_msg='.gettext("Cannot edit the resolution as workorder status does not allow it."));
    exit;
}
    
// Update Work Resolution Only
if(isset($VAR['submitchangesonly'])) {
    update_workorder_resolution($db, $workorder_id, $VAR['workorder_resolution']);
    force_page('workorder', 'details&workorder_id='.$workorder_id, 'information_msg='.gettext("Resolution has been updated."));
    exit;
}

// Close without invoice
if(isset($VAR['closewithoutinvoice'])) {
    close_workorder_without_invoice($db, $workorder_id, $VAR['workorder_resolution']);
    force_page('workorder', 'detailsworkorder_id='.$workorder_id, 'information_msg='.gettext("Work Order has been closed without an invoice."));
    exit; 
}

// Close with invoice
if(isset($VAR['closewithinvoice'])) {
    close_workorder_with_invoice($db, $workorder_id, $VAR['workorder_resolution']);       
    force_page('invoice', 'new&workorder_id='.$workorder_id, 'information_msg='.gettext("Work Order has been closed with an invoice."));
    exit;
}
        
// Build the page
$smarty->assign('workorder_resolution', get_workorder_details($db, $workorder_id, 'resolution'));
$BuildPage .= $smarty->fetch('workorder/details_edit_resolution.tpl');

    
    
