<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'invoice.php');
require(INCLUDES_DIR.'payment.php');
require(INCLUDES_DIR.'report.php');
require(INCLUDES_DIR.'user.php'); // need when changing WO from 'closed_with_invoice' to 'closed_without_invoice'
require(INCLUDES_DIR.'voucher.php');
require(INCLUDES_DIR.'workorder.php');


// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm('invoice', 'status')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have an invoice_id
if(!isset(\QFactory::$VAR['invoice_id']) || !\QFactory::$VAR['invoice_id']) {
    systemMessagesWrite('danger', _gettext("No Invoice ID supplied."));
    force_page('invoice', 'search');
}

// Delete Invoice
if(!delete_invoice(\QFactory::$VAR['invoice_id'])) {    
    
    // Load the invoice details page with error
    force_page('invoice', 'details&invoice_id='.\QFactory::$VAR['invoice_id'], 'msg_success='._gettext("The invoice failed to be deleted."));    
    
} else {   
    
    // Load the invoice search page with success message
    systemMessagesWrite('success', _gettext("The invoice has been deleted successfully."));
    force_page('invoice', 'search');
    
}