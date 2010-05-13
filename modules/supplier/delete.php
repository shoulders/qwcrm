<?php
require('include.php');
$supplierID = $VAR['supplierID'];

// Make sure we got an Supplier ID number
if(!isset($supplierID) || $supplierID =="") {
	$smarty->assign('results', 'Please go back and select an supplier record');
	die;
}	

// Delete the supplier function call
if(!delete_supplier($db,$supplierID)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
} else {
        force_page('supplier', 'view&page_title=Supplier');
        exit;
}

?>