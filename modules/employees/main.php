<?php
/* Employee Main page */
// Load the customer classes
	require_once("include.php");
if(!xml2php("employees")) {
	$smarty->assign('error_msg',"Error in language file");
}
	
	$alpha = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
	
	if(!isset($VAR["page_no"]))
	{
		$page_no = 1;
	} else {
		$page_no = $VAR['page_no'];
	}	
	
	$employee_search_result = display_employee_search($db, $VAR['name'], $page_no);
	
	$smarty->assign('alpha', $alpha);
	$smarty->assign('employee_search_result' ,$employee_search_result );
	$smarty->display('employees'.SEP.'main.tpl');
	


?>