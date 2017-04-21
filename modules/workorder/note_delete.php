<?php

require(INCLUDES_DIR.'modules/workorder.php');

// check if we have a workorder_note_id
if($VAR['workorder_note_id'] == ''){
    force_page('core', 'error', 'error_msg=No Workorder Note ID supplied.');
    exit;
}

// Get the workorder_id before we delete the record
$workorder_id = get_workorder_note($db, $VAR['workorder_note_id'], 'WORK_ORDER_ID');

// Delete the record
delete_workorder_note($db, $VAR['workorder_note_id']);

// Reload the workorder details page
force_page('workorder', 'details&workorder_id='.$workorder_id);