<?php

require(INCLUDES_DIR.'modules/workorder.php');

// Check that there is a workorder_id set
if($workorder_id == '') {
    force_page('workorder', 'overview', 'warning_msg='.$smarty->get_template_vars('translate_workorder_advisory_message_details_edit_description_noworkorderid'));
    exit;
}

// If updated scope and description are submitted
if(isset($VAR['submit'])) {
    
    update_workorder_scope_and_description($db, $workorder_id, $VAR['workorder_scope'], $VAR['workorder_description']);
    force_page('workorder', 'details', 'workorder_id='.$workorder_id.'information_msg='.$smarty->get_template_vars('translate_workorder_advisory_message_details_edit_description_updated'));
    exit;

// Fetch the page with the scope and description from the database 
} else {

    $workorder_scope_description = get_workorder_scope_and_description($db, $workorder_id);
    
    $smarty->assign('workorder_id',             $workorder_id                                                   );    
    $smarty->assign('workorder_scope',          $workorder_scope_description->fields['WORK_ORDER_SCOPE']        );
    $smarty->assign('workorder_description',    $workorder_scope_description->fields['WORK_ORDER_DESCRIPTION']  );
    
    $BuildPage .= $smarty->fetch('workorder/details_edit_description.tpl');

}