<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/workorder.php');

// Check that there is a workorder_id set
if($workorder_id == '') {
    force_page('workorder', 'overview', 'warning_msg='.gettext("You cannot add a note as there is no Workorder ID set."));
    exit;
}

// If a note is submitted
if(isset($VAR['submit'])){
    
    insert_workorder_note($db, $workorder_id, $VAR['workorder_note']);    
    force_page('workorder', 'details', 'workorder_id='.$workorder_id.'information_msg='.gettext("The note has been inserted."));
    exit;
    
// Fetch the page ready for a note submission 
} else {
    
    $smarty->assign('workorder_id', $VAR['workorder_id']);    
    $BuildPage .= $smarty->fetch('workorder/note_new.tpl');
    
}