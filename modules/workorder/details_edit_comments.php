<?php

require(INCLUDES_DIR.'modules/workorder.php');

// Check that there is a workorder_id set
if($workorder_id == '') {    
    force_page('workorder', 'open', 'warning_msg='.$smarty->get_template_vars('translate_workorder_error_message_details_edit_comments_loadpage_no_workorder_id'));
    exit;
}

// If updated comments are submitted
if(isset($VAR['submit'])) {
    
    update_workorder_comments($db, $workorder_id, $VAR['workorder_comments']);    
    force_page('workorder', 'details', 'workorder_id='.$workorder_id);
    exit;

// Display the page with the comments from the database    
} else {
    
    $smarty->assign('workorder_comments', get_workorder_comments($db, $workorder_id));

    $smarty->display('workorder/details_edit_comments.tpl');
    
}