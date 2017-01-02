<?php

// Load the Expense Functions
require_once('include.php');


// Assign the arrays
$smarty->assign('expense_details', display_expense_info($db, $VAR['expense_id']));
$BuildPage .= $smarty->fetch('expense'.SEP.'details.tpl');