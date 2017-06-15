<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/workorder.php');

// Check that there is a workorder_id set
if(empty($VAR['workorder_id'])){
    force_page('workorder', 'overview', 'warning_msg='.gettext("Cannot update Workorder status because there is no Work Order ID set."));
    exit;
}

// Delete the Workorder
if(delete_workorder($db, $workorder_id)) {
    force_page('workorder', 'overview', 'information_msg='.gettext("Work Order has been deleted."));
    exit;  
} else {
    force_page('workorder', 'status', 'workorder_id='.$workorder_id);
    exit;  
}
    