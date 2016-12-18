<?php

require(INCLUDES_DIR.'modules/workorder.php');

$workorder_resolution = $VAR['workorder_resolution'];


// sort this, the format is wrong - is this advisory or an error
if($workorder_id == ''){
    force_page('core', 'error', 'error_type=warning&error_location=workorder:resolution&php_function=&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_print_loadpage_failed').'&php_error_msg='.$php_errormsg.'&database_error='.$db->ErrorMsg());
    exit;
}

/* Check if we can edit the work order resolution*/
resolution_edit_status_check($db, $workorder_id);


/* Update Work Resolution Only */
if(isset($VAR['submitchangesonly'])) {
    update_workorder_resolution($db, $workorder_id, $workorder_resolution);
}

/* Close without invoice */
if(isset($VAR["closewithoutinvoice"])){
    close_workorder_without_invoice($db, $workorder_id, $workorder_resolution);
}

/* Close with invoice */
if(isset($VAR["closewithinvoice"])){
    close_workorder_with_invoice($db, $workorder_id, $workorder_resolution);
}

/* If nothing else it loads the work order resolution page */
$smarty->assign('workorder_id', $workorder_id);
$smarty->assign('workorder_resolution', get_workorder_resolution($db, $workorder_id));

$smarty->display('workorder'.SEP.'resolution.tpl');
