<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/supplier.php');

// If details submitted run update values, if not set load edit.tpl and populate values
if(isset($VAR['submit'])) {    
        
    if (!update_supplier($db, $supplier_id, $VAR)){

        force_page('supplier', 'edit','error_msg=Falied to Update Supplier Information&supplier_id='.$supplier_id);
        exit;
                
    } else {
            
        force_page('supplier', 'details&supplier_id='.$supplier_id);
        exit;
    }

} else {
    $smarty->assign('supplier_details', get_supplier_details($db, $supplier_id));
    $BuildPage .= $smarty->fetch('supplier/edit.tpl');
}