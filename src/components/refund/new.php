<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'company.php');
require(INCLUDES_DIR.'invoice.php');
require(INCLUDES_DIR.'refund.php');
require(INCLUDES_DIR.'report.php');
require(INCLUDES_DIR.'payment.php');
require(INCLUDES_DIR.'voucher.php');
require(INCLUDES_DIR.'workorder.php');

$refund_details = array();

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm('refund', 'new') && !check_page_accessed_via_qwcrm('invoice', 'status')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have a refund type and is valid
if(!isset($VAR['item_type']) || !$VAR['item_type'] && ($VAR['item_type'] == 'invoice' || $VAR['item_type'] == 'cash_purchase')) {
    force_page('refund', 'search', 'warning_msg='._gettext("No Refund Type."));
}

// Check if we have an invoice_id
if(!isset($VAR['invoice_id']) || !$VAR['invoice_id']) {
    force_page('refund', 'search', 'warning_msg='._gettext("No Invoice ID supplied."));
}
    
// Process the submitted refund
if (isset($VAR['submit'])) {
    
    // Insert the Refund into the database
    $refund_id = refund_invoice($VAR);
    recalculate_refund_totals($refund_id);  // This is not strictly needed here because balance = unit_gross
    
        if ($VAR['submit'] == 'submitandpayment') {

            // Load the new payment page for expense
             force_page('payment', 'new&type=refund&refund_id='.$refund_id );

        } else {

            // load refund details page
            force_page('refund', 'details&refund_id='.$refund_id, 'information_msg='._gettext("Refund added successfully.").' '._gettext("ID").': '.$refund_id);            
        }    

 // Load refund page with the invoice refund details
} else { 

    // Make sure the invoice is allowed to be refunded
    if(!check_invoice_can_be_refunded($VAR['invoice_id'])) {
        force_page('invoice', 'details&invoice_id='.$VAR['invoice_id'], 'warning_msg='._gettext("Invoice").': '.$VAR['invoice_id'].' '._gettext("cannot be refunded."));
    }

    $invoice_details = get_invoice_details($VAR['invoice_id']);
        
    // Build array
    $refund_details['client_id'] = $invoice_details['client_id'];
    $refund_details['workorder_id'] = $invoice_details['workorder_id'];
    $refund_details['invoice_id'] = $invoice_details['invoice_id'];
    $refund_details['date'] = date('Y-m-d');
    $refund_details['tax_system'] = $invoice_details['tax_system'];    
    $refund_details['item_type'] = $VAR['item_type'];    
    $refund_details['unit_net'] = $invoice_details['unit_net'];
    if(preg_match('/^vat_/', $invoice_details['tax_system']) && $VAR['item_type'] == 'invoice') {
        $refund_details['vat_tax_code'] = 'TVM';
    } else {
        $refund_details['vat_tax_code'] = get_default_vat_tax_code($invoice_details['tax_system']);
    }
    $refund_details['unit_tax_rate'] = ($invoice_details['tax_system'] == 'sales_tax_cash') ? $invoice_details['sales_tax_rate'] : get_vat_rate($refund_details['vat_tax_code']); 
    $refund_details['unit_tax'] = $invoice_details['unit_tax'];
    $refund_details['unit_gross'] = $invoice_details['unit_gross'];  
    $refund_details['note'] = '';

    // Get Client display_name
    $client_display_name = get_client_details($invoice_details['client_id'], 'display_name'); 

}  

// Build the page
$smarty->assign('refund_details', $refund_details);
$smarty->assign('refund_types', get_refund_types());
$smarty->assign('vat_tax_codes', get_vat_tax_codes()); 
$smarty->assign('client_display_name', $client_display_name);
$BuildPage .= $smarty->fetch('refund/new.tpl');