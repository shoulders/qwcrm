<?php

require(INCLUDES_DIR.'modules/workorder.php');

// Check that there is a workorder_id set
if($workorder_id == '') {
    force_page('workorder', 'overview', 'warning_msg='.$smarty->get_template_vars('translate_workorder_error_message_details_new_note_noworkorderid'));
    exit;
}

// If a note is submitted
if(isset($VAR['submit'])){
    
    insert_new_note($db, $workorder_id, $VAR['workorder_note']);    
    force_page('workorder', 'details', 'workorder_id='.$workorder_id.'information_msg='.$smarty->get_template_vars('translate_workorder_error_message_details_new_note_inserted'));
    exit;
    
// Display the page ready for a note submission 
} else {
    
    $smarty->assign('workorder_id', $VAR['workorder_id']);
    
    $BuildPage .= $smarty->fetch('workorder/details_new_note.tpl');
}