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
require(INCLUDES_DIR.'workorder.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm()) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have an payment_id
if(!isset($VAR['payment_id']) || !$VAR['payment_id']) {
    force_page('payment', 'search', 'warning_msg='._gettext("No Payment ID supplied."));
}   

// Get payment details
$payment_details = get_payment_details($VAR['payment_id']);

// Cannot delete giftcerts
if($payment_details['method'] == 'gift_certificate') {    
    force_page('payment', 'search', 'warning_msg='._gettext("You cannot delete a Gift Certificate."));
}

// Delete the payment
delete_payment($VAR['payment_id']);

// Load the payment search page
if($payment_details['type'] == 'invoice') {
    force_page('payment', 'search', 'information_msg='._gettext("Payment deleted successfully and invoice").' '.$payment_details['invoice_id'].' '._gettext("has been updated to reflect this change."));
}

force_page('payment', 'search', 'warning_msg='._gettext("Unknown Payment Type."));