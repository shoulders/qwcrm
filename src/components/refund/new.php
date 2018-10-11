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
require(INCLUDES_DIR.'refund.php');
require(INCLUDES_DIR.'payment.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm('refund', 'new') && !check_page_accessed_via_qwcrm('invoice', 'status')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have an invoice_id or giftcert_id but not both (not when submitting from refunds:new)
if(    
    (!isset($VAR['invoice_id']) || !$VAR['invoice_id']) && (!isset($VAR['giftcert_id']) || !$VAR['giftcert_id'] && !isset($VAR['submit'])) ||
    (isset($VAR['invoice_id']) && isset($VAR['giftcert_id']) && !isset($VAR['submit']))        
) {
    force_page('refund', 'search', 'warning_msg='._gettext("Not a valid refund operation."));
} 

// If details submitted insert record, if none are submitted load new.tpl and populate values
if((isset($VAR['submit']))) {

    // validate refund submission here i.e. can it be redunded
    //check to make sure the giftcert or the invoice id have not been refunded
    // re-validate the invoice and giftcert status
    
    // insert the refund and get the refund_id
    $VAR['refund_id'] = insert_refund($VAR);
        
    if (isset($VAR['submitandnew'])){

        // Load New Refund page
        force_page('refund', 'new', 'information_msg='._gettext("Refund added successfully.")); 

    } else {

        // Load Refund Details page
        force_page('refund', 'details&refund_id='.$VAR['refund_id'], 'information_msg='._gettext("Refund added successfully."));        

    }
         
} else {
    
    $refund_details = array();
        
    // Invoice version
    if(isset($VAR['invoice_id'])) {
        
        // Make sure the invoice is allowed to be refunded
        if(!check_invoice_can_be_refunded($invoice_id)) {
            force_page('invoice', 'details&invoice_id='.$VAR['invoice_id'], 'information_msg='._gettext("Invoice").': '.$VAR['invoice_id'].' '._gettext("cannot be refunded."));
        }
        
        $invoice_details = get_invoice_details($VAR['invoice_id']);
        
        // Build array
        $refund_details['date'] = date('Y-m-d');
        $refund_details['client_id'] = $invoice_details['client_id'];
        $refund_details['invoice_id'] = $invoice_details['invoice_id'];
        $refund_details['giftcert_id'] = null;
        $refund_details['type'] = 'invoice';
        $refund_details['payment_method'] = null;
        $refund_details['net_amount'] = $invoice_details['net_amount'];
        $refund_details['vat_rate'] = $invoice_details['tax_rate'];  // the correct vat needs to be calculated here, there might be no VAT
        $refund_details['vat_amount'] = $invoice_details['tax_amount'];  // the correct vat needs to be calculated here, there might be no VAT
        $refund_details['gross_amount'] = $invoice_details['gross_amount'];        
        $refund_details['note'] = _gettext("This is a refund for an Invoice.");
        
        // Get Client display_name
        $client_display_name = get_client_details($invoice_details['client_id'], 'display_name');        
        
    // Gift Certificate version    
    } elseif (isset($VAR['giftcert_id'])) {
        
        // Make sure the giftcert is allowed to be refunded
        if(!check_giftcert_can_be_refunded($invoice_id)) {
            force_page('giftcert', 'details&giftcert_id='.$VAR['giftcert_id'], 'information_msg='._gettext("Gift Certificate").': '.$VAR['giftcert_id'].' '._gettext("cannot be refunded."));
        }
        
        $giftcert_details = get_giftcert_details($VAR['giftcert_id']);
        
        // Build array
        $refund_details['date'] = date('Y-m-d');
        $refund_details['client_id'] = $giftcert_details['client_id'];
        $refund_details['invoice_id'] = null;
        $refund_details['giftcert_id'] = $giftcert_details['giftcert_id'];
        $refund_details['type'] = 'giftcert';
        $refund_details['payment_method'] = null;
        $refund_details['net_amount'] = $giftcert_details['amount'];
        $refund_details['vat_rate'] = null;
        $refund_details['vat_amount'] = null;
        $refund_details['gross_amount'] = $giftcert_details['amount'];        
        $refund_details['note'] = _gettext("This is a refund for a Gift Certificate.");
        
        // Get Client display_name
        $client_display_name = get_client_details($giftcert_details['client_id'], 'display_name');        
    
    // Fall back possition
    } else {
        // do nohing here
    }    
    
}

// Predict the next refund_id
$new_record_id = last_refund_id_lookup() +1;

// Build the page
$smarty->assign('refund_details', $refund_details);
$smarty->assign('refund_types', get_refund_types());
$smarty->assign('payment_methods', get_payment_purchase_methods());
$smarty->assign('new_record_id', $new_record_id);
$smarty->assign('client_display_name', $client_display_name);
$BuildPage .= $smarty->fetch('refund/new.tpl');