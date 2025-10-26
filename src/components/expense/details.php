<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have an expense_id
if(!isset(\CMSApplication::$VAR['expense_id']) || !\CMSApplication::$VAR['expense_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Expense ID supplied."));
    $this->app->system->page->forcePage('expense', 'search');
}

// Load expense record
$expense_details = $this->app->components->expense->getRecord(\CMSApplication::$VAR['expense_id']);

// Check if expense is deleted
if($expense_details['status'] === 'deleted') {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("You cannot view this expense because it has been deleted."));
    $this->app->system->page->forcePage('expense', 'search');
}

// Items
$this->app->smarty->assign('expense_items',  $this->app->components->expense->getItems($expense_details['expense_id']));

// Payment Details
$this->app->smarty->assign('payment_types',            $this->app->components->payment->getTypes()                                                                                 );
$this->app->smarty->assign('payment_methods',          $this->app->components->payment->getMethods()                                                             );
$this->app->smarty->assign('payment_directions',       $this->app->components->payment->getDirections());
$this->app->smarty->assign('payment_statuses',         $this->app->components->payment->getStatuses()                                                                              );
$this->app->smarty->assign('display_payments',         $this->app->components->payment->getRecords('payment_id', 'DESC', 0, false, null, null, null, null, null, null, null, null, null, null, null, \CMSApplication::$VAR['expense_id']));

// Misc
$this->app->smarty->assign('employee_display_name',    $this->app->components->user->getRecord($expense_details['employee_id'], 'display_name'));
$this->app->smarty->assign('supplier_display_name', $this->app->components->supplier->getRecord($expense_details['supplier_id'] ?? null, 'display_name'));

// Build the page
$this->app->smarty->assign('expense_statuses', $this->app->components->expense->getStatuses()            );
$this->app->smarty->assign('expense_types', $this->app->components->expense->getTypes());
$this->app->smarty->assign('vat_tax_codes', $this->app->components->company->getVatTaxCodes(false));
$this->app->smarty->assign('expense_details', $expense_details);
