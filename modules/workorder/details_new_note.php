<?php

require('includes'.SEP.'modules'.SEP.'workorder.php');

$wo_id = $VAR['wo_id'];
$workorder_note = $VAR['workorder_note'];

if($wo_id == '') {
    force_page('core', 'error', 'error_type=warning&error_location=workorder:details_new_note&php_function=&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_details_new_note_loadpage_failed').'&php_errormsg='.$php_errormsg.'&database_error='.$db->ErrorMsg());
    exit;
}

if(isset($VAR['submit'])){
    insert_new_note($db, $wo_id, $work_order_note_content);
} else {
    $smarty->assign('wo_id', $VAR['wo_id']);
    
    $smarty->display('workorder'.SEP.'details_new_note.tpl');
}