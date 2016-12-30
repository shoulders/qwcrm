<?php

require(INCLUDES_DIR.'modules/workorder.php');

// Check that there is a workorder_id set
if($workorder_id == '') {
    force_page('workorder', 'open', 'warning_msg='.$smarty->get_template_vars('translate_workorder_error_message_details_new_note_loadpage_no_workorder_id'));
    exit;
}

// If a note is submitted
if(isset($VAR['submit'])){
    
    insert_new_note($db, $workorder_id, $VAR['workorder_note']);    
    force_page('workorder', 'details', 'workorder_id='.$workorder_id);
    exit;
    
// Display the page ready for a note submission 
} else {
    
    $smarty->assign('workorder_id', $VAR['workorder_id']);
    
    $smarty->display('workorder/details_new_note.tpl');
}