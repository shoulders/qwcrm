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
    $this->app->system->page->force_page('expense', 'search');
}

// Payment Details
$this->app->smarty->assign('payment_types',            $this->app->components->payment->getTypes()                                                                                 );
$this->app->smarty->assign('payment_methods',          $this->app->components->payment->getMethods()                                                             ); 
$this->app->smarty->assign('payment_statuses',         $this->app->components->payment->getStatuses()                                                                              );
$this->app->smarty->assign('display_payments',         $this->app->components->payment->getRecords('payment_id', 'DESC', false, null, null, null, null, 'expense', null, null, null, null, null, null, \CMSApplication::$VAR['expense_id']));

// Build the page
$this->app->smarty->assign('expense_statuses', $this->app->components->expense->getStatuses()            );
$this->app->smarty->assign('expense_types', $this->app->components->expense->getTypes());
$this->app->smarty->assign('vat_tax_codes', $this->app->components->company->getVatTaxCodes());
$this->app->smarty->assign('expense_details', $this->app->components->expense->getRecord(\CMSApplication::$VAR['expense_id']));