<?php

require(INCLUDES_DIR.'modules/workorder.php');

// Assign the arrays
$smarty->assign('expense_details', display_expense_info($db, $VAR['expense_id']));

$BuildPage .= $smarty->fetch('expense/details.tpl');