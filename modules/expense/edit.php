<?php

// Load the Expense Functions
require_once('include.php');



// Load PHP Language Translations
$langvals = gateway_xml2php('expense');

// Load expense details
$expense_details = display_expense_info($db, $VAR['expense_id']);

// If details submitted run update values, if not set load edit.tpl and populate values
if(isset($VAR['submit'])) {    
        
    if (!update_expense($db, $VAR)){

        force_page('expense', 'edit&error_msg=Falied to Update Expense Information&expense_id='.$VAR['expense_id']);
        exit;
                
    } else {
            
        force_page('expense', 'expense_details&expense_id='.$VAR['expense_id'].'&page_title='.$langvals['expense_details_title']);
        exit;
    }

} else {
    $smarty->assign('expense_details', $expense_details);
    $smarty->display('expense'.SEP.'edit.tpl');
       }