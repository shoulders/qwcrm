<?php

require(INCLUDES_DIR.'modules/expense.php');

// Load PHP Language Translations
$langvals = gateway_xml2php('expense');

// Predict the next expense_id
$new_record_id = last_expense_id_lookup($db) +1;

// If details submitted insert record, if non submitted load new.tpl and populate values
    if((isset($VAR['submit'])) || (isset($VAR['submitandnew']))) {
        
        if(!$expense_id = insert_expense($db, $VAR)){
            $smarty->assign('error_msg', 'Falied to insert Expense');
            $BuildPage .= $smarty->fetch('core/error.tpl');
            echo "expense insert error";

            } else {

                if (isset($VAR['submitandnew'])){

                     // Submit New Expense and reload page
                     force_page('expense', 'new');
                     exit;

                } else {

                    // Submit and load Expense View Details
                    force_page('expense', 'details&expense_id='.$expense_id);
                    exit;

                 }
            }

} else {
            
    $smarty->assign('new_record_id', $new_record_id);
    $smarty->assign('tax_rate', get_company_details($db, 'TAX_RATE'));
    $BuildPage .= $smarty->fetch('expense/new.tpl');

}