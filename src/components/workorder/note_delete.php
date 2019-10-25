<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'workorder.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm()) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have a workorder_note_id
if(!isset(\QFactory::$VAR['workorder_note_id']) || !\QFactory::$VAR['workorder_note_id']) {
    systemMessagesWrite('danger', _gettext("No Work Order Note ID supplied."));
    force_page('workorder', 'search');
}

// Get the workorder_id before we delete the record
$workorder_id = get_workorder_note_details(\QFactory::$VAR['workorder_note_id'], 'workorder_id');

// Delete the record
delete_workorder_note(\QFactory::$VAR['workorder_note_id']);

// Reload the workorder details page
force_page('workorder', 'details&workorder_id='.$workorder_id, 'msg_success='._gettext("The note has been deleted."));