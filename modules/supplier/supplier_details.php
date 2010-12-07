<?php

// Load the Supplier classes
require_once('include.php');

// Load the Translation for this Module
if(!xml2php('supplier')) {
	$smarty->assign('error_msg',"Error in language file");
}

// Assign the arrays
$smarty->assign('supplier_details', display_supplier_info($db, $VAR['supplierID']));
$smarty->display('supplier'.SEP.'supplier_details.tpl');

?>
