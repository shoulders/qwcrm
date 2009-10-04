<?php
require_once('include.php');
if(!xml2php("invoice")) {
	$smarty->assign('error_msg',"Error in language file");
}
if(!isset($VAR['page_no'])){
	$page_no = 1;
} else {
	$page_no = $VAR['page_no'];
}	
$invoice = display_paid_invoice($db,$page_no,$smarty);
	
	$smarty->assign('invoice', $invoice);
	$smarty->display('invoice'.SEP.'view_paid.tpl');



?>