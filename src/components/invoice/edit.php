<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent undefined variable errors
\CMSApplication::$VAR['qform']['invoice_items'] = \CMSApplication::$VAR['qform']['invoice_items'] ?? null;

// Check if we have an invoice_id
if(!isset(\CMSApplication::$VAR['invoice_id']) || !\CMSApplication::$VAR['invoice_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Invoice ID supplied."));
    $this->app->system->page->forcePage('invoice', 'search');
}

// Check if invoice is deleted
if($this->app->components->invoice->getRecord(\CMSApplication::$VAR['invoice_id'], 'status') === 'deleted') {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot edit this invoice because it has been deleted."));
    $this->app->system->page->forcePage('invoice', 'search');
}

// Check if invoice can be edited
if(!$this->app->components->invoice->checkRecordAllowsEdit(\CMSApplication::$VAR['invoice_id'])) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot edit this invoice because its status does not allow it."));
    $this->app->system->page->forcePage('invoice', 'details&invoice_id='.\CMSApplication::$VAR['invoice_id']);
}

##################################
#      Update Invoice            #
##################################

if(isset(\CMSApplication::$VAR['submit'])) {
    
    // insert the invoice items into an array
    $this->app->components->invoice->insertItems(\CMSApplication::$VAR['qform']['invoice_id'], \CMSApplication::$VAR['qform']['invoice_items']);
        
    // update and recalculate the invoice
    $this->app->components->invoice->updateRecord(\CMSApplication::$VAR['qform']);
    $this->app->components->invoice->recalculateTotals(\CMSApplication::$VAR['qform']['invoice_id']);
    
    //$this->app->system->page->forcePage('invoice', 'details&invoice_id='.\CMSApplication::$VAR['qform']['invoice_id']);  
    
    
}
    
##################################
#     Load invoice edit page     #
################################## 

// Invoice Details
$this->app->smarty->assign('company_details',          $this->app->components->company->getRecord()                                                                  );
$this->app->smarty->assign('client_details',           $this->app->components->client->getRecord($this->app->components->invoice->getRecord(\CMSApplication::$VAR['invoice_id'], 'client_id'))               );
$this->app->smarty->assign('workorder_details',        $this->app->components->workorder->getRecord($this->app->components->invoice->getRecord(\CMSApplication::$VAR['invoice_id'], 'workorder_id'))         ); 
$this->app->smarty->assign('invoice_details',          $this->app->components->invoice->getRecord(\CMSApplication::$VAR['invoice_id'])                                                );

// Prefill Items
$this->app->smarty->assign('invoice_prefill_items',     $this->app->components->invoice->getPrefillItems(1)                                               ); 
$this->app->smarty->assign('vat_tax_codes',            $this->app->components->company->getVatTaxCodes(false)                                                               );
$this->app->smarty->assign('default_vat_tax_code',     $this->app->components->company->getDefaultVatTaxCode($this->app->components->invoice->getRecord(\CMSApplication::$VAR['invoice_id'], 'tax_system'))        );

// Invoice Items
$this->app->smarty->assign('invoice_items_json',       json_encode($this->app->components->invoice->getItems(\CMSApplication::$VAR['invoice_id']))                                                  );
$this->app->smarty->assign('display_vouchers',         $this->app->components->voucher->getRecords('voucher_id', 'DESC', 25, false, null, null, null, null, null, null, null, \CMSApplication::$VAR['invoice_id']) );

// Sub Totals
$this->app->smarty->assign('invoice_items_subtotals',  $this->app->components->invoice->getItemsSubtotals(\CMSApplication::$VAR['invoice_id'])                                                          );
$this->app->smarty->assign('voucher_items_subtotals', $this->app->components->voucher->getInvoiceVouchersSubtotals(\CMSApplication::$VAR['invoice_id'])                                                       );

// Payment Details
$this->app->smarty->assign('payment_types',            $this->app->components->payment->getTypes()                                                                                 );
$this->app->smarty->assign('payment_methods',          $this->app->components->payment->getMethods()                                                             ); 
$this->app->smarty->assign('payment_statuses',         $this->app->components->payment->getStatuses()                                                                              );
$this->app->smarty->assign('display_payments',         $this->app->components->payment->getRecords('payment_id', 'DESC', 0, false, null, null, null, null, null, null, null, null, null, null, \CMSApplication::$VAR['invoice_id']));

// Misc
$this->app->smarty->assign('employee_display_name',    $this->app->components->user->getRecord($this->app->components->invoice->getRecord(\CMSApplication::$VAR['invoice_id'], 'employee_id'), 'display_name') );
$this->app->smarty->assign('invoice_statuses',         $this->app->components->invoice->getStatuses()                                                                   );
$this->app->smarty->assign('voucher_statuses',         $this->app->components->voucher->getStatuses()                                                                   );