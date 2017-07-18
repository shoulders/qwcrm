<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/supplier.php');

// Check if we have a supplier_id
if($supplier_id == '') {
    force_page('supplier', 'search', 'warning_msg='.gettext("No Supplier ID supplied."));
    exit;
}  

// Build the page
$smarty->assign('supplier_details', get_supplier_details($db, $supplier_id));
$BuildPage .= $smarty->fetch('supplier/details.tpl');