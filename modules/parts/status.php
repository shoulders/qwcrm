<?php
####################################################
# IN 			#	
#	 				#
#  				#
#  This program is distributed under the terms and 	#
#  conditions of the GPL										#
#  Order status													#
#  Version 0.0.1	Sat Nov 26 20:46:40 PST 2005		#
#																	#
####################################################
if(!xml2php("parts")) {
	$smarty->assign('error_msg',"Error in language file");
}
$status = $VAR['status'];
$smarty->assign('status',$status);

/* get page number set default */
if(!isset($VAR['page_no'])){
	$page_no = 1;
} else {
	$page_no = $VAR['page_no'];
}	

/* Figure out the limit for the query based */
$max_results = 10;

/*on the current page number.*/
$from = (($page_no * $max_results) - $max_results);
$q = "SELECT * FROM ".PRFX."ORDERS WHERE STATUS=".$db->qstr($status)." ORDER BY DATE_CREATE LIMIT $from, $max_results " ;
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
$order = $rs->GetArray();

/* Figure out the total number of results in DB: */
$q = "SELECT COUNT(*) as Num FROM ".PRFX."ORDERS WHERE STATUS=".$db->qstr($status);
	
	if(!$results = $db->Execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$total_results = $results->FetchRow();
		$smarty->assign('total_results', $total_results['Num']);
	}
	
	// Figure out the total number of pages. Always round up using ceil()
	$total_pages = ceil($total_results["Num"] / $max_results); 
	
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

if($status == 1) {
	$smarty->assign('or_status', 'Open');
} else if ($status == 0) {
	$smarty->assign('or_status', 'Closed');
}	

/* display Smarty */
$smarty->assign('order', $order);
$smarty->display('parts'.SEP.'status.tpl');
?>