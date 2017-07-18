<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/expense.php');

// Prevent direct access to this file
if(!check_page_accessed_via_qwcrm()) {
    force_page('expense', 'search', 'warning_msg='.gettext("No Direct Access Allowed"));
}

// Check if we have an expense_id
if($expense_id == '') {
    force_page('expense', 'search', 'warning_msg='.gettext("No Expense ID supplied."));
    exit;
}   

// Delete the expense
delete_expense($db, $expense_id);

// Load the expense search page
force_page('expense', 'search');
exit;