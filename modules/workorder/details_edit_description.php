<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/workorder.php');

// Check that there is a workorder_id set
if($workorder_id == '') {
    force_page('workorder', 'overview', 'warning_msg='.$smarty->getTemplateVars('translate_workorder_advisory_message_details_edit_description_noworkorderid'));
    exit;
}

// If updated scope and description are submitted
if(isset($VAR['submit'])) {
    
    update_workorder_scope_and_description($db, $workorder_id, $VAR['workorder_scope'], $VAR['workorder_description']);
    force_page('workorder', 'details', 'workorder_id='.$workorder_id.'information_msg='.$smarty->getTemplateVars('translate_workorder_advisory_message_details_edit_description_updated'));
    exit;

// Fetch the page with the scope and description from the database 
} else {
    
    $smarty->assign('workorder_id',             $workorder_id                                                       );    
    $smarty->assign('workorder_scope',          get_workorder_details($db, $workorder_id, 'WORK_ORDER_SCOPE')       );
    $smarty->assign('workorder_description',    get_workorder_details($db, $workorder_id, 'WORK_ORDER_RESOLUTION')  );
    
    $BuildPage .= $smarty->fetch('workorder/details_edit_description.tpl');

}