<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/workorder.php');

// Check if we have a workorder_id
if($workorder_id == '') {
    force_page('workorder', 'search', 'warning_msg='.gettext("No Workorder ID supplied."));
    exit;
}

// If a note is submitted
if(isset($VAR['submit'])){
    
    // insert the note into the database
    insert_workorder_note($db, $workorder_id, $VAR['workorder_note']);
    
    // load the workorder details page
    force_page('workorder', 'details', 'workorder_id='.$workorder_id.'information_msg='.gettext("The note has been inserted."));
    exit;
    
}
    
// Build the page
$smarty->assign('workorder_id', $workorder_id);    
$BuildPage .= $smarty->fetch('workorder/note_new.tpl');