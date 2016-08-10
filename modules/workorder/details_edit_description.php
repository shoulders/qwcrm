<?php

require('includes'.SEP.'modules'.SEP.'workorder.php');

$wo_id                  = $VAR['wo_id'];
$workorder_scope        = $VAR['workorder_scope'];
$workorder_description  = $VAR['workorder_description'];

if($wo_id == '') {
    force_page('core', 'error', 'error_type=warning&error_location=workorder:details_edit_description&php_function=&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_details_edit_description_loadpage_failed').'&php_errormsg='.$php_errormsg.'&database_error='.$db->ErrorMsg());
    exit;
}

if(isset($VAR['submit'])) {
    update_workorder_scope_and_description($db, $wo_id, $workorder_scope, $workorder_description);    
} else {

    $workorder_scope_description = get_workorder_scope_and_description($db, $wo_id);
    
    $smarty->assign('wo_id', $wo_id);    
    $smarty->assign('workorder_scope',        $workorder_scope_description->fields['WORK_ORDER_SCOPE']);
    $smarty->assign('workorder_description',  $workorder_scope_description->fields['WORK_ORDER_DESCRIPTION']);
    
    $smarty->display('workorder'.SEP.'details_edit_description.tpl');

}