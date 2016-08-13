<?php

// Load the Supplier classes
require_once('include.php');

// Load the Translation for this Module
if(!xml2php('supplier')) {
    $smarty->assign('error_msg',"Error in language file");
}

$supplier_id = $VAR['supplier_id'];

// Make sure we got an Supplier ID number
if(!isset($supplier_id) || $supplier_id =="") {
    $smarty->assign('results', 'Please go back and select an supplier record');
    die;
}    

// Delete the supplier function call
if(!delete_supplier($db,$supplier_id)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
} else {
        force_page('supplier', 'view&page_title=Supplier');
        exit;
}