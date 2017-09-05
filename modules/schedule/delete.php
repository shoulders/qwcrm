<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/customer.php');
require(INCLUDES_DIR.'modules/schedule.php');
require(INCLUDES_DIR.'modules/workorder.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm()) {
    die(gettext("No Direct Access Allowed."));
}

// Check if we have a schedule_id
if($schedule_id == '') {
    force_page('schedule', 'search', 'warning_msg='.gettext("No Schedule ID supplied."));
    exit;
}

// get workorder_id
$workorder_id = get_schedule_details($db, $schedule_id, 'workorder_id');

// Delete the schedule
delete_schedule($db, $schedule_id);

// load schedule search page
force_page('workorder', 'details&workorder_id='.$workorder_id);
exit;
