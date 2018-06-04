<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/customer.php');
require(INCLUDES_DIR.'components/workorder.php');
require(INCLUDES_DIR.'components/schedule.php');
require(INCLUDES_DIR.'components/user.php');

// Check if we have a workorder_id
if($VAR['workorder_id'] == '') {
    force_page('workorder', 'search', 'warning_msg='._gettext("No Workorder ID supplied."));
}

// Build the page with the workorder details from the database
$smarty->assign('employee_details',     get_user_details($db, get_workorder_details($db, $VAR['workorder_id'], 'employee_id'))     );
$smarty->assign('customer_details',     get_customer_details($db, get_workorder_details($db, $VAR['workorder_id'], 'customer_id')) );
$smarty->assign('workorder_statuses',   get_workorder_statuses($db)                                                         );
$smarty->assign('workorder_details',    get_workorder_details($db, $VAR['workorder_id'])                                           );
$smarty->assign('workorder_schedules',  display_workorder_schedules($db, $VAR['workorder_id'])                                     );
$smarty->assign('workorder_notes',      display_workorder_notes($db, $VAR['workorder_id'])                                         ); 
$smarty->assign('workorder_history',    display_workorder_history($db, $VAR['workorder_id'])                                       );
$smarty->assign('selected_date',        timestamp_to_calendar_format(time())                                                );

$BuildPage .= $smarty->fetch('workorder/details.tpl');
