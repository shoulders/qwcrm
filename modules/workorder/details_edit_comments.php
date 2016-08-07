<?php

require_once('include.php');

$wo_id              = $VAR['wo_id'];
$workorder_comments = $VAR['comment'];

if($wo_id == '') {
    force_page('core', 'error', 'error_type=warning&error_location=workorder:details&php_function=&error_msg='.$smarty->get_template_vars('translate_workorder_error_message_details_loadpage_failed').'&php_errormsg='.$php_errormsg.'&database_error='.$db->ErrorMsg());
    exit;
}

if(isset($VAR['submit'])) {
    update_workorder_comments($db, $wo_id, $workorder_comments);
}

$smarty->assign('wo_id', $wo_id);
$smarty->assign('workorder_comments', get_workorder_comments($db, $wo_id));

$smarty->display('workorder'.SEP.'details_edit_comments.tpl');

