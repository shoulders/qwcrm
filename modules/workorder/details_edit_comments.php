<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/workorder.php');

// Check that there is a workorder_id set
if($workorder_id == '') {    
    force_page('workorder', 'overview', 'warning_msg='.gettext("You cannot edit the comments as there is no Work Order ID set."));
    exit;
}

// If updated comments are submitted
if(isset($VAR['submit'])) {
    
    update_workorder_comments($db, $workorder_id, $VAR['workorder_comments']);    
    force_page('workorder', 'details', 'workorder_id='.$workorder_id.'information_msg='.gettext("Comments has been updated."));
    exit;

// Fetch the page with the comments from the database    
} else {
    
    $smarty->assign('workorder_comments', get_workorder_details($db, $workorder_id, 'WORK_ORDER_COMMENT'));

    $BuildPage .= $smarty->fetch('workorder/details_edit_comments.tpl');
    
}