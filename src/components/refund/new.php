<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

$refund_details = array();

// Prevent direct access to this page
if(!$this->app->system->security->checkPageAccessedViaQwcrm('refund', 'new') && !$this->app->system->security->checkPageAccessedViaQwcrm('invoice', 'status')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have a refund type and is valid
if(!isset(\CMSApplication::$VAR['type']) || !\CMSApplication::$VAR['type'] && (\CMSApplication::$VAR['type'] == 'invoice' || \CMSApplication::$VAR['type'] == 'cash_purchase')) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Refund Type."));
    $this->app->system->page->forcePage('refund', 'search');
}

// Check if we have an invoice_id
if(!isset(\CMSApplication::$VAR['invoice_id']) || !\CMSApplication::$VAR['invoice_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Invoice ID supplied."));
    $this->app->system->page->forcePage('refund', 'search');
}
    
// Process the submitted refund
if (isset(\CMSApplication::$VAR['submit'])) {
    
    // Insert the Refund into the database
    $refund_id = $this->app->components->invoice->refundRecord(\CMSApplication::$VAR['qform']);
    $this->app->components->refund->recalculateTotals($refund_id);  // This is not strictly needed here because balance = unit_gross
    
        if (\CMSApplication::$VAR['submit'] == 'submitandpayment') {

            // Load the new payment page for expense
            $this->app->system->variables->systemMessagesWrite('success', _gettext("Refund added successfully.").' '._gettext("ID").': '.$refund_id);
             $this->app->system->page->forcePage('payment', 'new&type=refund&refund_id='.$refund_id);

        } else {

            // load refund details page
            $this->app->system->variables->systemMessagesWrite('success', _gettext("Refund added successfully.").' '._gettext("ID").': '.$refund_id);
            $this->app->system->page->forcePage('refund', 'details&refund_id='.$refund_id);
        }    

 // Load refund page with the invoice refund details
} else { 

    // Make sure the invoice is allowed to be refunded
    if(!$this->app->components->invoice->checkRecordAllowsRefund(\CMSApplication::$VAR['invoice_id'])) {
        $this->app->system->variables->systemMessagesWrite('danger', _gettext("Invoice").': '.\CMSApplication::$VAR['invoice_id'].' '._gettext("cannot be refunded."));
        $this->app->system->page->forcePage('invoice', 'details&invoice_id='.\CMSApplication::$VAR['invoice_id']);
    }

    $invoice_details = $this->app->components->invoice->getRecord(\CMSApplication::$VAR['invoice_id']);
        
    // Build array
    $refund_details['client_id'] = $invoice_details['client_id'];
    $refund_details['workorder_id'] = $invoice_details['workorder_id'];
    $refund_details['invoice_id'] = $invoice_details['invoice_id'];
    $refund_details['date'] = date('Y-m-d');
    $refund_details['tax_system'] = $invoice_details['tax_system'];    
    $refund_details['type'] = \CMSApplication::$VAR['type'];    
    $refund_details['unit_net'] = $invoice_details['unit_net'];
    if(preg_match('/^vat_/', $invoice_details['tax_system']) && \CMSApplication::$VAR['type'] == 'invoice') {
        $refund_details['vat_tax_code'] = 'TVM';
    } else {
        $refund_details['vat_tax_code'] = $this->app->components->company->getDefaultVatTaxCode($invoice_details['tax_system']);
    }
    $refund_details['unit_tax_rate'] = ($invoice_details['tax_system'] == 'sales_tax_cash') ? $invoice_details['sales_tax_rate'] : $this->app->components->company->getVatRate($refund_details['vat_tax_code']); 
    $refund_details['unit_tax'] = $invoice_details['unit_tax'];
    $refund_details['unit_gross'] = $invoice_details['unit_gross'];  
    $refund_details['note'] = '';

    // Get Client display_name
    $client_display_name = $this->app->components->client->getRecord($invoice_details['client_id'], 'display_name'); 

}  

// Build the page
$this->app->smarty->assign('refund_details', $refund_details);
$this->app->smarty->assign('refund_types', $this->app->components->refund->getTypes());
$this->app->smarty->assign('vat_tax_codes', $this->app->components->company->getVatTaxCodes()); 
$this->app->smarty->assign('client_display_name', $client_display_name);