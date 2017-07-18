<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/workorder.php');

// Check if we have a workorder_id
if($workorder_id == '') {
    force_page('workorder', 'search', 'warning_msg='.gettext("No Workorder ID supplied."));
    exit;
}

// Delete the Workorder
if(delete_workorder($db, $workorder_id)) {
    
    // load the workorder overview page
    force_page('workorder', 'overview', 'information_msg='.gettext("Work Order has been deleted."));
    exit;
    
} else {
    
    // load the staus page
    force_page('workorder', 'status', 'workorder_id='.$workorder_id);
    exit;
    
}
    