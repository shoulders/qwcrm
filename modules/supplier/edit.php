<?php

// Load the Supplier classes
require_once('include.php');

// Load PHP Language Translations
$langvals = gateway_xml2php('supplier');

// Load supplier details
$supplier_details = display_supplier_info($db, $VAR['supplier_id']);

// If details submitted run update values, if not set load edit.tpl and populate values
if(isset($VAR['submit'])) {    
        
    if (!update_supplier($db, $VAR)){

        force_page('supplier', 'edit','error_msg=Falied to Update Supplier Information&supplier_id='.$VAR['supplier_id']);
        exit;
                
    } else {
            
        force_page('supplier', 'details','supplier_id='.$VAR['supplier_id'].'&page_title='.$langvals['supplier_details_title']);
        exit;
    }

} else {
    $smarty->assign('supplier_details', $supplier_details);
    $smarty->display('supplier'.SEP.'edit.tpl');
       }