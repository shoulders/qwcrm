<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'user.php');
require(INCLUDES_DIR.'workorder.php');

// Check if we have a workorder_id
if(!isset(\QFactory::$VAR['workorder_id']) || !\QFactory::$VAR['workorder_id']) {
    force_page('workorder', 'search', 'warning_msg='._gettext("No Workorder ID supplied."));
}

// Check if we can edit the workorder resolution
if(get_workorder_details(\QFactory::$VAR['workorder_id'], 'is_closed')) {
    force_page('workorder', 'details&workorder_id='.\QFactory::$VAR['workorder_id'], 'warning_msg='._gettext("Cannot edit the resolution of a closed Work Order."));
}

if(isset(\QFactory::$VAR['submit'])) {
    
    // Update Work Resolution Only
    if(\QFactory::$VAR['submit'] == 'submitchangesonly') {
        update_workorder_resolution(\QFactory::$VAR['workorder_id'], \QFactory::$VAR['resolution']);
        force_page('workorder', 'details&workorder_id='.\QFactory::$VAR['workorder_id'], 'information_msg='._gettext("Resolution has been updated."));
    }

    // Close without invoice
    if(\QFactory::$VAR['submit'] == 'closewithoutinvoice') {
        close_workorder_without_invoice(\QFactory::$VAR['workorder_id'], \QFactory::$VAR['resolution']);
        force_page('workorder', 'details&workorder_id='.\QFactory::$VAR['workorder_id'], 'information_msg='._gettext("Work Order has been closed without an invoice."));
    }

    // Close with invoice
    if(\QFactory::$VAR['submit'] == 'closewithinvoice') {
        close_workorder_with_invoice(\QFactory::$VAR['workorder_id'], \QFactory::$VAR['resolution']);
        
        // Create a new invoice attached to this work order
        force_page('invoice', 'new&workorder_id='.\QFactory::$VAR['workorder_id'], 'information_msg='._gettext("Work Order has been closed with an invoice."));
    }

}
        
// Build the page
$smarty->assign('resolution', get_workorder_details(\QFactory::$VAR['workorder_id'], 'resolution'));

    
    
