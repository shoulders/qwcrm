<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent undefined variable errors
\CMSApplication::$VAR['page_no'] = \CMSApplication::$VAR['page_no'] ?? null;

// Check if we have a supplier_id
if(!isset(\CMSApplication::$VAR['supplier_id']) || !\CMSApplication::$VAR['supplier_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Supplier ID supplied."));
    $this->app->system->page->forcePage('supplier', 'search');
}

// Build the page
$this->app->smarty->assign('supplier_statuses',   $this->app->components->supplier->getStatuses()   );
$this->app->smarty->assign('supplier_types', $this->app->components->supplier->getTypes());
$this->app->smarty->assign('supplier_details', $this->app->components->supplier->getRecord(\CMSApplication::$VAR['supplier_id']));

$this->app->smarty->assign('expense_types',            $this->app->components->expense->getTypes());
$this->app->smarty->assign('expense_statuses',         $this->app->components->expense->getStatuses());
$this->app->smarty->assign('display_expenses',        $this->app->components->expense->getRecords('expense_id', 'DESC', 25, false, null, null, null, null, null, \CMSApplication::$VAR['supplier_id']));
$this->app->smarty->assign('expense_stats',            $this->app->components->report->expenseGetStats('all', null, null, QW_TAX_SYSTEM, null, \CMSApplication::$VAR['supplier_id']));

$this->app->smarty->assign('otherincome_types',            $this->app->components->otherincome->getTypes());
$this->app->smarty->assign('otherincome_statuses',         $this->app->components->otherincome->getStatuses());
$this->app->smarty->assign('display_otherincomes',        $this->app->components->otherincome->getRecords('otherincome_id', 'DESC', 25, false, null, null, null, null, null, \CMSApplication::$VAR['supplier_id']));
$this->app->smarty->assign('otherincome_stats',        $this->app->components->report->otherincomeGetStats('all', null, null, QW_TAX_SYSTEM, $employee_id = null, \CMSApplication::$VAR['supplier_id']));

//$this->app->smarty->assign('payments_monies_received',      $this->app->components->payment->getRecords('payment_id', 'DESC', 25, false, \CMSApplication::$VAR['page_no'], null, null, 'monies_received', null, 'monies_received', null, null, null, \CMSApplication::$VAR['supplier_id'], null, null, null, null, 'monies_received'));
//$this->app->smarty->assign('payments_monies_sent',          $this->app->components->payment->getRecords('payment_id', 'DESC', 25, false, \CMSApplication::$VAR['page_no'], null, null, 'monies_sent', null, 'monies_sent', null, null, null, \CMSApplication::$VAR['supplier_id'], null, null, null, null, 'monies_sent'));
$this->app->smarty->assign('payments_credits',         $this->app->components->payment->getRecords('payment_id', 'DESC', 25, false, \CMSApplication::$VAR['page_no'], null, null, null, null, 'credit', null, null, null, null, \CMSApplication::$VAR['supplier_id']));
$this->app->smarty->assign('payments_debits',          $this->app->components->payment->getRecords('payment_id', 'DESC', 25, false, \CMSApplication::$VAR['page_no'], null, null, null, null, 'debit', null, null, null, null, \CMSApplication::$VAR['supplier_id']));
$this->app->smarty->assign('payment_stats',           $this->app->components->report->paymentGetStats('all', null, null, QW_TAX_SYSTEM, null, null, \CMSApplication::$VAR['supplier_id'])   );

$this->app->smarty->assign('allowed_to_create_creditnote', $this->app->components->creditnote->checkRecordCanBeCreated(null, null, \CMSApplication::$VAR['supplier_id'], null, true));
$this->app->smarty->assign('creditnote_types',            $this->app->components->creditnote->getTypes());
$this->app->smarty->assign('creditnote_action_types', $this->app->components->creditnote->getActionTypes());
$this->app->smarty->assign('creditnote_statuses',         $this->app->components->creditnote->getStatuses());
$this->app->smarty->assign('display_creditnotes',        $this->app->components->creditnote->getRecords('creditnote_id', 'DESC', 25, false, null, null, null, null, null, null, null, null, \CMSApplication::$VAR['supplier_id']));
$this->app->smarty->assign('creditnote_stats',        $this->app->components->report->creditnoteGetStats('all', null, null, QW_TAX_SYSTEM, null, null, \CMSApplication::$VAR['supplier_id']));
