<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/customer.php');
require(INCLUDES_DIR.'modules/workorder.php');
require(INCLUDES_DIR.'modules/schedule.php');
require(INCLUDES_DIR.'modules/user.php');

// Check if we have a workorder_id
if($workorder_id == '') {
    force_page('workorder', 'search', 'warning_msg='.gettext("No Workorder ID supplied."));
    exit;
}

// Build the page with the workorder details from the database
$smarty->assign('employee_details',     get_user_details($db, get_workorder_details($db, $workorder_id, 'employee_id'))     );
$smarty->assign('customer_details',     get_customer_details($db, get_workorder_details($db, $workorder_id, 'customer_id')) );
$smarty->assign('workorder_statuses',   get_workorder_statuses($db)                                                         );
$smarty->assign('workorder_details',    get_workorder_details($db, $workorder_id)                                           );
$smarty->assign('workorder_schedules',  display_workorder_schedules($db, $workorder_id)                                     );
$smarty->assign('workorder_notes',      display_workorder_notes($db, $workorder_id)                                         ); 
$smarty->assign('workorder_history',    display_workorder_history($db, $workorder_id)                                       );
$smarty->assign('selected_date',        timestamp_to_calendar_format(time())                                                );

$BuildPage .= $smarty->fetch('workorder/details.tpl');
