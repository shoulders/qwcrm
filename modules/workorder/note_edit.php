<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/workorder.php');

// Check if we have a workorder_note_id
if($VAR['workorder_note_id'] == '') {
    force_page('workorder', 'search', 'warning_msg='.gettext("No Work Order Note ID supplied."));
    exit;
}

// If record submitted for updating
if(isset($VAR['submit'])) {
    
    // update the workorder note
    update_workorder_note($db, $VAR['workorder_note_id'], date_to_timestamp($VAR['date']), $VAR['note']);
    
    // load the workorder details page
    force_page('workorder', 'details&workorder_id='.$workorder_id);   
    exit;
    
}   
    
// Build the page
$smarty->assign('workorder_note_details', get_workorder_note($db, $VAR['workorder_note_id']));
$BuildPage .= $smarty->fetch('workorder/note_edit.tpl');
