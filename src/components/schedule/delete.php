<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'report.php');
require(INCLUDES_DIR.'schedule.php');
require(INCLUDES_DIR.'workorder.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm()) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have a schedule_id
if(!isset(\QFactory::$VAR['schedule_id']) || !\QFactory::$VAR['schedule_id']) {
    systemMessagesWrite('danger', _gettext("No Schedule ID supplied."));
    force_page('schedule', 'search');
}

// Get workorder_id before deleting the record
\QFactory::$VAR['workorder_id'] = get_schedule_details(\QFactory::$VAR['schedule_id'], 'workorder_id');

// Delete the schedule
delete_schedule(\QFactory::$VAR['schedule_id']);

// load schedule search page
force_page('workorder', 'details&workorder_id='.\QFactory::$VAR['workorder_id'], 'msg_success='._gettext("Schedule record has been deleted."));
