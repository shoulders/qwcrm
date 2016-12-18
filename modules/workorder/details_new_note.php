<?php

require(INCLUDES_DIR.'modules/workorder.php');

$workorder_note = $VAR['workorder_note'];

if($workorder_id == '') {
    force_page('core', 'error', 'error_type=warning&error_location=workorder:details_new_note&php_function=&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_details_new_note_loadpage_failed').'&php_error_msg='.$php_errormsg.'&database_error='.$db->ErrorMsg());
    exit;
}

if(isset($VAR['submit'])){
    insert_new_note($db, $workorder_id, $work_order_note_content);
} else {
    $smarty->assign('workorder_id', $VAR['workorder_id']);
    
    $smarty->display('workorder'.SEP.'details_new_note.tpl');
}