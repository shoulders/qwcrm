<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/supplier.php');

// Assign the arrays
$smarty->assign('supplier_details', get_supplier_details($db, $supplier_id));
$BuildPage .= $smarty->fetch('supplier/details.tpl');