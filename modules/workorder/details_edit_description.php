<?php

require_once('include.php');

$wo_id = $VAR['wo_id'];
$workorder_scope = $VAR['workorder_scope'];
$workorder_description = $VAR['workorder_description'];

if($wo_id == '') {
    force_page('core', 'error&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_no_work_order_id'));
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