<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/supplier.php');

// Check if we have a supplier_id
if($supplier_id == '') {
    force_page('supplier', 'search', 'warning_msg='.gettext("No Supplier ID supplied."));
    exit;
}  

// Delete the supplier function call
delete_supplier($db, $supplier_id);

// Load the supplier search page
force_page('supplier', 'search', 'information_msg='.gettext("Supplier deleted successfully."));
exit;
