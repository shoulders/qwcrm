<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/workorder.php');

// Prevent direct access to this file
if(!check_page_accessed_via_qwcrm()) {
    force_page('workorder', 'search', 'warning_msg='.gettext("No Direct Access Allowed"));
}

// Check if we have a workorder_note_id
if($VAR['workorder_note_id'] == '') {
    force_page('workorder', 'search', 'warning_msg='.gettext("No Work Order Note ID supplied."));
    exit;
}

// Get the workorder_id before we delete the record
$workorder_id = get_workorder_note($db, $VAR['workorder_note_id'], 'workorder_id');

// Delete the record
delete_workorder_note($db, $VAR['workorder_note_id']);

// Reload the workorder details page
force_page('workorder', 'details&workorder_id='.$workorder_id);