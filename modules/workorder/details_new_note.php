<?php

require_once('include.php');

$wo_id = $VAR['wo_id'];
$workorder_note = $VAR['workorder_note'];

if(empty($VAR['wo_id'])){
    force_page('core', 'error&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_no_work_order_id'));
    exit;
}

if(isset($VAR['submit'])){
    insert_new_note($db, $wo_id, $work_order_note_content);
} else {
    $smarty->assign('wo_id', $VAR['wo_id']);
    
    $smarty->display('workorder'.SEP.'details_new_note.tpl');
}