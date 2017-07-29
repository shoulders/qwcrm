<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/supplier.php');

// Predict the next supplier_id
$new_record_id = last_supplier_id_lookup($db) +1;

// If details submitted insert record, if non submitted load new.tpl and populate values
if((isset($VAR['submit'])) || (isset($VAR['submitandnew']))) {
        
    // insert the supplier record and get the supplier_id
    $supplier_id = insert_supplier($db, $VAR);
            
    if (isset($VAR['submitandnew'])) {

        // load the new supplier page
        force_page('supplier', 'new');
        exit;

    } else {

        // load the supplier details page
        force_page('supplier', 'detailssupplier_id='.$supplier_id);
        exit;

    }

}

// Build the page            
$smarty->assign('new_record_id', $new_record_id);
$smarty->assign('tax_rate', get_company_details($db, 'tax_rate'));
$BuildPage .= $smarty->fetch('supplier/new.tpl');