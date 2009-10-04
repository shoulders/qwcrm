<?php
// Load the customer classes
require_once("include.php");

/* load translation for this module */
if(!xml2php("customer")) {
	$smarty->assign('error_msg',"Error in language file");
}

$alpha = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0');

	if(!isset($VAR["page_no"])) {
		$page_no = 1;
	} else {
		$page_no = $VAR['page_no'];
	}	

	$customer_search_result = display_customer_search($db, $name = $VAR['name'], $page_no, $smarty);

	$smarty->assign('alpha', $alpha);
	$smarty->assign('customer_search_result', $customer_search_result);
	$smarty->display('customer'.SEP.'search.tpl');
	
	
	





?>