<?php

require('includes'.SEP.'modules'.SEP.'workorder.php');

$workorder_resolution = $VAR['workorder_resolution'];

if($wo_id == ''){
    force_page('core', 'error', 'error_type=warning&error_location=workorder:resolution&php_function=&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_print_loadpage_failed').'&php_error_msg='.$php_errormsg.'&database_error='.$db->ErrorMsg());
    exit;
}

/* Check if we can edit the work order resolution*/
resolution_edit_status_check($db, $wo_id);


/* Update Work Resolution Only */
if(isset($VAR['submitchangesonly'])) {
    update_workorder_resolution($db, $wo_id, $workorder_resolution);
}

/* Close without invoice */
if(isset($VAR["closewithoutinvoice"])){
    close_workorder_without_invoice($db, $wo_id, $workorder_resolution);
}

/* Close with invoice */
if(isset($VAR["closewithinvoice"])){
    close_workorder_with_invoice($db, $wo_id, $workorder_resolution);
}

/* If nothing else it loads the work order resolution page */
$smarty->assign('wo_id', $wo_id);
$smarty->assign('workorder_resolution', get_workorder_resolution($db, $wo_id));

$smarty->display('workorder'.SEP.'resolution.tpl');
