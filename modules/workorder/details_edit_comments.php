<?php

require_once('include.php');

if($wo_id == '') {
    force_page('core', 'error&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_no_work_order_id'));
    exit;
}

$wo_id = $VAR['wo_id'];
$workorder_comments = $VAR['comment'];

if(isset($VAR['submit'])) {
    update_workorder_comments($db, $wo_id, $workorder_comments);
}

$smarty->assign('wo_id', $wo_id);
$smarty->assign('workorder_comments', get_workorder_comments($db, $wo_id));

$smarty->display('workorder'.SEP.'details_edit_comments.tpl');

