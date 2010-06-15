<?php
require_once('include.php');
if(!xml2php("workorder")) {
	$smarty->assign('error_msg',"Error in language file");
}

// Get the page number we are on if first page set to 1
	if(!isset($VAR["page_no"])) {
		$page_no = 1;
	} else {
		$page_no = $VAR['page_no'];
	}	

/* display new Workorders */	
$where = "WHERE ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CURRENT_STATUS= ".$db->qstr(1);
$smarty->assign('new', display_workorders($db, $page_no, $where));

/* display assigned Workorders */	
$where = "WHERE ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CURRENT_STATUS= ".$db->qstr(2);
$smarty->assign('assigned', display_workorders($db, $page_no, $where));

/* display Workorders awaiting parts  */	
$where = "WHERE ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CURRENT_STATUS= ".$db->qstr(3);
$smarty->assign('awaiting', display_workorders($db, $page_no, $where));

/* display work orders that need payment */
$where = "WHERE ".PRFX."TABLE_WORK_ORDER.WORK_ORDER_CURRENT_STATUS= ".$db->qstr(7);
$smarty->assign('payment', display_workorders($db, $page_no, $where));

//$smarty->assign('new_workorder', display_workorders($db, $page_no,$where));
$smarty->display('workorder'.SEP.'main.tpl');
?>
