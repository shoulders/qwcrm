<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'customer.php');
require(INCLUDES_DIR.'report.php');
require(INCLUDES_DIR.'schedule.php');
require(INCLUDES_DIR.'workorder.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm()) {
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have a schedule_id
if(!isset($VAR['schedule_id']) || !$VAR['schedule_id']) {
    force_page('schedule', 'search', 'warning_msg='._gettext("No Schedule ID supplied."));
}

// Get workorder_id before deleting the record
$VAR['workorder_id'] = get_schedule_details($VAR['schedule_id'], 'workorder_id');

// Delete the schedule
delete_schedule($VAR['schedule_id']);

// load schedule search page
force_page('workorder', 'details&workorder_id='.$VAR['workorder_id']);
