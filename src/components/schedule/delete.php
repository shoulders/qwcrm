<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(CINCLUDES_DIR.'client.php');
require(CINCLUDES_DIR.'report.php');
require(CINCLUDES_DIR.'schedule.php');
require(CINCLUDES_DIR.'workorder.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm()) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have a schedule_id
if(!isset(\CMSApplication::$VAR['schedule_id']) || !\CMSApplication::$VAR['schedule_id']) {
    systemMessagesWrite('danger', _gettext("No Schedule ID supplied."));
    force_page('schedule', 'search');
}

// Get workorder_id before deleting the record
\CMSApplication::$VAR['workorder_id'] = get_schedule_details(\CMSApplication::$VAR['schedule_id'], 'workorder_id');

// Delete the schedule
delete_schedule(\CMSApplication::$VAR['schedule_id']);

// load schedule search page
force_page('workorder', 'details&workorder_id='.\CMSApplication::$VAR['workorder_id'], 'msg_success='._gettext("Schedule record has been deleted."));
