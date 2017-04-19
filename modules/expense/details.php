<?php

require(INCLUDES_DIR.'modules/expense.php');

// Assign the arrays
$smarty->assign('expense_details', get_expense_details($db, $expense_id));

$BuildPage .= $smarty->fetch('expense/details.tpl');