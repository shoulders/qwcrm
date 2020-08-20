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

$invoice_details = $this->app->components->invoice->getRecord(\CMSApplication::$VAR['invoice_id']);

// Invoice Details
$this->app->smarty->assign('company_details',          $this->app->components->company->getRecord()                                                                    );
$this->app->smarty->assign('client_details',           $this->app->components->client->getRecord($invoice_details['client_id'])             );
$this->app->smarty->assign('workorder_details',        $this->app->components->workorder->getRecord($invoice_details['workorder_id'])           );
$this->app->smarty->assign('invoice_details',          $this->app->components->invoice->getRecord(\CMSApplication::$VAR['invoice_id'])                                                       );

// Prefill Items
$this->app->smarty->assign('vat_tax_codes',            $this->app->components->company->getVatTaxCodes()                                                               );

// Invoice Items
$this->app->smarty->assign('labour_items',             $this->app->components->invoice->getLabourItems(\CMSApplication::$VAR['invoice_id'])                                                  );
$this->app->smarty->assign('parts_items',              $this->app->components->invoice->getPartsItems(\CMSApplication::$VAR['invoice_id'])                                                   );
$this->app->smarty->assign('display_vouchers',        $this->app->components->voucher->getRecords('voucher_id', 'DESC', false, '25', null, null, null, null, null, null, null, \CMSApplication::$VAR['invoice_id']) );

// Sub Totals
$this->app->smarty->assign('labour_items_subtotals',         $this->app->components->invoice->getLabourItemsSubtotals(\CMSApplication::$VAR['invoice_id'])                                                          );
$this->app->smarty->assign('parts_items_subtotals',          $this->app->components->invoice->getPartsItemsSubtotals(\CMSApplication::$VAR['invoice_id'])                                                           );
$this->app->smarty->assign('voucher_subtotals',            $this->app->components->voucher->getInvoiceVouchersSubtotals(\CMSApplication::$VAR['invoice_id'])                                                       );

/* Refund Details - This is not used and should be deleted when i am sure I will not use it. I might use something like this to include the refund block
if($this->app->components->invoice->get_invoice_details(\CMSApplication::$VAR['invoice_id'], 'status') == 'refunded') {
    $this->app->smarty->assign('refund_details', $this->app->components->refund->get_refund_details($invoice_details['refund_id']));    
} else {
    $this->app->smarty->assign('refund_details', null);
}*/

// Payment Details
$this->app->smarty->assign('payment_types',            $this->app->components->payment->getTypes()                                                                                 );
$this->app->smarty->assign('payment_methods',          $this->app->components->payment->getMethods()                                                             ); 
$this->app->smarty->assign('payment_statuses',         $this->app->components->payment->getStatuses()                                                                              );
$this->app->smarty->assign('display_payments',         $this->app->components->payment->getRecords('payment_id', 'DESC', false, null, null, null, null, null, null, null, null, null, \CMSApplication::$VAR['invoice_id'])  );

// Misc
$this->app->smarty->assign('employee_display_name',    $this->app->components->user->getRecord($invoice_details['employee_id'], 'display_name')  );
$this->app->smarty->assign('invoice_statuses',         $this->app->components->invoice->getStatuses()                                                                   );
$this->app->smarty->assign('voucher_statuses',        $this->app->components->voucher->getStatuses()                                                                   );