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

// Check if we have a workorder_id
if($VAR['workorder_id'] == '') {
    force_page('workorder', 'search', 'warning_msg='._gettext("No Workorder ID supplied."));
}

// Delete the Workorder
if(!delete_workorder($VAR['workorder_id'])) {
    
    // load the staus page
    force_page('workorder', 'status', 'workorder_id='.$VAR['workorder_id']);
    
} else {
    
    
    // load the workorder search page
    force_page('workorder', 'search', 'information_msg='._gettext("Work Order has been deleted."));
    
}
    