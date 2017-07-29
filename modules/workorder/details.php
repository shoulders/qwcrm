<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/workorder.php');
require(INCLUDES_DIR.'modules/schedule.php');

// Check if we have a workorder_id
if($workorder_id == '') {
    force_page('workorder', 'search', 'warning_msg='.gettext("No Workorder ID supplied."));
    exit;
}

// Build the page with the workorder details from the database
$smarty->assign('single_workorder',     display_single_workorder($db, $workorder_id)    );
$smarty->assign('workorder_schedules',  display_workorder_schedules($db, $workorder_id) );
$smarty->assign('workorder_notes',      display_workorder_notes($db, $workorder_id)     ); 
$smarty->assign('workorder_history',    display_workorder_history($db, $workorder_id)   );
$smarty->assign('selected_date',        timestamp_to_calendar_format(time())            );

$BuildPage .= $smarty->fetch('workorder/details.tpl');
