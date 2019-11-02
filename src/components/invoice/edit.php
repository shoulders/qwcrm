<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent undefined variable errors
\CMSApplication::$VAR['qform']['labour_items'] = isset(\CMSApplication::$VAR['qform']['labour_items']) ? \CMSApplication::$VAR['qform']['labour_items'] : null;
\CMSApplication::$VAR['qform']['parts_items'] = isset(\CMSApplication::$VAR['qform']['parts_items']) ? \CMSApplication::$VAR['qform']['parts_items'] : null;

// Check if we have an invoice_id
if(!isset(\CMSApplication::$VAR['invoice_id']) || !\CMSApplication::$VAR['invoice_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Invoice ID supplied."));
    $this->app->system->page->force_page('invoice', 'search');
}

// Check if invoice can be edited
if(!$this->app->components->invoice->check_invoice_can_be_edited(\CMSApplication::$VAR['invoice_id'])) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot edit this invoice because its status does not allow it."));
    $this->app->system->page->force_page('invoice', 'details&invoice_id='.\CMSApplication::$VAR['invoice_id']);
}

##################################
#      Update Invoice            #
##################################

if(isset(\CMSApplication::$VAR['submit'])) {
    
    // insert the parts and labour item arrays
    $this->app->components->invoice->insert_labour_items(\CMSApplication::$VAR['qform']['invoice_id'], \CMSApplication::$VAR['qform']['labour_items']);
    $this->app->components->invoice->insert_parts_items(\CMSApplication::$VAR['qform']['invoice_id'], \CMSApplication::$VAR['qform']['parts_items']);
    
    // update and recalculate the invoice
    $this->app->components->invoice->update_invoice_static_values(\CMSApplication::$VAR['qform']['invoice_id'], \CMSApplication::$VAR['qform']['date'], \CMSApplication::$VAR['qform']['due_date'], \CMSApplication::$VAR['qform']['unit_discount_rate']);    
    $this->app->components->invoice->recalculate_invoice_totals(\CMSApplication::$VAR['qform']['invoice_id']);
    
}
    
##################################
#     Load invoice edit page     #
################################## 

// Invoice Details
$this->app->smarty->assign('company_details',          $this->app->components->company->get_company_details()                                                                  );
$this->app->smarty->assign('client_details',           $this->app->components->client->get_client_details($this->app->components->invoice->get_invoice_details(\CMSApplication::$VAR['invoice_id'], 'client_id'))               );
$this->app->smarty->assign('workorder_details',        $this->app->components->workorder->get_workorder_details($this->app->components->invoice->get_invoice_details(\CMSApplication::$VAR['invoice_id'], 'workorder_id'))         ); 
$this->app->smarty->assign('invoice_details',          $this->app->components->invoice->get_invoice_details(\CMSApplication::$VAR['invoice_id'])                                                );

// Prefill Items
$this->app->smarty->assign('labour_prefill_items',     $this->app->components->invoice->get_invoice_prefill_items('Labour', '1')                                               ); 
$this->app->smarty->assign('parts_prefill_items',      $this->app->components->invoice->get_invoice_prefill_items('Parts', '1')                                                );
$this->app->smarty->assign('vat_tax_codes',            $this->app->components->company->get_vat_tax_codes(false)                                                               );
$this->app->smarty->assign('default_vat_tax_code',     $this->app->components->company->get_default_vat_tax_code($this->app->components->invoice->get_invoice_details(\CMSApplication::$VAR['invoice_id'], 'tax_system'))        );

// Invoice Items
$this->app->smarty->assign('labour_items',             $this->app->components->invoice->get_invoice_labour_items(\CMSApplication::$VAR['invoice_id'])                                                  );
$this->app->smarty->assign('parts_items',              $this->app->components->invoice->get_invoice_parts_items(\CMSApplication::$VAR['invoice_id'])                                                   );
$this->app->smarty->assign('display_vouchers',        $this->app->components->voucher->display_vouchers('voucher_id', 'DESC', false, '25', null, null, null, null, null, null, null, \CMSApplication::$VAR['invoice_id']) );

// Sub Totals
$this->app->smarty->assign('labour_items_sub_totals',     $this->app->components->invoice->get_labour_items_sub_totals(\CMSApplication::$VAR['invoice_id'])                                                          );
$this->app->smarty->assign('parts_items_sub_totals',      $this->app->components->invoice->get_parts_items_sub_totals(\CMSApplication::$VAR['invoice_id'])                                                           );
$this->app->smarty->assign('voucher_items_sub_totals',    $this->app->components->voucher->get_invoice_vouchers_sub_totals(\CMSApplication::$VAR['invoice_id'])                                                       );

// Payment Details
$this->app->smarty->assign('payment_types',            $this->app->components->payment->get_payment_types()                                                                                 );
$this->app->smarty->assign('payment_methods',          $this->app->components->payment->get_payment_methods()                                                             ); 
$this->app->smarty->assign('payment_statuses',         $this->app->components->payment->get_payment_statuses()                                                                              );
$this->app->smarty->assign('display_payments',         $this->app->components->payment->display_payments('payment_id', 'DESC', false, null, null, null, null, null, null, null, null, null, \CMSApplication::$VAR['invoice_id'])  );

// Misc
$this->app->smarty->assign('employee_display_name',    $this->app->components->user->get_user_details($this->app->components->invoice->get_invoice_details(\CMSApplication::$VAR['invoice_id'], 'employee_id'), 'display_name') );
$this->app->smarty->assign('invoice_statuses',         $this->app->components->invoice->get_invoice_statuses()                                                                   );
$this->app->smarty->assign('voucher_statuses',        $this->app->components->voucher->get_voucher_statuses()                                                                   );