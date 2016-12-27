<?php

// Load the Supplier classes
require_once('include.php');

// Assign the arrays
$smarty->assign('supplier_details', display_supplier_info($db, $VAR['supplier_id']));
$smarty->display('supplier'.SEP.'details.tpl');