<?php

####################################################
# IN 			#	
#	 				#
#  				#
#  This program is distributed under the terms and 	#
#  conditions of the GPL										#
#  Close Work Order											#
#  Version 0.0.1	Sat Nov 26 20:46:40 PST 2005		#
#																	#
####################################################
if(!xml2php("workorder")) {
	$smarty->assign('error_msg',"Error in language file");
}
require_once ("include.php");

if(empty($VAR['wo_id'])){
	force_page('core', 'error&error_msg=No Work Order ID');
	exit;
}
$wo_id = $VAR['wo_id'];

/* Check if work Order Is already Closed*/
$q = "SELECT WORK_ORDER_STATUS,WORK_ORDER_CURRENT_STATUS FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr($wo_id);
if(!$rs = $db->execute($q)) {
	force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
	exit;
}

if($rs->fields['WORK_ORDER_STATUS'] == 9) {
	force_page('workorder', "view&wo_id=$wo_id&error_msg=Work Order Is already Closed. Please Create an Invoice.&page_title=Work Order ID $wo_id&type=info");
} elseif ($rs->fields['WORK_ORDER_CURRENT_STATUS'] == 3) {
	force_page('workorder', "view&wo_id=$wo_id&error_msg=Can not close a work order if it is Waiting For Parts. Please Adjust the status.&page_title=Work Order ID $wo_id&type=warning");
}
			
if(isset($VAR["submit1"])){

	if (!close_work_order($db,$VAR)){
		force_page('workorder', "view&wo_id=$wo_id&error_msg=Failed to Close Work Order.&page_title=Work Order ID $wo_id");
	} else {
		$q = "SELECT CUSTOMER_ID FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr($wo_id);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}
				
		$customer_id = $rs->fields['CUSTOMER_ID'];	
		force_page('invoice', 'new&wo_id='.$wo_id.'&customer_id='.$customer_id.'&page_title=Create Invoice for Work Order# wo_id='.$wo_id);
	}
	
} 

if(isset($VAR["submit2"])){

	if (!close_work_order_no_invoice($db,$VAR)){
		force_page('workorder', "view&wo_id=$wo_id&error_msg=Failed to Close Work Order.&page_title=Work Order ID $wo_id");
	} else {
		$q = "SELECT CUSTOMER_ID FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr($wo_id);
		if(!$rs = $db->execute($q)) {
			force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
			exit;
		}
      force_page('workorder', "main&page_title=Work Orders");
	}
	
}
else {
		$smarty->assign('wo_id', $VAR['wo_id']);
		$smarty->display('workorder'.SEP.'close.tpl');
}	
	
?>
