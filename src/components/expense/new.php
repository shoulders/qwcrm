<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// If details submitted insert record, if non submitted load new.tpl and populate values
if(isset(\CMSApplication::$VAR['submit'])) {

    // Insert the Expense into the database
    $expense_id = $this->app->components->expense->insertRecord(\CMSApplication::$VAR['qform']);
    $this->app->components->expense->recalculateTotals($expense_id);

    if (\CMSApplication::$VAR['submit'] == 'submitandnew') {

         // Load the new expense page
         $this->app->system->page->force_page('expense', 'new', 'msg_success='._gettext("Expense added successfully.").' '._gettext("ID").': '.$expense_id );

    } elseif (\CMSApplication::$VAR['submit'] == 'submitandpayment') {
         
        // Load the new payment page for expense
         $this->app->system->page->force_page('payment', 'new&type=expense&expense_id='.$expense_id, 'msg_success='._gettext("Expense added successfully.").' '._gettext("ID").': '.$expense_id);
         
    } else {

        // load expense details page
        $this->app->system->page->force_page('expense', 'details&expense_id='.$expense_id, 'msg_success='._gettext("Expense added successfully.").' '._gettext("ID").': '.$expense_id);

     }        

} else {
    
    // Build the page
    $this->app->smarty->assign('expense_types', $this->app->components->expense->getTypes());    
    $this->app->smarty->assign('vat_tax_codes', $this->app->components->company->getVatTaxCodes(false));   
    $this->app->smarty->assign('default_vat_tax_code', $this->app->components->company->getDefaultVatTaxCode());
}