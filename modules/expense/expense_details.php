<?php
require_once('include.php');
if(!xml2php('expense')) {
	$smarty->assign('error_msg',"Error in language file");
}

// Assign the arrays
$smarty->assign('expense_details', display_expense_info($db, $VAR['expenseID']));
$smarty->display('expense'.SEP.'expense_details.tpl');

?>
