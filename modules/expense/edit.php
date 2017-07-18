<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/expense.php');

// Check if we have an expense_id
if($expense_id == '') {
    force_page('expense', 'search', 'warning_msg='.gettext("No Expense ID supplied."));
    exit;
}

// If details submitted run update values, if not set load edit.tpl and populate values
if(isset($VAR['submit'])) {    
        
        update_expense($db, $expense_id, $VAR);
        force_page('expense', 'details&expense_id='.$expense_id, 'information_msg='.gettext("The Customer's information was updated."));
        exit;    

} else {
    
    // Build the page
    $smarty->assign('expense_details', get_expense_details($db, $expense_id));
    $BuildPage .= $smarty->fetch('expense/edit.tpl');
    
}