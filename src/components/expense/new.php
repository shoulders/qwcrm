<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'company.php');
require(INCLUDES_DIR.'expense.php');
require(INCLUDES_DIR.'payment.php');

// If details submitted insert record, if non submitted load new.tpl and populate values
if(isset($VAR['submit'])) {

    // Insert the Expense into the database
    $VAR['expense_id'] = insert_expense($VAR);

    if ($VAR['submit'] == 'submitandnew'){

         // Load the new expense page
         force_page('expense', 'new', 'information_msg='._gettext("Expense added successfully.").' '._gettext("ID").': '.$VAR['expense_id']);

    } elseif ($VAR['submit'] == 'submitandpayment') {
         
        // Load the new payment page for expense
         force_page('payment', 'new', 'type=expense&expense_id='.$VAR['expense_id']);
         
    } else {

        // load expense details page
        force_page('expense', 'details&expense_id='.$VAR['expense_id'], 'information_msg='._gettext("Expense added successfully.").' '._gettext("ID").': '.$VAR['expense_id']);

     }        

} else {
    
    // Build the page
    $smarty->assign('expense_types', get_expense_types());    
    $smarty->assign('vat_tax_codes', get_vat_tax_codes(false));   
    $smarty->assign('default_vat_tax_code', get_default_vat_tax_code());    
    $BuildPage .= $smarty->fetch('expense/new.tpl');

}