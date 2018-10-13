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
require(INCLUDES_DIR.'giftcert.php');
require(INCLUDES_DIR.'payment.php');
require(INCLUDES_DIR.'report.php');
require(INCLUDES_DIR.'workorder.php');
//require(INCLUDES_DIR.'user.php'); - i dont think this is needed

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm('invoice', 'status')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have an invoice_id
if(!isset($VAR['invoice_id']) || !$VAR['invoice_id']) {
    force_page('invoice', 'search', 'warning_msg='._gettext("No Invoice ID supplied."));
}

// Check the Gift Certificates do not prevent the invoice getting cancelled (if present)
check_giftcerts_allow_invoice_cancellation($VAR['invoice_id']);

// Delete Invoice
if(!cancel_invoice($VAR['invoice_id'])) {    
    
    // Load the invoice details page with error
    force_page('invoice', 'edit&invoice_id='.$VAR['invoice_id'].'&information_msg='._gettext("The invoice failed to be cancelled."));
    
    
} else {   
    
    // load the invoice search page with success message
    force_page('invoice', 'search', 'information_msg='._gettext("The invoice has been cancelled successfully."));
    
}