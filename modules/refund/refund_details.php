<?php

// Load the Refund Functions
require_once('include.php');

// Load the Translation for this Module
if(!xml2php('refund')) {
	$smarty->assign('error_msg',"Error in language file");
}

// Assign the arrays
$smarty->assign('refund_details', display_refund_info($db, $VAR['refundID']));
$smarty->display('refund'.SEP.'refund_details.tpl');

?>
