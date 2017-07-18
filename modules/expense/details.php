<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/expense.php');

// Check if we have an expense_id
if($expense_id == '') {
    force_page('expense', 'search', 'warning_msg='.gettext("No Expense ID supplied."));
    exit;
}

// Build the page
$smarty->assign('expense_details', get_expense_details($db, $expense_id));
$BuildPage .= $smarty->fetch('expense/details.tpl');