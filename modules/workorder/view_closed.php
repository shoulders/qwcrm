<?php
if(!xml2php("workorder")) {
	$smarty->assign('error_msg',"Error in language file");
}
if(!isset($VAR["page_no"])){
	$page_no = 1;
} else {
	$page_no = $VAR['page_no'];
}	

$work_order = display_closed($db,$page_no,$smarty);
$smarty->assign('work_order', $work_order);
$smarty->display('workorder'.SEP.'view_closed.tpl');

function display_closed($db,$page_no,$smarty) {
	
global $smarty;
	
	// Define the number of results per page
	$max_results = 25;
	
	// Figure out the limit for the Execute based
	// on the current page number.
	$from = (($page_no * $max_results) - $max_results);  
	
	$sql = "SELECT 
			".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID, 
			".PRFX."TABLE_WORK_ORDER.WORK_ORDER_OPEN_DATE,
			".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ASSIGN_TO,
			".PRFX."TABLE_WORK_ORDER.WORK_ORDER_SCOPE, 
			".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CLOSE_DATE,
			".PRFX."TABLE_CUSTOMER.*, 
			".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID, 
			".PRFX."TABLE_EMPLOYEE.EMPLOYEE_DISPLAY_NAME, 
			".PRFX."TABLE_EMPLOYEE.EMPLOYEE_WORK_PHONE, 
			".PRFX."TABLE_EMPLOYEE.EMPLOYEE_HOME_PHONE, 
			".PRFX."TABLE_EMPLOYEE.EMPLOYEE_MOBILE_PHONE, 
			".PRFX."CONFIG_WORK_ORDER_STATUS.CONFIG_WORK_ORDER_STATUS
			FROM ".PRFX."TABLE_WORK_ORDER
			LEFT JOIN ".PRFX."TABLE_CUSTOMER ON ".PRFX."TABLE_WORK_ORDER.CUSTOMER_ID = ".PRFX."TABLE_CUSTOMER.CUSTOMER_ID
			LEFT JOIN ".PRFX."TABLE_EMPLOYEE ON ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ASSIGN_TO = ".PRFX."TABLE_EMPLOYEE.EMPLOYEE_ID
			LEFT JOIN ".PRFX."CONFIG_WORK_ORDER_STATUS ON ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CURRENT_STATUS = ".PRFX."CONFIG_WORK_ORDER_STATUS.CONFIG_WORK_ORDER_STATUS_ID
			WHERE WORK_ORDER_STATUS=".$db->qstr(6)." GROUP BY ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID ORDER BY ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_ID DESC LIMIT $from, $max_results";

	if(!$rs = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$work_order = $rs->GetArray();
	}

	// Figure out the total number of results in DB: 
	$q = "SELECT COUNT(*) as Num FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_STATUS=".$db->qstr(6);
	if(!$results = $db->Execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}


	if(!$total_results = $results->FetchRow()) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$smarty->assign('total_results', $total_results['Num']);
	}
		
	// Figure out the total number of pages. Always round up using ceil()
	$total_pages = ceil($total_results["Num"] / $max_results); 
	$smarty->assign('total_pages', $total_pages);
	
	// Assign the first page
	if($page_no > 1) {
    	$prev = ($page_no - 1);	 
	} 	

	// Build Next Link
	if($page_no < $total_pages){
    	$next = ($page_no + 1); 
	}


	$smarty->assign('name', $name);
	$smarty->assign('page_no', $page_no);
	$smarty->assign("previous", $prev);	
	$smarty->assign("next", $next);
	return $work_order;
}
?>