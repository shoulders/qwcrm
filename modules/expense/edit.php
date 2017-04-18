<?php

require(INCLUDES_DIR.'modules/workorder.php');

// Load PHP Language Translations
$langvals = gateway_xml2php();

// Load expense details
$expense_details = display_expense_info($db, $expense_id);

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
    
    $smarty->assign('expense_details', $expense_details);
    $BuildPage .= $smarty->fetch('expense/edit.tpl');
    
}