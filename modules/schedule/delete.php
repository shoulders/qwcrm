<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/schedule.php');

// Prevent direct access to this file
if(!check_page_accessed_via_qwcrm()) {
    force_page('schedule', 'search', 'warning_msg='.gettext("No Direct Access Allowed"));
}

// Check if we have a schedule_id
if($schedule_id == '') {
    force_page('schedule', 'search', 'warning_msg='.gettext("No Schedule ID supplied."));
    exit;
}
  
// Delete the schedule
delete_schedule($db, $schedule_id);

// load schedule search page
force_page('schedule', 'search');
exit;
