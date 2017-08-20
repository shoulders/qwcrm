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

// Check if we have a workorder_id
if($workorder_id == '') {
    force_page('workorder', 'search', 'warning_msg='.gettext("No Workorder ID supplied."));
    exit;
}

// Check if we can edit the workorder resolution
if(get_workorder_details($db, $workorder_id, 'is_closed')) {
    force_page('workorder', 'details&workorder_id='.$workorder_id);
    exit;
}

if(isset($VAR['submit'])) {
    
    // Update Work Resolution Only
    if($VAR['submit'] == 'submitchangesonly') {
        update_workorder_resolution($db, $workorder_id, $VAR['resolution']);
        force_page('workorder', 'details&workorder_id='.$workorder_id, 'information_msg='.gettext("Resolution has been updated."));
        exit;
    }

    // Close without invoice
    if($VAR['submit'] == 'closewithoutinvoice') {
        close_workorder_without_invoice($db, $workorder_id, $VAR['resolution']);
        force_page('workorder', 'detailsworkorder_id='.$workorder_id, 'information_msg='.gettext("Work Order has been closed without an invoice."));
        exit; 
    }

    // Close with invoice
    if($VAR['submit'] == 'closewithinvoice') {
        close_workorder_with_invoice($db, $workorder_id, $VAR['resolution']);       
        force_page('invoice', 'new&workorder_id='.$workorder_id, 'information_msg='.gettext("Work Order has been closed with an invoice."));
        exit;
    }

}
        
// Build the page
$smarty->assign('resolution', get_workorder_details($db, $workorder_id, 'resolution'));
$BuildPage .= $smarty->fetch('workorder/details_edit_resolution.tpl');

    
    
