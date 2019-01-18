<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'giftcert.php');
require(INCLUDES_DIR.'invoice.php');
require(INCLUDES_DIR.'payment.php');
require(INCLUDES_DIR.'workorder.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm('giftcert', 'new') && !check_page_accessed_via_qwcrm('invoice', 'edit')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have an invoice_id
if(!isset($VAR['invoice_id']) || !$VAR['invoice_id']) {
    force_page('invoice', 'search', 'warning_msg='._gettext("No Invoice ID supplied."));
}

// Check if giftcert payment method is enabled
if(!check_payment_method_is_active('gift_certificate')) {
    force_page('invoice', 'edit&invoice_id='.$VAR['invoice_id'], 'warning_msg='._gettext("Gift Certificate payment method is not enabled. Goto Payment Options and enable Gift Certificates there."));
}

// if information submitted - add new gift certificate
if(isset($VAR['submit'])) {   
        
    // Create a new gift certificate
    $VAR['giftcert_id'] = insert_giftcert($VAR['invoice_id'], $VAR['date_expires'], $VAR['amount'], $VAR['note']);

    // Load the attached invoice Details page
    force_page('invoice', 'edit&invoice_id='.$VAR['invoice_id']);

}
    
// Build the page
$smarty->assign('client_details', get_client_details(get_invoice_details($VAR['invoice_id'], 'client_id')));
$BuildPage .= $smarty->fetch('giftcert/new.tpl');