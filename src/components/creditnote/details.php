<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have an creditnote_id
if(!isset(\CMSApplication::$VAR['creditnote_id']) || !\CMSApplication::$VAR['creditnote_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Credit Note ID supplied."));
    $this->app->system->page->forcePage('creditnote', 'search');
}

// Load credit note record
$creditnote_details = $this->app->components->creditnote->getRecord(\CMSApplication::$VAR['creditnote_id']);

// Check if credit note is deleted
if($creditnote_details['status'] === 'deleted') {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot view this credit note because it has been deleted."));
    $this->app->system->page->forcePage('creditnote', 'search');
}

// Credit Note Details
$this->app->smarty->assign('company_details',          $this->app->components->company->getRecord());
$this->app->smarty->assign('client_details',           $this->app->components->client->getRecord($creditnote_details['client_id']));
$this->app->smarty->assign('invoice_details',          $this->app->components->invoice->getRecord($creditnote_details['invoice_id']));
$this->app->smarty->assign('supplier_details',         $this->app->components->supplier->getRecord($creditnote_details['supplier_id']));
$this->app->smarty->assign('expense_details',          $this->app->components->expense->getRecord($creditnote_details['expense_id']));
$this->app->smarty->assign('creditnote_details',       $creditnote_details);
$this->app->smarty->assign('creditnote_items',         $this->app->components->creditnote->getItems($creditnote_details['creditnote_id']));

// Payment Details
$this->app->smarty->assign('payment_types',            $this->app->components->payment->getTypes());
$this->app->smarty->assign('payment_methods',          $this->app->components->payment->getMethods());
$this->app->smarty->assign('payment_directions',       $this->app->components->payment->getDirections());
$this->app->smarty->assign('payment_statuses',         $this->app->components->payment->getStatuses());
$this->app->smarty->assign('display_payments',         $this->app->components->payment->getRecords('payment_id', 'DESC', 0, false, null, null, null, null, null, null, null, null, null, null, null, null, null, \CMSApplication::$VAR['creditnote_id']));

// Misc
$this->app->smarty->assign('creditnote_statuses',      $this->app->components->creditnote->getStatuses());
$this->app->smarty->assign('creditnote_types',         $this->app->components->creditnote->getTypes());
$this->app->smarty->assign('employee_display_name',    $this->app->components->user->getRecord($creditnote_details['employee_id'], 'display_name'));
$this->app->smarty->assign('vat_tax_codes',            $this->app->components->company->getVatTaxCodes());
