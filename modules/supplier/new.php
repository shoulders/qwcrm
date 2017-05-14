<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/supplier.php');

// Predict the next supplier_id
$new_record_id = last_supplier_id_lookup($db) +1;

// If details submitted insert record, if non submitted load new.tpl and populate values
if((isset($VAR['submit'])) || (isset($VAR['submitandnew']))) {
        
    if(!$supplier_id = insert_supplier($db, $VAR)){
            $smarty->assign('error_msg', 'Falied to insert Supplier');
            $BuildPage .= $smarty->fetch('core/error.tpl');
    } else {

        if (isset($VAR['submitandnew'])) {

            // Submit New Supplier and reload page
            force_page('supplier', 'new');
            exit;

        } else {

            // Submit and load Supplier View Details
            force_page('supplier', 'detailssupplier_id='.$supplier_id);
            exit;

        }
        
    }

} else {
            
    $smarty->assign('new_record_id', $new_record_id);
    $smarty->assign('tax_rate', get_company_details($db, 'TAX_RATE'));
    $BuildPage .= $smarty->fetch('supplier/new.tpl');

}