<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/customer.php');
require(INCLUDES_DIR.'components/invoice.php');
require(INCLUDES_DIR.'components/payment.php');
require(INCLUDES_DIR.'components/workorder.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm()) {
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have an expense_id
if($VAR['transaction_id'] == '') {
    force_page('payment', 'search', 'warning_msg='._gettext("No Transaction ID supplied."));
    exit;
}   

// get invoice_id
$invoice_id = get_transaction_details($db, $VAR['transaction_id'], 'invoice_id');

// Delete the transaction
delete_payment($db, $VAR['transaction_id']);

// Load the transaction search page
force_page('payment', 'search', 'information_msg='._gettext("Transaction deleted successfully and invoice").' '.$invoice_id.' '._gettext("has been updated to reflect this change."));
exit;