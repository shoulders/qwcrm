<?php

require(INCLUDES_DIR.'modules/workorder.php');

// Check that there is a workorder_id set
if($workorder_id == '') {    
    force_page('workorder', 'overview', 'warning_msg='.$smarty->get_template_vars('translate_workorder_advisory_message_details_edit_comments_noworkorderid'));
    exit;
}

// If updated comments are submitted
if(isset($VAR['submit'])) {
    
    update_workorder_comments($db, $workorder_id, $VAR['workorder_comments']);    
    force_page('workorder', 'details', 'workorder_id='.$workorder_id.'information_msg='.$smarty->get_template_vars('translate_workorder_advisory_message_details_edit_comments_updated'));
    exit;

// Display the page with the comments from the database    
} else {
    
    $smarty->assign('workorder_comments', get_workorder_comments($db, $workorder_id));

    $BuildPage .= $smarty->fetch('workorder/details_edit_comments.tpl');
    
}