<?php
####################################################
#  This program is distributed under the terms and 	#
#  conditions of the GPL										#
#  Unpaid Invoices												#
#  Version 0.0.1	Sat Nov 26 20:46:40 PST 2005		#
#																	#
####################################################
require_once('include.php');
if(!xml2php("invoice")) {
	$smarty->assign('error_msg',"Error in language file");
}
if(!isset($VAR['page_no'])){
	$page_no = 1;
} else {
	$page_no = $VAR['page_no'];
}	

if(!$invoice = display_open_invoice($db,$page_no,$smarty)) {
	$smarty->assign('invoice', $invoice);
	$smarty->display('invoice'.SEP.'view_unpaid.tpl');
} else {
	$smarty->assign('invoice', $invoice);
	$smarty->display('invoice'.SEP.'view_unpaid.tpl');
}


?>
