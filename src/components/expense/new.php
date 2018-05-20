<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/expense.php');
require(INCLUDES_DIR.'components/payment.php');

// Predict the next expense_id
$new_record_id = last_expense_id_lookup($db) +1;

// If details submitted insert record, if non submitted load new.tpl and populate values
if((isset($VAR['submit'])) || (isset($VAR['submitandnew']))) {

    // Insert the Expense into the databse
    $VAR['expense_id'] = insert_expense($db, $VAR);

    if (isset($VAR['submitandnew'])){

         // Load the new expense page
         force_page('expense', 'new', 'information_msg='._gettext("Expense added successfully."));
         exit;

    } else {

        // load expense details page
        force_page('expense', 'details&expense_id='.$VAR['expense_id'], 'information_msg='._gettext("Expense added successfully."));
        exit;

     }        

} else {
    
    // Build the page
    $smarty->assign('expense_types', get_expense_types($db));
    $smarty->assign('payment_methods', get_payment_manual_methods($db));
    $smarty->assign('new_record_id', $new_record_id);
    $smarty->assign('vat_rate', get_company_details($db, 'tax_rate'));
    $BuildPage .= $smarty->fetch('expense/new.tpl');

}