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

// Check if invoice is deleted
if($invoice_details['status'] === 'deleted') {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot view this invoice because it has been deleted."));
    $this->app->system->page->forcePage('invoice', 'search');
}

// Update all invoice Voucher expiry statuses
$this->app->components->voucher->checkAllVouchersForExpiry(\CMSApplication::$VAR['invoice_id']);

// Invoice Details
$this->app->smarty->assign('company_details',          $this->app->components->company->getRecord()                                                                    );
$this->app->smarty->assign('client_details',           $this->app->components->client->getRecord($invoice_details['client_id'])             );
$this->app->smarty->assign('workorder_details',        $this->app->components->workorder->getRecord($invoice_details['workorder_id'])           );
$this->app->smarty->assign('invoice_details',          $this->app->components->invoice->getRecord(\CMSApplication::$VAR['invoice_id'])                                                       );

// Prefill Items
$this->app->smarty->assign('vat_tax_codes',            $this->app->components->company->getVatTaxCodes()                                                               );

// Invoice Items
$this->app->smarty->assign('invoice_items',           $this->app->components->invoice->getItems(\CMSApplication::$VAR['invoice_id'])                                                  );
$this->app->smarty->assign('display_vouchers',        $this->app->components->voucher->getRecords('voucher_id', 'DESC', 25, false, null, null, null, null, null, null, null, \CMSApplication::$VAR['invoice_id']) );

// Sub Totals
$this->app->smarty->assign('invoice_items_subtotals',         $this->app->components->invoice->getItemsSubtotals(\CMSApplication::$VAR['invoice_id'])                                                          );
$this->app->smarty->assign('voucher_subtotals',            $this->app->components->voucher->getInvoiceVouchersSubtotals(\CMSApplication::$VAR['invoice_id'])                                                       );

// Payment Details
$this->app->smarty->assign('payment_types',            $this->app->components->payment->getTypes()                                                                                 );
$this->app->smarty->assign('payment_methods',          $this->app->components->payment->getMethods()                                                             );
$this->app->smarty->assign('payment_directions',       $this->app->components->payment->getDirections());
$this->app->smarty->assign('payment_statuses',         $this->app->components->payment->getStatuses()                                                                              );
$this->app->smarty->assign('payment_creditnote_action_types', $this->app->components->payment->getCreditnoteActionTypes());
$this->app->smarty->assign('display_payments',         $this->app->components->payment->getRecords('payment_id', 'DESC', 0, false, null, null, null, null, null, null, null, null, null, null, \CMSApplication::$VAR['invoice_id']));

// Misc
$this->app->smarty->assign('employee_display_name',    $this->app->components->user->getRecord($invoice_details['employee_id'], 'display_name')  );
$this->app->smarty->assign('invoice_statuses',         $this->app->components->invoice->getStatuses()                                                                   );
$this->app->smarty->assign('voucher_statuses',        $this->app->components->voucher->getStatuses()                                                                   );
$this->app->smarty->assign('allowed_to_create_creditnote', $this->app->components->creditnote->checkRecordCanBeCreated(null, \CMSApplication::$VAR['invoice_id']));
