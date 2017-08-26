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

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm()) {
    die(gettext("No Direct Access Allowed"));
}

// Check if we have a workorder_id
if($workorder_id == '') {
    force_page('workorder', 'search', 'warning_msg='.gettext("No Workorder ID supplied."));
    exit;
}

// Delete the Workorder
if(!delete_workorder($db, $workorder_id)) {
    
    // load the staus page
    force_page('workorder', 'status', 'workorder_id='.$workorder_id);
    exit;
    
} else {
    
    
    // load the workorder overview page
    force_page('workorder', 'overview', 'information_msg='.gettext("Work Order has been deleted."));
    exit;
    
}
    