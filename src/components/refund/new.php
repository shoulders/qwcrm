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

// Check if we have an invoice_id
if(!isset($VAR['invoice_id']) || !$VAR['invoice_id']) {
    force_page('refund', 'search', 'warning_msg='._gettext("No Invoice ID supplied."));
}

if (isset($VAR['invoice_id'])) {
    
    // Load refund page with the invoice refund details
    if (!isset($VAR['submit'])) {

        // Make sure the invoice is allowed to be refunded
        if(!check_invoice_can_be_refunded($VAR['invoice_id'])) {
            force_page('invoice', 'details&invoice_id='.$VAR['invoice_id'], 'warning_msg='._gettext("Invoice").': '.$VAR['invoice_id'].' '._gettext("cannot be refunded."));
        }
        
        $invoice_details = get_invoice_details($VAR['invoice_id']);
        
        // Build array
        $refund_details['date'] = date('Y-m-d');
        $refund_details['client_id'] = $invoice_details['client_id'];
        $refund_details['invoice_id'] = $invoice_details['invoice_id'];        
        $refund_details['item_type'] = 'invoice';
        $refund_details['payment_method'] = null;
        $refund_details['net_amount'] = $invoice_details['net_amount'];
        $refund_details['vat_tax_code'] = get_default_vat_tax_code($invoice_details['tax_system']);
        $refund_details['vat_rate'] = get_vat_rate('standard');
        $refund_details['vat_amount'] = $invoice_details['tax_amount'];  // the correct vat needs to be calculated here, there might be no VAT
        $refund_details['gross_amount'] = $invoice_details['gross_amount'];        
        $refund_details['note'] = '';
        
        // Get Client display_name
        $client_display_name = get_client_details($invoice_details['client_id'], 'display_name'); 
        
    // Process the submitted refund 
    } else {        
        
        if(!$refund_id = refund_invoice($VAR)) {

            // Load the invoice details page with error
            force_page('invoice', 'details&invoice_id='.$VAR['invoice_id'].'&information_msg='._gettext("The invoice failed to be refunded."));

        } else {

            // Load the invoice search page with success message
            force_page('invoice', 'search', 'information_msg='._gettext("The invoice has been refunded successfully.").' '._gettext("Refund").' '._gettext("ID").': '.$refund_id);

        }       
        
    }    
    
}

// Build the page
$smarty->assign('refund_details', $refund_details);
$smarty->assign('refund_types', get_refund_types());
$smarty->assign('vat_tax_codes', get_vat_tax_codes(false)); 
$smarty->assign('payment_methods', get_payment_methods('send', 'enabled'));
$smarty->assign('client_display_name', $client_display_name);
$BuildPage .= $smarty->fetch('refund/new.tpl');