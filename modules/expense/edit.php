<?php

require(INCLUDES_DIR.'modules/expense.php');

// Load PHP Language Translations
$langvals = gateway_xml2php('expense');

// If details submitted run update values, if not set load edit.tpl and populate values
if(isset($VAR['submit'])) {    
        
    if (!update_expense($db, $VAR)){

        force_page('expense', 'edit','error_msg=Falied to Update Expense Information&expense_id='.$expense_id);
        exit;
                
    } else {
            
        force_page('expense', 'expense_details&expense_id='.$expense_id);
        exit;
    }

} else {
    
    $smarty->assign('expense_details', get_expense_details($db, $expense_id));
    $BuildPage .= $smarty->fetch('expense/edit.tpl');
    
}