<?php
require('include.php');
$refundID = $VAR['refundID'];

// Make sure we got an Refund ID number
if(!isset($refundID) || $refundID =="") {
	$smarty->assign('results', 'Please go back and select an refund record');
	die;
}	

// Delete the refund function call
if(!delete_refund($db,$refundID)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
} else {
        force_page('refund', 'view&page_title=Refund');
        exit;
}

?>