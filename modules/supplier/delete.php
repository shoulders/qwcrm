<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/supplier.php');

// Make sure we got an Supplier ID number
if($supplier_id == '') {
    $smarty->assign('results', 'Please go back and select an supplier record');
    die;
}    

// Delete the supplier function call
if(!delete_supplier($db, $supplier_id)) {
        force_page('core', 'error', 'error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
} else {
        force_page('supplier', 'search');
        exit;
}