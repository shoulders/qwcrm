<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/expense.php');

// Predict the next expense_id
$new_record_id = last_expense_id_lookup($db) +1;

// If details submitted insert record, if non submitted load new.tpl and populate values
if((isset($VAR['submit'])) || (isset($VAR['submitandnew']))) {

    // Insert the Expense into the databse
    $expense_id = insert_expense($db, $VAR);

    if (isset($VAR['submitandnew'])){

         // Load the new expense page
         force_page('expense', 'new');
         exit;

    } else {

        // load expense details page
        force_page('expense', 'details&expense_id='.$expense_id, 'information_msg='.gettext("Expense added successfully."));
        exit;

     }        

} else {
    
    // Build the page
    $smarty->assign('new_record_id', $new_record_id);
    $smarty->assign('tax_rate', get_company_details($db, 'tax_rate'));
    $BuildPage .= $smarty->fetch('expense/new.tpl');

}