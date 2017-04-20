<?php

require(INCLUDES_DIR.'modules/expense.php');

// Assign the arrays
$smarty->assign('supplier_details', get_supplier_details($db, $supplier_id));
$BuildPage .= $smarty->fetch('supplier/details.tpl');