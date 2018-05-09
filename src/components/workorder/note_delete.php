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

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm()) {
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have a workorder_note_id
if($VAR['workorder_note_id'] == '') {
    force_page('workorder', 'search', 'warning_msg='._gettext("No Work Order Note ID supplied."));
    exit;
}

// Get the workorder_id before we delete the record
$workorder_id = get_workorder_note($db, $VAR['workorder_note_id'], 'workorder_id');

// Delete the record
delete_workorder_note($db, $VAR['workorder_note_id']);

// Reload the workorder details page
force_page('workorder', 'details&workorder_id='.$workorder_id, 'information_msg='._gettext("The note has been deleted."));