<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/workorder.php');

// check if we have a workorder_note_id
if($VAR['workorder_note_id'] == ''){
    force_page('core', 'error', 'error_msg=No Workorder Note ID supplied.');
    exit;
}

// If record submitted for updating
if(isset($VAR['submit'])) {
               
    update_workorder_note($db, $VAR['workorder_note_id'], date_to_timestamp($VAR['date']), $VAR['note']);
    force_page('workorder', 'details&workorder_id='.$workorder_id);   
    exit;
    
} else {    
    
    // Fetch and load the page
    $smarty->assign('workorder_note', get_workorder_note($db, $VAR['workorder_note_id']));
    $BuildPage .= $smarty->fetch('workorder/note_edit.tpl');
    
}
