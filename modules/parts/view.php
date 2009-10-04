<?php
####################################################
# IN 			#	
#	 				#
#  				#
#  This program is distributed under the terms and 	#
#  conditions of the GPL										#
#  Orders View													#
#  Version 0.0.1	Sat Nov 26 20:46:40 PST 2005		#
#																	#
####################################################
$order_id = $VAR['ORDER_ID'];
if(!xml2php("parts")) {
	$smarty->assign('error_msg',"Error in language file");
}

$q = "SELECT * FROM ".PRFX."ORDERS WHERE ORDER_ID=".$db->qstr($order_id);
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
$arr = $rs->GetArray();

$q = "SELECT ".PRFX."ORDERS_DETAILS . * , ".PRFX."TABLE_INVOICE_PARTS.INVOICE_PARTS_DESCRIPTION, ".PRFX."TABLE_INVOICE_PARTS.INVOICE_PARTS_MANUF
		FROM ".PRFX."ORDERS_DETAILS, ".PRFX."TABLE_INVOICE_PARTS
		WHERE ORDER_ID = INVOICE_ID
		AND ORDER_ID =".$db->qstr($arr[0]['ORDER_ID']);
$rs = $db->execute($q);

$details = $rs->GetArray();

$smarty->assign('order_details', $details);
$smarty->assign('order', $arr);
$smarty->display('parts'.SEP.'view.tpl');
?>