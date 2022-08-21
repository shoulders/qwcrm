<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have an invoice_id
if(!isset(\CMSApplication::$VAR['invoice_id']) || !\CMSApplication::$VAR['invoice_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Invoice ID supplied."));
    $this->app->system->page->forcePage('invoice', 'search');
}

// Check the request is valid
if
(
    !isset(\CMSApplication::$VAR['commContent'], \CMSApplication::$VAR['commType']) &&
    !in_array(\CMSApplication::$VAR['commContent'], array('invoice', 'client_envelope')) ||
    !in_array(\CMSApplication::$VAR['commType'], array('htmlBrowser', 'pdfBrowser', 'pdfDownload'))
)
{
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("The print request is not valid."));
    $this->app->system->page->forcePage('invoice', 'search');
}

// Get Record Details
$invoice_details = $this->app->components->invoice->getRecord(\CMSApplication::$VAR['invoice_id']);
$client_details = $this->app->components->client->getRecord($invoice_details['client_id']);

// Only show payment instruction if bank_transfer|cheque|PayPal is enabled, these are the only valid instructions you can put on an invoice
$payment_methods = $this->app->components->payment->getMethods('receive', true);
$display_payment_instructions = false;
foreach ($payment_methods as $key => $value) {
    if(
        ($value['method_key'] == 'bank_transfer' && $value['enabled']) ||
        ($value['method_key'] == 'cheque' && $value['enabled']) ||
        ($value['method_key'] == 'paypal' && $value['enabled'])
    ) {
        $display_payment_instructions = true;
    }
}

// Details
$this->app->smarty->assign('company_details',                  $this->app->components->company->getRecord()                                      );
$this->app->smarty->assign('client_details',                   $client_details                                            );
$this->app->smarty->assign('workorder_details',                $this->app->components->workorder->getRecord($invoice_details['workorder_id'])    );
$this->app->smarty->assign('invoice_details',                  $invoice_details                                           );

// Prefill Items
$this->app->smarty->assign('vat_tax_codes',                    $this->app->components->company->getVatTaxCodes(false)                                                               );

// Invoice Items
$this->app->smarty->assign('invoice_items',                     $this->app->components->invoice->getItems(\CMSApplication::$VAR['invoice_id'])               );
$this->app->smarty->assign('display_vouchers',                 $this->app->components->voucher->getRecords('voucher_id', 'DESC', 25, false, null, null, null, null, null, null, null, \CMSApplication::$VAR['invoice_id']) );

// Sub Totals
$this->app->smarty->assign('invoice_items_subtotals',          $this->app->components->invoice->getItemsSubtotals(\CMSApplication::$VAR['invoice_id'])                                                          );
$this->app->smarty->assign('voucher_subtotals',               $this->app->components->voucher->getInvoiceVouchersSubtotals(\CMSApplication::$VAR['invoice_id'])                                                       );

// Payment Details
$this->app->smarty->assign('payment_options',                  $this->app->components->payment->getOptions()                                      );
$this->app->smarty->assign('payment_methods',                  $payment_methods                                           );

// Misc
$this->app->smarty->assign('display_payment_instructions',     $display_payment_instructions                              );
$this->app->smarty->assign('employee_display_name',            $this->app->components->user->getRecord($invoice_details['employee_id'], 'display_name')  );
$this->app->smarty->assign('invoice_statuses',                 $this->app->components->invoice->getStatuses()                                     );

// Invoice Print Routine
if(\CMSApplication::$VAR['commContent'] == 'invoice')
{    
    $templateFile = 'invoice/printing/print_invoice.tpl';
    $filename = _gettext("Invoice").' '.\CMSApplication::$VAR['invoice_id'];
    
    // Print HTML Invoice
    if (\CMSApplication::$VAR['commType'] == 'htmlBrowser')
    {        
        $record = _gettext("Invoice").' '.\CMSApplication::$VAR['invoice_id'].' '._gettext("has been printed as html.");       
    }
    
    // Print PDF Invoice
    if (\CMSApplication::$VAR['commType'] == 'pdfBrowser')
    {        
        $record = _gettext("Invoice").' '.\CMSApplication::$VAR['invoice_id'].' '._gettext("has been printed as a PDF.");
    } 
    
    // Download PDF Invoice
    if (\CMSApplication::$VAR['commType'] == 'pdfDownload')
    {        
        $record = _gettext("Invoice").' '.\CMSApplication::$VAR['invoice_id'].' '._gettext("has been dowloaded as a PDF.");      
    } 
    
}

// Client Envelope Print Routine
if(\CMSApplication::$VAR['commContent'] == 'client_envelope')
{    
    $templateFile = 'invoice/printing/print_client_envelope.tpl';
    $filename = _gettext("Invoice Envelope").' '.\CMSApplication::$VAR['invoice_id'];
    
    // Print HTML Client Envelope
    if (\CMSApplication::$VAR['commType'] == 'htmlBrowser')
    {        
        $record = _gettext("Invoice Envelope").' '.\CMSApplication::$VAR['invoice_id'].' '._gettext("for").' '.$client_details['display_name'].' '._gettext("has been printed as html.");
    }    
}

// Log activity
$this->app->system->general->writeRecordToActivityLog($record, $invoice_details['employee_id'], $invoice_details['client_id'], $invoice_details['workorder_id'], $invoice_details['invoice_id']);

// Perform Communication Action - This also stops further processing (Logging currently done in this file, not this function which has an option for it)
$this->app->system->communication->performAction(\CMSApplication::$VAR['commType'], $templateFile, null, $filename ?? null, $client_details ?? null, $emailSubject ?? null, $emailBody ?? null);
